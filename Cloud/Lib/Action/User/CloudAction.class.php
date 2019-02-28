<?php
//云主机管理
class CloudAction extends MainuserAction{
	public function index(){
		$ary_get=$_GET;
		$where="c.user_id=".$this->uid." and c.username='".$this->username."'";
		if(!empty($ary_get['cloudname'])){
			$ary_get['cloudname']=I('cloudname','','htmlspecialchars');
			$where=$where." and c.cloudname like '%".$ary_get['cloudname']."%'";
		}
		if(!empty($ary_get['starttime'])){
			$starttime=str_replace("+"," ",$ary_get['starttime']);	
			$starttime=convert_datefm($starttime);
			$starttime=intval($starttime);//预防sql注入3-25
			$where=$where." and c.addtime >=".$starttime;
			$this->assign("starttime",date('Y-m-d', $starttime));
		}
		if(!empty($ary_get['endtime'])){
			$endtime=str_replace("+"," ",$ary_get['endtime']);	
			$endtime=convert_datefm($endtime);
			$endtime=intval($endtime);//预防sql注入3-25
			$where=$where." and c.addtime <=".($endtime+86400);
			$this->assign("endtime",date('Y-m-d', $endtime));
		}
		$count = D("cloud")->table(C("DB_PREFIX")."cloud as c")->where($where)->count();
		$obj_page =$this->UserPage($count,10);
        $pageinfo = $obj_page->show();
		$data = D("cloud")->table(C("DB_PREFIX").'cloud as c')
				->join(C("DB_PREFIX")."cloud_product as p on c.Cloudtype = p.Cloudtype")
				->field("c.*,p.jfname")
				->where($where)
				->order('c.endtime desc')
        		->limit($obj_page->firstRow, $obj_page->listRows)
				->select();	
		$result=array();
		foreach ($data as $k=>$v){
			$cloud_auto=D('cloud_auto')->where(array('pid'=>$v['id']))->find();
			$result[$k]=$v;
			$result[$k]['isauto']=$cloud_auto['isauto'];			
		}
		$this->assign("pageinfo",$pageinfo);
		$this->assign('cloudname',$ary_get['cloudname']);
		$this->assign('data',$result);
		$this->display();
	}
	public function cloudact(){
		$id=I('id','0','htmlspecialchars');
		$act=I('act','p','htmlspecialchars');
		//获取最新云主机信息
		
		$cloud=D('cloud')->where(array('id'=>$id,'user_id'=>$this->uid,'username'=>$this->username))->find();
		if(!$cloud)$this->error('云主机不存在');
		if ($cloud['status']=='已删除')$this->error("已删除云主机禁止管理");
		if ($cloud['status']=='配置中')$this->error("配置中云主机禁止管理");
		if ($cloud['status']=='开通失败')$this->error("配置中云主机禁止管理");
		D('Cloud')->updatevminfo($cloud['id'],$cloud['vm_id']);
		D('Cloud')->updatediskinfo($cloud['id'],$cloud['vm_id']);
		D('Cloud')->updateipqosinfo($cloud['id'],$cloud['vm_id']);
		if ($act=='p'){
			$this->redirect('User/Cloudrepay/index',array('id'=>$id));
			exit();
		}
		//调整
		if ($act=='a'){
			if ($cloud['endtime']<time()){
				$this->error("已过期中云主机禁止调整");
			}
			$this->redirect('User/Cloudup/index',array('id'=>$id));
			exit();
		}
		//调整
		if ($act=='b'){
			if ($cloud['endtime']<time()){
				$this->error("已过期中云主机禁止调整");
			}
			$this->redirect('User/Clouddown/index',array('id'=>$id));
			exit();
		}
		//管理
		if ($act=='m'){
			$result=D('Cloudapi')->cloudupdate($cloud['vm_id']);//获取最新信息
			if ($result['status']=='failed')$this->error($result['value']);
			$postdata['apiusername']=C('APIUSERNAME');
			$postdata['randomstring']=D('Cloudapi')->randstrs();
			$postdata['sign']=md5(C('APIKEY').$postdata['randomstring'].$cloud['cloudname'].$result['value']['cloudpassword']);
			$postdata['vm_name']=$cloud['cloudname'];
			$postdata['vm_id']=$cloud['vm_id'];
			$postdata['logouturl']=C('api_logout_url');//退出返回地址
			$postdata['host']=C('API_MANAGE_LOGIN');
			foreach($postdata as $key=>$value){
				$posturl.=$key.'='.urlencode($value)."&";
			}
			$this->assign("data",$postdata);
			$this->display('loginmanage');
		}
	}
	public function vmstate(){
		$act=I('act','vmstate','htmlspecialchars');
		$id=I('id','0','htmlspecialchars');
		if (empty($id)){
			echo 0;
			exit;
		}
		$cloud=D('Cloud')->where(array('id'=>$id,'user_id'=>$this->uid,'username'=>$this->username))->find();
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
				$data['info']='重新获取';
				echo json_encode($data);
				exit;
		}
	}
	
}
?>