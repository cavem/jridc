<?php
//财务管理
class MoneyAction extends MainuserAction{
	public function index(){
		$isadd = I('get.isadd');
		$starttime = I('get.starttime','');
		$endtime = I('get.endtime','');
		$where="user_id=".$this->uid." and username='".$this->username."'";
		if (!empty($isadd))$where=$where." and isadd=".$isadd;
		if(!empty($starttime)){
			$starttime=str_replace("+"," ",$starttime);	
			$starttime=convert_datefm($starttime);
			$where=$where." and addtime >=".$starttime;
			$this->assign("starttime",date('Y-m-d', $starttime));
		}
		if(!empty($endtime)){
			$endtime=str_replace("+"," ",$endtime);	
			$endtime=convert_datefm($endtime);
			$where=$where." and addtime <=".($endtime+86400);
			$this->assign("endtime",date('Y-m-d', $endtime));
		}
		$count = M("money_log")->where($where)->count();
		$obj_page =$this->UserPage($count,10);
        $pageinfo = $obj_page->show();
        $data = M("money_log")
			    ->where($where)
        		->limit($obj_page->firstRow, $obj_page->listRows)
				->order("id desc")
				->select();
		$this->assign("isadd",$ary_get['isadd']);
		$this->assign("pageinfo",$pageinfo);
		$this->assign('data',$data);
		$this->display();
	
	}
	public function show(){
		$id=I('get.id','','intval');
		if (empty($id))$this->error("参数错误");
		$data=D('money_log')->where(array('id'=>$id,'user_id'=>$this->uid,'username'=>$this->username))->find();
		if (empty($data))$this->error("数据不存在");
	
		
		$this->assign('data',$data);
		$this->display();
	}
}
?>