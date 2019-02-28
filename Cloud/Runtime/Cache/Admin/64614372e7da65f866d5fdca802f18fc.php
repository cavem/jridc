<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>控制台首页</title>
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
 <div class="centercontent">
        <div class="pageheader">
            <h1 class="pagetitle">控制台</h1>
            <ul class="hornav">
                <li class="current"><a href="#activities">欢迎首页</a></li>
                <li><a href="#updates">软件信息</a></li>
            </ul>
        </div><!--pageheader-->
        <div id="contentwrapper" class="contentwrapper">
        	<div id="activities" class="subcontent">
        	         <div class="notibar announcementuser">
                        <h3><?php echo (session('admin_name')); ?></h3>
                        <p>您等当前登录时间为：<?php echo ($last_time); ?>&nbsp;&nbsp;登录IP为:<?php echo ($last_ip); ?></p>
                    </div><!-- notification announcement -->
        	             <table cellpadding="0" cellspacing="0" border="0" class="stdtable stdtablecb overviewtable2">
                            <colgroup>
                                <col class="con1" width="25%" />
                                <col class="con0" width="25%" />
                                <col class="con1" width="25%"/>
                                <col class="con0" width="25%"/>
                                <col class="con1" width="25%"/>
                            </colgroup>
                            <tbody>
                                <tr class="first-tr">
                                    <td>总注册用户数量</td>
                                    <td><?php echo ($alluser); ?></td>
                                    <td>今天新用户注册</td>
                                    <td><?php echo ($tduser); ?></td>
                                </tr>
                                <tr>
                                    <td>今日充值入账</td>
                                    <td><?php echo ($tdolRecharge); ?></td>
                                    <td>今日后台入账</td>
                                    <td><?php echo ($tdbsRecharge); ?></td>
                                </tr>
                                <tr>
                                    <td>今日开通云主机</td>
                                    <td><?php echo ($tdcloud); ?></td>
                                    <td>今日到期云主机</td>
                                    <td><?php echo ($tdendcloud); ?></td>
                                </tr>
                                <tr>
                                    <td>今天工单数量</td>
                                    <td><?php echo ($tdsupport); ?></td>
                                    <td>未处理工单数量</td>
                                    <td><?php echo ($udsupport); ?></td>
                                </tr>
                            </tbody>
                        </table>
            </div><!-- #updates -->
            <div id="updates" class="subcontent" style="display: none;">
                       <table cellpadding="0" cellspacing="0" border="0" class="stdtable stdtablecb overviewtable2">
                            <colgroup>
                                <col class="con1" />
                                <col class="con0" />
                                <col class="con1" />
                                <col class="con0" />
                            </colgroup>
                            <tbody>
                                <tr class="first-tr">
                                    <td>版本</td>
                                    <td><?php echo C('VERSION'); ?>
                                    <a href='javascript:void(0);' onclick='updateweb()' style='color:red;'>检查更新</a>
                                    <div id="updatelog"></div>
                                    </td>
                                    <td>操作系统</td>
                                    <td><?php echo PHP_OS; ?></td>
                                </tr>
                                <tr>
                                   <td>PHP版本</td>
                                    <td><?php echo PHP_VERSION; ?></td>
                                    <td>服务器地址</td>
                                    <td><?php echo $_SERVER['SERVER_ADDR'].":".$_SERVER['SERVER_PORT']; ?></td>
                                </tr>
                                <tr>
                                    <td>ThinkPHP版本</td>
                                    <td><?php echo (THINK_VERSION); ?></td>
                                    <td>jQuery Version</td>
                                    <td><script type="text/javascript">document.write(jQuery.fn.jquery)</script></td>
                                </tr>
                                  <tr>
                                   <td>GD 库</td>
                                    <td>  <?php if(function_exists("imageline")==1){ $gd = gd_info(); echo $gd['GD Version']; } else { echo '<font color=red><b>×</b></font>'; } ?></td>
                                    <td>MySQL 版本</td>
                                    <td><?php echo mysql_get_server_info(); ?></td>
                                </tr>
                                       <tr>
                                    <td>服务器时间</td>
                                    <td><?php echo date("Y-m-d H:i:s",time()); ?></td>
                                    <td>WEB服务器</td>
                                    <td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
                                </tr>
                                  <tr>
                                    <td>服务器语言</td>
                                    <td><?php echo getenv("HTTP_ACCEPT_LANGUAGE"); ?></td>
                                    <td>POST提交限制</td>
                                    <td><?php echo get_cfg_var("post_max_size") ; ?></td>
                                </tr>
                                  <tr>
                                    <td>图像处理支持</td>
                                    <td>
                     <?php if(function_exists("imageline")==1){ echo '<font color=green><b>√</b></font>'; } else { echo '<font color=red><b>×</b></font>'; } ?>
                </td>
                                    <td>Session支持:</td>
                                    <td>
                   <?php if(function_exists("session_start")==1){ echo '<font color=green><b>√</b></font>'; } else { echo '<font color=red><b>×</b></font>'; } ?>
                </td>
                                </tr>
                                <tr>
                                    <td>脚本运行内存</td>
                                    <td><?php echo get_cfg_var("memory_limit")?get_cfg_var("memory_limit"):"无" ; ?></td>
                                    <td>上传大小限制</td>
                                    <td><?php echo get_cfg_var("upload_max_filesize")?get_cfg_var("upload_max_filesize"):"不允许上传文件" ; ?></td>
                                </tr>
                                  <tr>
                                    <td>被屏蔽的函数</td>
                                    <td colspan="3"><?php echo get_cfg_var("disable_functions")?get_cfg_var("disable_functions"):"无" ; ?></td>
                              
                                </tr>
                            </tbody>
                        </table>
            </div><!-- #activities -->
        </div><!--contentwrapper-->
        <br clear="all" />
	</div><!-- centercontent -->
</div><!--bodywrapper-->
<script>
function updateweb(){
	layer.confirm('确定检测更新文件?',function(index){
		$(this).ajaxSubmit({	
			type:"post",  //提交方式  	
			dataType:  'json',		
			url:'<?php echo U("Admin/Main/update");?>',
			data:{action:1},
			beforeSend: function() {
				var loadi = layer.load('系统检测中,请勿刷新页面');//提示框
			},
			success: function(data) {
				if(data.status == '1'){
					$("#updatelog").html(data.info+"<a href='javascript:void(0);' onclick='updatewebnew()' style='color:red;'>一键更新</a>");
					layer.msg('检测到更新文件',2,1);
				}else{
					layer.msg(data.info,2,1);
				}
			}
		});
	});
	return false;
}
function updatewebnew(){
	layer.confirm('确定更新当前系统?',function(index){
		$(this).ajaxSubmit({	
			type:"post",  //提交方式  	
			dataType:  'json',		
			url:'<?php echo U("Admin/Main/update");?>',
			data:{action:2},
			beforeSend: function() {
				var loadi = layer.load('系统更新 中,请勿刷新页面');//提示框
			},
			success: function(data) {
				if(data.status == '1'){
					layer.msg(data.info,2,1);
					setTimeout(function(){
						location.reload();
					},3000);
				}else{
					layer.msg(data.info,2,1);
				}
			}
		});
	});
	return false;
}
</script>
</body>
</html>