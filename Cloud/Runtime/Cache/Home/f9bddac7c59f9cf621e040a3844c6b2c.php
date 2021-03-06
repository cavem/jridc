<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="icon" href="favicon.ico" type="image/x-icon">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=1300">
<meta name="keywords" content="<?php echo ($data["keyword"]); ?>">
<meta name="description" content="<?php echo ($data["description"]); ?>">
<title>弹性购买_<?php echo ($Web["Config"]["site_name"]); ?></title>
<link href="__PUBLIC__/Home/Default/css/index.css" rel="stylesheet" type="text/css">
<link href="__PUBLIC__/Home/Default/css/page.css" rel="stylesheet" type="text/css">
<link href="__PUBLIC__/Home/css/cloud.css" rel="stylesheet" type="text/css">
<script language="javaScript" src="__PUBLIC__/Home/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/layer/layer.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/jquery.form.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/ul/jquery-ui.js"></script>
<link href="__PUBLIC__/Home/js/ul/jquery-uinew.css" rel="stylesheet" type="text/css">
</head>
<style>
/* bootstrap */
.form-control:focus {
    border-color: #66afe9;
    outline: 0;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102,175,233,.6);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102,175,233,.6);
}
.form-control {
    display: block;
    width: 100%;
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}
/* style */
.documentwrapper{float: left;}
.sphinxsidebar{float:right;background: #f4f4f4;font-size: 12px;}
.sphinxsidebarfix{float:right;position: fixed;left: 50%;margin-left: 310px;top: 0;}
.yzj_info{border-bottom:initial;}
.jf_list{background: initial;border-left: initial;padding-left: 1px;border-right: initial;}
.js_list{overflow: hidden;margin-bottom: 20px;}
.js_list ul{margin-left:initial;}
.jf_list ul li{margin:initial;margin-left: -1px;}
.js_list ul li{margin-bottom:initial;margin-right: initial;}
.yzj_jfls{padding-left: 40px;width: initial;margin-bottom: 1px;padding-bottom: 1px;position: relative;border: 1px solid #edf1f2;margin-top: 15px;}
.ui-widget-content{background: #528bd2;}
.helpnt{background: initial;padding-left: 0;}
.yzj_noticenew{background: #fff;border:1px solid #ff9600;height: initial;}
.documentwrapper p{font-size: 12px;padding-left: 100px;}
/* extra */
.sphinxsidebar .inner {
	padding: 10px
}
.sphinxsidebar h3 {
	padding-bottom: 7px;
	line-height: 20px;
	border-bottom: 1px solid #e7e7e7;
	font-size: 14px;
	display: block;
}
.sphinxsidebar .info_line {
	border-top: 1px solid #e7e7e7
}
.sphinxsidebar .info li .tit {
	position: relative;
	float: left;
	padding-right: 10px;
	width: 70px;
	text-align: right;
	color: #999;
	line-height: 22px;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis
}
.sphinxsidebar .info li .tit:after {
	content: "\FF1A";
	position: absolute;
	right: 0
}
.sphinxsidebar .info li p {
	overflow: hidden;
	line-height: 22px
}
.sphinxsidebar .info li.price-line .tit,.sphinxsidebar .info li.price-line p {
	line-height: 24px
}
.sphinxsidebar .info li .tit110 {
	float: left;
	width: 110px;
	text-align: right;
	color: #666;
	line-height: 22px
}
.sphinxsidebar .info li .price {
	line-height: 24px
}
.sphinxsidebar .specialBtn{
	font-size: 14px;
	display: block;
	width: 100%;
	padding: 8px 0px;
	text-align: center;
	background: #19549C;
	color: #fff;
	margin-bottom: 10px;
}
.price{font-size: 24px;color: #ff9600}

.choice {
	height: 30px;
	padding-top: 1px;
	float: left
}

.choice.fr {
	float: right
}

.choice li {
	float: left;
	position: relative;
	margin-left: -1px;
	min-width: 46px;
	height: 28px;
	background: #fff;
	border: 1px solid #e7e7e7;
	cursor: pointer;
	text-align: center;
	line-height: 28px;
	font-size: 12px;
	letter-spacing: normal;
	word-spacing: normal;
	padding: 0 12px
}
.choice li input{position: absolute;width: 100%;height: 100%;top: 0;left: 0;opacity: 0;z-index: 9;cursor: pointer;}
.choice li.hover {
	z-index: 5;
	background: #528bd2;
	border-color: #528bd2;
	color: #fff
}
.xlable{float: left;padding: 7px 0 0 0;width:100px;text-align: right;}
.ctitle{position: absolute;left: 0;top: 0;width: 30px;height: 100%;background: #edf1f2;transition: .3s all;}
.leftspan{position: absolute;
    top: 50%;
    left: 50%;
    width: 1em;
    margin-top: -30px;
    margin-left: -6px;
    line-height: 1;
    color: #999;}
</style>
<script>
$(function(){
	var sphinxsidebartop=$('.sphinxsidebar').offset().top;
	$(window).scroll(function(){
		var scrtop=$(window).scrollTop();
		if(scrtop>sphinxsidebartop){
			if(!$('.sphinxsidebar').hasClass('sphinxsidebarfix')){
				$('.sphinxsidebar').addClass('sphinxsidebarfix');
			}
		}else{
			$('.sphinxsidebar').removeClass('sphinxsidebarfix');
		}
	})
	$('.yzj_jfls').hover(
		function(){
			$(this).find('.ctitle').css({'background':'#19549C'});
			$(this).find('.ctitle span').css({'color':'#fff'});
		},
		function(){
			$(this).find('.ctitle').css({'background':'','color':''});
			$(this).find('.ctitle span').css({'color':''});
		}
	)
})
</script>
<body>
	<div id="header">
  <div class="viewport-inner"><a class="header-logo" href="/" >CLOUD</a>
    <div class="header-account">
    <?php if($_SESSION['uid']): ?><a class="btn btn-outline btn-signin" href="<?php echo U('User/Center/index');?>">管理</a>
  	    <a class="btn btn-primary btn-signup" href="<?php echo U('User/Center/dologout');?>" >退出</a>
    <?php else: ?>
    <a class="btn btn-outline btn-signin" href="<?php echo U('Home/User/login');?>">登录</a>
    <a class="btn btn-primary btn-signup" href="<?php echo U('Home/User/register');?>" >注册</a><?php endif; ?>
    </div>
    <ul class="header-nav">
      <li class="nav-item home"><a href="/" >首页</a></li>
      <li class="nav-item customers"><a href="<?php echo U('Home/Cloud/buy');?>" >云产品</a>
      <div class="items cpmain">
      	<div class="viewport-inner">
        	<div class="cpnav">
                <ul>
                  <li><a href="<?php echo U('Home/Cloud/buy');?>" >弹性购买</a></li>
                   <?php $_result=M('page')->order('sort desc')->where("type_id=3")->limit("10")->select();if($_result): $i=0;foreach($_result as $key=>$page):++$i;$mod = ($i % 2 );?><li>
						<a href="<?php echo U('Home/Page/Show',array('id'=>$page[id]));?>"><?php echo ($page["title"]); ?></a></li><?php endforeach; endif;?>
                </ul>
            </div>
        </div>
       </div>
      </li>
      <li class="nav-item customers"><a href="<?php echo U('Home/Page/Show',array('id'=>5));?>" >解决方案</a>
        <div class="items cpmain">
          <div class="viewport-inner">
            <div class="cpnav">
              <ul>
              <?php $_result=M('page')->order('sort desc')->where("type_id=2")->limit("10")->select();if($_result): $i=0;foreach($_result as $key=>$page):++$i;$mod = ($i % 2 );?><li>
						<a href="<?php echo U('Home/Page/Show',array('id'=>$page[id]));?>"><?php echo ($page["title"]); ?></a></li><?php endforeach; endif;?>
              </ul>
            </div>
        </div>
       </div>
      </li>
      <li class="nav-item pricing"><a href="<?php echo U('Home/News/index/');?>" >新闻公告</a></li>
      <li class="nav-item console"><a href="<?php echo U('Home/Page/show/',array('id'=>12));?>">合作加盟</a></li>
      <li class="nav-item about"><a href="<?php echo U('Home/Page/show/',array('id'=>1));?>" >关于我们</a>
      <div class="items cpmain">
      	<div class="viewport-inner">
        	<div class="cpnav">
                <ul>              
                  <?php $_result=M('page')->order('sort desc')->where("type_id=1")->limit("10")->select();if($_result): $i=0;foreach($_result as $key=>$page):++$i;$mod = ($i % 2 );?><li>
						<a href="<?php echo U('Home/Page/Show',array('id'=>$page[id]));?>"><?php echo ($page["title"]); ?></a></li><?php endforeach; endif;?>
                </ul>
            </div>
        </div>
       </div>
      </li>
    </ul>
  </div>
</div>
	<div class="twonav">
	  <div class="viewport-inner"></div>
	</div>
	<div class="document">
		<!-- 侧边栏  -->
		<div class="sphinxsidebar">
			<div class="sphinxsidebarwrapper">
					<div class="inner">
						<h3>已选配置</h3> 
						<ul class="info">
							<li><span class="tit">CPU</span><p><span id="cpu_show_info">1</span><span>核</span></p></li> 
							<li><span class="tit">内存</span><p><span id="mem_show_info">512</span><span>MB</span></p></li> 
							<li><span class="tit">数据盘</span><p><span id="disk_show_info">10</span><span>G</span></p></li> 
							<li><span class="tit">带宽</span><p><span id="qos_show_info">1</span><span>Mbps</span></p></li> 
							<li><span class="tit">购买年限</span><p id="year_show_info">一个月</p></li> 
							<li><span class="tit">IP类型</span><p id="ip_show_info">独立IP</p></li> 
							<li><span class="tit">机房</span><p id="jf_show_info">北京BGP</p></li> 
							<li><span class="tit">操作系统</span><p id="os_show_info">无系统</p></li> 
							<li class="price-line"><span class="tit">费用</span><p><span class="price">￥</span><span class="price" id="cloudprice">0</span></p></li> 
						</ul> 
						<a href="javascript:;" class="specialBtn" id="subpost">立即购买</a> 
					</div>		
			</div>
			<div style="height: 20px;background: #fff;"></div>
			<div class="yzj_noticenew" id="cloudinfo" style="margin-left: 0;color: #ff9600;">
				声明：谢绝私服、博彩、色情、成人用品以及其他一切非法用途的申请,一经发现,立即关闭,概不退款。
			</div>
		</div>
	  <div class="documentwrapper" style="width:830px;">
		<div class="bodywrapper">
		  <div class="body">
		  <!-- start cloud -->
<table width="100%" border="0" cellspacing="1" cellpadding="0">
		  <tr>
		   <td>
<div class="rightcont">
	<div class="yzj_info">港湾云服务器基于规模资源池，以虚拟化方式为客户提供多种规格的计算、存储和网络等资源服务，用户可根据自己的需求弹性选择自己所属服务器的硬件配置，随意部署自己的硬件及系统，智能的控制面板可在线启动、停止、重置、重启、更换操作系统、定期智能备份、以及修改密码、监控资源使用情况等更多管理属性。</div>
  	<div class="yzj_jfls">
		<div class="ctitle">
			<span class="leftspan">线路和节点</span>
		</div>
		<div class="jf_list">
			<span class="xlable">线路：</span>
			<ul id="iptype" class="iptype choice">
				<?php if(is_array($iptype)): $i = 0; $__LIST__ = $iptype;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li <?php if($vo[ipdefalt]): ?>class="hover"<?php endif; ?> data="<?php echo ($vo["iptype"]); ?>"><?php echo ($vo["ipname"]); ?></li><?php endforeach; endif; else: echo "" ;endif; ?>
			</ul>
		</div>
		<div class="js_list" id="productlist">
		</div>
	</div>
	<div class="yzj_jfls">
		<div class="ctitle">
			<span class="leftspan">配置与存储</span>
		</div>
		<div class="jf_list">
			<span class="xlable">CPU：</span>
			<div id="cpu">
				<div id="cpu_slider" style="width:500px;;margin-left:10px;float:left;margin-top:6px;"></div>
				&nbsp;&nbsp;&nbsp;<input type="text" id="cpu_slider_info" name=cpu_slider_info value="1" style="width:50px;height:10px;" onKeyPress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" maxLength=4 size="4"/>核
			</div>
		</div>
		<div class="jf_list">
			<span class="xlable">内存：</span>
			<div id="mem">
				<div id="mem_slider" style="width:500px;margin-left:10px;float:left;margin-top:6px;"></div>
				&nbsp;&nbsp;&nbsp;<input type="text" id="mem_slider_info" name=mem_slider_info value="0.5" style="width:50px;height:10px;"  maxLength=4 size="4"/>G
			</div>
		</div>
		<p class="helpnt">为了保证良好的性能体验，512MB内存不建议使用windows操作系统</p>
		<div class="jf_list">
			<span class="xlable">系统盘：</span>
			<ul class="choice"><li class="hover">免费赠送<span id="syssize">0</span>G系统盘</li></ul>
		</div>
		<div class="jf_list">
			<span class="xlable">数据盘：</span>
			<div id="disk">
				<div id="disk_slider" style="width:500px;margin-left:10px;float:left;margin-top:6px;"></div>&nbsp;&nbsp;&nbsp;
				<input type="text" id="disk_slider_info" name="disk_slider_info" value="10" style="width:50px;height:10px;" onKeyPress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" maxLength=4 size="4"/>G
			</div>
		</div>
		<p class="helpnt">免费赠送<span id="ddisk">0</span>G，可根据需求增加容量，以10G为步长增加</p>
	</div>
	<div class="yzj_jfls">
		<div class="ctitle">
			<span class="leftspan">网络与安全</span>
		</div>
		<div class="jf_list">
			<span class="xlable">带宽：</span>
			<div id="qos">
				<div id="qos_slider" style="width:500px;margin-left:10px;float:left;margin-top:6px;"></div>&nbsp;&nbsp;&nbsp;
				<input type="text" id="qos_slider_info" name="qos_slider_info" value="1" onKeyPress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" maxLength=4 size="4" style="width:50px;height:10px;"/>M
			</div><p class="helpnt">以1Mbps为步长增加</p>
		</div>
		<div class="jf_list">
			<span class="xlable">IP类型：</span>
			<div id="dlip_p"></div>
		</div>
	</div>
	<div class="yzj_jfls">
		<div class="ctitle">
			<span class="leftspan">系统信息</span>
		</div>
		<div class="jf_list">
			<span class="xlable">操作系统：</span>
			<select name="image_type" id="image_type" class="form-control" style="width: 200px;display: inline-block;">
				<option value="0" selected>--请选择--</option>
				<option value="1" >--Windows-</option>
				<option value="2" >--Centos-</option>
				<option value="3" >--Ubuntu-</option>
			</select>	
			<select name="image_uuid" id="image_uuid" class="form-control" style="width: 200px;display: inline-block;">
				<option value='no'>-请选择操作系统-</option>
			</select>
		</div>
		<div class="jf_list">
			<span class="xlable">云主机名称：</span>
			<input type="text" name="Cloudnames" class="yzj_input" autocomplete="off"  id="Cloudnames" onblur="checkCloudname(this.value)" onfocus="IuCloudname()">
			<span class="helpnt" id="Cloudnameshow">4-14位字母或数字，必须以字母开头</span>
		</div>
		<div class="jf_list">
			<span class="xlable">初始密码：</span>
			<input type="password" name="passwords" class="yzj_input" autocomplete="off" id="passwords" onblur="checkpassword(this.value)" onfocus="Iupassword()">
			<span class="helpnt" id="passwordshow">此密码为系统初始登录使用</span>
		</div>
	</div>
	<div class="yzj_jfls">
		<div class="ctitle">
			<span class="leftspan">购买时长</span>
		</div>
		<div class="jf_list">
			<span class="xlable">购买时长：</span>
			<select name="years" id="years" class="form-control" style="width: 100px;"></select>
		</div>
	</div>
</div>
<div class="clearer"></div>
			
		</td>
	</tr>
</table>
		  </div>
		</div>
	  </div>
	  <div class="clearer"></div>
</div>
<div id="footer">
  <div class="footer">
    <div class="footer-navs">
      <div class="viewport-inner">
        <ul class="grid_20 footer-nav" style="width:100%;">
          <li class="grid_3">
            <h4><a>产品</a><span>products</span></h4>
            <ul class="items">
              <li><a href="#">云计算合作</a></li>
              <li><a href="#" >云产品购买</a></li>
            </ul>
          </li>
          <li class="grid_3">
            <h4><a>解决方案</a><span>Solutions</span></h4>
            <ul class="items">
              <li><a href="#" >云计算系统软件</a></li>
              <li><a href="#" >云负载SLB</a></li>
            </ul>
          </li>
          <li class="grid_3">
            <h4><a>帮助中心</a><span>Help</span></h4>
            <ul class="items">
              <li><a href="jg.html" >通知公告</a></li>
              <li><a href="jg.html#axjf" >优惠促销</a></li>
            </ul>
          </li>
          <li class="grid_3">
            <h4><a>开放平台</a><span>Open platform </span></h4>
            <ul class="items">
              <li><a href="#">申请云业务</a></li>
              <li><a href="#">API 文档</a></li>
            </ul>
          </li>
          <li class="grid_3">
            <h4><a>关于</a><span>about</span></h4>
            <ul class="items">
              <li><a href="gywm_about.html" >关于我们</a></li>
              <li><a href="gywm_newlist.html" >媒体报道</a></li>
            </ul>
          </li>
           <li class="grid_3">
            <h4><a>关于</a><span>about</span></h4>
            <ul class="items">
              <li><a href="gywm_about.html" >关于我们</a></li>
              <li><a href="gywm_newlist.html" >媒体报道</a></li>
            </ul>
          </li>
        </ul>
        <div class="grid_4">
        </div>
        <div class="clearfix">
          <p class="footerp">
          <?php echo ($Web["Config"]["site_code"]); ?> 
          <?php echo ($Web["Config"]["site_icp"]); ?>          
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="cloudform">
	<form id='cloud_form' action='<?php echo U("User/Order/cloud");?>' method='post'>
		<input type='hidden' name='id' id='id' value='0'>
		<input type='hidden' name='cpunum' id='cpunum' value='1'>
		<input type='hidden' name='memnum' id='memnum' value='512'>
		<input type='hidden' name='disknum' id='disknum' value='0'>
		<input type='hidden' name='maxdisk' id='maxdisk' value='2000'>
		<input type='hidden' name='ddisk' id='ddisk' value='0'>
		<input type='hidden' name='qosnum' id='qosnum' value='0'>
		<input type='hidden' name='maxqos' id='maxqos' value='100'>
		<input type='hidden' name='dqos' id='dqos' value='0'>
		<input type='hidden' name='ipnum' id='ipnum' value='1'>
		<input type='hidden' name='cloudname' id='cloudname' value=''>
		<input type='hidden' name='cloudpassword' id='cloudpassword' value=''>
		<input type='hidden' name='imageuuid' id='imageuuid' value='no'>
		<input type='hidden' name='year' id='year' value='1'>
		<input type='hidden' name='isrebate' id='isrebate' value='n'>
		<input type='hidden' name='jfname' id='jfname' value=''>
		<!-- 试用控制 -->
		<input type='hidden' name='maxcpu' id='maxcpu' value='16'>
		<input type='hidden' name='maxmem' id='maxmem' value='16'>
		<input type='hidden' name='cantestcpu' id='cantestcpu' value='1'>
		<input type='hidden' name='cantestmem' id='cantestmem' value='1'>
		<input type='hidden' name='cantestdisk' id='cantestdisk' value='10'>
		<input type='hidden' name='cantestqos' id='cantestqos' value='1'>
	</form>
</div>
<script>
var isuser=<?php echo ($isuser); ?>;
var defaulttype=<?php echo ($defaulttype); ?>;
var Loginurl="<?php echo U('Home/User/login');?>";
var Ajaxosurl="<?php echo U('Home/Cloud/ajaxos');?>";
var Ajaxproducturl="<?php echo U('Home/Cloud/ajaxproduct');?>";
var Ajaxcloudinfourl="<?php echo U('Home/Cloud/ajaxcloudinfo');?>";
var Ajaxcloudpriceurl="<?php echo U('Home/Cloud/ajaxcloudprice');?>";
var Ajaxcloudname="<?php echo U('Home/Cloud/ajaxcloudname');?>";
</script>
<script type="text/javascript" src="__PUBLIC__/Home/js/cloud1.1.js"></script>
</body>
</html>