<?php
class LoginAction extends Action{
    public function _initialize() {
	    	 import('ORG.Util.Session');
			 layout(false);//关闭布局
	}
    public function index(){
			$this->redirect('Admin/Login/Login');
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
	        $code= D('Config')->getCfgByModule('CODE_SET');
	        if(!empty($code['BALOGIN']) && $code['BALOGIN'] == '1'){
	            if(empty($ary_post['code']) || trim($ary_post['code']) == "验证码"){
	                $this->error('请输入验证码！');
	            }
	           
	            $verify = session("code");
	       		if ($verify != md5($ary_post['code'])) {
	                $this->error('验证码错误！');
	            }
	        }
	        $map = array();
	        $map['u_name'] = $ary_post['username'];
	        $map["u_status"] = array('gt' , 0);
	        $rbac = new Arbac();
	        $auth_info = $rbac->authenticate($map);
			if (empty($auth_info))$this->error('权限错误！');
			if ($auth_info['u_passwd'] != md5($ary_post['password'])) $this->error('密码错误！');
			$admin_access = D('Config')->getCfgByModule('ADMIN_ACCESS');
	        $exitTime = $admin_access['EXPIRED_TIME'];
	        Session::setExpire(time() + $exitTime * 60);
	        $_SESSION[C('USER_AUTH_KEY')] = $auth_info['u_id'];
	        $_SESSION['admin_name'] = $auth_info['u_name'];
	        $_SESSION['u_email'] = $auth_info['u_email'];
	        $_SESSION['u_photo'] = $auth_info['u_photo'];
	        $_SESSION['last_time'] = $auth_info['last_time'];
			$_SESSION['u_countlog'] = $auth_info['u_countlog'];
			$_SESSION[C('USER_AUTH_KEY')."admagentkey"] =md5($_SESSION['admin_name'].get_client_ip());
	        if ($auth_info['u_name'] == $admin_access['SYS_ADMIN']) {
	        	$_SESSION[C('ADMIN_AUTH_KEY')] = true;
	        }
	        //保存登录信息
            $admin = M(C('USER_AUTH_MODEL'));
            $ip = get_client_ip();
            $time = date("Y-m-d H:i:s");
            $data = array();
            $data['u_lastlogin_time'] = $time;
            $data['u_countlog'] = array('exp', 'u_countlog + 1');
            $data['u_ip'] = $ip;
            $_SESSION['ip'] = $ip;
            $admin->where(array('u_name'=>$ary_post['username']))->save($data);
			 // 缓存访问权限
            $rbac->saveAccessList();
            $ary_data = array();
            $admin_log = M("AdminLog");
            $ary_data['u_id'] = $auth_info['u_id'];
            $ary_data['u_name'] = $auth_info['u_name'];
            $ary_data['log_ip'] = $ip;
            $ary_data['log_create'] = $time;
            $admin_log->add($ary_data);
             //将菜单控制台写入COOKIE
            $rolenav = M('RoleNav')->field('id')->where(array('name'=>'控制台'))->find();
            cookie("nav_id",$rolenav['id']);
            $this->redirect('Admin/Main/index');
	}
	public function doLogout(){
		 	if(isset($_SESSION[C('USER_AUTH_KEY')])){
	            unset($_SESSION[C('USER_AUTH_KEY')]);
	            unset($_SESSION);
	            session_destroy();
	            $this->success("成功退出", U('Admin/Login/Login'));
	        }else{
	            $this->error(L('成功退出'),U('Admin/Login/Login'));
	        }
	}
   /**
     * 验证码
     * @author Terry <573992533@qq.com>
     * @date 2013-03-23
     */
    public function verify() {
        import('ORG.Util.Image');
        $ary_data = D('Config')->getCfgByModule('CODE_SET');
        if(!empty($ary_data) && is_array($ary_data)){
            $ary_data['RECODESIZE'] = json_decode($ary_data['RECODESIZE'],true);
            $ary_data['BACODESIZE'] = json_decode($ary_data['BACODESIZE'],true);
        }
        if(!empty($ary_data['BUILDTYPE']) && $ary_data['BUILDTYPE'] == '4'){
            Image::GBVerify(
                $ary_data['BACODENUMS'], 
                $ary_data['EXPANDTYPE'], 
                $ary_data['BACODESIZE']['width'], 
                $ary_data['BACODESIZE']['height']*3, 
                'simhei.ttf',
                'code'
            );
        }else{
            Image::buildImageVerify(
                $ary_data['BACODENUMS'], 
                $ary_data['BUILDTYPE'], 
                $ary_data['EXPANDTYPE'], 
                $ary_data['BACODESIZE']['width'], 
                $ary_data['BACODESIZE']['height'], 
                'code'
            );
        }
    }	
}
?>