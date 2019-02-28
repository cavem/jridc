<?php
/**
 * 订单管理
 * Enter description here ...
 * @author Geyoulei
 * 
 */
class OrderAction extends AdminAction{
    public function index(){
		$mod = M("order");
		$ary_get = $this->_get();
		//查询条件
		$where=" 1=1 ";
		$username = $ary_get['username'];
		if (!empty($username)){
		 	$typedata=M('user')->where(array('status'=>1,'username'=>$username))->find();
			$id = $typedata['user_id'];
			$where=$where." and o.user_id = ".$id;
			$this->assign("username",$username);
		}
		
   		$ordernumber = $ary_get['ordernumber'];
		if (!empty($ordernumber)){
			$where=$where." and o.ordernumber = ".$ordernumber;
			$this->assign("ordernumber",$ordernumber);
		}
   		if(!empty($ary_get['starttime'])){
			$starttime=str_replace("+"," ",$ary_get['starttime']);	
			$starttime=convert_datefm($starttime);
			$where=$where." and o.addtime >=".$starttime;
			$this->assign("starttime",date('Y-m-d', $starttime));
		}
		if(!empty($ary_get['endtime'])){
			$endtime=str_replace("+"," ",$ary_get['endtime']);	
			$endtime=convert_datefm($endtime);
			$where=$where." and o.addtime <=".($endtime+86400);
			$this->assign("endtime",date('Y-m-d', $endtime));
		}
		//分页
		$ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', C('PAGE_COUNT'));
		$count = $mod->table(C("DB_PREFIX")."order as o")->where($where)->count();
		$obj_page =$this->AdminPage($count, $ary_get['pageall']);
		$page = $obj_page->show();
		$this->assign("page", $page);
		//查询数据
		$data = $mod->table(C("DB_PREFIX").'order as o')
				->join(C("DB_PREFIX").'user as u on u.user_id = o.user_id')
				->field("o.*,u.username")
				->where($where)
				->order('id desc')
				->limit($obj_page->firstRow, $obj_page->listRows)
				->select();
		$this->assign("data", $data);
		$this->display();
	}
	//查看详情
	public function detail(){
		$mod=M('order');
		$id = I('id','','htmlspecialchars');
		if (empty($id))$this->error('编号不能为空!');
		$data=D('order')->where(array('id'=>$id))->find();
		if (empty($data))$this->error("数据不存在");
		if ($data['type']==1){
			$cloudos=D('cloud_os')->where(array('image_uuid'=>$data['image_uuid']))->find();
			$cloudproduct=D('cloud_product')->where(array('Cloudtype'=>$data['producttype']))->find();
			$tpl='cloud';
			if ($data['ordertype']=='云主机开通'){
				$tpl='cloud';
			}
			if ($data['ordertype']=='云主机续费'){
				$tpl='cloudrepay';
				$qosnetwork=json_decode($data['qosnum'],true);
				$qosnetwork=$qosnetwork['value'];
				$data['qosnetwork']=$qosnetwork;
				
			}
			if ($data['ordertype']=='云主机调整'){
				$tpl='clouddown';
			}
			if ($data['ordertype']=='云主机升级'){
				$tpl='cloudup';
			}
			
			if ($data['year']==999)$data['yearname']='试用';
			if ($data['year']==$cloudproduct['PAY_Month'])$data['yearname']='月付';
			if ($data['year']==$cloudproduct['PAY_Season'])$data['yearname']='季度付';
			if ($data['year']==$cloudproduct['PAY_halfyear'])$data['yearname']='半年付';
			if ($data['year']==$cloudproduct['PAY_Nextyear'])$data['yearname']='年付';
			if ($data['year']==$cloudproduct['PAY_2year'])$data['yearname']='二年';
			if ($data['year']==$cloudproduct['PAY_3year'])$data['yearname']='三年';
			if ($data['year']==$cloudproduct['PAY_4year'])$data['yearname']='四年';
			if ($data['year']==$cloudproduct['PAY_5year'])$data['yearname']='五年';
			
			
			//查找未到期 而且可以使用的优惠券
			$map['status']=array('eq',0);
			$map['type']=array('eq','Cloud产品');
			$map['user_id']=array('eq',$this->uid);
			$map['condition']=array('elt',$data['usermoney']);
			$coupon=D('coupon')->where($map)->select();
			$this->assign('data',$data);
			$this->assign('coupon',$coupon);
			$this->assign('cloudos',$cloudos);
			$this->assign('cloudproduct',$cloudproduct);
			$this->display($tpl);
			exit();
		}if ($data['type']==4){//负载均衡
			$cloudos=D('loadb_os')->where(array('image_uuid'=>$data['image_uuid']))->find();
			$cloudproduct=D('loadb_product')->where(array('Cloudtype'=>$data['producttype']))->find();
			$tpl='cloud';
			if ($data['ordertype']=='负载均衡开通'){
				$tpl='loadb';
			}
			if ($data['ordertype']=='负载均衡续费'){
				$tpl='loadbrepay';
				$qosnetwork=json_decode($data['qosnum'],true);
				$qosnetwork=$qosnetwork['value'];
				$data['qosnetwork']=$qosnetwork;
				
			}
			if ($data['ordertype']=='负载均衡调整'){
				$tpl='loadbdown';
			}
			if ($data['ordertype']=='负载均衡升级'){
				$tpl='loadbup';
			}
			if ($data['year']==888)$data['yearname']='自动支付';
			if ($data['year']==999)$data['yearname']='试用';
			if ($data['year']==$cloudproduct['PAY_Month'])$data['yearname']='月付';
			if ($data['year']==$cloudproduct['PAY_Season'])$data['yearname']='季度付';
			if ($data['year']==$cloudproduct['PAY_halfyear'])$data['yearname']='半年付';
			if ($data['year']==$cloudproduct['PAY_Nextyear'])$data['yearname']='年付';
			if ($data['year']==$cloudproduct['PAY_2year'])$data['yearname']='二年';
			if ($data['year']==$cloudproduct['PAY_3year'])$data['yearname']='三年';
			if ($data['year']==$cloudproduct['PAY_4year'])$data['yearname']='四年';
			if ($data['year']==$cloudproduct['PAY_5year'])$data['yearname']='五年';
			//查找未到期 而且可以使用的优惠券
			$map['status']=array('eq',0);
			$map['type']=array('eq','负载均衡');
			$map['user_id']=array('eq',$this->uid);
			$map['condition']=array('elt',$data['usermoney']);
			$coupon=D('coupon')->where($map)->select();
			$this->assign('data',$data);
			$this->assign('coupon',$coupon);
			$this->assign('cloudos',$cloudos);
			$this->assign('cloudproduct',$cloudproduct);
			$this->display($tpl);
			exit();
		}
		if ($data['type']==5){//云硬盘
			$diskproduct=D('disk_product')->where(array('Disktype'=>$data['producttype']))->find();
			$tpl='disk';
			if ($data['ordertype']=='云硬盘开通'){
				$tpl='disk';
			}
			if ($data['ordertype']=='云硬盘续费'){
				$tpl='diskrepay';
			}
			if ($data['ordertype']=='云硬盘升级'){
				$tpl='diskup';
			}
			if ($data['year']==888)$data['yearname']='自动支付';
			if ($data['year']==999)$data['yearname']='试用';
			if ($data['year']==$diskproduct['PAY_Month'])$data['yearname']='月付';
			if ($data['year']==$diskproduct['PAY_Season'])$data['yearname']='季度付';
			if ($data['year']==$diskproduct['PAY_halfyear'])$data['yearname']='半年付';
			if ($data['year']==$diskproduct['PAY_Nextyear'])$data['yearname']='年付';
			if ($data['year']==$diskproduct['PAY_2year'])$data['yearname']='二年';
			if ($data['year']==$diskproduct['PAY_3year'])$data['yearname']='三年';
			if ($data['year']==$diskproduct['PAY_4year'])$data['yearname']='四年';
			if ($data['year']==$diskproduct['PAY_5year'])$data['yearname']='五年';
			$this->assign('data',$data);
			$this->assign('diskproduct',$diskproduct);
			$this->display($tpl);
			exit();
		}
		$this->display();
	}
	public function del(){
		$id = I('id','');
		if($id){
			$mod = M("order");
			$info = $mod->where("id = ".$id)->delete();
			if(false!=$info){
				$this->success("删除成功");
			}else{
				$this->error("删除失败");
			}
		}else{
			$this->error('参数为空');
		}
		
	
	}
}
?>