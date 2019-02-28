<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>发放优惠券</title>
<link rel="stylesheet" href="__PUBLIC__/Wechat/Admin/css/style.default.css" type="text/css" />
<script type="text/javascript" src="__PUBLIC__/Wechat/Admin/js/jquery-1.8.2.js"></script>
<script type="text/javascript" src="__PUBLIC__/Wechat/Admin/js/jquery-ui.js"></script>
<script type="text/javascript" src="__PUBLIC__/Wechat/Admin/js/jquery.cookie.js"></script>
<script type="text/javascript" src="__PUBLIC__/Wechat/Admin/js/layer/layer.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Wechat/Admin/js/jquery.form.js"></script>
<script type="text/javascript" src="__PUBLIC__/Wechat/Admin/js/general.js"></script>
<!--[if IE 9]>
    <link rel="stylesheet" media="screen" href="__PUBLIC__/Wechat/Admin/css/style.ie9.css"/>
<![endif]-->
<!--[if IE 8]>
    <link rel="stylesheet" media="screen" href="__PUBLIC__/Wechat/Admin/css/style.ie8.css"/>
<![endif]-->
<!--[if lt IE 9]>
	<script src="__PUBLIC__/Wechat/Admin/js/css3-mediaqueries.js"></script>
<![endif]-->

<link rel="stylesheet" href="__PUBLIC__/Wechat/Admin/js/validate/validate.css" />
<script type="text/javascript" src="__PUBLIC__/Wechat/Admin/js/validate/jquery.validate.1.9.js"></script>
<script type="text/javascript" src="__PUBLIC__/Wechat/Admin/js/validate/jquery.metadata.js"></script>
<script type="text/javascript" src="__PUBLIC__/Wechat/Admin/js/validate/messages_cn.js"></script>
<script type="text/javascript" src="__PUBLIC__/Wechat/Admin/js/validate/global.js"></script>
<script src="__PUBLIC__/Admin/JS/mydatepicker/WdatePicker.js"></script>
<script type="text/javascript">
function checksendform(){
	var choUser = $("#choUser").val();
	$("#utype").val(choUser);//设置选择用户类型
	if(choUser=='some'){ //如果是指定用户 获取用户编号
		var chk_ids = '';//获取编号
		$('input[cc="cc"]:checked').each(function(){
			chk_ids += $(this).val()+",";
		});
		chk_ids = chk_ids.substr( 0,chk_ids.length-1);
		$("#ids").val(chk_ids);
		if(!chk_ids){
        	layer.msg('没有选择任何用户！',2,8);
			return false;
		}
	}
	return true;
}
</script>
</head>
<body class="withvernav">
<div class="bodywrapper">
 <div class="topheader">
        <div class="left">
            <h1 class="logo">Wechat<span>Admin</span></h1>
            <span class="slogan">微信管理</span>
            <br clear="all" />
        </div><!--left-->
        <div class="right">
            <div class="userinfo">
                <span onclick="location.href='<?php echo U('Wechat/Admin/doquit');?>'"><a href="<?php echo U('Wechat/Admin/doquit');?>" style="color:White">退出返回</a></span>
            </div><!--userinfo-->
            
        </div><!--right-->
    </div><!--topheader-->
    <div class="header">
    <ul class="headermenu">
    	<li nav="1" id="nav_1" >
    	<a href="" >
    		<span class="icon icon-flatscreen"></span>微信管理</a>
    	</li>   
    	</ul>
       <div class="headerwidget">
        </div><!--headerwidget-->
    </div><!--header-->
<div class="vernav2 iconmenu">
	<ul>
 		<li class="current">
			<a href="#form_内容管理" class="support">关键词回复</a>
			<span class="arrow"></span> 
			<ul id="form_内容管理">
				<li nc_type='内容管理'>
				<a href="<?php echo U('Wechat/Admin/reply');?>"  name="item_Cloud_index" id="item_Cloud_index">回复规则管理</a>
				<a href="<?php echo U('Wechat/Admin/replyadd');?>"  name="item_Cloud_index" id="item_Cloud_index">添加回复规则</a>
				</li> 
			</ul>
		</li>
		<li class="current">
			<a href="#form_用户管理" class="support">用户管理</a>
			<span class="arrow"></span>
			<ul id="form_用户管理">
				<li nc_type='用户管理'>
				<a href="<?php echo U('Wechat/Admin/userlist');?>" name="item_Cloudconfig_master" id="item_Cloudconfig_master">用户管理</a>
				</li>
		 	</ul>
		</li>
		<li class="current">
			<a href="#form_促销活动" class="support">促销活动</a>
			<span class="arrow"></span>
			<ul id="form_促销活动">
				<li nc_type='促销活动'>
				<a href="<?php echo U('Wechat/Admin/couponlist');?>" name="item_Cloudconfig_master" id="item_Cloudconfig_master">优惠券发放列表</a>
				<a href="<?php echo U('Wechat/Admin/couponsend');?>" name="item_Cloudconfig_master" id="item_Cloudconfig_master">优惠券发放</a>
				</li>
		 	</ul>
		</li>
		<li class="current">
			<a href="#form_微信配置" class="support">微信配置</a>
			<span class="arrow"></span>
			<ul id="form_微信配置">
				<li nc_type='微信配置'>
				<a href="<?php echo U('Wechat/Admin/setapi');?>" >微信接口</a>
				<a href="<?php echo U('Wechat/Admin/menu');?>">自定义菜单</a>
				<a href="<?php echo U('Wechat/Admin/menuadd');?>">添加菜单</a>
				</li>
			</ul>
		</li>
		<li class="current">
			<a href="#form_模版消息" class="support">模版消息</a>
			<span class="arrow"></span>
			<ul id="form_模版消息">
				<li nc_type='模版消息'>
					<a href="<?php echo U('Wechat/Admin/templatesend');?>" >模版消息群发</a>
					<a href="<?php echo U('Wechat/Admin/templatelist');?>" >模版消息列表</a>
					<a href="<?php echo U('Wechat/Admin/templateadd');?>" >添加模版消息</a>
				</li>
			</ul>
		</li>
	</ul>
	<a class="togglemenu"></a>
	<br /><br />
</div><!--leftmenu-->
<div class="centercontent tables">
		<div class="pageheader notab">
			<h1 class="pagetitle20">微信发放优惠券</h1>
			<span class="pagedesc">发放</span>
		</div><!--pageheader-->
		<div id="contentwrapper" class="contentwrapper">
		   <div id="basicform" class="subcontent">
			<form class="stdform formvalidate" action="<?php echo U('Wechat/Admin/couponsend');?>" method="post">
				<p><label>优惠券类型</label>
				<span class="field">
					<select name='coupon_type' class="selectwidth40" >
						<option value='Cloud产品'>Cloud产品</option>
					</select>
				</span></p>
				<p><label>面值</label>
				<span class="field">
					<input type="text" name="cmoney" id="cmoney" class="smallinput20" validate='{required:true,isIntGteZero:true,messages:{required:"不能为空"}}' />
				</span></p>
				<p><label>使用过期时间</label>
				<span class="field">
					<input type="text" name="expire_time" id="expire_time" class='hasDatepicker' onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd H:m:s'})" validate='{required:true,messages:{required:"不能为空"}}'/>
				</span></p>
				<p><label>使用金额</label>
				<span class="field">
					<input type="text" name="condition" id="condition" class="smallinput20" validate='{required:true,isIntGteZero:true,messages:{required:"不能为空"}}' />
				超过多少总金额 方可使用
				</span></p>
				<p><label>所属客服</label>
				<span class="field">
					<select name='kid' class="selectwidth20" >
						<option value='0' >无</option>
						<?php if(is_array($kefus)): $i = 0; $__LIST__ = $kefus;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value='<?php echo ($vo["rank_id"]); ?>' disabled='disabled'><?php echo ($vo["rank_name"]); ?></option>
							<?php if(is_array($vo["kefus"])): $i = 0; $__LIST__ = $vo["kefus"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;?><option value='<?php echo ($voo["id"]); ?>'>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($voo["kefuname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
					</select>
				</span></p>
				<p><label>发放用户</label>
				<span class="field">
					<select name='chosUser' id='choUser' class="selectwidth20" onchange='changeUserType(this);'>
						<option value='all' >所有</option>
						<option value='some' >指定客户</option>
					</select>
				</span></p>
				<p class="stdformbutton">
					<input type='hidden' name='utype' id='utype'/>
					<input type='hidden' name='ids' id='ids'/>
					<input type="submit" class="submit radius2 submitsend" value="发放"/>
				</p>
			</form>
			</div>
			<div id='choseUser' style='display:none;border:0px solid #999;width:70%;'>
				<div style='border:1px solid #ddd'>
					<form class="formcs" action="<?php echo U('Wechat/Admin/couponusers');?>" method='get'>
						OpenId：		<input type='text' name='open_id' class='smallinput20'/>
						用户昵称：	<input type='text' name='nickname' class='smallinput20'/>
						绑定用户：	<input type='text' name='uname' class='smallinput20'/>
						<input type='submit' class='couponsearch' value='查询'/>
					</form>
				</div>
				<div>
					<table cellpadding="0" cellspacing="0" border="0" id="table2" class="stdtable stdtablecb">
						<thead>
							<tr>
								<th class="head0">
									<input type='checkbox' name='checkboxall' id='checkboxall' value='0'/>
								</th>
								<th class="head0">OpenId</th>
								<th class="head0">昵称</th>
								<th class="head1">绑定用户</th>
							</tr>
						</thead>
						<tbody id='tablelist'>
							<?php if(is_array($users)): $i = 0; $__LIST__ = $users;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$user): $mod = ($i % 2 );++$i;?><tr>
								<td><input type='checkbox' cc='cc' name='chbx<?php echo ($user["id"]); ?>' id='chbx<?php echo ($user["id"]); ?>' value='<?php echo ($user["id"]); ?>' /></td>
								<td><?php echo ($user['wxid']); ?></td>
								<td><?php echo ($user['nickname']); ?></td>
								<td><?php echo ($user['uname']); ?></td>
							</tr><?php endforeach; endif; else: echo "" ;endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div><!--contentwrapper-->
	</div><!--centercontent-->
</div><!--bodywrapper-->
<script type="text/javascript">
function changeUserType(obj){
	var type = $(obj).val();
	if(type=='all'){
		$("#choseUser").hide();
	}else{
		$("#choseUser").show();
	}
}
//数据提交处理
$(function () {
	$(".couponsearch").click(function(){ 
		$(".formcs").ajaxSubmit({
			dataType:  'json',
			resetForm: false,//resetForm: true,重置form表单
			beforeSend: function() {
				var loadi = layer.load('数据提交中...');//提示框
			},
			success: function(data) {
				layer.closeAll();
				var datas = data.info;
				$("#tablelist").html("");
				for(var i=0;i<datas.length;i++){
					var item = datas[i];
					var html = "<tr>";
					html += "<td><input type='checkbox' cc='cc' name='chkb"+item['id']+"' id='chkb"+item['id']+"' value='"+item['id']+"'/></td>";			
					html += "<td>"+item['wxid']+"</td>";
					html += "<td>"+item['nickname']+"</td>";;
					html += "<td>"+item['uname']+"</td>";
					html += "</tr>";
					$("#tablelist").append(html);
				}
			},
			error:function(xhr){
				layer.msg(xhr,2,8);
			}
		});
		return false;
	});
	$(".submitsend").click(function(){ 
		if(checksendform())
		$(".formvalidate").ajaxSubmit({
			dataType:  'json',
			resetForm: true,//resetForm: true,重置form表单
			beforeSend: function() {
				var loadi = layer.load('数据提交中...');//提示框
			},
			beforeSubmit: function() {
			   return $(".formvalidate").valid();  //进行回调验证
			},
			success: function(data) {
				layer.closeAll();
				if(data.status == '1'){
		            	layer.msg(data.info,2,1);
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
</script>
</body>
</html>