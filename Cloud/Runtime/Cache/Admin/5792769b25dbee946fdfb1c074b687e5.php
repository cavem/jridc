<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>云商管理后台</title>
<link rel="stylesheet" href="__PUBLIC__/Admin/css/style.default.css" type="text/css" />
<script type="text/javascript" src="__PUBLIC__/Admin/js/plugins/jquery-1.8.2.js"></script>
<!--[if IE 9]>
    <link rel="stylesheet" media="screen" href="__PUBLIC__/Admin/css/style.ie9.css"/>
<![endif]-->
<!--[if IE 8]>
    <link rel="stylesheet" media="screen" href="__PUBLIC__/Admin/css/style.ie8.css"/>
<![endif]-->
</head>
<body class="loginpage">
	<div class="loginbox">
    	<div class="loginboxinner">
            <div class="logo">
            	<h1 class="logo">Cloud<span>Agent</span></h1>
            </div><!--logo-->
            
            <div class="nousername">
				<div class="loginmsg">用户名不能为空.</div>
            </div><!--nousername-->
            
            <div class="nopassword">
				<div class="loginmsg">用户密码不能为空.</div>
            </div><!--nopassword-->
            <form id="login" action="<?php echo U('Admin/Login/dologin');?>" method="post">
                <div class="username">
                	<div class="usernameinner">
                    	<input type="text" name="username" id="username" placeholder="用户名" autocomplete="off"/>
                    </div>
                </div>
                <div class="password">
                	<div class="passwordinner">
                    	<input type="password" name="password" id="password" placeholder="密码" autocomplete="off"/>
                    </div>
                </div>
                <div class="verifycode">
                	<div class="verifycodeinner">
                    	<input type="text" name="code" id="code" placeholder="验证码" autocomplete="off" />
 <img id="verify" src="<?php echo U('Admin/Login/verify');?>" class="changeVerify" style="cursor: pointer; vertical-align:top" title="点击更换" />
                    	
                    </div>
                </div>
                <button>登录</button>            
            </form>
            
        </div><!--loginboxinner-->
    </div><!--loginbox-->
    <script>
            $(document).ready(function(){
                var timer;
                $(".changeVerify").click(function(){
                    clearTimeout(timer);
                    $('#verify').attr('src','<?php echo U('Admin/Login/verify');?>'+'?r='+Math.random());
                });
                $('#login').submit(function(){
            		if($('#username').val() == '') {
            			$('.nousername').fadeIn();
            			$('.nopassword').hide();
            			return false;	
            		}
            		if( $('#password').val() == '') {
            			$('.nopassword').fadeIn();
            			$('.nousername').hide();
            			return false;	
            		}
            	});
            });
         </script>      
</body>
</html>