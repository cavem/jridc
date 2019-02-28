<?php
class MainwebAction extends Action{
	public $wechat;
	public function _initialize(){
	 	import('ORG.Util.Session');
		$weixin_config=D('weixin_config')->where(array('id'=>1))->find();
        $this->wechat=new Wechat();
        $this->wechat->appid = $weixin_config['appid'];
		$this->wechat->appsecret=$weixin_config['appsecret'];
	}
	
	
	
}
?>