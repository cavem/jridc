<?php
class PageAction extends HomeAction{
	public function index(){
		$this->redirect("/home/index/index");
	}
	//显示单页
	public function show(){
		$arr_get = $this->_get();
		$id = I('get.id','0','intval');
		if(!$id)$this->redirect("/home/index/index");
		$mod = M('page');
		$data = $mod->where('id =%d',$id)->find();
		if(!$data['content'])$this->redirect("/home/index/index");
		$this->assign('data',$data);
		$this->display($data['temp']);
	}
}
?>