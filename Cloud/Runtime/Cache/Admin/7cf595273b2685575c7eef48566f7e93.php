<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>云主机管理</title>
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
			<h1 class="pagetitle20">云主机管理</h1>
			<span class="pagedesc">云主机管理</span>
		</div><!--pageheader-->
		<div id="contentwrapper" class="contentwrapper">
		   <div class="tableoptions">
                	<form action="<?php echo U('Admin/Cloud/manage');?>" method="get">
                	用户名
					<input type="text" name="username" id="username" value='' value="" class="smallinput20"/>
                   或云主机		<input type="text" name="cloudname" id="cloudname"  value="" class="smallinput20"/>
                    <button class="radius3">查询</button>
	                  	<a href="<?php echo U('Admin/Cloud/index',array('status'=>urlencode('正常')));?>">正常</a> 
	                  	<a href="<?php echo U('Admin/Cloud/index',array('status'=>urlencode('已删除')));?>">已删除</a> 
	                  	<a href="<?php echo U('Admin/Cloud/index',array('day'=>'31'));?>">一个月到期  </a> 
	                  	<a href="<?php echo U('Admin/Cloud/index',array('day'=>'15'));?>">15天到期</a> 
	                  	<a href="<?php echo U('Admin/Cloud/index',array('day'=>'7'));?>">7天到期</a>
	                  	<a href="<?php echo U('Admin/Cloud/incloud');?>">转入云主机</a> 
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
							<th class="head1">编号</th>
							<th class="head0">产品类型</th>
							<th class="head1">所属机房</th>
							<th class="head0">用户名</th>
							<th class="head1">云主机名</th>
							<th class="head1">IP</th>
							<th class="head0">开通日期</th>
							<th class="head1">到期日期</th>
							<th class="head0">状态</th>
							<th class="head1">操作</th>
						</tr>
					</thead>
					<tbody id='tablelist'>
					<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
							<td><?php echo ($vo["id"]); ?><input type="hidden" name="vmid[]" value='<?php echo ($vo["id"]); ?>' /></td>
							<td><?php echo ($vo["Cloudtype"]); ?></td>
							<td><?php echo ($vo["jfname"]); ?></td>
							<td><a href="<?php echo U('Admin/User/edit',array('id'=>$vo['user_id']));?>"><?php echo ($vo["username"]); ?></a>(<?php echo ($vo["kefuname"]); ?>)</td>
							<td>
								<?php echo ($vo["cloudname"]); ?>(VM_ID: <?php echo ($vo["vm_id"]); ?> )
							</td>
							<td id='vm_ip_<?php echo ($vo["id"]); ?>'>
							<img src='__PUBLIC__/Admin/images/loadings.gif' alt="loading" title="loading"/>
							</td>
							<td>
								<?php echo (date('Y-m-d H:i:s',$vo["starttime"])); ?>
							</td>
							<td>
								<?php echo (date('Y-m-d H:i:s',$vo["endtime"])); ?>
							</td>
							<td><?php echo ($vo["status"]); if($vo[istest] == 'y'): ?><br><font color="red">试用</font><?php endif; ?></td>
							<td class="center">
				 				<a href="<?php echo U('Admin/Cloud/manage',array('id'=>$vo['id']));?>">管理</a>
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
<script type="text/javascript">
function openjiance(){
	var url="<?php echo U('Admin/Cloud/cloudcheck');?>";
	window.open(url);	
}
var getvmstatusurl="<?php echo U('Admin/Cloud/vmstate');?>";
</script>
<script type="text/javascript">
var vmstates='{';
vmstates+="running:{'image':'/Public/Admin/images/running.png','title':'运行'},";
vmstates+="halted:{'image':'/Public/Admin/images/halted.png','title':'停止'},";
vmstates+="creating:{'image':'/Public/Admin/images/loadings.gif','title':'创建中'},";
vmstates+="error:{'image':'/Public/Admin/images/error.png','title':'创建失败'},";
vmstates+="unknow:{'image':'/Public/Admin/images/except.png','title':'异常'},";
vmstates+="suppending:{'image':'/Public/Admin/images/starting.png','title':'操作中'},";
vmstates+="nocloud:{'image':'/Public/Admin/images/except.png','title':'虚拟机未找到'},";
vmstates+="loading:{'image':'/Public/Admin/images/loadings.gif','title':'加载中'}";
vmstates+='}';
var vmstate=eval("(" + vmstates + ")");
function get_vm_status() {
    var vms = $("input[name='vmid[]']");
    if (vms.length == 0) {
        if (vm_interval) {
            clearInterval(vm_interval);
        }
        return;
    }
    for (var i=0; i<vms.length; i++) {
        vmid = vms[i].value;
        $.get(getvmstatusurl,{'act':'vmnetwork','id':vmid},function(network){
				if(network['statusinfo']=='正常'){
					if(network['cloudstatus']==true){
						$("#vm_ip_"+network['id']).html(network['value']);
						
					}else{
						$("#vm_ip_"+network['id']).html(network['info']);
					}
				}else if(vminfo['statusinfo']=='配置中'){
					$("#vm_ip_"+network['id']).html(network['info']);
					
				}else if(vminfo['statusinfo']=='开通失败'){
					$("#vm_ip_"+network['id']).html(network['info']);
					
				}else{
					$("#vm_ip_"+network['id']).html(network['info']);
				}
        },'json');
    }
}
</script>
<script type="text/javascript">
get_vm_status();
if (!vm_interval) {
   //var vm_interval = setInterval(get_vm_status,30000);
}
</script>
</body>
</html>