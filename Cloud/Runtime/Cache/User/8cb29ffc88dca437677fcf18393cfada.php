<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>我的云主机-<?php echo ($Web["Config"]["site_name"]); ?></title>
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
			<b>我的云主机</b>
		</div>
		
		<div class="mainM">
		<div id="search" class="forms">
			<form action="<?php echo U('User/Cloud/index');?>" method="get" class="forms">
				<div class="item">
				主机名<input style="width: 100px; color: rgb(153, 153, 153);" value="<?php echo ($cloudname); ?>" name="cloudname" type="text">
				开始时间
				<input  class="time hasDatepicker" name="starttime" value="<?php echo ($starttime); ?>" onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})" type="text">
				结束时间
				<input  class="time hasDatepicker" name="endtime"  value="<?php echo ($endtime); ?>" onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})" type="text">
				</div>
					<button class="btn btnOrg2" type="submit">查询</button>&nbsp
			</form>
		</div>
			<table class="table">
				<tbody>
				<tr class="tdColor">
					<th>机房</th>
					<th>名称</th>
					<th>IP</th>
					<th>创建时间</th>
					<th>到期时间</th>
					<th>状态</th>
					<th>操作</th>
				</tr>	
			<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
				<td><?php echo ($vo['jfname']); ?></td>
				<td id='vm_name_<?php echo ($vo["id"]); ?>'>
				<input type="hidden" name="vmid[]" value='<?php echo ($vo["id"]); ?>' />
					<?php echo ($vo['cloudname']); ?>
				</td>
				<td id='vm_ip_<?php echo ($vo["id"]); ?>'>
				<img src='__PUBLIC__/User/images/loading.gif' alt="loading" title="loading"/>
				</td>
				<td><?php echo (date('Y-m-d H:i:s',$vo["starttime"])); ?></td>
				<td><?php echo (date('Y-m-d H:i:s',$vo["endtime"])); ?></td>
				<td>
					<?php if($vo['istest'] == 'y'): ?>试用<?php endif; ?>
					<span id='vm_status_<?php echo ($vo["id"]); ?>'><?php echo ($vo['status']); ?></span>
				</td>
				<td>
					<?php if($vo['istest'] == 'y'): ?><a href="<?php echo U('User/Cloud/cloudact',array('id'=>$vo['id'],'act'=>'p'));?>">购买</a>
					<?php else: ?>
						<a href="<?php echo U('User/Cloud/cloudact',array('id'=>$vo['id'],'act'=>'p'));?>">延期</a><?php endif; ?>
					<a href="<?php echo U('User/Cloud/cloudact',array('id'=>$vo['id'],'act'=>'a'));?>">增配</a>
					<a href="<?php echo U('User/Cloud/cloudact',array('id'=>$vo['id'],'act'=>'b'));?>">减配</a>
					<a href="<?php echo U('User/Cloud/cloudact',array('id'=>$vo['id'],'act'=>'m'));?>">管理</a>
				</td>
				</tr><?php endforeach; endif; else: echo "" ;endif; ?>
				<?php if(empty($data)): ?><tr>
				<td colspan="8">暂无数据</td>
				</tr><?php endif; ?>
				<tr>
				<td colspan="8">
				<div class="pagediv">
				<div class="pageinfo"><?php echo ($pageinfo); ?></div>
				</div>
				</td>
				</tr>	
			</tbody></table>
	

			<button class="btn btnOrg1" type="submit" onclick="location.href='<?php echo U('Home/Cloud/buy');?>'">创建云主机</button>
		</div>
	</div>
</div>
<!--尾部-->
<div id="footer"><i></i>©港湾网络版权所有.2013-2014 粤ICP备13068038号-1</div>
<script type="text/javascript">
var getvmstatusurl="<?php echo U('User/Cloud/vmstate');?>";
</script>
<script type="text/javascript" src="__PUBLIC__/User/js/cloud.js"></script>
<script type="text/javascript">
get_vm_status();
if (!vm_interval) {
   var vm_interval = setInterval(get_vm_status,20000);
}
</script>
</body>
</html>