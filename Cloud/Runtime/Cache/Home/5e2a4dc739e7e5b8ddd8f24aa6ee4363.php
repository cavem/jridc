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
          <li class="computing selected"><a href="<?php echo U('Home/Page/show',array('id'=>8));?>" >云计算业务合作</a></li>
          <li class="network "><a href="<?php echo U('Home/Page/show',array('id'=>9));?>" >数据中心资源</a></li>
          <li class="storage"><a href="<?php echo U('Home/Page/show',array('id'=>10));?>" >服务器集群</a></li>
          <li class="security"><a href="<?php echo U('Home/Page/show',array('id'=>11));?>" >部署与管理</a></li>
          <li class="management"><a href="<?php echo U('Home/Cloud/buy');?>" >弹性购买</a></li>
        </ul>
      </div>
    </section>
       <section class="products-features">

      <div class="product-item overview">

        <div class="viewport-inner">

          <h2>云计算 Cloud Computing</h2>

          <p class="lead">大秦云为您提供一种云计算解决方案，您可以拥有独立的云数据中心资源平台，包括：<a href="#">Cloud Host</a>&nbsp;和&nbsp;<a href="#">开放 API</a></p>

          <div class="grid_8 product-feature">

            <div class="product-graph">

              <div class="product-icon computing-elastic"></div>

            </div>

            <h3>超级规模级部署</h3>

            <p>所有计算资源弹性可扩展，您不仅仅拥有国内数据中心机房资源，国外数据中心机房资源也很轻松。</p>

          </div>

          <div class="grid_8 product-feature">

            <div class="product-graph">

              <div class="product-icon computing-second"></div>

            </div>

            <h3>技术支持  快速响应</h3>

            <p>您可以轻松创建云数据中心资源，最大限度满足您的需求技术支持快速响应。</p>

          </div>

          <div class="grid_8 product-feature">

            <div class="product-graph">

              <div class="product-icon computing-backup"></div>

            </div>

            <h3>可视化操作</h3>

            <p>图形化展示网络拓扑结构，简单、直观、丰富您的云数据中心资源。</p>

          </div>

        </div>

      </div>

      <div class="product-item" id="image">

        <div class="viewport-inner">

          <div class="product-title">

            <h2>CloudHost服务</h2>


          </div>

        </div>

        <div class="viewport-inner">

          <p class="desc">Cloud Host（云主机）是云数据中心以虚拟机器的形式运行在大秦云中的映像副本。 基于一个映像，您可以创建任意数量的主机。在创建主机时， 您需要指明CPU 和内存的配置。大

秦云允许您任意指定 CPU、内存的数量， 也允许您在主机创建之后随时再行调整。</p>

          <div class="grid_12">

            <ul class="product-item-features">

              <li>

                <h4>多种操作系统</h4>

                <p class="desc">我们为您提供最新、最常用、最稳定的映像</p>

              </li>

              <li>

                <h4>高弹性</h4>

                <p class="desc">弹性可扩展，随时调整CPU、内存、存储</p>

              </li>

              <li>

                <h4>自有映像</h4>

                <p class="desc">可在搭建好环境后捕获成自有映像，之后可基于此自由映像创建更多主机</p>

              </li>

              <li>

                <h4>全面监控</h4>

                <p class="desc">历史和实时监控主机CPU、内存、硬盘读写、网络流量等重要数据</p>

              </li>

              <li>

                <h4>备份与恢复</h4>

                <p class="desc">可与其挂载的硬盘批量在线备份，且可随时回滚到某一个备份点</p>

              </li>

            </ul>

          </div>

          <div class="grid_12 product-screenshot images"></div>

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