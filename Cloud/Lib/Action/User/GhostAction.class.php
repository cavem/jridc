<?php
//自定义镜像列表
class GhostAction extends MainuserAction{
	public function index(){
		$ary_get=$_GET;
		$where="c.user_id=".$this->uid." and c.username='".$this->username."'";
		$count = D("cloud_tem")->table(C("DB_PREFIX")."cloud_tem as c")->where($where)->count();
		$obj_page =$this->UserPage($count,10);
        $pageinfo = $obj_page->show();
		$data = D("cloud_tem")->table(C("DB_PREFIX").'cloud_tem as c')
				->join(C("DB_PREFIX")."cloud as p on c.pid = p.id")
				->field("c.*,p.Cloudtype")
				->where($where)
				->order('c.id desc')
        		->limit($obj_page->firstRow, $obj_page->listRows)
				->select();	
		$this->assign("pageinfo",$pageinfo);
		$this->assign('data',$data);
		$this->display();
	}
	public function handle(){
		$id=I('id','0');
		if (empty($id))$this->error("数据项错误");
		$cloud_tem=D('cloud_tem')->where(array('id'=>$id,'user_id'=>$this->uid))->find();
		if (empty($cloud_tem))$this->error("数据不存在");
		$cloud_product=D('cloud_product')->where(array('Cloudtype'=>$cloud_tem['cloudtype']))->find();
		if (empty($cloud_product))$this->error("产品配置不存在");
		$cloudinfo=json_decode($cloud_tem['templatevalue'],true);
		$this->assign('product',$cloud_product);
		$this->assign('cloud',$cloudinfo);
		$this->assign('data',$cloud_tem);
		$this->display();		
	}
	public function handlesave(){
		//$this->success("云主机开通成功",U('User/Cloud/index'));
		if (!IS_POST)$this->error("数据错误");
		$ary_post=$_POST;
		$cloud_tem=D('cloud_tem')->where(array('id'=>$ary_post['id'],'user_id'=>$this->uid))->find();
		if (empty($cloud_tem))$this->error("数据不存在");
		$cloud_product=D('cloud_product')->where(array('Cloudtype'=>$cloud_tem['cloudtype']))->find();
		if (empty($cloud_product))$this->error("产品配置不存在");
		$data=D('Cloud')->cloudprice($cloud_product['id'],$ary_post['cpu'],$ary_post['mem'],$ary_post['disk'],$ary_post['dlip'],$ary_post['qos'],$ary_post['year'],$this->uid);
		$endmoney=$data['Price'];
		$userorder=$this->userinfo($this->uid);
		if ($endmoney>$userorder['usermoney']){
			$this->error("账户余额不够支付当前云主机延期费用");
		}
		$vm_name=$ary_post['vmname'];
		if (empty($vm_name))$this->error('云主机名不能为空');
		$cloudinfo=D('Cloud')->where(array('cloudname'=>$vm_name))->find();
		if ($cloudinfo)$this->error('云主机名已存在');
		$starttime=time();
		$endtime=D('Cloud')->cloudyear($cloud_product['id'],$ary_post['year'],$starttime);
		$masterid=$cloud_product['masterid'];
		$resource_pool_id=$cloud_product['cid'];
		$template_uuid=$cloud_tem['templateuuid'];
		if ($ary_post['dlip']==1){
			$networks=$cloud_product['iptypeid0'];
		}
		if ($ary_post['dlip']==2){
			$networks=$cloud_product['iptypeid1'];
		}
		$n_cpu=$ary_post['cpu'];
		$m_memory=$ary_post['mem'];
		$bandwidth=$ary_post['qos']*128;
		$result=D('Cloudapi')->vmcreatefromtemplate($masterid,$resource_pool_id,$vm_name,$template_uuid,$networks,$n_cpu,$m_memory,$bandwidth);
		if ($result['status']!='success') $this->error($result['value']);
		$vm_id=$result['value'];
		$Cloud_Old=D('Cloud')->where(array('id'=>$cloud_tem['pid'],'user_id'=>$this->uid))->find();
		$clouddata=array(
			'user_id'=>$this->uid,
			'username'=>$this->username,
			'Cloudtype'=>$cloud_product['Cloudtype'],
			'masterid'=>$cloud_product['masterid'],
			'cid'=>$cloud_product['cid'],
			'cloudname'=>$vm_name,
			'cloudpassword'=>$Cloud_Old['cloudpassword'],
			'vm_id'=>$vm_id,
			'osname'=>$Cloud_Old['osname'],
			'isrebate'=>0,
			'isrebatetime'=>$endtime,
			'istest'=>'n',
			'starttime'=>$starttime,
			'endtime'=>$endtime
		);
		$clouddata['status']="正常";
		$cloudid=D('Cloud')->add($clouddata);
		$ordernumber=getordcode();
		$data=array(
			'user_id'=>$this->uid,
			'username'=>$this->username,
			'ordernumber'=>$ordernumber,
			'type'=>1,
			'ordertype'=>"云主机开通",
			'producttype'=>$cloud_product['Cloudtype'],
			'usermoney'=>$endmoney,
			'year'=>$ary_post['year'],
			'masterid'=>$cloud_product['masterid'],
			'cid'=>$cloud_product['cid'],
			'cloudname'=>$vm_name,
			'cloudpassword'=>$Cloud_Old['cloudpassword'],
			'ipnum'=>$ary_post['dlip'],
			'cpunum'=>$n_cpu,
			'memnum'=>$m_memory,
			'disknum'=>$ary_post['disk'],
			'qosnum'=>$ary_post['qos'],
			'dlip'=>$ary_post['dlip'],
			'image_uuid'=>"",
			'whichProduct'=>"Cloud产品",
			'isrebate'=>0,
			'addtime'=>time(),
			'logfor'=>'用户区自定义镜像开通云主机云主机ID:'.$cloudid,
			'pid'=>$cloudid,
			'status'=>2,
			'starttime'=>$starttime,
			'endtime'=>$endtime,
		);
		$order=D('order')->add($data);
		$user_id=$this->uid;
        $money=$endmoney;
        $forwhat="用户区自定义镜像开通云主机云主机ID:".$vm_name."(订单号".$ordernumber.")";
        $whichProduct="Cloud产品";
        $pid=$cloudid;//产品ID
        $type=4;//1在线充值 2后台入款 3后台扣除 4开通扣除 5续费扣除 6升级配置 7降级配置   10充值卡$type
        $pingzheng="";
        $acspace="用户区";
        $isadd=2;//1入款 2出 $isadd
        $ptype=$cloud_product['Cloudtype'];//产品类型
        $orderid=$ordernumber;//交易号或者订单号
        $paddtime=$starttime; //产品开通时间 $paddtime
        $endtime=$endtime;  //产品到期时间 $pendtime 
        D('User')->addmoney($user_id,$money,$forwhat,$whichProduct,$pid,$type,$pingzheng,$acspace,$isadd,$ptype,$orderid,$paddtime,$endtime);
		//发送短信通知
		D('Sendsms')->Sendcloudopen($this->username,$money,$vm_name,$starttime,$endtime);
		//发送邮件
		D('Sendmail')->Sendcloudopen($this->username,$money,$vm_name,$starttime,$endtime);
		D('Cloud')->updatevminfo($cloudid,$vm_id);//云主机详情
		D('Cloud')->updatediskinfo($cloudid,$vm_id);//云主机存储列表
		D('Cloud')->updateipqosinfo($cloudid,$vm_id);//云主机存储列表
        $this->success("云主机开通成功",U('User/Cloud/index'));
			
	}
	public function price(){
		$id=I('id',0,'htmlspecialchars');
		$cpu=I('cpu',0,'htmlspecialchars');
		$mem=I('mem',0,'htmlspecialchars');
		$disk=I('disk',0,'htmlspecialchars');
		$iptype=I('iptype',0,'htmlspecialchars');
		$qos=I('qos',0,'htmlspecialchars');
		$year=I('year',0,'htmlspecialchars');
		if (empty($id))exit();
		if (empty($cpu))exit();
		if (empty($mem))exit();
		if (empty($disk))exit();
		if (empty($iptype))exit();
		if (empty($qos))exit();
		$cloud_tem=D('cloud_tem')->where(array('id'=>$id,'user_id'=>$this->uid))->find();
		if (empty($cloud_tem))$this->error("数据不存在");
		$cloud_product=D('cloud_product')->where(array('Cloudtype'=>$cloud_tem['cloudtype']))->find();
		if (empty($cloud_product))$this->error("产品配置不存在");
		$data=D('Cloud')->cloudprice($cloud_product['id'],$cpu,$mem,$disk,$iptype,$qos,$year,$this->uid);
		echo json_encode($data);
		exit();
	}
	
	public function del(){
		$id=I('id','0');
		$cloud_tem=D('cloud_tem')->where(array('id'=>$id,'user_id'=>$this->uid))->find();
		if ($cloud_tem){
			$result=D('Cloudapi')->vmsnapshotdelete($cloud_tem['masterid'],$cloud_tem['vmid'],$cloud_tem['templateuuid']);
			if ($result['status']!='success'){
				if (strpos($result['value'],"无效")){
					D('cloud_tem')->where(array('templateuuid'=>$cloud_tem['templateuuid']))->delete();
				}
			}else{
				D('cloud_tem')->where(array('templateuuid'=>$cloud_tem['templateuuid']))->delete();
			}
			sleep(5);//休眠
			$this->success("操作完成");
		}else{
			$this->success("操作失败");
		}
		
	}
}
?>