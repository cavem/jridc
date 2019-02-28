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
	<style >
	.tcontent { 
			width:420px;
			display:none;
			z-index:999;
			position:absolute;top:10px;left:300px;
			background:white;
		}
	#tcontentsub { width:30px;height:20px;border:1px solid #eee;text-align:center;position:absolute;bottom:10px;right:10px;cursor:pointer}

.tcys{height:auto; overflow:hidden; max-width:420px; background:#fff; border:#ccc solid 1px;}
.tcys h2{ margin-top:10px;font-weight:bold;padding:0 10px; height:30px; line-height:20px; color:#575757; font-size:14px; border-bottom:#fb9337 solid 1px; }
.gban{ float:right; }
.tcys dl{ width:100%; padding:15px 15px;}
.tcys dt,.tcys dd{ float:left; padding-bottom:25px; }
.tcys dd{ width:20%; text-align:right; font-size:16px; color:#666;}
.tcys dt{ width:75%; }
.tcys dt input.tcadd{float:right; border:none; width:80px; line-height:30px; padding:0px; height:30px; background:#fb9337;border-radius: 4px; color:#FFF; font-size:14px; font-weight:bold; text-align:center; cursor: pointer;}
	
	</style>
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
			<h1 class="pagetitle20">发送模版消息</h1>
			<span class="pagedesc">发送模版消息</span>
		</div><!--pageheader-->
		<div id="contentwrapper" class="contentwrapper">
			<span><b>选择模版：</b></span>
			<div class="tableoptions">
		 		<form action="<?php echo U('Wechat/Admin/templatesend');?>" method="get">
				模版名称:
				<input type="text" name="prm_name" id="prm_name" value='<?php echo ($prm_name); ?>' autocomplete="off" class="smallinput20"/>
				模版ID:
				<input type="text" name="prm_t_id" id="prm_t_id" value='<?php echo ($prm_t_id); ?>' autocomplete="off" class="smallinput20"/>
				<button class="radius3">查询</button>
				</form>
			</div><!--tableoptions-->
			<table cellpadding="0" cellspacing="0" border="0" id="table2" class="stdtable stdtablecb">
				<thead>
					<tr>
						<th class="head0">请选择</th>
						<th class="head1">模版名称</th>
						<th class="head0">模板ID</th>
						<th class="head1">排序</th>
					</tr>
				</thead>
				<tbody >
				<?php if(is_array($datat)): $i = 0; $__LIST__ = $datat;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr onclick="setContent(<?php echo ($vo['id']); ?>,'<?php echo ($vo['name']); ?>');">
						<td><input type='radio' name='tmsg_id' id="tmsg_id<?php echo ($vo['id']); ?>" value="<?php echo ($vo['id']); ?>"/></td>
						<td><?php echo ($vo["name"]); ?></td>
						<td><?php echo ($vo["t_id"]); ?></td>
						<td><?php echo ($vo["sort"]); ?></td>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>
				<?php if(empty($datat)): ?><tr>
						<td colspan="4">暂无数据</td>
					</tr><?php endif; ?>
				</tbody>
			</table>
		</div><!--contentwrapper-->

		<div id="contentwrapper" class="contentwrapper">
			<span><b>选择粉丝：</b></span>
			<select id='chosefans' onchange="chosefans();">
				<option value='1'>所有粉丝</option>
				<option value='2'>指定粉丝</option>
			</select>
			<input type='button' value="发送" onclick="dosendtmsg();"/>
			
			<div id='fans1' class="tableoptions" style='display:none'>
		 		<form action="<?php echo U('Wechat/Admin/templatesend');?>" method="get">
				OpenId:
				<input type="text" name="wx_id" id="wx_id" value='<?php echo ($prm_wx_id); ?>' autocomplete="off" class="smallinput20"/>
				绑定用户:
				<input type="text" name="uname" id="uname" value='<?php echo ($prm_uname); ?>' autocomplete="off" class="smallinput20"/>
				<button class="radius3">查询</button>
				</form>
			</div><!--tableoptions-->
			<form id='formtsend' class="stdform formvalidate" action="<?php echo U('Wechat/Admin/templatesend');?>" method="post">
			<div id='tcontent' class='tcontent' >
				<div id='tcontents'>Loading...</div>
				<input type="hidden" name='fanstype' id='fanstype'/>
				<input type="hidden" name='t_id' id='t_id'/>
			</div>
			<div id='fans2' style='display:none'>
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
					<?php if(is_array($datawu)): $i = 0; $__LIST__ = $datawu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
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
					<?php if(empty($datawu)): ?><tr>
						<td colspan="10">暂无数据</td>
					</tr><?php endif; ?>
				</tbody>
				</table>
			</div>
			</form>
		</div><!--contentwrapper-->
	</div><!--centercontent-->
</div><!--bodywrapper-->
<script type="text/javascript">
//模版保存内容
function setContent(id,title){
	$("#tmsg_id"+id).attr("checked","checked");//改行单选被选中
	$("#t_id").val($("#tmsg_id"+id).val());
	$("#tcontents").html("Loading...");
	ajaxgettcontent(id,title);
}
function ajaxgettcontent(id,title){
	$.ajax({
		url:'<?php echo U("Wechat/Ajax/gettcontent");?>',
		cache:false,
		dataType:"json",
		data:{'id':id},
		type:"GET",
		success:function(data){
			/**
			$("#product_id option").each(function(){
				$(this).remove();
			});	**/
			if(data){
				retstr = "<div class='tcys'>";
				retstr += "<h2><span style='float:left;'>"+title+"</span><a href='javascript:void(0);' onclick='settcontent();' class='gban'><img src='__PUBLIC__/Wechat/Admin/images/GB.png' alt='关闭'/></a></h2>";
				retstr += "<dl class='tclb'>";
				$.each(data, function(i,val){
					retstr += "<dd>"+val+"：</dd>";
					retstr += "<dt><input type='text' name='"+val+"'></dt>"; 
				});
				retstr += "<dd>&nbsp;</dd><dt><input name='' type='button' class='tcadd' onclick='settcontent();' value='确定' /></dt></dl></div>";
			}else{
				retstr = "暂无数据"; 
			}
			$("#tcontents").html(retstr);
		}
	});
	$("#tcontent").show();
}
function settcontent(){
	$("#tcontent").hide();
}
//选择粉丝 全部、指定
function chosefans(){
	var fanstype = $("#chosefans").val();
	if(fanstype==1){
		$("#fans1").hide();
		$("#fans2").hide();
	}else{
		$("#fans1").show();
		$("#fans2").show();
	}
}
function dosendtmsg(){
	$("#fanstype").val($("#chosefans").val());//选取的粉丝类型 全部、指定
	if(!checkform())return false;
	layer.confirm('确认发送当前配置模版消息？',function(index){
/**		$("#formtsend").submit();**/
		$("#formtsend").ajaxSubmit({
			dataType:  'json',
			beforeSend: function() {
				var loadi = layer.load('数据提交中...');//提示框
			},
			beforeSubmit: function() {

			},
			success: function(data) {
				layer.closeAll();
				if(data.status == '1'){
					layer.msg(data.info,2,1);
				}else{
					layer.msg(data.info,2,8);
				}
				settimeout(location.reload(),3000);
			},
			error:function(xhr){
				layer.msg(xhr,2,8);
				location.reload();
			}
		});
	});
}
function checkform(){
	//检查模版消息模版
	if(!$("#t_id").val()){
		layer.msg("请选择并配置发送模版",2,8);
		return false;
	}
	return true;
/**	//检查发放粉丝类型 
	if(!$("#fanstype").val()){
		layer.msg("请选择需要发送模版消息的粉丝",2,8);
		return false;
	};**/
}
</script>
</body>
</html>