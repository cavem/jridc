<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>缓存设置</title>
<link rel="stylesheet" href="__PUBLIC__/Admin/css/style.default.css" type="text/css" />
<script type="text/javascript" src="__PUBLIC__/Admin/js/plugins/jquery-1.8.2.js"></script>
<script type="text/javascript" src="__PUBLIC__/Admin/js/plugins/jquery-ui.js"></script>
<script type="text/javascript" src="__PUBLIC__/Admin/js/plugins/jquery.cookie.js"></script>
<script type="text/javascript" src="__PUBLIC__/Admin/js/plugins/layer/layer.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Admin/js/plugins/jquery.form.js"></script>
<script>var ISRewrite=<?php echo ($ISRewrite); ?>;</script>
<script type="text/javascript" src="__PUBLIC__/Admin/js/custom/general.js"></script>
<!--[if IE 9]>
    <link rel="stylesheet" media="screen" href="__PUBLIC__/Admin/css/style.ie9.css"/>
<![endif]-->
<!--[if IE 8]>
    <link rel="stylesheet" media="screen" href="__PUBLIC__/Admin/css/style.ie8.css"/>
<![endif]-->
<!--[if lt IE 9]>
	<script src="__PUBLIC__/Admin/js/plugins/css3-mediaqueries.js"></script>
<![endif]-->
<link rel="stylesheet" href="__PUBLIC__/Admin/js/plugins/validate/validate.css" />
<script type="text/javascript" src="__PUBLIC__/Admin/js/plugins/validate/jquery.validate.1.9.js"></script>
<script type="text/javascript" src="__PUBLIC__/Admin/js/plugins/validate/jquery.metadata.js"></script>
<script type="text/javascript" src="__PUBLIC__/Admin/js/plugins/validate/messages_cn.js"></script>
<script type="text/javascript" src="__PUBLIC__/Admin/js/plugins/validate/global.js"></script>
</head>
<body class="withvernav">
<div class="bodywrapper">
 <form action="<?php echo U('User/Change/changeuser');?>" method="post" name="formTop" target="_blank">
	 <input type="hidden" name="username" id="username" />
 </form>
 <div class="topheader">
        <div class="left">
            <h1 class="logo">Cloud<span>Agent</span></h1>
            <span class="slogan">代理平台</span>
            <br clear="all" />
        </div><!--left-->
        <div class="right">
            <div class="userinfo">
            	<img src="<?php echo (session('u_photo')); ?>" width="28px" height="28px" alt="" />
                <span><?php echo (session('admin_name')); ?></span>
            </div><!--userinfo-->
            <div class="userinfodrop">
            	<div class="avatar">
                	<a href="">
                	<img src="<?php echo (session('u_photo')); ?>" width="95px" height="95px" alt="" />
                	</a>
                    <div class="changetheme">
                    	切换主题: <br />
                    	<a class="default"></a>
                        <a class="blueline"></a>
                        <a class="greenline"></a>
                        <a class="contrast"></a>
                    </div>
                </div><!--avatar-->
                <div class="userdata">
                	<h4><?php echo (session('admin_name')); ?></h4>
                    <span class="email"><?php echo (session('u_email')); ?></span>
                    <ul>
                        <li><a href="<?php echo U('Admin/System/adminpasswd');?>">修改密码</a></li>
                    	<li><a href="<?php echo U('Admin/Setting/index');?>">全局设置</a></li>
                        <li><a href="<?php echo U('Admin/Login/doLogout');?>">退出</a></li>
                    </ul>
                </div><!--userdata-->
            </div><!--userinfodrop-->
        </div><!--right-->
    </div><!--topheader-->
    <div class="header">
    	<ul class="headermenu">
    	<?php if(is_array($tops)): $i = 0; $__LIST__ = $tops;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nav): $mod = ($i % 2 );++$i;?><li nav="<?php echo ($nav["id"]); ?>" id="nav_<?php echo ($nav["id"]); ?>"  <?php if($nav[id] == $navid): ?>class="current"<?php endif; ?> >
    	<a href="javascript:;" onClick="openItem('<?php echo ($nav["id"]); ?>,<?php echo ($nav["module"]); ?>,<?php echo ($nav["action"]); ?>,<?php echo ($nav["rn_id"]); ?>');" >
    	<span class="icon <?php echo ($nav["cssstyle"]); ?>"></span><?php echo ($nav["name"]); ?></a>
    	</li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
       <div class="headerwidget">
        </div><!--headerwidget-->
    </div><!--header-->
<div class="vernav2 iconmenu">
    	<ul>
       		<?php if(is_array($menus)): $j = 0; $__LIST__ = $menus;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($j % 2 );++$j; if($menu["name"] == '控制台'): if(is_array($menu["nodes"])): $i = 0; $__LIST__ = $menu["nodes"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$me): $mod = ($i % 2 );++$i;?><li nc_type='<?php echo ($menu["name"]); ?>'>
						 <a href="javascript:void(0);" class="support"  onClick="openItem('<?php echo ($me["nav_id"]); ?>,<?php echo ($me["module"]); ?>,<?php echo ($me["action"]); ?>,<?php echo ($me["id"]); ?>');" name="item_<?php echo ($me["module"]); ?>_<?php echo ($me["action"]); ?>" id="item_<?php echo ($me["module"]); ?>_<?php echo ($me["action"]); ?>"><?php echo ($me["action_name"]); ?></a>
						</li>
						<li><a  href="<?php echo U('Admin/Login/doLogout');?>"" class="support">退出管理</a></li><?php endforeach; endif; else: echo "" ;endif; ?>
	              <?php else: ?>
	                <li <?php if(strtolower($menu['module']) == strtolower($modulename)): ?>class="current"<?php endif; ?> dataparam='<?php echo ($menu["name"]); ?>'>
	                	<a href="#form_<?php echo ($menu["name"]); ?>" class="support"><?php echo ($menu["name"]); ?></a>
            			<span class="arrow"></span> 
            			<ul id="form_<?php echo ($menu["name"]); ?>">
            			   <?php if(is_array($menu["nodes"])): $i = 0; $__LIST__ = $menu["nodes"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$me): $mod = ($i % 2 );++$i;?><li nc_type='<?php echo ($menu["name"]); ?>'>
                            <a href="javascript:void(0);"  onClick="openItem('<?php echo ($me["nav_id"]); ?>,<?php echo ($me["module"]); ?>,<?php echo ($me["action"]); ?>,<?php echo ($me["id"]); ?>');" name="item_<?php echo ($me["module"]); ?>_<?php echo ($me["action"]); ?>" id="item_<?php echo ($me["module"]); ?>_<?php echo ($me["action"]); ?>"><?php echo ($me["action_name"]); ?></a>
                            </li><?php endforeach; endif; else: echo "" ;endif; ?>
               		 </ul>
            			
            			   
	                </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
        </ul>
 <a class="togglemenu"></a>
        <br /><br />
</div><!--leftmenu-->
       <div class="centercontent tables">
        <div class="pageheader notab">
            <h1 class="pagetitle20">缓存设置</h1>
            <span class="pagedesc">1. 缓存设置
2. 缓存设置</span>
        </div><!--pageheader-->
        <div id="contentwrapper" class="contentwrapper">
	        <div id="basicform" class="subcontent">
	    <table cellpadding="0" cellspacing="0" border="0" id="table2" class="stdtable stdtablecb">
                <tr>
                    <td style="border:0px;" width="120" class="right">
                        <label>
                            <input type="checkbox" class="checkAll mr5" checked value="field" name="type" data-uri="<?php echo U('Admin/Setting/clear', array('type'=>'field'));?>" data-checklist="checkSon_x" data-direction="x"/>
                            数据库字段缓存
                        </label>
                    </td>
                    <td style="border:0px;" width="300" class="right">
                        <span class='gray'>修改过数据库结构之后更新</span>
                    </td>
                    <td style="border:0px;">
                        <span id="field_ifm"></span>
                    </td>
                </tr>
                <tr>
                    <td style="border:0px;" width="60" class="right">
                        <label>
                            <input value="tpl" name="type" type="checkbox" checked class="checkAll mr5" data-uri="<?php echo U('Admin/Setting/clear', array('type'=>'field'));?>" data-checklist="checkSon_x" data-direction="x"/>
                            模板编译缓存
                        </label>
                        
                    </td>
                    <td style="border:0px;" width="300" class="right">
                        <span class='gray'>修改过模板文件后更新</span>
                    </td>
                    <td style="border:0px;">
                        <span id="tpl_ifm"></span>
                    </td>
                </tr>
                <tr>
                    <td style="border:0px;" width="60" class="right">
                        <label>
                            <input value="data" name="type" type="checkbox" checked class="checkAll mr5" data-uri="<?php echo U('Admin/Setting/clear', array('type'=>'data'));?>" data-checklist="checkSon_x" data-direction="x"/>
                            站点数据缓存
                        </label>
                    </td>
                    <td style="border:0px;" width="300" class="right">
                        <span class='gray'>网站迁移、恢复、修改配置文件后网站数据异常时更新</span>
                        
                    </td>
                    <td style="border:0px;">
                        <span id="data_ifm"></span>
                    </td>
                </tr>
                <tr>
                    <td style="border:0px;" width="60" class="right">
                        <label>
                            <input value="runtime" name="type" type="checkbox"  checked class="checkAll mr5" data-uri="<?php echo U('Admin/Setting/clear', array('type'=>'runtime'));?>" id="checkboxall"  data-checklist="checkSon_x" data-direction="x"/>
                            网站编译缓存
                        </label>
                    </td>
                    <td style="border:0px;" width="300" class="right">
                        <span class='gray'>网站迁移、恢复、修改网站配置后更新</span>
                    </td>
                    <td style="border:0px;">
                        <span id="runtime_ifm"></span>
                    </td>
                </tr>
                <tr>
                    <td style="border:0px;" width="60" class="right">
                        <label>
                            <input value="logs" name="type" type="checkbox" checked class="checkAll mr5" id="checkboxall" data-uri="<?php echo U('Admin/Setting/clear', array('type'=>'logs'));?>"  data-checklist="checkSon_x" data-direction="x"/>
                            网站日志文件
                        </label>
                    </td>
                    <td style="border:0px;" width="300" class="right">
                        <span class='gray'>清理项目日志文件释放服务器空间</span>
                    </td>
                    <td style="border:0px;">
                        <span id="logs_ifm" ></span>
                    </td>
                </tr>
                <tr>
                    <td style="border:0px;" width="60" class="right">
                        <label>
                            <input value="Xenapi" name="type" type="checkbox" checked class="checkAll mr5" id="checkboxall" data-uri="<?php echo U('Admin/Setting/clear', array('type'=>'Xenapi'));?>"  data-checklist="checkSon_x" data-direction="x"/>
                          	接口缓存
                        </label>
                    </td>
                    <td style="border:0px;" width="300" class="right">
                        <span class='gray'>清理项目接口缓存</span>
                    </td>
                    <td style="border:0px;">
                        <span id="Xenapi_ifm" ></span>
                    </td>
                </tr>
                <tr>
                    <td style="border:0px;" align="left" height="60" colspan="3">
                        <form>
                      	 <input id="clearCache" class="btn radius2" type="submit" value="清理">
                        </form>
                    </td>
                </tr>
            </table>
	   </div>
        </div><!--contentwrapper-->
    </div><!--centercontent-->
</div><!--bodywrapper-->
<script type="text/javascript">
$(document).ready(function(){
    $('#clearCache').live('click', function(){
        $('input[name="type"]:checked').each(function(){
            var type = $(this).val();
            uri = $(this).attr('data-uri');
            $('#'+type+'_ifm').html("正在清理中...");
            $.getJSON(uri, {type:type}, function(result){
                $('#'+type+'_ifm').addClass('onCorrect').html('<img src="__PUBLIC__/Admin/images/icons/icon_1.png" />清理成功');
            });
        });
        return false;
    });
});
</script>
</body>
</html>