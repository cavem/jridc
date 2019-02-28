<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="icon" href="favicon.ico" type="image/x-icon">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=1300">
<meta name="keywords" content="<?php echo ($Web["Config"]["site_keyword"]); ?>">
<meta name="description" content="<?php echo ($Web["Config"]["site_description"]); ?>">
<title>用户注册-<?php echo ($Web["Config"]["site_name"]); ?></title>
<link href="__PUBLIC__/Home/Default/css/index.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="__PUBLIC__/Home/js/jquery-1.8.2.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/layer/layer.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/jquery.form.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/validate/jquery.validate.1.9.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/validate/jquery.metadata.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/validate/messages_cn.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/validate/global.js"></script>
<link rel="stylesheet" href="__PUBLIC__/Home/js/validate/validate.css" />
</head>
<body>
<div id="header">
  <div class="viewport-inner"><a class="header-logo" href="/" >CLOUD</a>
    <div class="header-account">
    <?php if($_SESSION['uid']): ?><a class="btn btn-outline btn-signin" href="<?php echo U('User/Center/index');?>">管理</a>
  	    <a class="btn btn-primary btn-signup" href="<?php echo U('User/Center/dologout');?>" >退出</a>
    <?php else: ?>
    <a class="btn btn-outline btn-signin" href="<?php echo U('Home/User/login');?>">登录</a>
    <a class="btn btn-primary btn-signup" href="<?php echo U('Home/User/register');?>" >注册</a><?php endif; ?>
    </div>
    <ul class="header-nav">
      <li class="nav-item home"><a href="/" >首页</a></li>
      <li class="nav-item customers"><a href="<?php echo U('Home/Cloud/buy');?>" >云产品</a>
      <div class="items cpmain">
      	<div class="viewport-inner">
        	<div class="cpnav">
                <ul>
                  <li><a href="<?php echo U('Home/Cloud/buy');?>" >弹性购买</a></li>
                   <?php $_result=M('page')->order('sort desc')->where("type_id=3")->limit("10")->select();if($_result): $i=0;foreach($_result as $key=>$page):++$i;$mod = ($i % 2 );?><li>
						<a href="<?php echo U('Home/Page/Show',array('id'=>$page[id]));?>"><?php echo ($page["title"]); ?></a></li><?php endforeach; endif;?>
                </ul>
            </div>
        </div>
       </div>
      </li>
      <li class="nav-item customers"><a href="<?php echo U('Home/Page/Show',array('id'=>5));?>" >解决方案</a>
        <div class="items cpmain">
          <div class="viewport-inner">
            <div class="cpnav">
              <ul>
              <?php $_result=M('page')->order('sort desc')->where("type_id=2")->limit("10")->select();if($_result): $i=0;foreach($_result as $key=>$page):++$i;$mod = ($i % 2 );?><li>
						<a href="<?php echo U('Home/Page/Show',array('id'=>$page[id]));?>"><?php echo ($page["title"]); ?></a></li><?php endforeach; endif;?>
              </ul>
            </div>
        </div>
       </div>
      </li>
      <li class="nav-item pricing"><a href="<?php echo U('Home/News/index/');?>" >新闻公告</a></li>
      <li class="nav-item console"><a href="<?php echo U('Home/Page/show/',array('id'=>12));?>">合作加盟</a></li>
      <li class="nav-item about"><a href="<?php echo U('Home/Page/show/',array('id'=>1));?>" >关于我们</a>
      <div class="items cpmain">
      	<div class="viewport-inner">
        	<div class="cpnav">
                <ul>              
                  <?php $_result=M('page')->order('sort desc')->where("type_id=1")->limit("10")->select();if($_result): $i=0;foreach($_result as $key=>$page):++$i;$mod = ($i % 2 );?><li>
						<a href="<?php echo U('Home/Page/Show',array('id'=>$page[id]));?>"><?php echo ($page["title"]); ?></a></li><?php endforeach; endif;?>
                </ul>
            </div>
        </div>
       </div>
      </li>
    </ul>
  </div>
</div>
<div class="twonav">
	<div class="viewport-inner">
    	<a href="#"><?php echo ($Web["Config"]["site_name"]); ?></a>
    </div>
</div>
<section class="page" style="min-height: 500px;">
  <div class="viewport-inner signup" style="height:700px;width:1200px;">
    <div class="form-wrapper">
    <form class="form form-horizontal formvalidate"  action="<?php echo U('Home/User/doregister');?>" method="post">
        <fieldset>
          <legend>注册</legend>
          <div class="item">
            <div class="control-label"><b>*</b>用户名</div>
            <div class="controls">
              <input type="text" name="username"  validate='{ required:true,username:true,checkusername:true,rangelength:[6,15],messages:{required:"用户名不能为空"}}'>
              </div>
          </div>
          <div class="item">
            <div class="control-label"><b>*</b>登陆密码</div>
            <div class="controls">
              <input type="password"  name="password" id="password" validate='{ required:true,rangelength:[6,20],equalTo:"#password1",messages:{required:"登录密码不能为空"}}'>
              </div>
          </div>
          <div class="item">
            <div class="control-label"><b>*</b>确认密码</div>
            <div class="controls">
              <input type="password" name="password1" id="password1" validate='{ required:true,rangelength:[6,20],equalTo:"#password",messages:{required:"确认密码不能为空"}}'>
            </div>
          </div>
          <div class="item">
            <div class="control-label"><b>*</b>电子邮箱</div>
            <div class="controls">
              <input type="text" name="email" >
             </div>
          </div>
          <?php if($regcode): ?><div class="item yzm">
	            <div class="control-label"><b>*</b>邀请码</div>
	            <div class="controls">
	              <input type="text" name="icode" id='icode' validate='{ required:true,messages:{required:"邀请码不能为空"}}'>       
	            </div>
	          </div><?php endif; ?>
          <?php if($regv == '3'): ?><div class="item yzm">
	           <div class="control-label"><b>*</b>手机号码</div>
	            <div class="controls">
	              <input type="text" name="mobi" id="mobi"  validate='{ required:true,isMobile:true,checkmobi:true,messages:{required:"手机号码不能为空"}}' >
	           <input id="btnSendCode" type="button" value="发送验证码" onclick="sendMessage()"/></div>
	          </div>
	           <div class="item yzm">
	           <div class="control-label"><b>*</b>短信验证码</div>
	            <div class="controls">
	              <input type="text" name="mobicode"  validate='{ required:true,checkmobicode:true,messages:{required:"短信验证码不能为空"}}'>
	             </div>
	          </div><?php endif; ?>
           <div class="item yzm">
            <div class="control-label"><b>*</b>腾讯QQ</div>
            <div class="controls">
              <input type="text" name="qq" validate='{ required:true,checkusername:true,messages:{required:"QQ不能为空"}}'>
            </div>
          </div>
        
          <?php if($MREGISTER): ?><div class="item yzm">
            <div class="control-label"><b>*</b>验证码</div>
            <div class="controls">
              <input type="text"  name="code" validate='{ required:true,checkcode:true,messages:{required:"验证码不能为空"}}'>
              <img class="captcha changeVerify" id="verify" src="<?php echo U('Home/User/verify');?>"  style="cursor: pointer;" title="点击更换">
            </div>
          </div><?php endif; ?>
          
          <div class="item zc">
            <div class="controls">
              <input class="btn btn-primary btn-session" type="submit" id="subregs" value="立即注册">
            </div>
          </div>
        </fieldset>
      </form>
    </div>
    <div class="zcyb" style="height:660px;">
    	<p>已注册过帐号？立即登录</p>
        <a href="<?php echo U('Home/User/login');?>" class="dlzh">登录帐号</a>
       <p> 注册成功，即享特权：</p>
<p>1、领取新人大礼包</p>
<p>2、免费试用云服务器</p>
    </div>
  </div>
</section>
<div id="footer">
  <div class="footer">
    <div class="footer-navs">
      <div class="viewport-inner">
        <ul class="grid_20 footer-nav" style="width:100%;">
          <li class="grid_3">
            <h4><a>产品</a><span>products</span></h4>
            <ul class="items">
              <li><a href="#">云计算合作</a></li>
              <li><a href="#" >云产品购买</a></li>
            </ul>
          </li>
          <li class="grid_3">
            <h4><a>解决方案</a><span>Solutions</span></h4>
            <ul class="items">
              <li><a href="#" >云计算系统软件</a></li>
              <li><a href="#" >云负载SLB</a></li>
            </ul>
          </li>
          <li class="grid_3">
            <h4><a>帮助中心</a><span>Help</span></h4>
            <ul class="items">
              <li><a href="jg.html" >通知公告</a></li>
              <li><a href="jg.html#axjf" >优惠促销</a></li>
            </ul>
          </li>
          <li class="grid_3">
            <h4><a>开放平台</a><span>Open platform </span></h4>
            <ul class="items">
              <li><a href="#">申请云业务</a></li>
              <li><a href="#">API 文档</a></li>
            </ul>
          </li>
          <li class="grid_3">
            <h4><a>关于</a><span>about</span></h4>
            <ul class="items">
              <li><a href="gywm_about.html" >关于我们</a></li>
              <li><a href="gywm_newlist.html" >媒体报道</a></li>
            </ul>
          </li>
           <li class="grid_3">
            <h4><a>关于</a><span>about</span></h4>
            <ul class="items">
              <li><a href="gywm_about.html" >关于我们</a></li>
              <li><a href="gywm_newlist.html" >媒体报道</a></li>
            </ul>
          </li>
        </ul>
        <div class="grid_4">
        </div>
        <div class="clearfix">
          <p class="footerp">
          <?php echo ($Web["Config"]["site_code"]); ?> 
          <?php echo ($Web["Config"]["site_icp"]); ?>          
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(function(){
    var timer;
    $(".changeVerify").click(function(){
        clearTimeout(timer);
        $('#verify').attr('src','<?php echo U("Home/User/verify");?>'+'?r='+Math.random());
    });
    $("#subreg").click(function(){
    		$(".formvalidate").ajaxSubmit({
    			dataType:  'json',
    			beforeSend: function() {
    				var loadi = layer.load('注册中...');
    			},
    			beforeSubmit: function() {
    			   return $(".formvalidate").valid();  //进行回调验证
    			},
    			success: function(data) {
    				layer.closeAll();
    				if(data.status == '1'){
    		            	layer.msg(data.info,2,1);
    		            	setTimeout(function(){
    	                        location.href = '<?php echo U("Home/User/login");?>';
    	                    },3000);
    		         }else{
    		            	layer.msg(data.info,2,8);
    		        }
    			},
    			error:function(xhr){
    				layer.msg(xhr,2,8);
    			}
    		});
        return false;
    });
});

$.validator.addMethod("checkusername",
	    function(value) {
			var url = "<?php echo U('Home/User/ajaxusername');?>";
			var rdata=true;
		 	$.ajax({
		        url:url,
		        cache:false,
		        dataType:"json",
		        data:{'username':value},
		        type:"GET",
		        async:false,
		        success:function(msgObj){
			        if(msgObj.status=="0"){
			        	rdata=false;
				    }
		        }
		    });
	        return rdata;
}, "用户名已经存在");
$.validator.addMethod("checkmobi",
	    function(value) {
			var url = "<?php echo U('Home/User/ajaxmobi');?>";
			var rdata=true;
		 	$.ajax({
		        url:url,
		        cache:false,
		        dataType:"json",
		        data:{'mobi':value},
		        type:"GET",
		        async:false,
		        success:function(msgObj){
			        if(msgObj.status=="0"){
			        	rdata=false;
				    }
		        }
		    });
	        return rdata;
}, "手机号码已存在");
$.validator.addMethod("checkmobicode",
	    function(value) {
			var url = "<?php echo U('Home/User/ajaxmobicode');?>";
			var rdata=true;
		 	$.ajax({
		        url:url,
		        cache:false,
		        dataType:"json",
		        data:{'mobicode':value},
		        type:"GET",
		        async:false,
		        success:function(msgObj){
			        if(msgObj.status=="0"){
			        	rdata=false;
				    }
		        }
		    });
	        return rdata;
}, "手机号码验证码错误");
$.validator.addMethod("checkcode",
	    function(value) {
			var url = "<?php echo U('Home/User/ajaxcode');?>";
			var rdata=true;
		 	$.ajax({
		        url:url,
		        cache:false,
		        dataType:"json",
		        data:{'code':value},
		        type:"GET",
		        async:false,
		        success:function(msgObj){
			        if(msgObj.status=="0"){
			        	rdata=false;
				    }
		        }
		    });
	        return rdata;
}, "验证码错误");
var InterValObj; //timer变量，控制时间
var count = 60; //间隔函数，1秒执行
var curCount;//当前剩余秒数
function sendMessage() {
	if($("#btnSendCode").val()=="重新发送验证码"){
		$("#mobi").removeAttr("readonly");//启用按钮
		$("#btnSendCode").val("发送验证码");
		return false;
	}
    curCount = count;
   	if($("#mobi").val()==""){
   		layer.msg("手机号码不能为空",2,8);
   		$("#mobi").focus();
   		return false;
   	}
 	$.ajax({
        url:'<?php echo U("Home/User/sendmobicode");?>',
        cache:false,
        dataType:"json",
        data:{'mobi':$("#mobi").val(),'icode':$("#icode").val()},
        type:"POST",
        success:function(data){
        	if(data.status == '1'){
        		layer.msg(data.info,2,1);
        		$("#btnSendCode").attr("disabled", "true");
        		$("#mobi").attr("readonly", "true");
        		InterValObj = window.setInterval(SetRemainTime, 1000);
        	}else{
        		layer.msg(data.info,2,8);
            }
        }
    });
}
function SetRemainTime() {
    if (curCount == 0) {                
        window.clearInterval(InterValObj);//停止计时器
        $("#btnSendCode").removeAttr("disabled");//启用按钮
        $("#btnSendCode").val("重新发送验证码");
       
    }
    else {
        curCount--;
        $("#btnSendCode").val("请在" + curCount + "秒内输入验证码");
    }
}
</script>
</body>
</html>