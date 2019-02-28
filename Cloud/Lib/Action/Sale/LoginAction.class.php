<?php
class LoginAction extends Action{
    public function _initialize() {
	    	 import('ORG.Util.Session');
			 layout(false);//关闭布局
	}
    public function index(){
			$this->redirect('Sale/Login/Login');
	}
	public function login(){
			$this->display();	
	}
	public function dologin(){
	        $ary_post = $this->_post();
	        if (empty($ary_post['username'])) {
	            $this->error('请输入用户名!');
	        } else if (empty($ary_post['password'])) {
	            $this->error('请输入密码！');
	        } 
	        $map = array();
	        $map['kefuloginname'] = $ary_post['username'];
	     	$kefu=D('kefu')->where("kefuloginname='%s'",$ary_post['username'])->find();
	     	if (empty($kefu))$this->error('账户错误');
			if ($kefu['kefuloginpass'] != $ary_post['password']) $this->error('密码错误！');
			$_SESSION['kefuid'] = $kefu['id'];
	        $_SESSION['kefuname'] = $kefu['kefuname'];
	        $_SESSION['kefukey'] = rand(0,70000);
	        $_SESSION['kefusign']=sha1($kefu['kefuname'].$_SESSION['kefukey'].$_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR']);
            $this->redirect('Sale/Main/index');
	}
	public function doLogout(){
		 	if(isset($_SESSION['kefuid'])){
	            unset($_SESSION['kefuid']);
	            unset($_SESSION);
	            session_destroy();
	            $this->success("成功退出", U('Sale/Login/Login'));
	        }else{
	            $this->error(L('成功退出'),U('Sale/Login/Login'));
	        }
	}
}
?>