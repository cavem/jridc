<?php
class CallbackAction extends Action{
	public function index(){
		define("TOKEN", "weixin");
		$wechat=new Wechat();
		if (isset($_GET['echostr'])) {
   			$wechat->valid();
		}else{
    		$wechat->responseMsg();
		}
	}
}