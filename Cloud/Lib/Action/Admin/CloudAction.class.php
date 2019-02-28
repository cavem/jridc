<?php
/**
 * 云主机管理
 * Enter description here ...
 * @author Geyoulei
 */
class CloudAction extends AdminAction{
	public function index(){
		$mod = M("cloud");
		$ary_get = $this->_get();
		$ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', C('PAGE_COUNT'));
		$where=" 1=1 ";
		if (!empty($ary_get['status']))$where=$where." and c.status='".urldecode($ary_get['status'])."'";
		if (!empty($ary_get['istest']))$where=$where." and c.istest='".$ary_get['istest']."'";
		if (!empty($ary_get['day'])){
			$contestday=$ary_get['day'];
			$endtime=strtotime("+$contestday day", time());
			//echo $endtime;
			$where=$where." and c.endtime<'".$endtime."'";
		}	
		$count = $mod->table(C("DB_PREFIX")."cloud as c")->where($where)->count();
        $obj_page =$this->AdminPage($count, $ary_get['pageall']);
        $page = $obj_page->show();
		$data = $mod->table(C("DB_PREFIX").'cloud as c')
				->join(C("DB_PREFIX")."cloud_product as cp on cp.Cloudtype = c.Cloudtype")
				->join(C("DB_PREFIX")."user as u on u.user_id = c.user_id")
				->join(C("DB_PREFIX")."user_rank as uk on uk.rank_id = u.user_rank")
				->join(C("DB_PREFIX")."kefu as k on k.id = u.kid")
				->field("c.*,u.username,k.kefuname,uk.rank_name,cp.jfname")
				->where($where)
        		->limit($obj_page->firstRow, $obj_page->listRows)
				->select();
        $this->assign("data", $data);
        $this->assign("page", $page);
		$this->display();
	}
	public function incloud(){
		if (IS_POST){
			$data=$_POST;
			if (empty($data['username']))$this->error("用户不能为空");
			$user=D('User')->where(array('username'=>$data['username']))->find();
			if (!$user)$this->error("用户不存在");
			$clouds=D('Cloud')->where(array('vm_id'=>$data['vm_id'],'status'=>'正常'))->find();
			if ($clouds)$this->error("云主机已存在");
			$cloud_product=D('cloud_product')->where(array('id'=>$data['newcloudpid']))->find();
			if (empty($cloud_product))$this->success("产品不存在");
			$result=D('Cloudapi')->cloudupdate($data['vm_id']);
			if ($result['status']=='failed')$this->error($result['value']);
			$adddata['user_id']=$user['user_id'];
			$adddata['username']=$user['username'];
			$adddata['cloudname']=$result['value']['cloudname'];
			$adddata['cloudpassword']=$result['value']['cloudpassword'];
			$adddata['vminfo']=$result['value']['vminfo'];
			$adddata['diskinfo']=$result['value']['diskinfo'];
			$adddata['ipqosinfo']=$result['value']['ipqosinfo'];
			$adddata['starttime']=$result['value']['starttime'];
			$adddata['endtime']=$result['value']['endtime'];
			$adddata['status']=$result['value']['status'];
			$adddata['vm_id']=$result['value']['id'];
			$adddata['Cloudtype']=$cloud_product['Cloudtype'];
			$adddata['masterid']=$cloud_product['masterid'];
			$adddata['cid']=$cloud_product['cid'];
			D('Cloud')->add($adddata);
			$this->success("操作完成");
			exit();
		}
		$Cloud_product=D('Cloud_product')->where(array())->select();
		$this->assign('Cloudproduct',$Cloud_product);
		$this->display();
	}
	public function manage(){
		$id=I('id','0','htmlspecialchars');
		if (!empty($id)){
			$cloud=D('Cloud')->where(array('id'=>$id))->find();
			if (!$cloud){
				$this->redirect("Admin/Cloud/index");
			}
			$vminfos=D('Cloudapi')->cloudinfo($cloud['vm_id']);//云主机详情
			if ($vminfos['status']=='failed'){
				$vmstatus=$vminfos['value'];
			}else{
				$vminfo=$vminfos['value'];
				$vmstatus="运行中";
		    	if (strtoupper($vminfo['power_state'])=="RUNNING")$vmstatus="运行中";
		    	if (strtoupper($vminfo['power_state'])=="HALTED")$vmstatus="已关机";
		    	if (strtoupper($vminfo['power_state'])=="BUILDING")$vmstatus="创建中";
		    	if (strtoupper($vminfo['power_state'])=="ERROR")$vmstatus="故障中";
		    	if (strtoupper($vminfo['power_state'])=="EXCEPT")$vmstatus="故障中";
			}
	    	$this->assign('vmstatus',$vmstatus);
			$user=D('user')->where(array('user_id'=>$cloud['user_id']))->find();
			$cloudlist=D('Cloud')->where(array('user_id'=>$user['user_id'],'id'=>$cloud['id']))->select();
			$Cloud_product=D('Cloud_product')->where(array())->select();
			$this->assign('Cloudproduct',$Cloud_product);
			$this->assign('cloudlist',$cloudlist);
			$this->assign('user',$user);
			$this->assign('id',$id);
			$this->assign('cloud',$cloud);
			$this->display();
			exit();
		}else{
			$username=I('username','','htmlspecialchars');
			$cloudname=I('cloudname','','htmlspecialchars');
			if (!empty($username)){
				$user=D('user')->where(array('username'=>$username))->find();
				$cloudlist=D('Cloud')->where(array('username'=>$username))->select();
			}
			if (!empty($cloudname)){
				$cloudlist=D('Cloud')->where(array('cloudname'=>$cloudname))->select();
			}
			if (!empty($cloudname) && !empty($username)){
				$cloudlist=D('Cloud')->where(array('cloudname'=>$cloudname,'username'=>$username))->select();
			}
			$this->assign('user',$user);
			$this->assign('cloudlist',$cloudlist);
			$this->display();
			exit();
		}
	}
	public function managesave(){
		$act=I('act','','htmlspecialchars');
		$id=I('id','0','htmlspecialchars');
		if (empty($act))$this->error("act错误");
		if (empty($id))$this->error("ID错误");
		if ($act=="cloudebak"){
			$ebak=I('ebak','','htmlspecialchars');
			D('Cloud')->where(array('id'=>$id))->save(array('corewhat'=>$ebak));
			//写入系统日志
			D('Systemlog')->adddata('cloud-sysadmin',"管理员设置备注"."云主机名(".$cloud['cloudname']."操作人:".$_SESSION['admin_name']);
			$this->success("操作完成");
			exit();
		}		
		if ($act=="cloudrmoneyno"){
			D('Cloud')->where(array('id'=>$id))->save(array('repaymoney'=>null));
			//写入系统日志
			D('Systemlog')->adddata('cloud-sysadmin',"管理员取消单独续费"."云主机名(".$cloud['cloudname']."操作人:".$_SESSION['admin_name']);
			$this->success("操作完成");
			exit();
		}
		if ($act=="cloudrmoney"){
			$repaymoney=I('repaymoney','','htmlspecialchars');
			if (empty($repaymoney))$this->error("续费价格不能为空 ");
			D('Cloud')->where(array('id'=>$id))->save(array('repaymoney'=>$repaymoney));
			//写入系统日志
			D('Systemlog')->adddata('cloud-sysadmin',"管理员设置单独续费"."云主机名(".$cloud['cloudname'].")金额:".$repaymoney."操作人:".$_SESSION['admin_name']);
			$this->success("操作完成");
			exit();
		}
		if ($act=='cloudzy'){
			$cloud=D('Cloud')->where(array('id'=>$id))->find();
			if (!$cloud)$this->error("云主机不存在");
			$username=I('username','','htmlspecialchars');
			if (empty($username))$this->error("新 用户不能为空");
			$user=D('User')->where(array('username'=>$username))->find();
			if (!$user)$this->error("新用户不存在");
			$new_user_id=$user['user_id'];
			$new_username=$user['username'];
			D('Cloud')->where(array('id'=>$id))->save(array('user_id'=>$new_user_id,'username'=>$new_username));
			//写入系统日志
			D('Systemlog')->adddata('cloud-sysadmin',"管理员转移云主机"."云主机名(".$cloud['cloudname'].")旧用户:".$cloud['username']."新用户:".$new_username."操作人:".$_SESSION['admin_name']);
			$this->success("操作完成");
			exit();
		}
		if ($act=="open"){
			$cloud=D('Cloud')->where(array('id'=>$id))->find();
			$result=D('Cloudapi')->cloudstart($cloud['vm_id']);
			if ($result['status']=='failed')$this->error($result['value']);
			$this->success("开机完成");
			exit();
		}
		if ($act=="close"){
			$cloud=D('Cloud')->where(array('id'=>$id))->find();
			$result=D('Cloudapi')->cloudstop($cloud['vm_id']);
			if ($result['status']=='failed')$this->error($result['value']);
			$this->success("开机完成");
			exit();
		}
		if ($act=="reboot"){
			$cloud=D('Cloud')->where(array('id'=>$id))->find();
			$result=D('Cloudapi')->cloudreboot($cloud['vm_id']);
			if ($result['status']=='failed')$this->error($result['value']);
			$this->success("开机完成");
			exit();
		}
		if ($act=="cloudupdate"){
			$cloud=D('Cloud')->where(array('id'=>$id))->find();
			$result=D('Cloudapi')->cloudupdate($cloud['vm_id']);
			if ($result['status']=='failed')$this->error($result['value']);
			$updata['cloudname']=$result['value']['cloudname'];
			$updata['cloudpassword']=$result['value']['cloudpassword'];
			$updata['vminfo']=$result['value']['vminfo'];
			$updata['diskinfo']=$result['value']['diskinfo'];
			$updata['ipqosinfo']=$result['value']['ipqosinfo'];
			$updata['starttime']=$result['value']['starttime'];
			$updata['endtime']=$result['value']['endtime'];
			$updata['status']=$result['value']['status'];
			D('Cloud')->where(array('id'=>$id))->save($updata);
			$this->success("同步完成");
			exit();
		}
		if ($act=="cloudedel"){
			D('Cloud')->where(array('id'=>$id))->delete();
			//写入系统日志
			D('Systemlog')->adddata('cloud-sysadmin',"管理员删除云主机"."云主机名(".$cloud['cloudname'].")操作人:".$_SESSION['admin_name']);
			$this->success("操作完成");		
			exit();
		}
		if ($act=="updatepro"){
			$cloud=D('Cloud')->where(array('id'=>$id))->find();
			$oldCloudtype=$cloud['Cloudtype'];
			$oldid=$cloud['id'];
			$newcloudpid=I('newcloudpid','','htmlspecialchars');
			$cloud_product=D('cloud_product')->where(array('id'=>$newcloudpid))->find();
			if (empty($cloud_product))$this->success("产品不存在");
			$newCloudtype=$cloud_product['Cloudtype'];
			D('Cloud')->where(array('id'=>$id))->save(array('Cloudtype'=>$newCloudtype,'masterid'=>$cloud_product['masterid'],'cid'=>$cloud_product['cid']));
			D('order')->where(array('pid'=>$id,'type'=>1))->save(array('producttype'=>$newCloudtype,'masterid'=>$cloud_product['masterid'],'cid'=>$cloud_product['cid']));
			D('money_log')->where(array('pid'=>$id,'whichProduct'=>'Cloud产品'))->save(array('ptype'=>$newCloudtype));
			//写入系统日志
			D('Systemlog')->adddata('cloud-sysadmin',"管理员转移云主机所属产品"."云主机名(".$cloud['cloudname'].")旧产品(".$oldCloudtype.")新(".$newCloudtype.")操作人:".$_SESSION['admin_name']);
			$this->success("操作完成");
			exit();
		}
		
		$this->success("操作完成");
				
	}
	public function vmstate(){
		$act=I('act','vmstate','htmlspecialchars');
		$id=I('id','0','htmlspecialchars');
		if (empty($id)){
			echo 0;
			exit;
		}
		//查询当前产品是否存在
		$cloud=D('Cloud')->where(array('id'=>$id))->find();
		if (empty($cloud)){
			echo 0;
			exit;
		}
		$data=array();
		$data['id']=$cloud['id'];
		$data['statusinfo']=$cloud['status'];
		if ($cloud['status']=="配置中"){
					$data['cloudstatus']=false;
					$data['info']="配置中";
					echo json_encode($data);
					exit;
		}
		if ($cloud['status']=="开通失败"){
					$data['cloudstatus']=false;
					$data['info']="开通失败";
					echo json_encode($data);
					exit;
		}
		if ($cloud['status']=="已删除"){
					$data['cloudstatus']=false;
					$data['info']="已删除";
					echo json_encode($data);
					exit;
		}
		if ($act=='vmnetwork'){
			if (empty($cloud['ipqosinfo'])){
				D('Cloud')->updateipqosinfo($cloud['id'],$cloud['vm_id']);			
			}
			$cloud=D('Cloud')->where(array('id'=>$cloud['id']))->find();
			$ipqosinfo=json_decode($cloud['ipqosinfo'],true);
			if($ipqosinfo){
				if($ipqosinfo['status']=='success'){
					D('Cloud')->updateipqosinfo($cloud['id'],$cloud['vm_id']);
					$ipqosinfovalue=$ipqosinfo['value'];
					$endip="";
					foreach ($ipqosinfovalue as $k=>$v){
						if ($v['shared']==false){
							foreach ($v['ip_infos'] as $kk=>$vv){
								if (!$endip){
									$endip=$vv['ip'];
								}else{
									$endip=$endip."<br>".$vv['ip'];
								}
							}
						}
					}
					$data['cloudstatus']=true;
					$data['value']=$endip;
					echo json_encode($data);
					exit;
				}
				$data['cloudstatus']=false;
				$data['info']=$ipqosinfo['value'];
				echo json_encode($data);
				exit;
			}
			$data['cloudstatus']=false;
			$data['info']='请重新获取';
			echo json_encode($data);
			exit;
		}
	}
	
}
?>