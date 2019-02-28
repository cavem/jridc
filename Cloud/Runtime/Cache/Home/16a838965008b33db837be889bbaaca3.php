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
          <li class="computing "><a href="<?php echo U('Home/Page/show',array('id'=>8));?>" >云计算业务合作</a></li>
          <li class="network "><a href="<?php echo U('Home/Page/show',array('id'=>9));?>" >数据中心资源</a></li>
          <li class="storage "><a href="<?php echo U('Home/Page/show',array('id'=>10));?>" >服务器集群</a></li>
          <li class="security selected"><a href="<?php echo U('Home/Page/show',array('id'=>11));?>" >部署与管理</a></li>
          <li class="management"><a href="<?php echo U('Home/Cloud/buy');?>" >弹性购买</a></li>
        </ul>
      </div>
    </section>
    
    
    <section class="products-features">

      <div class="product-item overview">

        <div class="viewport-inner">

          <h2>部署与管理  Web Console</h2>

          <p class="lead">简单方便实现对资源的部署，包括：<a href="#image">IDC数据中心</a>&nbsp;、&nbsp;<a href="#image">服务器集群</a>&nbsp;、&nbsp;<a href="#instance">业务平台</a>...</p>

          <div class="grid_8 product-feature">

            <div class="product-icon performance pro-icon8"></div>

            <h3>IDC数据机房</h3>

            <p>全球顶级IDC数据中心机房资源巨力呈现让数据中心节点更加便捷易用</p>

          </div>

          <div class="grid_8 product-feature">

            <div class="product-icon stable pro-icon9"></div>

            <h3>服务器集群</h3>

            <p>SAS-SAN架构，最大96G吞吐量，最高近100万IOPS，最大超1PB的容量</p>

          </div>

          <div class="grid_8 product-feature">

            <div class="product-icon snapshot pro-icon10"></div>

            <h3>业务平台</h3>

            <p>独立的业务平台、图形化界面和开放API一个小品牌，都有一个专属云</p>

          </div>

          <div class="grid_8 product-feature">

            <div class="product-icon performance pro-icon11"></div>

            <h3>港湾云系统</h3>

            <p>强大的承载能力让每个节点资源有效整合云之基石，自由计算</p>

          </div>
          <div class="grid_8 product-feature">
            <div class="product-icon stable pro-icon12"></div>
            <h3>技术支持服务</h3>
            <p>专业的技术服务标准，让每个云梦想者省心每一个问题都会即时响应</p>
          </div>
          <div class="grid_8 product-feature">
            <div class="product-icon snapshot pro-icon13"></div>
            <h3>备案服务</h3>
            <p>快速备案，快速接入</p>
          </div>
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