<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="icon" href="favicon.ico" type="image/x-icon">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=1300">
<meta name="keywords" content="<?php echo ($data["keyword"]); ?>">
<meta name="description" content="<?php echo ($data["description"]); ?>">
<title><?php echo ($data["title"]); ?>_<?php echo ($Web["Config"]["site_name"]); ?></title>
<link href="__PUBLIC__/Home/Default/css/index.css" rel="stylesheet" type="text/css">
<link href="__PUBLIC__/Home/Default/css/page.css" rel="stylesheet" type="text/css">
</head>
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
	<section class="page" style="min-height: 500px;">
	 <div class="products">
    <section class="intro products">
      <div class="viewport-inner">
        <h1>公有云会像水、电一样服务于每个人的生活</h1>
        <blockquote>我们致力于提供一个平台，使计算资源的交付更加简单、高效、可靠，甚至更环保</blockquote>
        <ul class="page-tab">
          <li class="computing"><a href="<?php echo U('Home/Page/show',array('id'=>8));?>" >云计算业务合作</a></li>
          <li class="network selected"><a href="<?php echo U('Home/Page/show',array('id'=>9));?>" >数据中心资源</a></li>
          <li class="storage"><a href="<?php echo U('Home/Page/show',array('id'=>10));?>" >服务器集群</a></li>
          <li class="security"><a href="<?php echo U('Home/Page/show',array('id'=>11));?>" >部署与管理</a></li>
          <li class="management"><a href="<?php echo U('Home/Cloud/buy');?>" >弹性购买</a></li>
        </ul>
      </div>
    </section>
    
      <section class="products-features">

      <div class="product-item overview">

        <div class="viewport-inner">

          <h2>数据中心  Data Center</h2>

          <p class="lead">大秦云为您提供全球数据中心资源，您可以快速享受IDC服务的中心资源平台，包括：<a href="#">西部数据中心、</a><a href="#">香港机房、</a><a href="#">美国机房、....</a></p>

          <div class="pro-sj">

            <div class="pro-left"> <i class="pro-icon1"></i>

              <ul>

                <li><a href="#">西部数据中心</a></li>

                <li><a href="#">宝鸡电信</a></li>

                <li><a href="#">襄阳电信</a></li>

              </ul>

            </div>

            <div class="pro-right"> <i class="pro-icon2"></i>

              <ul>

                <li><a href="#">宝鸡双线</a></li>

                <li><a href="#">亦庄双线</a></li>

              </ul>

            </div>

            <div class="pro-left"> <i class="pro-icon3"></i>

              <ul>

                <li><a href="#">西安联通</a></li>

                <li><a href="#">宝鸡联通</a></li>

              </ul>

            </div>

            <div class="pro-right"> <i class="pro-icon4"></i>

              <ul>

                <li><a href="#">香港数据中心</a></li>

              </ul>

            </div>

            <div class="pro-left"> <i class="pro-icon5"></i>

              <ul>

                <li><a href="#">浙江移动</a></li>

                <li><a href="#">江西移动</a></li>

              </ul>

            </div>

            <div class="pro-right"> <i class="pro-icon6"></i>

              <ul>

                <li><a href="#">谷歌数据中心</a></li>

                <li><a href="#">亚马逊数据中心</a></li>

              </ul>

            </div>

          </div>

        </div>

      </div>

      <div class="product-item" id="sjfwzx">

        <div class="viewport-inner">

          <div class="product-title">

            <h2>全球数据中心服务</h2>


          </div>

        </div>

        <div class="viewport-inner">

          <p class="desc">大秦云启动全球化部署，香港数据中心即将上线；将专注于亚太地区和欧美地区，主要为这两个地区的企业提供全面的云计算服务。大秦云香港数据中心中将运用先进的模块化数据中心

            技术、高效的绿色节能技术以及尝试使用下一代的硬件技术，在承载海量的应用的同时，有效降低了服务器成本。</p>


        </div>

      </div>

    </section>
    
    
    
    </div>
    </section>
	
	
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
</body>
</html>