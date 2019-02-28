<?php
header('Content-type:text/html;charset=utf-8'); 
set_time_limit(0);
class RunmainAction extends Action {
	/**
	 * 发送云主机微信通知
	 */
	protected function cloudsendwechat(){
		$sendtime1=30;
    	$sendtime2=7;
    	$sendtime3=1;
    	echo "CLOUD类微信通知...."."<br>";
    	for($i = 0; $i <= 2; $i++) 
    	{
    		if($i==0){
    			$endtime=strtotime("+$sendtime1 day", time());
    			$where=" endtime <".$endtime." and wechatstatus is null and istest='n' and status='正常'";
    		}
    		if($i==1){
    			$endtime=strtotime("+$sendtime2 day", time());
    			$where=" endtime <".$endtime." and wechatstatus='a' and istest='n' and status='正常'";
    		}
    		if($i==2){
    			$endtime=strtotime("+$sendtime2 day", time());
    			$where=" endtime <".$endtime." and wechatstatus='b' and istest='n' and status='正常'";
    		}
    		$cloud=D('Cloud')->where($where)->select();
    		foreach ($cloud as $k=>$v){
    			if ($v['wechatstatus']=='b')$wechatstatus='c';
    			if ($v['wechatstatus']=='a')$wechatstatus='b';
 				if (is_null($cloud['wechatstatus']))$wechatstatus='a';
 				$user=D('User')->where(array('user_id'=>$v['user_id']))->find();
 				if ($user){
 					$weixin_config=D('weixin_config')->where(array('id'=>1))->find();
 					if ($weixin_config){
 						$weixin_user=D('weixin_user')->where(array('uid'=>$user['user_id']))->find();
	 					if ($weixin_user){
		 					echo "用户名：".$user['username'];
		 					echo "云主机名：".$cloud['cloudname'];
		 					echo "微信号wxid：".$weixin_user['wxid'];
		 					echo "到期时间：".convert_datefm($v['endtime'],2);
		 					echo "<br>";
		 					$wechat=new Wechat();
		 					$data=array(
		 						'user_id'=>$user['user_id'],
		 						'first'=>'云主机到期提醒',
		 						'data1'=>'云主机名'.$cloud['cloudname'],
		 						'data2'=>'到期时间'.convert_datefm($v['endtime'],2)
		 					);
		 					$wechat->sendMsg($data);
		 					D('Cloud')->where(array('id'=>$v['id']))->save(array('wechatstatus'=>$wechatstatus));
	 					}
 					}
 				}
    		}
    	}
    	echo "CLOUD类到微信结束...."."<br>";
	}

	/**
	 * 云主机暂停和删除操作
	 */
    protected function cloud(){
		    $cloud=D("Cloud");
		    $where['endtime']=array('LT',time());
		    $where['status']=array('EQ','正常');
		    $clouddata=$cloud->where($where)->select();
		    foreach ($clouddata as $k=>$v){
				$result=D('Cloudapi')->cloudupdate($v['vm_id']);
				if ($result['status']=='failed')continue;
				$updata['cloudname']=$result['value']['cloudname'];
				$updata['cloudpassword']=$result['value']['cloudpassword'];
				$updata['vminfo']=$result['value']['vminfo'];
				$updata['diskinfo']=$result['value']['diskinfo'];
				$updata['ipqosinfo']=$result['value']['ipqosinfo'];
				$updata['starttime']=$result['value']['starttime'];
				$updata['endtime']=$result['value']['endtime'];
				$updata['status']=$result['value']['status'];
				D('Cloud')->where(array('id'=>$v['id']))->save($updata);
		        sleep(5);
		    }
    }
  	/**
  	 * 发送云主机邮件通知
  	 */
    protected function cloudsendemail(){
        $sendtime1=30;
    	$sendtime2=7;
    	$sendtime3=1;
    	echo "CLOUD类到期邮件通知...."."<br>";
    	for($i = 0; $i <= 2; $i++) 
    	{
    		if($i==0){
    			$endtime=strtotime("+$sendtime1 day", time());
    			$where=" endtime <".$endtime." and emailstatus is null and istest='n' and status='正常'";
    		}
    		if($i==1){
    			$endtime=strtotime("+$sendtime2 day", time());
    			$where=" endtime <".$endtime." and emailstatus='a' and istest='n' and status='正常'";
    		}
    		if($i==2){
    			$endtime=strtotime("+$sendtime2 day", time());
    			$where=" endtime <".$endtime." and emailstatus='b' and istest='n' and status='正常'";
    		}
    		$cloud=D('Cloud')->where($where)->select();
    		foreach ($cloud as $k=>$v){
    			if ($v['emailstatus']=='b')$emailstatus='c';
    			if ($v['emailstatus']=='a')$emailstatus='b';
 				if (is_null($cloud['emailstatus']))$emailstatus='a';
 				$user=D('User')->where(array('user_id'=>$v['user_id']))->find();
 				if ($user){
 					echo "用户名：".$user['username'];
 					echo "云主机名：".$cloud['cloudname'];
 					echo "邮箱：".$user['email'];
 					echo "到期时间：".convert_datefm($v['endtime'],2);
 					echo "<br>";
					D('Sendmail')->Sendcloudexpire($user['username'],$v['cloudname'],$v['endtime']);
					D('Cloud')->where(array('id'=>$v['id']))->save(array('emailstatus'=>$emailstatus));		
 				}
    		}
    	}
    	echo "CLOUD类到期邮件通知结束...."."<br>";
    }
    /**
     * 发送云主机短信通知
     */
 	protected function cloudsendsms(){
        $sendtime1=30;
    	$sendtime2=7;
    	$sendtime3=1;
    	echo "CLOUD类到期短信通知...."."<br>";
    	for($i = 0; $i <= 2; $i++) 
    	{
    		if($i==0){
    			$endtime=strtotime("+$sendtime1 day", time());
    			$where=" endtime <".$endtime." and smsstatus is null and istest='n' and status='正常'";
    		}
    		if($i==1){
    			$endtime=strtotime("+$sendtime2 day", time());
    			$where=" endtime <".$endtime." and smsstatus='a' and istest='n' and status='正常'";
    		}
    		if($i==2){
    			$endtime=strtotime("+$sendtime2 day", time());
    			$where=" endtime <".$endtime." and smsstatus='b' and istest='n' and status='正常'";
    		}
    		$cloud=D('Cloud')->where($where)->select();
    		foreach ($cloud as $k=>$v){
    			if ($v['smsstatus']=='b')$smsstatus='c';
    			if ($v['smsstatus']=='a')$smsstatus='b';
 				if (is_null($cloud['smsstatus']))$smsstatus='a';
 
 				$user=D('User')->where(array('user_id'=>$v['user_id']))->find();
 				if ($user){
 					echo "用户名：".$user['username'];
 					echo "云主机名：".$cloud['cloudname'];
 					echo "手机：".$user['mobi'];
 					echo "到期时间：".convert_datefm($v['endtime'],2);
 					echo "<br>";
					D('Sendsms')->Sendcloudexpire($user['username'],$v['cloudname'],$v['endtime']);
					D('Cloud')->where(array('id'=>$v['id']))->save(array('smsstatus'=>$smsstatus));
 				}
    		}
    	}
    	echo "CLOUD类到期短信通知结束...."."<br>";
    }
	
  	
	/**
	 * 获取用户信息
	 */
    protected function userinfo($uid){
		return  M("user")->where('user_id = '.$uid)->field('usermoney')->find();
	}
}