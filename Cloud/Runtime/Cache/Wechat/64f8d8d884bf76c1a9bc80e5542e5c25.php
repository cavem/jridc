<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>微信管理平台</title>
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
			<h1 class="pagetitle20">微信用户管理</h1>
			<span class="pagedesc">微信用户管理</span>
		</div><!--pageheader-->
		<div id="contentwrapper" class="contentwrapper">
			<div class="tableoptions">
		 		<form action="<?php echo U('Wechat/Admin/userlist');?>" method="get">
				OpenId:
				<input type="text" name="wx_id" id="wx_id" value='<?php echo ($prm_wx_id); ?>' autocomplete="off" class="smallinput20"/>
				绑定用户:
				<input type="text" name="uname" id="uname" value='<?php echo ($prm_uname); ?>' autocomplete="off" class="smallinput20"/>
				<button class="radius3">查询</button>
				</form>
			</div><!--tableoptions-->
			<form class="stdform formvalidate" action="<?php echo U('Wechat/Admin/optuser');?>" method="post">
				<table cellpadding="0" cellspacing="0" border="0" id="table2" class="stdtable stdtablecb">
					<thead>
						<tr>
							<th class="head0">
								<input type='checkbox' name='checkboxall' id='checkboxall' value='0'/>
							</th>
							<th class="head0">公众号编号</th>
							<th class="head1">OpenId</th>
							<th class="head1">微信昵称</th>
							<th class="head0">绑定用户</th>
							<th class="head0">是否关注</th>
							<th class="head1">关注时间</th>
							<th class="head1">更新时间</th>
							<th class="head1">添加时间</th>
							<th class="head0">操作</th>
						</tr>
					</thead>
					<tbody id='tablelist'>
					<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
							<td>
								<input type='checkbox' name='chkb<?php echo ($vo["id"]); ?>' id='chkb<?php echo ($vo["id"]); ?>' value="<?php echo ($vo["id"]); ?>"/>
							</td>
							<td><?php echo ($vo["wechat_id"]); ?></td>
							<td><?php echo ($vo["wxid"]); ?></td>
							<td><?php echo ($vo["nickname"]); ?></td>
							<td><?php echo ($vo["uname"]); ?></td>
							<td>
								<?php if($vo[subscribe] == 1): ?><img  src="__PUBLIC__/Admin/images/icons/icon_1.png" alt="已关注" title="已关注">
								<?php else: ?>
								   <img  src="__PUBLIC__/Admin/images/icons/icon_0.png" alt="未关注" title="未关注"><?php endif; ?>
							</td>
							<td><?php echo (date("Y-m-d H:i:s",$vo["subscribe_time"])); ?></td>
							<td><?php echo (date("Y-m-d H:i:s",$vo["upd_time"])); ?></td>
							<td><?php echo (date("Y-m-d H:i:s",$vo["add_time"])); ?></td>
							<td class="center">
				 				<a href="<?php echo U('Wechat/Admin/useredit',array('id'=>$vo['id']));?>" class="editbutton">编辑</a>
				 				<a href="<?php echo U('Wechat/Admin/userdel',array('id'=>$vo['id']));?>" class="confirmbutton">删除</a>
							</td>
						</tr><?php endforeach; endif; else: echo "" ;endif; ?>
					<?php if(empty($data)): ?><tr>
						<td colspan="10">暂无数据</td>
					</tr><?php endif; ?>
				</tbody>
				</table>
				<div class="dataTables_paginate paging_full_numbers" id="dyntable_paginate" style="height:30px;">
					<div  style='float:left;border:0px solid #F78A29;padding:3px 3px 3px 3px;cursor:pointer;' >
						<input type='hidden' name='opttype' id='opttype' value=''/>
						<input type="submit" class="submit radius2 submitdel" onclick="setOptType('del')" value="删除"  />
						<input type="submit" class="submit radius2 submitdel" onclick="setOptType('tb')" value="同步粉丝信息"  />
					</div>
				<div class="paginationnew"><?php echo ($page); ?></div>
			 </div>
			 </form>
		</div><!--contentwrapper-->
	</div><!--centercontent-->
</div><!--bodywrapper-->
<script type="text/javascript">
function setOptType(type){
	$("#opttype").val(type);
}
</script>
</body>
</html>