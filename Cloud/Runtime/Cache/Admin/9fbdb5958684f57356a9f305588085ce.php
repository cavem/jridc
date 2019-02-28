<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>编辑客服</title>
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
			<h1 class="pagetitle20">编辑客服</h1>
			<span class="pagedesc">客服编辑</span>
		</div><!--pageheader-->
		<div id="contentwrapper" class="contentwrapper">
			<div id="basicform" class="subcontent">
			   	<form class="stdform formvalidate" action="<?php echo U('Admin/Kefu/edit');?>" method="post">
					<p><label>所在组</label>
					<span class="field">
						<select name='rank_id' class="selectwidth40-1">
							<?php if(is_array($ranks)): $i = 0; $__LIST__ = $ranks;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value='<?php echo ($vo["rank_id"]); ?>'
								<?php if($data[rank_id] == $vo[rank_id]): ?>selected<?php endif; ?>
								><?php echo ($vo["rank_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
						</select>
					</span></p>
					<p>
						<label>客服名称</label>
						<span class="field">
							<input type="text" name="kefuname" id="kefuname" value='<?php echo ($data["kefuname"]); ?>' class="smallinput20" validate='{required:true,messages:{required:"不能为空"}}' />
						</span>
					</p>
					<p>
						<label>登录帐号</label>
						<span class="field">
							<input type="text" name="kefuloginname" id="kefuloginname" value='<?php echo ($data["kefuloginname"]); ?>' class="smallinput20" validate='{required:true,messages:{required:"不能为空"}}' />
						</span>
					</p>
					<p>
						<label>登录密码</label>
						<span class="field">
							<input type="text" name="kefuloginpass" id="kefuloginpass" value='<?php echo ($data["kefuloginpass"]); ?>' class="smallinput20" validate='{required:true,messages:{required:"不能为空"}}' />
						</span>
					</p>
					<p>
						<label>QQ</label>
						<span class="field">
							<input type="text" name="kefuqq" id="kefuqq" value='<?php echo ($data["kefuqq"]); ?>' class="smallinput20" />
						</span>
					</p>
					<p>
						<label>性别</label>
						<span class="field">
							<input type='radio' name='kefusex' value='1' 
							<?php if($data[kefusex] == 1): ?>checked<?php endif; ?>
							/>男
							<input type='radio' name='kefusex' value='0' 
							<?php if($data[kefusex] == 0): ?>checked<?php endif; ?>
							/>女
						</span>
					</p>
					<p>
						<label>电话</label>
						<span class="field">
							<input type="text" name="kefutel" id="kefutel" value='<?php echo ($data["kefutel"]); ?>' class="smallinput20"/>
						</span>
					</p>
					<p class="stdformbutton">
						<input type='hidden' name='id' value='<?php echo ($data["id"]); ?>' />
				   		<input type="submit" class="submit radius2 submitedit" value="编辑"  />
					</p>
				</form>
			</div>
		</div><!--contentwrapper-->
	</div><!--centercontent-->
</div><!--bodywrapper-->
</body>
</html>