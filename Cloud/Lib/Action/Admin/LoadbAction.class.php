<?php
/**
 * 负载均衡
 * Enter description here ...
 * @author Geyoulei
 */
class LoadbAction extends AdminAction{
	public function index(){
		$mod = M("loadb");
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
	//连接数设置
	public function con(){
		$Mod=D('loadb_con');
		$data = $Mod->table(C("DB_PREFIX")."loadb_con l")
				->field('l.*')
				->order('l.sort asc')
        		->where()
				->select();
		$this->assign('data',$data);
		$this->display('coni');
	}
	public function conadd(){
		$mod = M("loadb_con");
		if (IS_POST){
			$arr_post = $this->_post();
			$add_rs = $mod->add($arr_post);
			if(FALSE !== $add_rs){
				$this->success("添加成功",U('Admin/Loadb/con'));
			}else{
				$this->error("添加失败");
			}
			exit();
		}
		$this->display();
	}
	public function conedit(){
		if (IS_POST){
			$data=$_POST;
			$id=$data['id'];
			unset($data['id']);
			$info=D('loadb_con')->where(array('id'=>$id))->save($data);
			if (FALSE !==$info){
				 $this->success('操作成功');
			}else{
				 $this->error('操作失败');
			}
			exit();	
		}
		$id=I('id','');
		$ary_get = $this->_get();
		$data=D('loadb_con')->where(array('id'=>$id))->find();
		$this->assign("data",$data);
		$this->display();
	}
	public function condel(){
		$id=I('id','');
		if ($id){
			$loadbcon=D('loadb_con');
			$ary_result=$loadbcon->where(array('id'=>$id))->delete();
			if ($ary_result){
				$this->success('操作成功');
			}else{
				$this->error('操作失败');
			}	
		}else{
			$this->error('删除项不能为空!');
		}
		
	}
	public function manage(){
		$id=I('id','0','htmlspecialchars');
		if (!empty($id)){
			$cloud=D('Loadb')->where(array('id'=>$id))->find();
			if (!$cloud){
				$this->redirect("Admin/Loadb/index");
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
		}else{
			$username=I('username','','htmlspecialchars');
			$cloudname=I('cloudname','','htmlspecialchars');
			if (!empty($username)){
				$user=D('user')->where(array('username'=>$username))->find();
				$cloudlist=D('Loadb')->where(array('username'=>$username))->select();
			}
			if (!empty($cloudname)){
				$cloudlist=D('Loadb')->where(array('cloudname'=>$cloudname))->select();
			}
			if (!empty($cloudname) && !empty($username)){
				$cloudlist=D('Loadb')->where(array('cloudname'=>$cloudname,'username'=>$username))->select();
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
		if ($act=="cloudtime"){
			$starttime=I('starttime','','htmlspecialchars');
			$endtime=I('endtime','','htmlspecialchars');
			$usermoney=I('usermoney','','htmlspecialchars');
			if (empty($starttime))$this->error("开始时间不能为空");
			if (empty($endtime))$this->error("结束时间不能为空");
			$starttime=convert_datefm($starttime);
			$endtime=convert_datefm($endtime);
			if ($starttime>$endtime)$this->error("开始时间不能大于结束时间");
			$cloud=D('Loadb')->where(array('id'=>$id))->find();
			D('Loadb')->where(array('id'=>$id))->save(array('starttime'=>$starttime,'endtime'=>$endtime));
			if (!empty($usermoney)){
					$forwhat="管理员调整时间负载均衡名:".$cloud['cloudname']."操作人:".$_SESSION['admin_name'];
				    $whichProduct="负载均衡";
			        $pid=$cloud['id'];//产品ID
			        $type=3;//1在线充值 2后台入款 3后台扣除 4开通扣除 5续费扣除 6升级配置 7降级配置   10充值卡$type
			        $pingzheng="";
			        $acspace="管理区";
			        $isadd=2;//1入款 2出 $isadd
			        $ptype=$cloud['Cloudtype'];//产品类型
			        $orderid=0;//交易号或者订单号
			        $paddtime=$starttime; //产品开通时间 $paddtime
			        $endtime=$endtime;  //产品到期时间 $pendtime 
			        D('User')->addmoney($cloud['user_id'],$usermoney,$forwhat,$whichProduct,$pid,$type,$pingzheng,$acspace,$isadd,$ptype,$orderid,$paddtime,$endtime);
			}
			D('Systemlog')->adddata('cloud-sysadmin',"管理员调整时间"."负载均衡名(".$cloud['cloudname']."操作人:".$_SESSION['admin_name']);
			$this->success("操作完成");
			exit();
		}
		if ($act=="cloudebak"){
			$ebak=I('ebak','','htmlspecialchars');
			D('Loadb')->where(array('id'=>$id))->save(array('corewhat'=>$ebak));
			//写入系统日志
			D('Systemlog')->adddata('cloud-sysadmin',"管理员设置备注"."负载均衡名(".$cloud['cloudname']."操作人:".$_SESSION['admin_name']);
			$this->success("操作完成");
			exit();
		}
		if ($act=="cloudrmoneyno"){
			D('Loadb')->where(array('id'=>$id))->save(array('repaymoney'=>null));
			//写入系统日志
			D('Systemlog')->adddata('cloud-sysadmin',"管理员取消单独续费"."负载均衡名(".$cloud['cloudname']."操作人:".$_SESSION['admin_name']);
			$this->success("操作完成");
			exit();
		}
		if ($act=="cloudrmoney"){
			$repaymoney=I('repaymoney','','htmlspecialchars');
			if (empty($repaymoney))$this->error("续费价格不能为空 ");
			D('Loadb')->where(array('id'=>$id))->save(array('repaymoney'=>$repaymoney));
			//写入系统日志
			D('Systemlog')->adddata('cloud-sysadmin',"管理员设置单独续费"."负载均衡名(".$cloud['cloudname'].")金额:".$repaymoney."操作人:".$_SESSION['admin_name']);
			$this->success("操作完成");
			exit();
		}
		if ($act=='cloudzy'){
			$cloud=D('Loadb')->where(array('id'=>$id))->find();
			if (!$cloud)$this->error("负载均衡不存在");
			$username=I('username','','htmlspecialchars');
			if (empty($username))$this->error("新 用户不能为空");
			$user=D('User')->where(array('username'=>$username))->find();
			if (!$user)$this->error("新用户不存在");
			$new_user_id=$user['user_id'];
			$new_username=$user['username'];
			D('Loadb')->where(array('id'=>$id))->save(array('user_id'=>$new_user_id,'username'=>$new_username));
			//写入系统日志
			D('Systemlog')->adddata('cloud-sysadmin',"管理员转移负载均衡"."负载均衡名(".$cloud['cloudname'].")旧用户:".$cloud['username']."新用户:".$new_username."操作人:".$_SESSION['admin_name']);
			$this->success("操作完成");
			exit();
		}
		
		if ($act=='clouddel'){
			$cloud=D('Loadb')->where(array('id'=>$id))->find();
			$clouddelresult=D('Cloudapi')->clouddel($cloud['masterid'],$cloud['vm_id']);
			if ($clouddelresult['status']=='failed'){
				D('Loadb')->where(array('id'=>$id))->delete();
				D('Systemlog')->adddata('cloud-sysadmin',"管理员删除负载均衡"."负载均衡名(".$cloud['cloudname'].")操作人:".$_SESSION['admin_name']);
				$this->error($clouddelresult['value']);
			}else{
				
				D('Loadb')->where(array('id'=>$id))->delete();
				//写入系统日志
				D('Systemlog')->adddata('cloud-sysadmin',"管理员删除负载均衡"."负载均衡名(".$cloud['cloudname'].")操作人:".$_SESSION['admin_name']);
			}
			$this->success("操作完成");
			exit();
		}
		if ($act=="open"){
			$cloud=D('Loadb')->where(array('id'=>$id))->find();
			$result=D('Cloudapi')->cloudstart($cloud['masterid'],$cloud['vm_id']);
			if ($result['status']=='failed')$this->error($result['value']);
			$this->success("操作完成");
			exit();
		}
		if ($act=="close"){
			$cloud=D('Loadb')->where(array('id'=>$id))->find();
			$result=D('Cloudapi')->cloudstop($cloud['masterid'],$cloud['vm_id']);
			if ($result['status']=='failed')$this->error($result['value']);
			$this->success("操作完成");
			exit();
		}
		if ($act=="reboot"){
			$cloud=D('Loadb')->where(array('id'=>$id))->find();
			$result=D('Cloudapi')->cloudreboot($cloud['masterid'],$cloud['vm_id']);
			if ($result['status']=='failed')$this->error($result['value']);
			$this->success("操作完成");
			exit();
		}
		if ($act=="updatepro"){
			$cloud=D('Loadb')->where(array('id'=>$id))->find();
			$oldCloudtype=$cloud['Cloudtype'];
			$oldid=$cloud['id'];
			$newcloudpid=I('newcloudpid','','htmlspecialchars');
			$cloud_product=D('Loadb_product')->where(array('id'=>$newcloudpid))->find();
			if (empty($cloud_product))$this->success("产品不存在");
			$newCloudtype=$cloud_product['Cloudtype'];
			D('Loadb')->where(array('id'=>$id))->save(array('Cloudtype'=>$newCloudtype,'masterid'=>$cloud_product['masterid'],'cid'=>$cloud_product['cid']));
			D('order')->where(array('pid'=>$id,'type'=>4))->save(array('producttype'=>$newCloudtype,'masterid'=>$cloud_product['masterid'],'cid'=>$cloud_product['cid']));
			D('money_log')->where(array('pid'=>$id,'whichProduct'=>'Cloud产品'))->save(array('ptype'=>$newCloudtype));
			//写入系统日志
			D('Systemlog')->adddata('cloud-sysadmin',"管理员转移负载均衡所属产品"."负载均衡名(".$cloud['cloudname'].")旧产品(".$oldCloudtype.")新(".$newCloudtype.")操作人:".$_SESSION['admin_name']);
			$this->success("操作完成");
			exit();
		}
		$this->success("操作完成");
				
	}
	//转向到用户主机管理
	public function actuser(){
		$id=I('id','0','htmlspecialchars');
		$cloud=D('Loadb')->where(array('id'=>$id))->find();
		$vminfos=D('Cloudapi')->cloudinfo($cloud['masterid'],$cloud['vm_id']);//云主机详情
		if ($vminfos['status']=='failed'){
			$this->error($vminfos['value']);
		}
		$this->redirect('Manageldb/Login/jspost',array('cloudname'=>$cloud['cloudname'],'pwd'=>$cloud['cloudpassword']));
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
	public function product(){
		$ary_get = $this->_get();
		$ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', C('PAGE_COUNT'));
		$cloudcdos=D('loadb_product');
		if (!empty($ary_get['masterid']))$where="O.masterid=".$ary_get['masterid'];
		if (!empty($ary_get['cid']))$where="O.cid=".$ary_get['cid'];
		$count = $cloudcdos->table(C("DB_PREFIX")."loadb_product AS O")
						 ->where($where)
						 ->count();
        $obj_page =$this->AdminPage($count, $ary_get['pageall']);
        $page = $obj_page->show();
        $ary_data=$cloudcdos->table(C("DB_PREFIX")."loadb_product AS O")
        				  ->field("O.*,M.mastername")
        				  ->join(C("DB_PREFIX")."cloud_master as M ON M.id=O.masterid")
        				  ->where($where)
        				  ->limit($obj_page->firstRow, $obj_page->listRows)
        				  ->order('O.sort desc')
        				  ->select();
        $master = D('cloud_master')->where()->order('id desc')->select();
		$this->assign('master',$master);
		$this->assign('masterid',$ary_get['masterid']);
		$this->assign('cid',$ary_get['cid']);
		$this->assign('ostype',$ary_get['ostype']);
        $this->assign("data", $ary_data);
        $this->assign("page", $page);
		$this->display();
	}
	public function productadd(){
		if (IS_POST){
			$ary_post = $this->_post();
			$dqos=$ary_post['dqos'];
			if ($dqos<1)$this->success("赠送带宽不能小于1M");
			$Cloudtype=$ary_post['Cloudtype'];
			if (empty($Cloudtype))$this->error("产品名不能为空");
			$Cinfo=D('loadb_product')->where(array('Cloudtype'=>$Cloudtype))->find();
			if ($Cinfo)$this->error("产品已存在");
			$ary_result=D('loadb_product')->add($ary_post);
			if(FALSE !== $ary_result){
				$this->success("添加成功");
			}else{
				$this->error("添加失败");
			}
			exit();
		}
		$master = D('cloud_master')->where()->order('id desc')->select();
		$this->assign('master',$master);
		$this->display();
	}
	public function productedit(){
		if (IS_POST){
			$data=$_POST;
			$dqos=$data['dqos'];
			if ($dqos<1)$this->success("赠送带宽不能小于1M");
			$id=$data['id'];
			unset($data['id']);
			if(empty($data['canmonth']))$data['canmonth']=0;
			if(empty($data['canseason']))$data['canseason']=0;
			if(empty($data['canhalfyear']))$data['canhalfyear']=0;
			if(empty($data['cantest']))$data['cantest']=0;
			$info=D('loadb_product')->where(array('id'=>$id))->save($data);
			if (FALSE !==$info){
				 $this->success('操作成功');
			}else{
				 $this->error('操作失败');
			}
			exit();	
		}
		$id=I('id','');
		$ary_get = $this->_get();
		$data=D('loadb_product')->where(array('id'=>$id))->find();
		$master = D('cloud_master')->where()->order('id desc')->select();
		$this->assign('master',$master);
		$this->assign("data",$data);
		$this->display();
	}
	public function productdel(){
		$id=I('id','');
		if ($id){
			$cloudproduct=D('loadb_product');
			$info=$cloudproduct->where(array('id'=>$id))->find();
			if(!$info)$this->error("产品不存在");
			$map['Cloudtype']=array('eq',$info['Cloudtype']);
			$map['status']=array('neq','已删除');
			$cloud=D('loadb')->where($map)->select();
			if ($cloud)$this->error("当前产品配置有正在使用的云产品");
			$ary_result=$cloudproduct->where(array('id'=>$id))->delete();
			if ($ary_result){
				$this->success('操作成功');
			}else{
				$this->error('操作失败');
			}	
		}else{
			$this->error('删除项不能为空!');
		}
	}
	public function productname(){
		if (IS_POST){
			$ary_post = $this->_post();
			$id = $ary_post['id'];
			$name_o = $ary_post['name_o'];
			$name_n = $ary_post['name_n'];
			if($name_o == $name_n)$this->error('新旧名称相同');
			$data['Cloudtype'] = $name_n;
			$info=D('loadb_product')->where(array('id'=>$id))->save($data);
			if(FALSE !==$info){
				$info2=D('loadb')->where(array('Cloudtype'=>$name_o))->save($data);
				$info3=D('order')->where(array('producttype'=>$name_o,'type'=>4))->save(array('producttype'=>$name_n));
				$info4=D('money_log')->where(array('ptype'=>$name_o,'whichProduct'=>'负载均衡'))->save(array('ptype'=>$name_n));
				if(FALSE !==$info2){
					$this->success('修改成功');
				}else{
					$this->error('修改失败');
				}
				$this->success('名称修改成功');
			}else{
				$this->error('操作失败');
			}
			exit();
		}
		$id=I('id','');
		if ($id){
			$cloudproduct=D('loadb_product');
			$info=$cloudproduct->where(array('id'=>$id))->find();
			if(!$info)$this->error("产品不存在");
			$this->assign('data',$info);
			$this->display('');
		}else{
			$this->error('参数为空');
		}
	}
	public function os(){
		$ary_get = $this->_get();
		$ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', C('PAGE_COUNT'));
		$cloudos=D('loadb_os');
		if (!empty($ary_get['masterid']))$where="O.masterid=".$ary_get['masterid'];
		if (!empty($ary_get['cid']))$where="O.cid=".$ary_get['cid'];
		if (!empty($ary_get['ostype']))$where="O.ostype=".$ary_get['ostype'];
		$count = $cloudos->table(C("DB_PREFIX")."loadb_os AS O")
						 ->where($where)
						 ->count();
        $obj_page =$this->AdminPage($count, $ary_get['pageall']);
        $page = $obj_page->show();
        $ary_data=$cloudos->table(C("DB_PREFIX")."loadb_os AS O")
        				  ->field("O.*,M.mastername")
        				  ->join(C("DB_PREFIX")."cloud_master as M ON M.id=O.masterid")
        				  ->where($where)
        				  ->limit($obj_page->firstRow, $obj_page->listRows)
        				  ->order('O.sort desc')
        				  ->select();
        $master = D('cloud_master')->where()->order('id desc')->select();
		$this->assign('master',$master);
		$this->assign('masterid',$ary_get['masterid']);
		$this->assign('cid',$ary_get['cid']);
		$this->assign('ostype',$ary_get['ostype']);
        $this->assign("data", $ary_data);
        $this->assign("page", $page);
		$this->display();
	}
	public function osadd(){
		
		if (IS_POST){
			$ary_post = $this->_post();
			$cloudmaster=D('loadb_os');
			$ary_result=$cloudmaster->add($ary_post);
			if(FALSE !== $ary_result){
				$this->success("添加成功");
			}else{
				$this->error("添加失败");
			}
			exit();
		}
		//获取主控
		$master = D('cloud_master')->where()->order('id desc')->select();
		$this->assign('master',$master);
		$this->display();
	}
	public function osedit(){
		if (IS_POST){
			$data=$_POST;
			$id=$data['id'];
			unset($data['id']);
			$info=D('loadb_os')->where(array('id'=>$id))->save($data);
			if (FALSE !==$info){
				 $this->success('操作成功');
			}else{
				 $this->error('操作失败');
			}
			exit();	
		}
		$id=I('id','');
		$ary_get = $this->_get();
		$data=D('loadb_os')->where(array('id'=>$id))->find();
		$master = D('cloud_master')->where()->order('id desc')->select();
		$this->assign('master',$master);
		$this->assign("data",$data);
		$this->display();
	}
	public function osdel(){
		$id=I('id','');
		if ($id){
			$cloudos=D('loadb_os');
			$ary_result=$cloudos->where(array('id'=>$id))->delete();
			if ($ary_result){
				$this->success('操作成功');
			}else{
				$this->error('操作失败');
			}	
		}else{
			$this->error('删除项不能为空!');
		}
	}
}
?>