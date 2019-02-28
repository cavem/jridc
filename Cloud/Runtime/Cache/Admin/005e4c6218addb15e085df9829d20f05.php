<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>添加产品配置</title>
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
<div class="bodywrapper" >
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
            <h1 class="pagetitle20">添加产品配置</h1>
            <span class="pagedesc">产品配置</span>
        </div><!--pageheader-->
        <div id="contentwrapper" class="contentwrapper">
	        <div id="basicform" class="subcontent">
<form class="stdform formvalidate" action="<?php echo U('Admin/Cloudconfig/productadd');?>" method="post">
    <p>
    	<label>产 品 名</label>
       	<span class="field">
        <input type="text" name="Cloudtype" class="smallinput20" validate='{required:true,messages:{required:"不能为空"}}' />
        排队顺序
        <input type="text" name="sort" value="1" class="smallinput10"/>
        (数值大排前面) 
        </span>
    </p>
<p>
<label>选择产品<a onclick="openctype()" style="color:red">查看详情</a></label>
<span class="field">
<select name="pid" id="pid"  class="selectwidth40-1" validate='{required:true,messages:{required:"请选择"}}' >
<option value="" >--请选择--</option>
<?php if($prodcut['status'] == 'success'): if(is_array($prodcut["value"])): $i = 0; $__LIST__ = $prodcut["value"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" ><?php echo ($vo["cloudtype"]); ?></option><?php endforeach; endif; else: echo "" ;endif; endif; ?>
</select>
</span>
</p>
<p>
<label>线路类型</label>
 <span class="formwrapper">
<input type="radio" name="iptype" id="iptype0"  value="1" checked/>BGP(1)&nbsp;
<input type="radio" name="iptype" id="iptype1" value="2" />香港(2)
&nbsp;
<input type="radio" name="iptype" id="iptype1" value="3" />双线(3)
&nbsp;
<input type="radio" name="iptype" id="iptype1" value="4" />电信(4)
&nbsp;
<input type="radio" name="iptype" id="iptype1" value="5" />联通(5)
&nbsp;
<input type="radio" name="iptype" id="iptype1" value="6" />国外(6)
&nbsp;
<input type="radio" name="iptype" id="iptype1" value="7" />移动(7)
&nbsp;
<input type="radio" name="iptype" id="iptype1" value="8" />高防(8)
               </span>
</p>
<p>
<label>价    格</label>
<span class="field">
<input type="text" name="usermoney"  value="0" class="smallinput10" />元一年 
</span>
</p>
<p>
<label>CPU单价</label>
<span class="field">
<input type="text" name="moneycpu" class="smallinput" validate='{required:true,messages:{required:"不能为空"}}' />
元/核 (阶梯定价格式为 16<=800|8<=400|2<=200 16,8,2为对应的核数 <=后为价格)
</span>
</p>
<p>
<label>内存单价</label>
<span class="field">
<input type="text" name="moneymemory" class="smallinput" validate='{required:true,messages:{required:"不能为空"}}' />
元/1GB  (阶梯定价格式为 32<=800|16<=400|4<=200 32,16,4为对应的内存大小 <=后为价格)
</span>
</p>
<p>
<label>IP单价</label>
<span class="field">
<input type="text" name="moneyip"  value="0" class="smallinput10" />元/个
数据盘单价：<input type="text" name="moneydisk"  value="0" class="smallinput10"  />元/1GB
</span>
</p>
<p>
<label>带宽单价</label>
<span class="field">
<input type="text" name="moneyqos" class="smallinput" validate='{required:true,messages:{required:"不能为空"}}' />
元/1MB(阶梯定价格式为 200<=200|10<=100|5<=60 5,10,200为对应的带宽 <=后为价格)
</span>
</p>
<p>
<label>月 付 费 率</label>
<span class="field">
<input type="text" name="PAY_Month" value="0.1" class="smallinput10" validate='{required:true,messages:{required:"不能为空"}}' />
(如果一个产品是120元/年，每月支付的价格就是120X费率，默认费率是0.084=1/12)
</span>
</p>
<p>
<label>季 付 费 率</label>
<span class="field">
<input type="text" name="PAY_Season" value="0.28"  class="smallinput10" validate='{required:true,messages:{required:"不能为空"}}' />
(如果一个产品是120元/年，每季支付的价格就是120X费率，默认费率是0.25=1/4)
</span>
</p>
<p>
<label>半年付费 率</label>
<span class="field">
<input type="text" name="PAY_halfyear"  value="0.55" class="smallinput10" validate='{required:true,messages:{required:"不能为空"}}' />
(如果一个产品是120元/年，每季支付的价格就是120X费率，默认费率是0.5=1/2) 
</span>
</p>
<p>
<label>一次购买两年费率</label>
<span class="field">
<input type="text" name="PAY_2year" value="2" class="smallinput10" validate='{required:true,messages:{required:"不能为空"}}' />
三年费率
<input type="text" name="PAY_3year" value="3" class="smallinput10" validate='{required:true,messages:{required:"不能为空"}}' />
四年费率
<input type="text" name="PAY_4year" value="4" class="smallinput10" validate='{required:true,messages:{required:"不能为空"}}' />
五年费率
<input type="text" name="PAY_5year" value="5" class="smallinput10" validate='{required:true,messages:{required:"不能为空"}}' />
</span>
</p>
<p>
<label>续费一年费率</label>
<span class="field">
<input type="text" name="PAY_Nextyear" value="1" class="smallinput10" validate='{required:true,messages:{required:"不能为空"}}' />
(续费二,三,四,五年的费率和一次性购买二,三,四,五年是一样的)
</span>
</p>
<p>
<label>是否允许月付</label>
<span class="formwrapper">
<input name=canmonth type="checkbox"  value="1"  checked>
 是否允许季付  <input name="canseason" type="checkbox" id="canmonth" value="1"  checked>
 是否允许年付  <input name="canhalfyear" type="checkbox" id="canmonth" value="1"  checked>
 是否允许半年  <input name="canhalfyear" type="checkbox" id="canmonth" value="1" checked>


</span>
</p>
<p>
<label>机房名称</label>
<span class="field">
<input type="text" name="jfname" value=""  class="smallinput10" validate='{required:true,messages:{required:"不能为空"}}' />
</span>
</p>
<p>
<label>简要说明</label>
 <span class="field">
<textarea name="info" class="text-input textarea" rows="8" cols="50"></textarea> 
 </span>
</p>
<p>
<label>详细说明</label>
<span class="field">
<textarea name="infomore" class="text-input textarea" rows="8" cols="50"></textarea> 
</span>
</p>
 <p>
                         <label>启用</label>
                              <span class="formwrapper">
							<input type="radio" name="status" checked="checked" value="1"/> 是 &nbsp; &nbsp;
                            <input type="radio" name="status"  value="0"/> 否 &nbsp; &nbsp;                  
                             </span>
                        </p>
<p class="stdformbutton">
	<input type="submit" class="submit radius2 submitadd" value="添加"  />
</p>
</form>
                    </div>
        </div><!--contentwrapper-->
    </div><!--centercontent-->
</div><!--bodywrapper-->
<script type="text/javascript">
function openctype(){
	$pid=$("#pid").val();
	var url="<?php echo U('Admin/Cloudconfig/productinfo');?>"+"?pid="+$pid;
	window.open(url);
}
</script>

</body>
</html>