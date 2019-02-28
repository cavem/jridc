<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户中心-<?php echo ($Web["Config"]["site_name"]); ?></title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/User/Default/css/style.css" />
<link rel="stylesheet" href="__PUBLIC__/User/js/validate/validate.css" />
<script language="javaScript" src="__PUBLIC__/User/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/User/js/layer/layer.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/User/js/jquery.form.js"></script>
<script type="text/javascript" src="__PUBLIC__/User/js/validate/jquery.validate.1.9.js"></script>
<script type="text/javascript" src="__PUBLIC__/User/js/validate/jquery.metadata.js"></script>
<script type="text/javascript" src="__PUBLIC__/User/js/validate/messages_cn.js"></script>
<script type="text/javascript" src="__PUBLIC__/User/js/validate/global.js"></script>
<script type="text/javascript" src="__PUBLIC__/User/js/mydatepicker/WdatePicker.js"></script>
<script language="javaScript"  src="__PUBLIC__/User/Default/js/common.js"></script>
<script type="text/javascript" src="__PUBLIC__/User/Default/js/general.js"></script>

</head>

<body>


<!--头部-->
<div id="header">
	<a href="/"><img alt="" src="__PUBLIC__/User/Default//images/logo.png"></a>
	<div class="topR topUser">
		<p>欢迎您，<span><?php echo ($user_info["username"]); ?></span></p>
		<dl>
			<dt></dt>
			<dd>
			<a href="<?php echo U('User/Center/basicdata');?>">账户设置</a>
			<a href="<?php echo U('User/Money/index');?>">财务记录</a>
			<a href="<?php echo U('User/Center/dologout');?>">退出</a></dd>
		</dl>
	</div>
<style type="text/css">
<!--
.zs2 { width:150px; border:1px solid #ddd; background:#fff; border-radius:5px; padding:10px; margin:8px 20px 0 0; float:right;}
.zs2 a{ width:80px; height:20px; background:url(__PUBLIC__/User/Default/images/kf_img2.png) no-repeat; text-indent:-9999px; float:left;}
-->
</style>
  <div>
  	<div class="zs2">
		<p>
			<span style="float:left; line-height:20px;">专有客服：</span>
			<?php echo ($user_kefu["kefuname"]); ?>
		</p>
		<p style="margin-top:10px; float:left;">
			<span style="float:left; line-height:20px;">在线客服：</span>
			<a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo ($user_kefu["kefuqq"]); ?>&site=qq&menu=yes" target="_blank">客服QQ</a>
		</p>
	</div>
  </div>
</div>
<!--主体-->
<div id="content">
<!--菜单-->
	<!--菜单-->
	<dl class="nav">
		<dt id="navUser">会员信息<i></i></dt>
		<dd>
			<a href="<?php echo U('User/Center/index');?>">安全信息</a>
			<a href="<?php echo U('User/Center/basicdata');?>">基本资料</a>
			<a href="<?php echo U('User/Center/contactinfo');?>">联系人信息</a>
		</dd>
		<dt id="navFin">财务管理<i></i></dt>
		<dd>
			<a href="<?php echo U('User/Center/account');?>">我的账户</a>
			<a href="<?php echo U('User/Order/index');?>">订单管理</a>
			<a href="<?php echo U('User/Money/index');?>">财务管理</a>
			<a href="<?php echo U('User/Pay/index');?>">在线充值</a>
			<a href="<?php echo U('User/Fapiao/index');?>">发票管理</a>
			<a href="<?php echo U('User/Card/index');?>">充值卡管理</a>
			<a href="<?php echo U('User/Coupon/index');?>">优惠券管理</a>
		</dd>
		<dt id="navPro">产品管理<i></i></dt>
		<dd>
			<a href="<?php echo U('User/Cloud/index');?>">我的云主机</a>
		</dd>
		<dt id="navService">售后服务<i></i></dt>
		<dd>
			<a href="<?php echo U('User/Support/add');?>">工单提交</a>
			<a href="<?php echo U('User/Support/index');?>"">我的工单</a>
		</dd>
	</dl>
	<!--内容-->
	<div class="main">
		<div class="mainT">
			<b>安全信息</b>
			<span>
				<a class="btn btnBlue2" href="<?php echo U('User/Center/account');?>"">我的账户</a>
				<a class="btn btnBlue2" href="<?php echo U('User/Center/index');?>">安全信息</a>
				<a class="btn btnBlue2" href="<?php echo U('User/Center/basicdata');?>">基本资料</a>
				<a class="btn btnBlue2" href="<?php echo U('User/Center/contactinfo');?>">联系人信息</a>
			</span>
		</div>
		<div class="mainM">
			<div class="user">
				<p>用户名：<?php echo ($user_info["username"]); ?></p>
				<p>手机号：<?php echo ($user_info["mobi"]); ?></p>
				<p>用户组：<?php echo ($user_rank["rank_name"]); ?>
				</p>
			</div>
			
			<div class="scyText">
				<p class="item">
					<span class="judge">登录密码</span>
					<span class="font4">
					安全性高的密码可以是账号更安全，建议您定期更换密码</span>
					<a href="<?php echo U('User/Center/password');?>">修改</a>
				</p>
				<p class="item">
					<span class="judge <?php if(empty($user_info['mobiv'])): ?>flase<?php endif; ?>">手机验证</span>
					<span class="font4">
					您的<?php echo ($user_info["mobi"]); ?>手机已经 <?php if(empty($user_info['mobiv'])): ?>未通过验证<?php else: ?>通过验证<?php endif; ?>（您的手机为安全手机）
					</span>
					<a href="<?php echo U('User/Center/mobile');?>">修改</a>
					<?php if($user_info['mobiv']): ?><a href="
						<?php if($remind['sms']): echo U('User/Center/setSupT?type=sms&status=0');?>
						<?php else: echo U('User/Center/setSupT?type=sms&status=1'); endif; ?>" style='margin-right:20px;'>
							<?php if($remind['sms']): ?>关闭<?php else: ?>开启<?php endif; ?>工单提醒</a><?php endif; ?>
				</p>
				<p class="item ">
					<span class="judge <?php if(empty($user_info['emailv'])): ?>flase<?php endif; ?>">邮箱验证</span>
					<span class="font4">
					您的<?php echo ($user_info["email"]); ?>邮箱<?php if(empty($user_info['emailv'])): ?>未通过验证<?php else: ?>通过验证<?php endif; ?>，请您尽快进行邮箱验证
					</span>
					<a href="<?php echo U('User/Center/email');?>">修改</a>
					<?php if($user_info['email']): ?><a href="
						<?php if($remind['email']): echo U('User/Center/setSupT?type=email&status=0');?>
						<?php else: echo U('User/Center/setSupT?type=email&status=1'); endif; ?>" style='margin-right:20px;'>
							<?php if($remind['email']): ?>关闭<?php else: ?>开启<?php endif; ?>工单提醒</a><?php endif; ?>
				</p>
				<p class="item noBor">
					<span class="judge <?php if(empty($bw)): ?>flase<?php endif; ?>">微信绑定</span>
					<span class="font4">
					您<?php if(empty($bw)): ?>没有任何<?php else: ?>已有<?php echo ($bw); ?>个<?php endif; ?>微信号绑定到本帐号，请关注公众号：gwclouds进入微信管理。
					</span>
					<a href="<?php echo U('User/Center/wechat');?>">查看</a>
				</p>
			</div>
		</div>
	<!-- 	<a href="http://www.yunip.com/xianggang" target="_blank"><img src="http://www.yunip.com/images/xianggang.png" style="margin-top:20px; margin-left:10px;"></a>
 -->	</div>
</div>
<!--尾部-->
<div id="footer"><i></i>©港湾网络版权所有.2013-2014 粤ICP备13068038号-1</div>

</body>
</html>