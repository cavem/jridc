<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>菜单管理</title>
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
<div class="centercontent tables">
		<div class="pageheader notab">
			<h1 class="pagetitle20">文章列表</h1>
			<span class="pagedesc">后台文章列表管理</span>
		</div><!--pageheader-->
		<div id="contentwrapper" class="contentwrapper">
                   <div class="tableoptions">
                	<form action="<?php echo U('Admin/Article/index');?>" method="get">
                	文章分类
					<select name='cid' class="selectwidth20">
                    	<option value="">--请选择--</option>
						<?php if(is_array($cates)): $i = 0; $__LIST__ = $cates;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value='<?php echo ($vo["id"]); ?>'
							<?php if($vo[id] == $prm_cid): ?>selected<?php endif; ?>
							><?php echo ($vo["title"]); ?></option>
							<?php if(is_array($vo["childs"])): $i = 0; $__LIST__ = $vo["childs"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;?><option value='<?php echo ($voo["id"]); ?>'
								<?php if($voo[id] == $prm_cid): ?>selected<?php endif; ?>
								>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($voo["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
					</select>
                    <button class="radius3">查询</button>
                     </form>
                </div><!--tableoptions-->
				<table cellpadding="0" cellspacing="0" border="0" id="table2" class="stdtable stdtablecb">
					<colgroup>
						<col class="con1" />
						<col class="con0" />
						<col class="con1" />
						<col class="con0" />
						 <col class="con1" />
					</colgroup>
					<thead>
						<tr>
							<th class="head0">编号</th>
							<th class="head1">分类</th>
							<th class="head0">标题</th>
							<th class="head1">状态</th>
							<th class="head0">更新时间</th>
							<th class="head1">操作</th>
						</tr>
					</thead>
					<tbody>
					<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
							<td><?php echo ($vo["id"]); ?></td>
							<td class="center"><?php echo ($vo["cate"]); ?></td>
							<td>
								<a target="_blank" href="<?php echo U('Home/News/detail',array('id'=>$vo['id']));?>" ><?php echo ($vo["title"]); ?></a>
							</td>
							<td class="center">
								<?php if($vo[status] == 1): ?><img  src="__PUBLIC__/Admin/images/icons/icon_1.png" alt="启用" title="启用">
								<?php else: ?>
								   <img  src="__PUBLIC__/Admin/images/icons/icon_0.png" alt="启用" title="启用"><?php endif; ?>
							</td>
							<td class="center"><?php echo (date('Y-m-d H:i:s',$vo["update_time"])); ?></td>
							<td class="center">
								<a href="<?php echo U('Admin/Article/edit',array('id'=>$vo['id']));?>" class="edit">编辑</a> &nbsp;
					 			<a href="#del" >
					  			<span href="<?php echo U('Admin/Article/del',array('id'=>$vo['id']));?>" class="confirmbutton">删除</span>
					  			</a> &nbsp;
							</td>
						</tr><?php endforeach; endif; else: echo "" ;endif; ?>
					</tbody>
				</table>
             <div class="dataTables_paginate paging_full_numbers" id="dyntable_paginate">
                <div class="paginationnew"><?php echo ($page); ?></div>
             </div>
		</div><!--contentwrapper-->
	</div><!--centercontent-->
</div><!--bodywrapper-->
</body>
</html>