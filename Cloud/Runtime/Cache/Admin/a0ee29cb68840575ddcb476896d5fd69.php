<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>财务管理</title>
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
<script type="text/javascript" src="__PUBLIC__/Admin/js/mydatepicker/WdatePicker.js"></script>
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
			<h1 class="pagetitle20">财务管理</h1>
			<span class="pagedesc">财务管理</span>
		</div><!--pageheader-->
		<div id="contentwrapper" class="contentwrapper">
				<div class="tableoptions">
                	<form action="<?php echo U('Admin/Money/index');?>" method="get">
                	用户名
					<input type="text" name="username" id="username" value='<?php echo ($prm_uname); ?>' placeholder="会员名" autocomplete="off" value="" class="smallinput20"/>
					
					会员组
					<select name="user_rank" id="user_rank" class="selectwidth20">
						<option value=''>请选择</option>
						<?php if(is_array($user_rank)): foreach($user_rank as $key=>$v): ?><option value="<?php echo ($v["rank_id"]); ?>" <?php if($v['rank_id'] == $userRank): ?>selected="selected"<?php endif; ?>><?php echo ($v["rank_name"]); ?></option><?php endforeach; endif; ?>
					</select>
					&nbsp;&nbsp;支付类型
					<select name="type" id="type" class="selectwidth20">
						<option value=''>请选择</option>
						<option value="1" <?php if($type == '1'): ?>selected="secleted"<?php endif; ?>>在线充值</option>
						<option value="2" <?php if($type == '2'): ?>selected="secleted"<?php endif; ?>>后台入款</option>
						<option value="3" <?php if($type == '3'): ?>selected="secleted"<?php endif; ?>>后台扣除</option>
						<option value="4" <?php if($type == '4'): ?>selected="secleted"<?php endif; ?>>开通扣除</option>
						<option value="4" <?php if($type == '5'): ?>selected="secleted"<?php endif; ?>>续费扣除</option>
						<option value="4" <?php if($type == '6'): ?>selected="secleted"<?php endif; ?>>升级配置</option>
						<option value="4" <?php if($type == '7'): ?>selected="secleted"<?php endif; ?>>降级配置</option>
						<option value="4" <?php if($type == '10'): ?>selected="secleted"<?php endif; ?>>充值卡</option>
					</select>
					&nbsp;&nbsp;产品类型
					<select name="whichProduct" id="whichProduct" class="selectwidth20">
						<option value=''>请选择</option>
						<option value="1" <?php if($whichProduct == '1'): ?>selected="secleted"<?php endif; ?>>cloud产品</option>
					</select>
					客服
                	<select name="kid" id="kid" class="selectwidth20">
                    	<option value="">--请选择--</option>
                      	 <?php if(is_array($kefu)): $i = 0; $__LIST__ = $kefu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$k): $mod = ($i % 2 );++$i;?><option value="<?php echo ($k["id"]); ?>" <?php if($k["id"] == $kid): ?>selected="selected"<?php endif; ?>><?php echo ($k["kefuname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
					<div style="width:100%;height:10px;"></div>	
					订单号
					<input type="text" name="orderid" id="orderid" value='<?php echo ($orderid); ?>' placeholder="订单号" autocomplete="off" value="" class="smallinput20"/>
					
					<input type="radio" name="isadd" id="isadd0"  value="0"  <?php if(empty($isadd)): ?>checked<?php endif; ?> />全部
					<input type="radio" name="isadd" id="isadd1"  value="1" <?php if($isadd == 1): ?>checked<?php endif; ?>/>进账
					<input type="radio" name="isadd" id="isadd2"  value="2" <?php if($isadd == 2): ?>checked<?php endif; ?> />出账			
					所属区域   
					<select name="acspace" id="acspace" class="selectwidth20">
						<option value=''>请选择</option>
						<option value="1" <?php if($acspace == '1'): ?>selected="secleted"<?php endif; ?>>用户区</option>
						<option value="2" <?php if($acspace == '2'): ?>selected="secleted"<?php endif; ?>>管理区</option>
					</select>					
					&nbsp;&nbsp;开始时间
					<input  class="time smallinput10" name="starttime" value="<?php echo ($starttime); ?>" onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})" type="text" />
					&nbsp;&nbsp;结束时间
					<input  class="time smallinput10" name="endtime"  value="<?php echo ($endtime); ?>" onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})" type="text" />					
					&nbsp;&nbsp;&nbsp;<button class="radius3">查询</button>
                     </form>
                </div><!--tableoptions-->
                <?php if($jinzhang > 0 || $zhichu > 0 ): ?><table cellpadding="0" cellspacing="0" border="0" id="table2" class="stdtable stdtablecb">
                	<thead>
                		<tr>
                			<th align="left" class="head1">总计</th>
                			<th class="head0">收支记录总数</th>
                			<th class="head1">进账</th>
                			<th class="head0">出帐</th>
                		</tr>
                	</thead>
                	<tbody>
                		<tr>
                			<td>详细</td>
                			<td><?php echo ($count); ?>笔</td>
                			<td><?php echo (($jinzhang)?($jinzhang):0); ?>元</td>
                			<td><?php echo (($zhichu)?($zhichu):0); ?>元</td>
                		</tr>
                	</tbody>
                	
                </table>
                <div style="height:10px;"></div><?php endif; ?>
				<table cellpadding="0" cellspacing="0" border="0" id="table2" class="stdtable stdtablecb">
					<thead>
						<tr>
							<th class="head1">编号</th>
							<th class="head0">用户</th>
							<th class="head0">进账</th>
							<th class="head1">出账</th>
							<th class="head0">余额</th>
							<th class="head1">交易时间</th>
							<th class="head0">交易内容</th>
							<th class="head1">操作</th>
						</tr>
					</thead>
					<tbody>
					<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
							<td><?php echo ($vo["id"]); ?></td>
							<td><a href="<?php echo U('Admin/User/edit',array('id'=>$vo['user_id']));?>"><?php echo ($vo["username"]); ?></a>&nbsp;&nbsp;(<?php echo ($vo["kefuname"]); ?>)</td>
							<td><?php if($vo[isadd] == 1): echo ($vo["usermoney"]); else: ?>0.0<?php endif; ?></td>
							<td><?php if($vo[isadd] == 2): echo ($vo["usermoney"]); else: ?>0.0<?php endif; ?></td>
							<td><?php echo ($vo["newusermoney"]); ?></td>
							<td><?php echo (date('Y-m-d H:i:s',$vo["addtime"])); ?></td>
							<td><?php echo ($vo["forwhat"]); ?></td>
							<td class="center">
				 				<a href="<?php echo U('Admin/Money/detail',array('id'=>$vo['id']));?>" class="edit">详情</a> &nbsp;
					 			<a href="<?php echo U('Admin/Money/del',array('id'=>$vo['id']));?>" class="confirmbutton">删除</a>
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