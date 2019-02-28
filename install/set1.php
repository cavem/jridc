<?php
error_reporting(0);
header('Content-Type: text/html; charset=utM-8');
include_once('lib/base.php');
if (file_exists('install.lock')){
    die('对不起，该程序已经安装过了。如您要重新安装，请手动删除install/install.lock文件。');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utM-8" />
<title>代理商户系统安装</title>
<link rel="stylesheet" type="text/css" href="css/metinfo.css" />
<link rel="stylesheet" type="text/css" href="css/reset.css" />
<script type="text/javascript">
<!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=450,height=280,screenX=150,screenY=150,top=150,left=150')
}
function popupWindowLrg(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=700,height=500,screenX=50,screenY=50,top=50,left=50')
}
//-->
</script>
<script language="javascript" src="js/install.js"></script>
<script language="javascript" src="js/jQuery1.7.2.js"></script>
<script type="text/javascript">
function ifreme_methei(){
	var l = $("#jsheit").height()+10;
	$(window.parent.document).find("#index").height(l);
}
function metfocus(intext){
        intext.focus(function(){
		    $(this).addClass('metfocus');
		});
        intext.focusout(function(){
		    $(this).removeClass('metfocus');
		});
}
$(document).ready(function(){
	var inputps = $("input[type='text'],input[type='password']");
		if(inputps)metfocus(inputps);
		ifreme_methei();
});
</script>
</head>
<!---->
<body>
<div id="jsheit">
<div class="contenttext round">
<h1 class="title">最终用户授权许可协议</h1>
  <p>用户授权许可协议</p>
<div style="padding-top:10px;">
<p style="float:left; line-height:1.8;"></p> 
</div>
<div class="clear"></div>
</div>
<form name="myform" method="post" action="set2.php" style="text-align:center; background:#f9f9f9;"><input type="submit" name="submit" class="submit" value="我已仔细阅读以上协议并同意安装" /></form>
</div>
</body>
</html>