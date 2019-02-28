<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>基本信息-<?php echo ($Web["Config"]["site_name"]); ?></title>
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
			<b>基本资料</b>
			<span>
			 	<a class="btn btnBlue2" href="<?php echo U('User/Center/account');?>"">我的账户</a>
				<a class="btn btnBlue2" href="<?php echo U('User/Center/index');?>">安全信息</a>
				<a class="btn btnBlue2" href="<?php echo U('User/Center/basicdata');?>">基本资料</a>
				<a class="btn btnBlue2" href="<?php echo U('User/Center/contactinfo');?>">联系人信息</a>
			</span>
		</div>
		<form action="<?php echo U('User/Center/dobasicdata');?>" method="post" id="saveinfo" class="formvalidate">
		<div class="mainM">
			<div class="mainHalf3">
				<p class="mainTson">基本资料</p>
				<div class="forms">
					<div class="item">
						<b style="width: 180px;">用户名：</b>
						<?php echo ($user_info["username"]); ?>
					</div>
					<div class="item">
						<b style="width: 180px;">手机：</b>
						<?php echo ($user_info["mobi"]); ?>
					</div>
					<div class="item">
						<b style="width: 180px;">Email：</b>
						<?php echo ($user_info["email"]); ?>
					</div>
					<div class="item">
						<b style="width: 180px;">姓名（或企业名称）：</b>
						<input name="conname" id="conname" value="<?php echo ($user_info["conname"]); ?>" type="text">
					</div>
					<div class="item">
						<b style="width: 180px;">身份证号码（或营业执照）：</b>
						<input name="concode" id="concode" value="<?php echo ($user_info["concode"]); ?>" type="text">
					</div>
					<div class="item">
						<b style="width: 180px;">省份/直辖市：</b>
	  <select name="province" id="province" onchange="loadRegion('province',2,'city','town','<?php echo U('User/Center/getregion');?>');">
       <option value="0" selected>省份/直辖市</option>
	       <?php if(is_array($province)): $i = 0; $__LIST__ = $province;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"    <?php if($vo["id"] == $user_info['province']): ?>selected="selected"<?php endif; ?>><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
      </select>          
					</div>
						<div class="item">
						<b style="width: 180px;">市/县：</b>       
     <select name="city" id="city"  onchange="loadRegion('city',3,'town','','<?php echo U('User/Center/getregion');?>');">
      	 <option value="0">市/县</option>
      	  <?php if(is_array($city)): $i = 0; $__LIST__ = $city;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($voo["id"]); ?>"    <?php if($voo["id"] == $user_info['city']): ?>selected="selected"<?php endif; ?>><?php echo ($voo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
     </select>

					</div>
						<div class="item">
						<b style="width: 180px;">镇/区：</b>
	 <select name="town" id="town">
     	  <option value="0">镇/区</option>
     	   <?php if(is_array($town)): $i = 0; $__LIST__ = $town;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vooo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vooo["id"]); ?>"    <?php if($vooo["id"] == $user_info['town']): ?>selected="selected"<?php endif; ?>><?php echo ($vooo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
     </select>
					</div>
						<div class="item">
						<b style="width: 180px;">地址：</b>
						<input name="address" id="address" value="<?php echo ($user_info["address"]); ?>" type="text">
					</div>
					<div class="item">
						<b style="width: 180px;">邮编：</b>
						<input name="zipcode" id="zipcode" value="<?php echo ($user_info["zipcode"]); ?>" type="text">
					</div>
					<div class="item">
						<b style="width: 180px;">固定电话：</b>
						<input name="tel" id="tel" value="<?php echo ($user_info["tel"]); ?>" type="text">
					</div>
				
				</div>
			</div>
			<hr class="dotted">
			<button class="btn btnOrg1 btnsubmitedit" type="submit">保存</button>
		</div>
		<input name="sort" value="0" type="hidden">
		
		</form>
	</div>
</div>
<!--尾部-->
<div id="footer"><i></i>©港湾网络版权所有.2013-2014 粤ICP备13068038号-1</div>
</body>
</html>