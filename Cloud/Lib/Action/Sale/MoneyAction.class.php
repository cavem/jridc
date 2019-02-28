<?php
/**
 * 财务管理
 */
class MoneyAction extends SaleadminAction{
	//列表
	public function index(){
		
		$mod = M("money_log");
		$ary_get = $this->_get();
		//查询条件
		$userid=$this->userid($this->kid);
		$where=" 1=1 and m.user_id in (".$userid.")";
		$username = $ary_get['username'];
		if (!empty($username)){
		 	$typedata=M('user')->where(array('status'=>1,'username'=>$username))->find();
			$id = $typedata['user_id'];
			$where=$where." and m.user_id = ".$id;
			$this->assign("prm_uname",$username);
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
		//分页
		$ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', C('PAGE_COUNT'));
		$count = $mod->table(C("DB_PREFIX")."money_log as m")->where($where)->count();
		$obj_page =$this->AdminPage($count, $ary_get['pageall']);
		$page = $obj_page->show();
		$this->assign("page", $page);
		//查询数据
		$data = $mod->table(C("DB_PREFIX").'money_log as m')
				->join(C("DB_PREFIX").'user as u on u.user_id = m.user_id')
				->field("m.*,u.username")
				->where($where)
				->order('id desc')
				->limit($obj_page->firstRow, $obj_page->listRows)
				->select();
		$this->assign("data", $data);
		$this->display();
	}
	public function show(){
		$Mod=M('money_log');
		$id = I('id','','htmlspecialchars');
		if ($id){
			$data = $Mod->table(C("DB_PREFIX")."money_log as m")
				->join(C("DB_PREFIX").'user as u on u.user_id = m.user_id')
				->field('m.*,u.username')
				->where(array('id'=>$id))
				->find();
			if (!$this->usercheck($data['user_id'],$this->kid)){
				$this->error('当前财务不属于当前客服!');
			}
		
			$this->assign('data',$data);
			$this->display();
		}else{
			$this->error('编号不能为空!');
		}
	}
}
?>