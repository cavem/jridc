<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>添加消息模版</title>
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
			<h1 class="pagetitle20">添加模版</h1>
			<span class="pagedesc">添加模版</span>
		</div><!--pageheader-->
		<div id="contentwrapper" class="contentwrapper">
			<div id="basicform" class="subcontent">
			<form class="stdform formvalidate" action="<?php echo U('Wechat/Admin/templateadd');?>" method="post">
				<p><label>模版名称</label>
				<span class="field">
					<input type="text" name="name" id="name" class="smallinput" validate='{required:true,messages:{required:"不能为空"}}' />
				</span></p>
				<p><label>模版编号</label>
					<span class="field">
					<input type="text" name="t_id" id="t_id" class="smallinput" validate='{required:true,messages:{required:"不能为空"}}' />
				</span></p>
				<p><label>排序</label>
					<span class="field">
					<input type="text" name="sort" id="sort" class="smallinput" validate='{required:true,messages:{required:"不能为空"}}' />
				</span></p>
				<p id='field0span'><label>字段名称</label>
					<span class="field">
					<input type="text" name="field0" id="field0" class="smallinput" validate='{required:true,messages:{required:"不能为空"}}' />
				</span></p>
				<p class="stdformbutton">
					<input type='button' id='' class='submit radius2' value='添加字段' onclick="addItem();"/>
					<input type="submit" class="submit radius2 submitadd" value="保存模版"  />
				</p>
			</form>
			</div>
		</div><!--contentwrapper-->
	</div><!--centercontent-->
</div><!--bodywrapper-->
<script>
var i=0;
function addItem(){
	var nowi = i;i++;
	//克隆字段
	var new_field = $("#field0span").clone();
	new_field.attr("id","field"+i+"span");//修改属性
	new_field.find('input[name=field0]').prop("name","field"+i);//修改表单元素name
	new_field.find('input[id=field0]').prop("id","field"+i);//修改表单元素id
	new_field.find('input[id=field'+i+']').prop("value","");//修改表单元素value

	var afid = "#field"+nowi+"span";
	$(new_field).insertAfter(afid);
	
}
</script>
</body>
</html>