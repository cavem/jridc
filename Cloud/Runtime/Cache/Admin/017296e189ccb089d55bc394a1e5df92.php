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
        <div class="pageheader">
            <h1 class="pagetitle">站点设置</h1>
            <ul class="hornav">
                 <li class="current"><a href="#tabs-1">站点信息</a></li>
                 <li><a href="#tabs-2">验证码</a></li>
                 <li><a href="#tabs-3">邮箱</a></li>
                 <li><a href="#tabs-4">支付设置</a></li>
                 <li><a href="#tabs-5">注册登录</a></li>
                 <li><a href="#tabs-6">发票配置</a></li>
            </ul>
        </div><!--pageheader-->
        <div id="contentwrapper" class="contentwrapper">
        
       		<div id="tabs-1" class="subcontent">
        	                              <form class="stdform formvalidate formvalidateconfig" action="<?php echo U('Admin/Setting/setconfig');?>" method="post">
                        <p>
                        	<label><span style="color:red">*</span>网址</label>
                            <span class="field">
                            <input type="text" name="site_url" id="site_url" value="<?php echo ($webConf["site_url"]); ?>" class="smallinput" validate='{required:true,messages:{required:"名称不能为空"}}' />
                            </span>
                        </p>
                         <p>
                        	<label><span style="color:red">*</span>网站名称</label>
                            <span class="field">
                            <input type="text" name="site_name" id="site_name" value="<?php echo ($webConf["site_name"]); ?>" class="smallinput" validate='{required:true,messages:{required:"名称不能为空"}}' />
                            </span>
                        </p>
                         <p>
                        	<label><span style="color:red">*</span>网站标题</label>
                            <span class="field">
                            <input type="text" name="site_title" id="site_title" value="<?php echo ($webConf["site_title"]); ?>" class="smallinput" validate='{required:true,messages:{required:"名称不能为空"}}' />
                            </span>
                        </p> 
                         <p>
                        	<label><span style="color:red">*</span>网站关键词</label>
                            <span class="field">
                            <input type="text" name="site_keyword" id="site_keyword" value="<?php echo ($webConf["site_keyword"]); ?>" class="smallinput" validate='{required:true,messages:{required:"名称不能为空"}}' />
                            </span>
                        </p> 
                         <p>
                        <label><span style="color:red">*</span>网站内容</label>
                            <span class="field">
                        <textarea name="site_description" class="text-input textarea" rows="8" cols="50"><?php echo ($webConf["site_description"]); ?></textarea> 
                          
                            </span>
                        </p>
                        <p>
                        <label><span style="color:red">*</span>ICP备案号</label>
                            <span class="field">
                            <input type="text" name="site_icp" value="<?php echo ($webConf["site_icp"]); ?>" id="site_icp" class="smallinput" validate='{required:true,messages:{required:"名称不能为空"}}' />
                            </span>
                        </p>
                        <p>
                        <label><span style="color:red">*</span>统计代码</label>
                            <span class="field">
							<textarea name="site_code" class="text-input textarea" rows="8" cols="50"><?php echo ($webConf["site_code"]); ?></textarea> 
                            </span>
                        </p>   
                        <p>
                        <label><span style="color:red">*</span>网站状态</label>
                            <span class="field">
 <input type="radio" value="1" <?php if($webConf["site_status"] == '1'): ?>checked="checked"<?php endif; ?> name="site_status">开启
  <input type="radio"  value="0" <?php if($webConf["site_status"] == '0'): ?>checked="checked"<?php endif; ?> name="site_status">关闭
 
                           </span>
                        </p> 
                         <p>
                        <label><span style="color:red">*</span>关闭说明</label>
                            <span class="field">
							<textarea name="site_close" class="text-input textarea" rows="8" cols="50"><?php echo ($webConf["site_close"]); ?></textarea> 
                            </span>
                        </p>  
                         <p class="stdformbutton">
                        	<input type="submit" id="subconfig" class="radius2" value="设置"  />
                        </p>
                        </form>
            </div><!-- #updates -->
            <div id="tabs-2" class="subcontent" style="display: none;">
           <form class="stdform formvalidate formvalidatecode" action="<?php echo U('Admin/Setting/setcode');?>" method="post">
	                        <p>
                        	<label><span style="color:red">*</span>开启系统验证码</label>
                            <span class="field">
                             <input type="checkbox" <?php if($vercode["MREGISTER"] == '1'): ?>checked="checked"<?php endif; ?> value="1" name="MREGISTER" />用户注册
                            <input type="checkbox" <?php if($vercode["RELOGIN"] == '1'): ?>checked="checked"<?php endif; ?> value="1" name="RELOGIN" />前台登陆
                             <input type="checkbox" <?php if($vercode["BALOGIN"] == '1'): ?>checked="checked"<?php endif; ?> value="1" name="BALOGIN" />后台登陆
					       </span>
                     	   </p>
	                   <p>
                        <label><span style="color:red">*</span>验证码生成类型</label>
                            <span class="field">
							<input type="radio" checked="checked" <?php if($vercode["BUILDTYPE"] == '1'): ?>checked="checked"<?php endif; ?> value="1" name="BUILDTYPE">
							数字
							 <input type="radio" <?php if($vercode["BUILDTYPE"] == '2'): ?>checked="checked"<?php endif; ?> value="2" name="BUILDTYPE">字母
							  <input type="radio" <?php if($vercode["BUILDTYPE"] == '4'): ?>checked="checked"<?php endif; ?> value="4" name="BUILDTYPE">中文
							   <input type="radio" <?php if($vercode["BUILDTYPE"] == '5'): ?>checked="checked"<?php endif; ?> value="5" name="BUILDTYPE">数字+字母
                            </span>
                        </p>
                            <p>
                        <label><span style="color:red">*</span>选择验证码文件类型</label>
                            <span class="field">
<input type="radio" <?php if($vercode["EXPANDTYPE"] == 'png'): ?>checked="checked"<?php endif; ?> value="png" name="EXPANDTYPE">png
 <input type="radio" <?php if($vercode["EXPANDTYPE"] == 'gif'): ?>checked="checked"<?php endif; ?> value="gif" name="EXPANDTYPE">gif                            </span>
                       </span>  </p>
                        
                          <p>
                        <label><span style="color:red">*</span>前台验证码图片大小</label>
                            <span class="field">
 宽:<input type="text" name="REWIDTH" value="<?php echo ($vercode["RECODESIZE"]["width"]); ?>" class="smallinput20" size="10">
                        高:<input type="text" name="REHEIGHT" value="<?php echo ($vercode["RECODESIZE"]["height"]); ?>" class="smallinput20" size="10">                         </p>
                         </span>  <p>
                        <label><span style="color:red">*</span>后台验证码图片大小</label>
                            <span class="field">
  宽:<input type="text" name="BAWIDTH" value="<?php echo ($vercode["BACODESIZE"]["width"]); ?>" class="smallinput20" size="10">
                        高:<input type="text" name="BAHEIGHT" value="<?php echo ($vercode["BACODESIZE"]["height"]); ?>" class="smallinput20" size="10">  
                           </span>  </p>
                        
                 <p>
                 <label><span style="color:red">*</span>前台验证码字数</label>
                <span class="field">
				<input type="text" name="RECODENUMS" value="<?php echo ($vercode["RECODENUMS"]); ?>" class="smallinput20" size="10" validate='{ required:true,messages:{required:"前台验证码字数不能为空"}}'> 
				</span>
				 
                </p>
                           <p>
                        <label><span style="color:red">*</span>后台验证码字数</label>
                            <span class="field">
   <input type="text" name="BACODENUMS" value="<?php echo ($vercode["BACODENUMS"]); ?>" class="smallinput20" size="10" validate='{ required:true,messages:{required:"后台验证码字数不能为空"}}'>  
   
    </span>
      </p>
	                         <p class="stdformbutton">
                        		<input type="submit" id="subcode" class="radius2" value="设置"  />
	                        </p>
	                       
	                       </form>   
            </div>
             <div id="tabs-3" class="subcontent" style="display: none;">
            <form  class="stdform formvalidate formvalidateemail" action="<?php echo U('Admin/Setting/setemail');?>" method="post">
                <p>
                 <label><span style="color:red">*</span>发送Email方式</label>
                <span class="field">
				 <input type="radio" checked="checked" <?php if($mailconf["email_type"] == '1'): ?>checked="checked"<?php endif; ?> value="1" name="email_type">
                	第三方SMTP方式</span>
				 
                </p>
                 <p>
                 <label><span style="color:red">*</span>发送邮件的地址</label>
                <span class="field">
				 <input type="text" name="mail_address" value="<?php echo ($mailconf["mail_address"]); ?>" class="smallinput20" size="40">
				 </span>
                </p>
                 <p>
                 <label><span style="color:red">*</span>SMTP地址</label>
                <span class="field">
				<input type="text" name="smtp" value="<?php echo ($mailconf["smtp"]); ?>" class="smallinput20" size="40">
				 </span>
                </p>
                 <p>
                 <label><span style="color:red">*</span>用户名</label>
                <span class="field">
				 <input type="text" name="smtp_user" value="<?php echo ($mailconf["smtp_user"]); ?>" class="smallinput20" size="40" validate='{ required:true,messages:{required:"前台验证码字数不能为空"}}'>  
				  </span>
				 
                </p> 
                 <p>
                 <label><span style="color:red">*</span>密码</label>
	                <span class="field">
					 <input type="password" name="smtp_pwd" value="<?php echo ($mailconf["smtp_pwd"]); ?>" class="smallinput20" size="40" validate='{ required:true,messages:{required:"后台验证码字数不能为空"}}'>  
					</span>
                </p> 
                 <p>
                 <label><span style="color:red">*</span>端口号</label>
                <span class="field">
				 <input type="text" name="smtp_port" value="<?php echo ($mailconf["smtp_port"]); ?>" class="smallinput20" size="10" validate='{ required:true,messages:{required:"后台验证码字数不能为空"}}'> 
				</span>
                </p> 
                 <p>
                 <label><span style="color:red">*</span>邮件编码</label>
	              <span class="field">
<input type="radio" value="UTF8" <?php if($mailconf["smtp_code"] == 'UTF8'): ?>checked="checked"<?php endif; ?> name="smtp_code">UTF8
<input type="radio"  value="GB2312" <?php if($mailconf["smtp_code"] == 'GB2312'): ?>checked="checked"<?php endif; ?> name="smtp_code">GB2312
<input type="radio"  value="BIG5" <?php if($mailconf["smtp_code"] == 'BIG5'): ?>checked="checked"<?php endif; ?> name="smtp_code">BIG5
				</span>
                </p>  
                
 
                 <p>
                 <label><span style="color:red">*</span>测试邮件地址</label>
              	  <span class="field">
				  <input type="text" name="test_address" value="<?php echo ($mailconf["test_address"]); ?>" class="smallinput20" size="40" validate='{ required:true,messages:{required:"后台验证码字数不能为空"}}'>  
				  </span>
                  </p> 
                  <p class="stdformbutton">
                        		<input type="submit" id="subemail" class="radius2" value="设置"  />
                        		<input type="reset" id="subemailsend" class="reset radius2" value="测试邮件发送" onclick="testMail(this);">
	                         
	                        </p>
                            </form> 
            </div>
             <div id="tabs-4" class="subcontent" style="display: none;">
             <form class="stdform formvalidate formvalidatealipay" action="<?php echo U('Admin/Setting/setalipay');?>" method="post">
                <p>
                <label><span style="color:red">*</span>合作者ID</label>
                <span class="field">
				 <input type="text" name="partner" value="<?php echo ($Alipay["partner"]); ?>" class="smallinput20" size="40">
				 </span>
                </p>
                <p>
                <label><span style="color:red">*</span>交易安全校证码</label>
                <span class="field">
				 <input type="text" name="key" value="<?php echo ($Alipay["key"]); ?>" class="smallinput20" size="40">
				 </span>
                </p>
                  <p>
                <label><span style="color:red">*</span>支付宝账号</label>
                <span class="field">
				 <input type="text" name="selleremail" value="<?php echo ($Alipay["selleremail"]); ?>" class="smallinput20" size="40">
				 </span>
                </p>
                 <p class="stdformbutton">
                 <input type="submit" id="subalipay"  class="radius2" value="设置"  />
	             </p>
 			</form> 
            </div>
             <div id="tabs-5" class="subcontent" style="display: none;">
             <form class="stdform formvalidate formvalidateuser" action="<?php echo U('Admin/Setting/setuser');?>" method="post">
             	<p>
                <label><span style="color:red">*</span>注册验证方式</label>
                <span class="formwrapper">
<input type="radio" value="1" <?php if($regconf["regv"] == '1'): ?>checked="checked"<?php endif; ?>  name="regv">关闭
<input type="radio"  value="2" <?php if($regconf["regv"] == '2'): ?>checked="checked"<?php endif; ?> name="regv">邮件激活
<input type="radio"  value="3" <?php if($regconf["regv"] == '3'): ?>checked="checked"<?php endif; ?> name="regv">短信验证
                </span>
                </p>
                <p>
				<label><span style="color:red">*</span>是否开启邀请码</label>
                <span class="formwrapper">
<input type="radio"  value="0" <?php if($regconf["regcode"] == '0'): ?>checked="checked"<?php endif; ?> name="regcode">关闭
 <input type="radio" value="1" <?php if($regconf["regcode"] == '1'): ?>checked="checked"<?php endif; ?> name="regcode">开启
 				 </span>
                </p>
              	 <p class="stdformbutton">
                 <input type="submit" id="subregconf"  class="radius2" value="设置"  />
	             </p>
             </form> 
             </div>
     <div id="tabs-6" class="subcontent" style="display: none;">
             <form class="stdform formvalidate formvalidatefapiap" action="<?php echo U('Admin/Setting/setfapiao');?>" method="post">
             	<p>
                <label><span style="color:red">*</span>最小的开发票金额</label>
                  <span class="field">
					 <input type="text" name="MinFapiaomoney" value="<?php echo ($Fapiao["MinFapiaomoney"]); ?>" class="smallinput20" size="40">
				 </span>
                </p>
                <p>
                <label><span style="color:red">*</span>申请发票时自动从用户帐上扣除点数</label>
                  <span class="field">
					 <input type="text" name="FapiaoPaypercent" value="<?php echo ($Fapiao["FapiaoPaypercent"]); ?>" class="smallinput20" size="40">%
				 </span>
                </p>
                <p>
				<label><span style="color:red">*</span>快递加收费用</label>
                <span class="formwrapper">
					<input type="text" name="Fapiaokuai" value="<?php echo ($Fapiao["Fapiaokuai"]); ?>" class="smallinput20" size="40">
 				 </span>
                </p>
              	 <p class="stdformbutton">
                 <input type="submit" id="subfapiaoconf"  class="radius2" value="设置"  />
	             </p>
             </form> 
             </div>
            
            
        
        
        
   
        </div><!--contentwrapper-->
    </div><!--centercontent-->
</div><!--bodywrapper-->
<script type="text/javascript">
$(function () {
	$("#subfapiaoconf").click(function(){ 
		$(".formvalidatefapiap").ajaxSubmit({
			dataType:  'json',
			beforeSend: function() {
				var loadi = layer.load('数据提交中...');//提示框
			},
			beforeSubmit: function() {
			   return $(".formvalidate").valid();  //进行回调验证
			},
			success: function(data) {
				layer.closeAll();
				layer.msg("设置成功",2,1);
			},
			error:function(xhr){
				layer.alert(xhr, 8);
				location.reload();
			}
		});
		return false;
	});
	$("#subregconf").click(function(){ 
		$(".formvalidateuser").ajaxSubmit({
			dataType:  'json',
			beforeSend: function() {
				var loadi = layer.load('数据提交中...');//提示框
			},
			beforeSubmit: function() {
			   return $(".formvalidate").valid();  //进行回调验证
			},
			success: function(data) {
				layer.closeAll();
				layer.msg("设置成功",2,1);
			},
			error:function(xhr){
				layer.alert(xhr, 8);
				location.reload();
			}
		});
		return false;
	});
	$("#subconfig").click(function(){ 
		$(".formvalidateconfig").ajaxSubmit({
			dataType:  'json',
			beforeSend: function() {
				var loadi = layer.load('数据提交中...');//提示框
			},
			beforeSubmit: function() {
			   return $(".formvalidate").valid();  //进行回调验证
			},
			success: function(data) {
				layer.closeAll();
				layer.msg("设置成功",2,1);
			},
			error:function(xhr){
				layer.alert(xhr, 8);
				location.reload();
			}
		});
		return false;
	});
	$("#subcode").click(function(){ 
		$(".formvalidatecode").ajaxSubmit({
			dataType:  'json',
			beforeSend: function() {
				var loadi = layer.load('数据提交中...');//提示框
			},
			beforeSubmit: function() {
			   return $(".formvalidate").valid();  //进行回调验证
			},
			success: function(data) {
				layer.closeAll();
				layer.msg("设置成功",2,1);
			},
			error:function(xhr){
				layer.alert(xhr, 8);
				location.reload();
			}
		});
		return false;
	});
	$("#subemail").click(function(){ 
		$(".formvalidateemail").ajaxSubmit({
			dataType:  'json',
			beforeSend: function() {
				var loadi = layer.load('数据提交中...');//提示框
			},
			beforeSubmit: function() {
			   return $(".formvalidate").valid();  //进行回调验证
			},
			success: function(data) {
				layer.closeAll();
				layer.msg("设置成功",2,1);
			},
			error:function(xhr){
				layer.alert(xhr, 8);
				location.reload();
			}

			
		});
		return false;
	});
	$("#subemailsend").click(function(){
		    var loadi = layer.load('数据提交中...');//提示框 
		    var ajaxUrl = '<?php echo U("Admin/Ajax/sendmail");?>';
	        var data = $('input[name="test_address"]').val();
	       	var url=ajaxUrl;
	        $.get(ajaxUrl,{'test_address':data},function(msgObj){
	        	layer.closeAll();
	            if(msgObj.status == '1'){
	            	layer.msg(msgObj.info,2,1);
	            }else{
	            	layer.msg(msgObj.info,2,8);
	            }
	        },"JSON");
	});
	$("#subalipay").click(function(){ 
		$(".formvalidatealipay").ajaxSubmit({
			dataType:  'json',
			beforeSend: function() {
				var loadi = layer.load('数据提交中...');//提示框
			},
			beforeSubmit: function() {
			   return $(".formvalidate").valid();  //进行回调验证
			},
			success: function(data) {
				layer.closeAll();
				layer.msg("设置成功",2,1);
			},
			error:function(xhr){
				layer.alert(xhr, 8);
				location.reload();
			}
		});
		return false;
	});
	
	
});
</script>

</body>
</html>