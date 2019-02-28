<?php
class IndexAction extends HomeAction{
    public function index(){
    	$this->display();
    	
	}
	public function test(){
			$Cloudapi=D('Cloudapi')->loginceshi();
			p($Cloudapi);
		
	}
}
?>