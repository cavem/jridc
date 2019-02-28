<?php
class ChangeAction extends Action{
	public function changeuser(){
		if (empty($_SESSION['admin_name'])){
			$this->error("数据错误");
		}else{
			$admin=D('admin')->where(array('u_name'=>$_SESSION['admin_name']))->find();
			if (empty($admin))$this->error("数据不存在");
		}
		if (!IS_POST)$this->error("数据错误");
		$ary_post=$_POST;
		$user=D('user')->where(array('username'=>$ary_post['username']))->find();
		if (empty($user)){
			$this->error("数据错误");
		}
	    $_SESSION['uid'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['userkey'] = rand(0,70000);
	    $_SESSION['sign']=sha1($user['username'].$_SESSION['userkey'].$_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR']);
        D('Systemlog')->adddata('cloud-sysadmin',"管理员登录用户"."中心(".$user['username']."操作人:".$_SESSION['admin_name']);
        $this->redirect('User/Center/index');
	}
}
?>