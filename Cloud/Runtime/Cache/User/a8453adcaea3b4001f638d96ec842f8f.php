<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>在线充值-<?php echo ($Web["Config"]["site_name"]); ?></title>
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
			<b>在线充值</b>
			<span>
				<a class="btn btnBlue2" href="<?php echo U('User/Center/account');?>"">我的账户</a>
				<a class="btn btnBlue2" href="<?php echo U('User/Center/index');?>">安全信息</a>
				<a class="btn btnBlue2" href="<?php echo U('User/Center/basicdata');?>">基本资料</a>
				<a class="btn btnBlue2" href="<?php echo U('User/Center/contactinfo');?>">联系人信息</a>
			</span>
		</div>
		<div class="mainM">
			<div class="money"><b>充值金额：</b>
			<input name="money" id="money" type="text" value="10">元 </div>
			<div class="payment">
				<div class="tabTbox">
					<ul class="tabT" id="tabT1">
						<li class="now">支付宝</li>
					</ul>
				</div>
				<div class="blockBody" id="tabB1">
					<div class="tabBlock now">
						<div class="item"></div>
						<div class="item">
							<div class="paymentBox">
								<label class="now">
								<input name="payment1" value="alipay" id="payment1_1" checked="checked" type="radio">
								<img src="__PUBLIC__/User/Default/images/yh/qr_yh22.png">
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<button class="btn btnOrg1" type="button" onclick="openBlank()">确认支付</button>
		</div>

	</div>
</div>
<!--尾部-->
<div id="footer"><i></i>©港湾网络版权所有.2013-2014 粤ICP备13068038号-1</div>
<script type="text/javascript">
function openBlank(){
	var action="<?php echo U('User/Payment/doalipay');?>";
	var data={money:$('#money').val()};
    var form = $("<form/>").attr('action',action).attr('method','post');
    form.attr('target','_blank');
    var input = '';
    $.each(data, function(i,n){
        input += '<input type="hidden" name="'+ i +'" value="'+ n +'" />';
    });
    form.append(input).appendTo("body").css('display','none').submit();
}
</script>

</body>
</html>