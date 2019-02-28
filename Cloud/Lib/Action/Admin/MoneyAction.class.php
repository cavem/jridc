<?php
/**
 * 财务管理
 * @author minran
 */
class MoneyAction extends AdminAction{
	//列表
	public function index(){
		$mod = M("money_log");
		$ary_get = $this->_get();
		$where=" 1=1 ";
		$username = $ary_get['username'];
		if (!empty($username)){
		 	$typedata=M('user')->where(array('status'=>1,'username'=>$username))->find();
			$id = $typedata['user_id'];
			$where=$where." and m.user_id = ".$id;
			$this->assign("prm_uname",$username);
		}
		$userRank = $ary_get['user_rank'];
		if (!empty($userRank)){
		 	$where=$where." and u.user_rank = ".$userRank;
			$this->assign("userRank",$userRank);
		}
		$orderid = $ary_get['orderid']; 
		if (!empty($orderid)){
		 	$where=$where." and m.orderid like '%".$orderid."%'";
			$this->assign("orderid",$orderid);
		}
		$type = $ary_get['type'];
		if (!empty($type)){
		 	$where=$where." and m.type =".$type;
			$this->assign("type",$type);
		}	
		$whichProduct = $ary_get['whichProduct'];
		if (!empty($whichProduct)){
			if($whichProduct == 1)
		 		$where=$where." and m.whichProduct = 'cloud产品'";
		 	else
		 		$where=$where." and m.whichProduct = '负载均衡'";
			$this->assign("whichProduct",$whichProduct);
		}
		$acspace = $ary_get['acspace'];
		if (!empty($acspace)){
			if($acspace == 1)
		 		$where=$where." and m.acspace = '用户区'";
		 	else 
		 		$where=$where." and m.acspace = '管理区'";
			$this->assign("acspace",$acspace);
		}
		if(!empty($ary_get['starttime'])){
			$starttime=str_replace("+"," ",$ary_get['starttime']);	
			$starttime=convert_datefm($starttime);
			$where=$where." and m.addtime >=".$starttime;
			$this->assign("starttime",date('Y-m-d', $starttime));
		}
		if(!empty($ary_get['endtime'])){
			$endtime=str_replace("+"," ",$ary_get['endtime']);	
			$endtime=convert_datefm($endtime);
			$where=$where." and m.addtime <=".($endtime+86400);
			$this->assign("endtime",date('Y-m-d', $endtime));
		}
		if(!empty($ary_get['kid'])){
			$where=$where." and k.id = ".$ary_get['kid'];
			$this->assign("kid",$ary_get['kid']);
		}
		if(!empty($ary_get['isadd'])){
			$where=$where." and m.isadd = ".$ary_get['isadd'];
			$this->assign("isadd",$ary_get['isadd']);
		}
		//分页
		$ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', C('PAGE_COUNT'));
		$count = $mod->table(C("DB_PREFIX")."money_log as m")->join(C("DB_PREFIX").'user as u on u.user_id = m.user_id')->where($where)->count();
		$this->jinzhang = $mod->table(C("DB_PREFIX")."money_log as m")->join(C("DB_PREFIX").'user as u on u.user_id = m.user_id')->where($where." and m.isadd = 1")->sum('m.usermoney');
		$this->zhichu = $mod->table(C("DB_PREFIX")."money_log as m")->join(C("DB_PREFIX").'user as u on u.user_id = m.user_id')->where($where." and m.isadd = 2")->sum('m.usermoney');
		$obj_page =$this->AdminPage($count, $ary_get['pageall']);
		$page = $obj_page->show();
		$this->user_rank = M('user_rank')->field("rank_id,rank_name")->select();
		$this->assign("page", $page);
		//查询数据
		$data = $mod->table(C("DB_PREFIX").'money_log as m')
				->join(C("DB_PREFIX").'user as u on u.user_id = m.user_id')
				->join(C("DB_PREFIX").'order as o on m.orderid = o.ordernumber')
				->join(C("DB_PREFIX").'kefu as k on k.id = u.kid')
				->field("m.*,u.username,k.kefuname")
				->where($where)
				->order('id desc')
				->limit($obj_page->firstRow, $obj_page->listRows)
				->select();
		$kefu=D('kefu')->where(array())->select();
		$this->assign('kefu',$kefu);
		$this->assign("data", $data);
		$this->assign("count",$count);
		$this->display();
	}
	//充值入款
	public function add(){
		if (IS_POST){
			$arr_post = $this->_post();
			$username=$arr_post['username'];
			$money=$arr_post['money'];
			$pingzheng=$arr_post['pingzheng'];
			$type=$arr_post['type'];
			$forwhat=$arr_post['forwhat'];
			$User=D('User')->where(array('username'=>$username))->find();
			if (!$User){
				$this->error("用户不存在");				
			}
			$user_id=$User['user_id'];
	        $money=$money;
	        $forwhat=$forwhat;
	        $whichProduct="";
	        $pid=0;//产品ID
	        $type=$type;//1在线充值 2后台入款 3后台扣除 4开通扣除 5续费扣除 6升级配置 7降级配置   10充值卡$type
	        $pingzheng=$pingzheng;
	        $acspace="管理区";
	        if ($type==2){
	        	$isadd=1;//1入款 2出 $isadd
	        }
			if ($type==3){
	        	$isadd=2;//1入款 2出 $isadd
	        }
	        $ptype="";//产品类型
            $orderid="";//交易号或者订单号
            $paddtime=time();
            $endtime=time();
	        D('User')->addmoney($user_id,$money,$forwhat,$whichProduct,$pid,$type,$pingzheng,$acspace,$isadd,$ptype,$orderid,$paddtime,$endtime);
	        $this->success("操作成功");
	        exit();
		}
		$this->display();
	}
	//详情
	public function detail(){
		$Mod=M('money_log');
		$id = I('id','','htmlspecialchars');
		if ($id){
			$data = $Mod->table(C("DB_PREFIX")."money_log as m")
				->join(C("DB_PREFIX").'user as u on u.user_id = m.user_id')
				->field('m.*,u.username')
				->where(array('id'=>$id))
				->find();
			$this->assign('data',$data);
			$this->display();
		}else{
			$this->error('编号不能为空!');
		}
	}

	public function del(){
		$id = I('id','');
		if($id){
			$mod = M("money_log");
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