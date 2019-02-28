<?php
/**
 * 负载均衡
 * Enter description here ...
 * @author Geyoulei
 */
class LoadbAction extends SaleadminAction{
	public function index(){
		$mod = M("loadb");
		$ary_get = $this->_get();
		$userid=$this->userid($this->kid);
		$where=" 1=1 and c.user_id in (".$userid.")";
		$ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', C('PAGE_COUNT'));
		if (!empty($ary_get['username'])){
			$where=$where." and c.username='".$ary_get['username']."'";
			$this->assign("username", $ary_get['username']);
		}
		if (!empty($ary_get['cloudname'])){
			$where=$where." and c.cloudname='".$ary_get['cloudname']."'";
			$this->assign("cloudname", $ary_get['cloudname']);
		}
		if (!empty($ary_get['status']))$where=$where." and c.status='".urldecode($ary_get['status'])."'";
		if (!empty($ary_get['istest']))$where=$where." and c.istest='".$ary_get['istest']."'";
		if (!empty($ary_get['day'])){
			$contestday=$ary_get['day'];
			$endtime=strtotime("+$contestday day", time());
			//echo $endtime;
			$where=$where." and c.endtime<'".$endtime."'";
		}	
		$count = $mod->table(C("DB_PREFIX")."loadb as c")->where($where)->count();
        $obj_page =$this->AdminPage($count, $ary_get['pageall']);
        $page = $obj_page->show();
		$data = $mod->table(C("DB_PREFIX").'loadb as c')
				->join(C("DB_PREFIX")."loadb_product as cp on cp.Cloudtype = c.Cloudtype")
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
	
	public function manage(){
		$id=I('id','0','htmlspecialchars');
		if (!empty($id)){
			$cloud=D('Loadb')->where(array('id'=>$id))->find();
			if (!$cloud){
				$this->redirect("Sale/Loadb/index");
			}
			if (!$this->usercheck($cloud['user_id'],$this->kid)){
				$this->redirect("Sale/Loadb/index");;
			}
			$vminfos=D('Cloudapi')->cloudinfo($cloud['masterid'],$cloud['vm_id']);//云主机详情
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
			$cloudlist=D('Loadb')->where(array('user_id'=>$user['user_id'],'id'=>$cloud['id']))->select();
			$Cloud_product=D('Loadb_product')->where(array())->select();
			$this->assign('Cloudproduct',$Cloud_product);
			$this->assign('cloudlist',$cloudlist);
			$this->assign('user',$user);
			$this->assign('id',$id);
			$this->assign('cloud',$cloud);
			$this->display();
			exit();
		}
	}
	public function vmstate(){
		$act=I('act','vmstate','htmlspecialchars');
		$id=I('id','0','htmlspecialchars');
		if (empty($id)){
			echo 0;
			exit;
		}
		//查询当前产品是否存在
		$cloud=D('Loadb')->where(array('id'=>$id))->find();
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
		if ($act=='vmstate'){
			$vminfo=D('Cloudapi')->cloudinfo($cloud['masterid'],$cloud['vm_id']);//云主机详情
			if($vminfo){
				if($vminfo['status']=='success'){
					$dataarr=$vminfo['value'];
					$data['cloudstatus']=true;
					$endarr=array_merge($data,$dataarr);
					echo json_encode($endarr);
					exit;
				}else{
					$data['cloudstatus']=false;
					$data['info']=$vminfo['value'];
					echo json_encode($data);
					exit;
				}
			}
			$data['cloudstatus']=false;
			$data['info']="nocloud";
			echo json_encode($data);
			exit;
		}
		if ($act=='vmnetwork'){
			$ipqosinfo=D('Cloudapi')->cloudnetwork($cloud['masterid'],$cloud['vm_id']);//云主机详情
			if($ipqosinfo){
				if($ipqosinfo['status']=='success'){
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
			}
			$data['cloudstatus']=false;
			$data['info']=$ipqosinfo['value'];
			echo json_encode($data);
			exit;
		}
	}
}
?>