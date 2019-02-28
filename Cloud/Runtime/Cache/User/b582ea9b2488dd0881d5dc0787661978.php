<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>订单支付-<?php echo ($Web["Config"]["site_name"]); ?></title>
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
			<b>订单管理</b>
			<span>
			 	<a class="btn btnBlue2" href="<?php echo U('User/Center/account');?>"">我的账户</a>
				<a class="btn btnBlue2" href="<?php echo U('User/Center/index');?>">安全信息</a>
				<a class="btn btnBlue2" href="<?php echo U('User/Center/basicdata');?>">基本资料</a>
				<a class="btn btnBlue2" href="<?php echo U('User/Center/contactinfo');?>">联系人信息</a>
			</span>
		</div>
		<div class="mainM">
			<div class="mainHalf3">
				<p class="mainTson">订单详情</p>
				<div class="forms">
					<div class="item">
						<b style="width: 180px;">编号：</b>
						<?php echo ($data["id"]); ?>
					</div>
					<div class="item">
						<b style="width: 180px;">下单时间：</b>
						<?php echo (date('Y-m-d H:i:s',$data["addtime"])); ?>
					</div>
					<div class="item">
						<b style="width: 180px;">订单类型：</b>
						 <?php if($data["type"] == 1): ?>云主机<?php endif; ?>
					</div>
					<div class="item">
						<b style="width: 180px;">操作类型：</b>
						<?php echo ($data["ordertype"]); ?>
					</div>
					<div class="item">
						<b style="width: 180px;">产品类型：</b>
						<?php echo ($data["producttype"]); ?>
					</div>
					<div class="item">
						<b style="width: 180px;">周期：</b>
						<?php echo ($data["yearname"]); ?>
					</div>
					<div class="item">
						<b style="width: 180px;">金额：</b>
						<?php echo ($data["usermoney"]); ?>
					</div>
					<div class="item">
					<b style="width: 180px;">订单详情：</b>
						CPU：<?php echo ($data["cpunum"]); ?>个
						内存：<?php echo ($data["memnum"]); ?>M
						硬盘:<?php echo ($data["disknum"]); ?>G
						带宽:<?php echo ($data["qosnum"]); ?>M
						IP: <?php if($data["dlip"] == 1): ?>独立IP<?php else: ?>共享IP<?php endif; ?>
					</div>
					<?php if(!empty($cloudos['osname'])): ?><div class="item">
							<b style="width: 180px;">系统类型：</b>
							<?php echo ($cloudos["osname"]); ?>
						</div><?php endif; ?>
					
					<div class="item">
						<b style="width: 180px;">状态：</b>
						 <?php if($data["status"] == 1): ?>未支付<?php endif; ?>
						 <?php if($data["status"] == 2): ?>已支付<?php endif; ?>
						 <?php if($data["status"] == 3): ?>已取消<?php endif; ?>
						 <?php if($data["status"] == 10): ?>已支付(开通失败)<?php endif; ?>
					</div>
					<?php if($data["status"] == 10): ?><div class="item">
							<b style="width: 180px;">备注：</b>
							<?php echo ($data["statusinfo"]); ?>
						</div><?php endif; ?>
					
				<?php if($data["status"] == 1): ?><div class="item">
						<b style="width: 180px;">是否启用折扣：</b>
								<?php if(empty($data['aid'])): if($data["isrebate"] == 1): ?>是<?php endif; ?>
									<?php if($data["isrebate"] == 0): ?>否<?php endif; ?>
									<?php else: ?> 
									促销活动<?php endif; ?>
								
						</div>
					
						<?php if($coupon): ?><div class="item">
							<b style="width: 180px;">优惠券：</b>
							<select  id="coupon" name="coupon">
								<option value="0">请选择</option>
								<?php if(is_array($coupon)): $i = 0; $__LIST__ = $coupon;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["couponnum"]); ?>(金额<?php echo ($vo["couponmoney"]); ?>)</option><?php endforeach; endif; else: echo "" ;endif; ?>
							</select>							
						</div><?php endif; endif; ?>
					<?php if($data["status"] == 2): ?><div class="item">
							<b style="width: 180px;">产品ID：</b>
							<?php echo ($data["pid"]); ?>
					</div>
					
					<div class="item">
							<b style="width: 180px;">开通时间：</b>
							<?php echo (date('Y-m-d H:i:s',$data["starttime"])); ?>
					</div>
					<div class="item">
							<b style="width: 180px;">到期时间：</b>
							<?php echo (date('Y-m-d H:i:s',$data["endtime"])); ?>
					</div>
					<div class="item">
							<b style="width: 180px;">说明：</b>
							<?php echo ($data["logfor"]); ?>
					</div><?php endif; ?>
					
				</div>
			</div>
			
			<hr class="dotted">
			<?php if($data["status"] == 1): ?><input type="hidden" name="id" id="id" value="<?php echo ($data["id"]); ?>">
				<button class="btn btnOrg1 btnsubmitcloudopen" type="submit">付款开通</button>
				<input class="btn btnOrg1 btnsubmitpost" type="button" onclick="location.href='<?php echo U('User/Order/index');?>'" value="返回"/>
			<?php else: ?>
				<button class="btn btnOrg1 btnsubmitpost" type="button" onclick="location.href='<?php echo U('User/Order/index');?>'">返回</button><?php endif; ?>
		</div>
		<!--<form id="subform" name="subform" action="<?php echo U('User/Order/cloudopen');?>" method="post">
		</form>-->
	</div>
</div>
<!--尾部-->
<div id="footer"><i></i>©港湾网络版权所有.2013-2014 粤ICP备13068038号-1</div>
<script>
$(".btnsubmitcloudopen").click(function(){ 
	var id=$("#id").val();
	var coupon=$("#coupon").val();
	layer.confirm('确定操作当前订单',function(index){
		$(this).ajaxSubmit({
			type:"post",
			dataType:  'json',
			resetForm: true,
			data:{id:id,coupon:coupon},
			url:"<?php echo U('User/Order/cloudopen');?>",
			beforeSend: function() {
				var loadi = layer.load('<font color="red">云主机开通中！请耐心等待!!!</font>');//提示框
			},
			success: function(data) {
				if(data.status == '1'){
					layer.msg(data.info,2,1);
					setTimeout(function(){
						 location.href = '<?php echo U("User/Cloud/index");?>';
					},1000);
	            }else{
	                
	            	layer.msg(data.info,2,8);
	            	
	     	    }
			}
		});
	});
	return false;
});
$(".btnsubmitcloudopenr").click(function(){ 
	var id=$("#id").val();
	var coupon=$("#coupon").val();
	layer.confirm('确定操作当前订单',function(index){
		$(this).ajaxSubmit({
			type:"post",
			dataType:  'json',
			resetForm: true,
			data:{id:id,coupon:coupon},
			url:"<?php echo U('User/Order/cloudopenafresh');?>",
			beforeSend: function() {
				var loadi = layer.load('<font color="red">云主机重新开通中！请耐心等待!!!</font>');//提示框
			},
			success: function(data) {
				if(data.status == '1'){
					layer.msg(data.info,2,1);
					setTimeout(function(){
						 location.href = '<?php echo U("User/Cloud/index");?>';
					},1000);
	            }else{
	                
	            	layer.msg(data.info,2,8);
	            	
	     	    }
			}
		});
	});
	return false;
});
</script>
</body>
</html>