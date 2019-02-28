<?php
class VlanAction extends MainuserAction{
	public function index(){
		$ary_get=$_GET;
		$where="c.user_id=".$this->uid." and c.username='".$this->username."'";
		$count = D("cloud_vlan")->table(C("DB_PREFIX")."cloud_vlan as c")->where($where)->count();
		$obj_page =$this->UserPage($count,10);
        $pageinfo = $obj_page->show();
		$data = D("cloud_vlan")->table(C("DB_PREFIX").'cloud_vlan as c')
				->join(C("DB_PREFIX")."cloud_product as p on c.pid = p.id")
				->field("c.*,p.Cloudtype")
				->where($where)
				->order('c.id desc')
        		->limit($obj_page->firstRow, $obj_page->listRows)
				->select();	
		$this->assign("pageinfo",$pageinfo);
		$this->assign('data',$data);
		$this->display();
	}
	//
	public function add(){
		if (IS_POST){
			$ary_post=$_POST;
			$ary_post['user_id']=$this->uid;
			$ary_post['username']=$this->username;
			D('Cloud_vlan')->add($ary_post);
			$this->success("操作完成");
			exit();
		}
		$cloud_product=D('Cloud_product')->where()->select();
		$this->assign('cloud_product',$cloud_product);
		$this->display();
	}
	public function handle(){
		$id=I('id','0','htmlspecialchars');
		if(empty($id))$this->error("ID错误");
		$where="c.user_id=".$this->uid." and c.username='".$this->username."'";
		$data = D("cloud_vlan")->table(C("DB_PREFIX").'cloud_vlan as c')
				->join(C("DB_PREFIX")."cloud_product as p on c.pid = p.id")
				->field("c.*,p.Cloudtype")
				->where($where)
				->order('c.id desc')
				->find();
		//查询当前产品下的所有正常的云主机
		//查询当前产品类型下所有已经组建的产品ID
		$map['pid']=array('eq',$data['pid']);
		$map['id']=array('neq',$data['id']);
		$cloud_vlan_list=D('cloud_vlan')->where($map)->select();
		$c_ids="";
		foreach ($cloud_vlan_list as $k=>$v){
			$cloudidarr=json_decode($v['cloudid'],true);
			if (empty($c_ids)){
				$c_ids=implode(',', $cloudidarr);
			}else {
				$c_ids=$c_ids.",".implode(',', $cloudidarr);
			}
			
		}	
		$wherecloud="status='正常' and user_id=".$this->uid." and username='".$this->username."' and Cloudtype='".$data['Cloudtype']."'";
		if (!empty($c_ids)){
			//2015-6-18修改
			$c_ids_arr=explode(',',$c_ids);
			foreach($c_ids_arr as $k=>$v){
				if(empty($v)){
					unset($c_ids_arr[$k]);
				}
			}
			$c_ids=implode(",",$c_ids_arr);
			//2015-6-18修改
			$wherecloud=$wherecloud." and id not in(".$c_ids.")";
		}
		$cloud=D('Cloud')->where($wherecloud)->select();
		$data['cloudarr']=json_decode($data['cloudid'],true);
		$this->assign('data',$data);
		$this->assign('cloud',$cloud);
		$this->display();
	}
	public function handlesave(){
		$ary_post=$_POST;
		$checkcloud=$ary_post['checkcloud'];
		if (count($checkcloud)<2)$this->error("组建私有网络不能少于2台云主机");
		$id=$ary_post['id'];
		$modvlan=D('cloud_vlan');
		$vlan=$modvlan->where(array('user_id'=>$this->uid,'id'=>$id))->find();
		if (empty($vlan))$this->error("私有网络错误ID");
		$cloud_product=D('cloud_product')->where(array('id'=>$vlan['pid']))->find();
		if (empty($cloud_product))$this->error("产品配置不存在");
		$iptypeidvlan=$cloud_product['iptypeidvlan'];
		$iptypeidvlanqos=$cloud_product['iptypeidvlanqos'];
		$mod=D('Cloud');
		$start=0;
		$setmac="";
		foreach ($checkcloud as $k=>$v){
			$cloud=$mod->where(array('user_id'=>$this->uid,'id'=>$v))->find();
			if (!empty($cloud)){
				$result=D('Cloudapi')->cloudnetwork($cloud['masterid'],$cloud['vm_id']);
				if ($result['status']!='success') $this->error($result['value']."id".$v);
				$shared=false;
				foreach ($result['value'] as $kk=>$vv){
					if ($vv['shared']){					
						$shared=true;
						if ($setmac==""){
							$setmac=$vv['MAC'];
						}else{
							$setmac=$setmac.",".$vv['MAC'];
						}
					}
				}
				if ($shared==false){
					//创建私有网卡
					$ip_ids_arr=D('Cloudapi')->listresourceip($cloud['masterid'],$cloud['cid'],$iptypeidvlan,0,0,1);
					if ($ip_ids_arr['status']!='success') $this->error($ip_ids_arr['value']."id".$v);
					if (count($ip_ids_arr['value'])==0)$this->error("私有网络IP获取失败");
					$ip_ids=$ip_ids_arr['value'][0][ipid];
					$start=$start+1;
					$resultcreate=D('Cloudapi')->vmnetworkcreate($cloud['masterid'],$cloud['vm_id'],$ip_ids,$iptypeidvlanqos*128,true);				
					if ($resultcreate['status']!='success') $this->error($resultcreate['value']."id".$v);
					if ($setmac==""){
						$setmac=$resultcreate['value'][0]['mac'];
					}else{
						$setmac=$setmac.",".$resultcreate['value'][0]['mac'];
					}
					sleep(3);
				}
			}
		}
		//清除原有的私有网络
		if (!empty($vlan['cloudid'])){
			$cloudidarr=json_decode($vlan['cloudid'],true);
			foreach ($cloudidarr as $kkk=>$vvv){
			$clouds=$mod->where(array('user_id'=>$this->uid,'id'=>$vvv))->find();
				if (!empty($clouds)){
					$results=D('Cloudapi')->cloudnetwork($clouds['masterid'],$clouds['vm_id']);
					if ($results['status']!='success') $this->error($results['value']."id".$vvv);
					foreach ($results['value'] as $kkkk=>$vvvv){
						if ($vvvv['shared']){	
							D('Cloudapi')->networkrulesset($clouds['masterid'],$clouds['cid'],$vvvv['MAC']);
						}
						sleep(2);
					}
				}
			}
		}
		$cloudjson=json_encode($checkcloud);
		$networkrulesset=D('Cloudapi')->networkrulesset($cloud_product['masterid'],$cloud_product['cid'],$setmac);
		if ($networkrulesset['status']!='success') $this->error($networkrulesset['value']);
		$vlans=$modvlan->where(array('user_id'=>$this->uid,'id'=>$id))->save(array('cloudid'=>$cloudjson));
		$this->success("操作完成");
	}
	public function del(){
		$id=I('id','0','htmlspecialchars');
		if(empty($id))$this->error("ID错误");
		$modvlan=D('cloud_vlan');
		$mod=D('Cloud');
		$vlan=$modvlan->where(array('user_id'=>$this->uid,'id'=>$id))->find();
		if(!empty($vlan['cloudid'])){
			$cloudidarr=json_decode($vlan['cloudid'],true);
			foreach ($cloudidarr as $kkk=>$vvv){
			$clouds=$mod->where(array('user_id'=>$this->uid,'id'=>$vvv))->find();
				if (!empty($clouds)){
					$results=D('Cloudapi')->cloudnetwork($clouds['masterid'],$clouds['vm_id']);
					if ($results['status']!='success') $this->error($results['value']."id".$vvv);
					foreach ($results['value'] as $kkkk=>$vvvv){
						if ($vvvv['shared']){	
							D('Cloudapi')->networkrulesset($clouds['masterid'],$clouds['cid'],$vvvv['MAC']);
						}
						sleep(2);
					}
				}
			}
		}
		$modvlan->where(array('user_id'=>$this->uid,'id'=>$id))->delete();
		$this->success("操作完成");
		
	}
}
?>