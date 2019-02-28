<?php
class MainadminAction extends Action{
	public $wechat;
	public function _initialize(){
		if (!$_SESSION['Wechatadmin']){
			$this->redirect('Admin/Main/index');
		}
	 	import('ORG.Util.Session');
	 	$actname=strtolower(ACTION_NAME);
		import('ORG.Util.Page');
		import('ORG.Util.Tree');
        import('ORG.Util.Dir');
        
		$weixin_config=D('weixin_config')->where(array('id'=>1))->find();
        $this->wechat=new Wechat();
        $this->wechat->appid = $weixin_config['appid'];
		$this->wechat->appsecret=$weixin_config['appsecret'];
	}
	/**
     * 后台统一分页
     */
    public function WechatPage($count, $pagesize) {
        $page = new Page($count, $pagesize);
        $page->setConfig("header", "条");
        $page->setConfig('theme', '<ul><li class="pageSelect">共%totalRow%%header%&nbsp;%nowPage%/%totalPage%页&nbsp;%first%&nbsp;%upPage%&nbsp;%prePage%&nbsp;%linkPage%&nbsp;%nextPage%&nbsp;%downPage%&nbsp;%end%</li></ul>');
        return $page;
    }
	
	
}
?>