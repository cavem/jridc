<?php
class UserAction extends HomeAction{
	//验证验证码
	public function ajaxcode(){
		$code=$_REQUEST['code'];
		if (empty($code))$this->error("验证码不能为空");
		if ($_SESSION['codelogin']){
			if ($_SESSION['codelogin']==md5($code)){
				$this->success("验证成功");
			}else{
				$this->error("验证码错误");
			}
		}else{
			$this->error("验证码错误");
		}
	}
	//验证手机页面未处理
	public function checkmbi(){
		$this->display();
	}
	public function docheckmbi(){
		if (IS_POST){
			Load('extend');
			$ary_post = $this->_post();
			foreach ($ary_post as $pk=>$pv){
				$ary_post[$pk]=remove_xss($pv);			
			}
			$ary_post['username'] = I('post.username','');
			$ary_post['mobi'] = I('post.mobi','');
			$ary_post['mobicode'] = I('post.mobicode',0,'intval');
			$ary_post['password'] = I('post.password','');			
	        if (empty($ary_post['username'])) {
	            $this->error('请输入用户名!');
	        }else if (empty($ary_post['mobi'])) {
	            $this->error('手机号不能为空！');
	        }else if (empty($ary_post['mobicode'])) {
	            $this->error('手机验证码不能为空！');
	        }else if (empty($ary_post['password'])) {
	            $this->error('密码不能为空！');
	        }
			$userinfo=D('user')->where("username='%s'",$ary_post['username'])->find();
			if (!$userinfo)$this->error("用户错误");
			$chkpass=md5($ary_post['password'].$userinfo['pwdhash']);
			if ($chkpass<>$userinfo['password'])$this->error("密码错误");
			if (!$_SESSION['checkmobicode']){
				$this->error("手机验证码错误");
			}else{
				if ($_SESSION['checkmobicode']==$ary_post['mobicode']){
				}else{
					$this->error("手机验证码错误");
				}
			}
			$data=array(
				'mobi'=>$ary_post['mobi'],
				'mobiv'=>1
			);
			$where=array('username'=>$userinfo['username'],'user_id'=>$userinfo['user_id']);
			$infos=D('user')->where($where)->data($data)->save();
			if ($infos){
	        	 $this->success("验证成功",U('Home/User/login'));
	        }else{
	        	 $this->error("验证错误");
	        }
			exit();
		 }
		 $this->error("数据提交错误");
		
	}
	public function register(){
		$code= D('Config')->getCfgByModule('CODE_SET');
	    $this->assign('MREGISTER',$code['MREGISTER']);
	 	$reglogin= D('Config')->getCfgByModule('REGSET');
        $rlogin = json_decode($reglogin['REGSET'], true);
        $this->assign('regcode',$rlogin['regcode']);//是否开启邀请注册
        $this->assign('regv',$rlogin['regv']);//开启那种注册验证方式
		$this->display();
	}
	public function doregister(){

		if (IS_POST){
			Load('extend');
			$ary_post = $this->_post();
			foreach ($ary_post as $pk=>$pv){
				$ary_post[$pk]=remove_xss($pv);			
			}
			$ary_post['username'] = I('post.username','');
			$ary_post['password'] = I('post.password','');
			$ary_post['code'] = I('post.code','');
			$ary_post['email'] = I('post.email','');
			$ary_post['qq'] = I('post.qq','');
			$ary_post['mobi'] = I('post.mobi','');
			$ary_post['icode'] = I('post.icode','');
	        if (empty($ary_post['username'])) {
	            $this->error('请输入用户名!');
	        } else if (empty($ary_post['password'])) {
	            $this->error('请输入密码！');
	        } 
	        $code= D('Config')->getCfgByModule('CODE_SET');
	        if(!empty($code['MREGISTER']) && $code['MREGISTER'] == '1'){
	            if(empty($ary_post['code']) || trim($ary_post['code']) == "验证码"){
	                $this->error('请输入验证码！');
	            }
	            $verify = session("codelogin");
	       		if ($verify != md5($ary_post['code'])) {
	                $this->error('验证码错误！');
	            }
	        }
         	$reglogin= D('Config')->getCfgByModule('REGSET');
       		$rlogin = json_decode($reglogin['REGSET'], true);
		    if (!checkusername($ary_post['username'])){
       			$this->error('用户名格式错误！');	
       		}
       		if (!checkemail($ary_post['email'])){
       			$this->error('邮箱格式错误！');	
       		}
			if (!checkqq($ary_post['qq'])){
       			$this->error('qq格式错误！');	
       		}
       		$checkuser = D('user')->where("username='%s'",$arr_post['username'])->find();
       		if($checkuser) $this->error("用户名已存在");
       		$data=array();
       		$data['user_rank']=1;
       		$data['username']=$ary_post['username'];
       		$data['pwdhash']=randstr();
       		$data['password']= md5($ary_post['password'].$data['pwdhash']);
       		$data['email']=$ary_post['email'];
       		$data['qq']=$ary_post['qq'];
       		$data['regtime']=time();
       		$data['regip']=getip();
       		$data['lastlogintime']=time();
       		$data['lastloginip']=getip();
       		$data['status']=1;
       		//手机验证处理
			if ($rlogin['regv']==3){
				if (!checkmobi($ary_post['mobi'])){
	       			$this->error('手机格式错误！');	
	       		}
				$data['mobi']=$ary_post['mobi'];
				if (!$_SESSION['checkmobicode']){
					$this->error("手机验证码错误");
				}else{
					if ($_SESSION['checkmobicode']==$ary_post['mobicode']){
						$data['mobiv']=1;
					}else{
						$this->error("手机验证码错误");
					}
				}
			}
       		if ($rlogin['regcode']){//开启邀请码注册
       			//查询当前邀请码是否可使用
       			$invite=D('invite')->where(array('usecode'=>$ary_post['icode'],'status'=>0))->find();
       			if (!$invite)$this->error("邀请码不存在");
       			$endtime=$invite['addtime']+($invite['useday']*86400);
       			if (time()>$endtime){
       				$this->error("邀请码已过期");
       			}
       			$kefuid=$invite['kefuid'];
       			$data['kid']=$kefuid;
       		}else{
       			$kefu=D('kefu')->order("RAND()")->find();
       			$data['kid']=$kefu['id'];
       		}
       		$ary_result=D('user')->add($data);
       		if (FALSE !==$ary_result){
				if ($rlogin['regcode']){
       				D('invite')->where(array('usecode'=>$ary_post['icode']))->save(array('userid'=>$ary_result,'usetime'=>time(),'status'=>1));
       			}
       			if ($rlogin['regv']==2){ //邮件激活发送
       				$codekey=strtotime(date('Y-m-d H'));
       				$userinfo=D('user')->where(array('user_id'=>$ary_result))->find();
					$code=md5($userinfo['user_id'].$userinfo['username'].$userinfo['pwdhash'].$userinfo['regtime'].$codekey);
					$sendmail=D('Sendmail')->sendactivation($userinfo['user_id'],$userinfo['username'],$userinfo['email'],$code);
       				$this->success("注册成功等待验证Email",U('Home/User/login'));
       			}
			
       			$this->success("注册成功",U('Home/User/login'));
       		}else{
       			$this->error("注册失败");
       		}
	        exit();
		 }
		 $this->error("数据提交错误");
	}
	//Ajax验证用户名已处理
	public function ajaxusername(){
		$username=$_REQUEST['username'];
		if (empty($username))$this->error("用户名不能为空");
		$userinfo=D('user')->where(array('username'=>$username))->find();
		if ($userinfo)$this->error("用户名已存在");
		$this->success("用户名可用");
	}
	//已处理
	public function ajaxmobi(){
		$mobi=$_REQUEST['mobi'];
		if (empty($mobi))$this->error("手机号码不能为空");
		$userinfo=D('user')->where(array('mobi'=>$mobi,'mobiv'=>1))->find();
		if ($userinfo)$this->error("手机号码已验证");
		$this->success("手机号码可用");
	}
	//发送短信验证码
	public function sendmobicode(){
		$mobi=I('mobi','','htmlspecialchars');
		//$icode=I('icode','','htmlspecialchars');
		if (!IS_POST)$this->error('错误提交');
		if (empty($mobi))$this->error("号码不能为空");
		//if (empty($icode))$this->error("邀请码不能为空");
		$reglogin= D('Config')->getCfgByModule('REGSET');
        $rlogin = json_decode($reglogin['REGSET'], true);
        if ($rlogin['regv']==1)$this->error('错误提交');
        if ($rlogin['regv']==2)$this->error('错误提交');
		$userinfo=D('user')->where(array('mobi'=>$mobi,'mobiv'=>1))->find();
		if ($userinfo)$this->error("手机号码已存在");
		unset($_SESSION['checkmobicode']);
		$change_sms_time=session('changesmstime');
		if ($change_sms_time){
			if ($change_sms_time['time']>time()){
				$sytime=$change_sms_time['time']-time();
				$this->error("请勿频繁操作,{$sytime}秒后重试");
			}else{
				session('changesmstime',array('time'=>time()+300));
			}
		}else{
			session('changesmstime',array('time'=>time()+300));
		}
		//验证邀请码是否可用
		/* $invite=D('invite')->where(array('usecode'=>$icode,'status'=>0))->find();
       	if (!$invite)$this->error("邀请码不存在");
       	$invite_mobi_count=D('invite_mobi')->where(array('usecode'=>$icode))->count();
       	if ($invite_mobi_count==3){
       		D('invite')->where(array('usecode'=>$icode))->save(array('usetime'=>time(),'status'=>1));
       		$this->error("邀请码已超出使用次数");
       	} */
       	//邀请码处理结束
		$Sendinfo=D('Sendsms')->sendcode($mobi,'checkmobicode');
		if ($Sendinfo){
			//发送三次邀请码失效
			D('invite_mobi')->add(array('usecode'=>$icode,'mobi'=>$mobi));
			$this->success("发送成功");
		}else{
			$this->error("发送失败");
		}
	}
	//验证手机收到的验证码
	public function ajaxmobicode(){
		$mobicode=$_REQUEST['mobicode'];
		if (empty($mobicode))$this->error("手机号码验证码不能为空");
		if ($_SESSION['checkmobicode']){
			if ($_SESSION['checkmobicode']==$mobicode){
				$this->success("验证成功");
			}else{
				$this->error("验证码错误");
			}
		}else{
			$this->error("验证码错误");
		}
	}
	//邮件验证页面
	public function checkemail(){
		$this->display();
	}
	//发送邮件验证
	public function sendmailac(){
		if (IS_POST){
			$ary_post=$_POST;
			Load('extend');
			foreach ($ary_post as $pk=>$pv){
				$ary_post[$pk]=remove_xss($pv);			
			}
			$ary_post['username'] = I('post.username','');
			$ary_post['password'] = I('post.password','');
			$ary_post['email'] = I('post.email','');
			if (empty($ary_post['username']))$this->error("用户名不能为空");
			if (empty($ary_post['password']))$this->error("用户密码不能为空");
			if (empty($ary_post['email']))$this->error("email不能为空");
			$username=$ary_post['username'];
			$email=$ary_post['email'];
			$userinfo=D('user')->where("username='%s' and email='%s'",$username,$email)->find();
			if (!$userinfo)$this->error("帐号或邮箱错误");
			$pwdinfo=D('User')->checkpass($userinfo['user_id'],$userinfo['username'],$ary_post['password']);//验证密码
			if (!$pwdinfo)$this->error("帐号或密码错误");
			//当前日期时间
			$codekey=strtotime(date('Y-m-d H'));
			$code=md5($userinfo['user_id'].$userinfo['username'].$userinfo['pwdhash'].$userinfo['regtime'].$codekey);
			$sendmail=D('Sendmail')->sendactivation($userinfo['user_id'],$userinfo['username'],$userinfo['email'],$code);
			if (!$sendmail)$this->error("发送失败");
			$this->success('发送成功',U('Home/User/login'));
			exit();
		}
		$this->error("数据提交错误");
	}
	//邮箱验证返回页面
	public function emailactivation(){
		$uid=I('uid','','htmlspecialchars');
		$code=I('ukey','','htmlspecialchars');
		$ekey=I('ekey','','htmlspecialchars');
		if (!is_numeric($uid))$this->error("uid数据错误");
		if (empty($code))$this->error("code数据错误");
		$userinfo=D('user')->where(array('user_id'=>$uid))->find();
		if (!$userinfo)$this->error("userinfo数据错误",U('Home/Index/index'));
		$codekey=strtotime(date('Y-m-d H'));
		if (empty($ekey)){
			$codenew=md5($userinfo['user_id'].$userinfo['username'].$userinfo['pwdhash'].$userinfo['regtime'].$codekey);
			$email=$userinfo['email'];
			if ($userinfo['emailv'])$this->redirect('Home/Index/index');
			$updata=array('emailv'=>1);
		}else{
			$codenew=md5($userinfo['user_id'].$userinfo['username'].decode($ekey).$userinfo['pwdhash'].$userinfo['regtime'].$codekey);
			$email=decode($ekey);
			$updata=array('emailv'=>1,'email'=>decode($ekey));
		}
		if ($code<>$codenew)$this->error("ukey数据错误",U('Home/Index/index'));
		$info=D('user')->where(array('user_id'=>$userinfo['user_id']))->save($updata);
		if (FALSE !==$info){
				$this->assign('ifaction',1);
		}else{
				$this->assign('ifaction',0);	
		}
		$this->assign('email',$email);
		$this->display();
	}
	public function index(){
		$this->redirect('Home/User/login');
	}
	public function login(){
		$url = U("User/Center/index");
        if(isset($_GET["doUrl"]) && "" != $_GET["doUrl"]){
            $url = urldecode($_GET["doUrl"]);
        }
		$this->assign("callback_url",$url);
	    $code= D('Config')->getCfgByModule('CODE_SET');
	    $this->assign('RELOGIN',$code['RELOGIN']);
		$this->display();
	}
	public function dologin(){
		if (IS_POST){
			Load('extend');
			$ary_post = $this->_post();
			foreach ($ary_post as $pk=>$pv){
				$ary_post[$pk]=remove_xss($pv);			
			}
			$ary_post['username'] = I('post.username','');
			$ary_post['password'] = I('post.password','');
			$ary_post['code'] = I('post.code',0,'intval');
	        if (empty($ary_post['username'])) {
	            $this->error('请输入用户名!');
	        } else if (empty($ary_post['password'])) {
	            $this->error('请输入密码！');
	        } 
	        $code= D('Config')->getCfgByModule('CODE_SET');
	        if(!empty($code['RELOGIN']) && $code['RELOGIN'] == '1'){
	            if(empty($ary_post['code']) || trim($ary_post['code']) == "验证码"){
	                $this->error('请输入验证码！');
	            }
	            $verify = session("codelogin");
	       		if ($verify != md5($ary_post['code'])) {
	                $this->error('验证码错误！');
	            }
	        }
	        $userinfo=D('user')->where("username='%s'",$ary_post['username'])->find();
	        if (!$userinfo)$this->error("用户或密码错误");
	        if (!$userinfo['status'])$this->error("用户已锁定");
	        $password=$userinfo['password'];
	        $checkpwd=md5($ary_post['password'].$userinfo['pwdhash']);
	        if ($password<>$checkpwd)$this->error("用户或密码错误");
	        //查询当前系统开启了哪种登录验证方式
	         $reglogin= D('Config')->getCfgByModule('REGSET');
	         $rlogin = json_decode($reglogin['REGSET'], true);
	         //假如开启了邮箱验证。那么必须验证邮箱后方可登录
	         if ($rlogin['regv']==2){
	         	if (empty($userinfo['emailv'])){
	         		$this->error("邮箱未验证",U('Home/User/checkemail'));
	         	}
	         }
            //假如开启了手机验证  那么必须验证手机后方可继续登录
			 if ($rlogin['regv']==3){
				 if (empty($userinfo['mobiv'])){
	         		$this->error("手机未验证",U('Home/User/checkmbi'));
	         	}
	         }
			$_SESSION['uid'] = $userinfo['user_id'];
	        $_SESSION['username'] = $userinfo['username'];
	        $_SESSION['userkey'] = rand(0,70000);
	        $_SESSION['sign']=sha1($userinfo['username'].$_SESSION['userkey'].$_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR']);
	         //写入日志信息
	        D('Userlog')->adddata($userinfo['user_id'],$userinfo['username'],1,"用户登录");
	        //更新用户登录信息
	       	D('user')->where(array('user_id'=>$userinfo['user_id']))->save(array('lastlogintime'=>time(),'lastloginip'=>getip()));
			$this->success("登录成功",U('User/Center/index'));
	        exit();
		}
		$this->error("数据错误");
	}
	public function verify(){
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
                'codelogin'
            );
        }else{
            Image::buildImageVerify(
                $ary_data['BACODENUMS'], 
                $ary_data['BUILDTYPE'], 
                $ary_data['EXPANDTYPE'], 
                $ary_data['BACODESIZE']['width'], 
                $ary_data['BACODESIZE']['height'], 
                'codelogin'
            );
        }
	}
	
	//忘记密码
	public function passset(){
		$this->display();
		
	}
	public function passwordset(){
		if (IS_POST){
			$ary_post=$_POST;
			if (empty($ary_post['uid']))$this->error("id不能为空");
			if (empty($ary_post['code']))$this->error("code不能为空");
			if (empty($ary_post['password']))$this->error("用户密码不能为空");
			if (empty($ary_post['password1']))$this->error("确认密码不能为空");
			if ($ary_post['password']<>$ary_post['password1'])$this->error("两次输入密码不同");
			if (!is_numeric($ary_post['uid']))$this->error("id错误");
			$userinfo=D('user')->where(array('user_id'=>$ary_post['uid']))->find();
			if (!$userinfo)$this->error("用户不存在");
			$codekey=strtotime(date('Y-m-d'));
			$codenew=md5($userinfo['user_id'].$userinfo['username'].$userinfo['email'].$userinfo['pwdhash'].$userinfo['regtime'].$codekey);
			if ($ary_post['code']<>$codenew)$this->error("code错误");
			//修复修改密码2016-3-31
			$pwdhash=randstr();
			$npwd=md5($ary_post['password'].$pwdhash);
			$updata=array('password'=>$npwd,'pwdhash'=>$pwdhash);
			$info=D('user')->where(array('user_id'=>$ary_post['uid']))->save($updata);
			if (FALSE !==$info){
				 $this->success("修改成功",U('Home/User/login'));
			}else{
					$this->error("修改失败");
			}
			exit();
		}
		$uid=I('uid','','htmlspecialchars');
		$code=I('code','','htmlspecialchars');
		if (!is_numeric($uid))$this->error("数据错误");
		if (empty($code))$this->error("数据错误");
		$userinfo=D('user')->where(array('user_id'=>$uid))->find();
		if (!$userinfo)$this->error("用户不存在");
		$codekey=strtotime(date('Y-m-d'));
		$codenew=md5($userinfo['user_id'].$userinfo['username'].$userinfo['email'].$userinfo['pwdhash'].$userinfo['regtime'].$codekey);
		if ($code<>$codenew)$this->error("code错误");
		$this->assign('uid',$uid);
		$this->assign('code',$code);			
		$this->display();
		
	}
	//发送邮件提取密码
	public function sendemail(){
		if (IS_POST){
			$ary_post=$_POST;
			foreach ($ary_post as $pk=>$pv){
				$ary_post[$pk]=remove_xss($pv);			
			}
			$ary_post['username'] = I('post.username','');
			$ary_post['email'] = I('post.email','');
			if (empty($ary_post['username']))$this->error("用户名不能为空");
			if (empty($ary_post['email']))$this->error("email不能为空");
			$username=$ary_post['username'];
			$email=$ary_post['email'];
			$userinfo=D('user')->where(array('username'=>$username,'email'=>$email))->find();
			if (!$userinfo)$this->error("帐号或邮箱错误");
			//当前日期时间
			$codekey=strtotime(date('Y-m-d'));
			$code=md5($userinfo['user_id'].$userinfo['username'].$userinfo['email'].$userinfo['pwdhash'].$userinfo['regtime'].$codekey);
			$sendmail=D('Sendmail')->sendpass($userinfo['user_id'],$userinfo['username'],$userinfo['email'],$code);
			if (!$sendmail)$this->error("发送失败");
			$this->success('发送成功',U('Home/User/login'));
			exit();
		}
		$this->error("数据提交错误");
	}
	
}
?>