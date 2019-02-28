<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="icon" href="favicon.ico" type="image/x-icon">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=1300">
<meta name="keywords" content="<?php echo ($Web["Config"]["site_keyword"]); ?>">
<meta name="description" content="<?php echo ($Web["Config"]["site_description"]); ?>">
<title><?php echo ($Web["Config"]["site_name"]); ?></title>
<link href="__PUBLIC__/Home/Default/css/index.css" rel="stylesheet" type="text/css">
<script src="__PUBLIC__/Home/js/jquery-1.9.1.js"  type="text/javascript"></script>
<script src="__PUBLIC__/Home/js/html5.js"></script>
<script src="__PUBLIC__/Home/Default/js/index.js"  type="text/javascript"></script>
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
<section class="page" style="min-height: 500px;">
  <div id="home" class="home">
    <section class="intro home timeline timeline-wrapper" id="banner">
      <ul class="bd" id="bd">
        <li style="background:url(__PUBLIC__/Home/Default/images/bg-nc.png) top center #3e3d49 no-repeat;" id="banner1">
          <div class="viewport-inner bannermain1">
            <h1>BETA内测</h1>
            <p>经过长达三年的研发周期，展开多次的封闭性测试。<br/>
              最大程度保障您的数据安全与稳定</p>
            <a href="#"><i ></i>了解更多</a> </div>
        </li>
        <li style="background:url(__PUBLIC__/Home/Default/images/bg-js.png) top center #3e3d49 no-repeat; display:none;"  id="banner2">
          <div class="viewport-inner bannermain2">
            <h1>自由计算</h1>
            <p>为你提供按需分配、弹性可伸缩的计算能力。<br/>
              使计算资源的交付更加简单、高效、可靠，甚至更环保。</p>
            <a href="#"><i ></i>了解更多</a> </div>
        </li>
        <li style="background:url(__PUBLIC__/Home/Default/images/bg-yj.png) top center #3e3d49 no-repeat;  display:none;"  id="banner3">
          <div class="viewport-inner bannermain3">
            <h1>云集模式</h1>
            <p>首推数据中心、硬件、业务合作云集模式，我们不仅仅只是云主机业务<br/>
              创领云计算服务新时代，共创价值。</p>
            <a href="#"><i ></i>了解更多</a> </div>
        </li>
        <li style="background:url(__PUBLIC__/Home/Default/images/bg-jq.png) top left #3e3d49 no-repeat;  display:none;"  id="banner4">
          <div class="viewport-inner bannermain4">
            <h1>服务器集群</h1>
            <p>通过缆线物理连接并通过集群软件实现程序上的连接<br/>
              使计算机实现单机无法实现的容错和负载均衡</p>
            <a href="#"><i ></i>了解更多</a> </div>
        </li>
        <li style="background:url(__PUBLIC__/Home/Default/images/bg-xb.png) top center #3e3d49 no-repeat;display:none;"  id="banner5">
          <div class="viewport-inner bannermain5">
            <h1>全新官网 全新形象</h1>
            <p>将完整、全新的  形象展示于此<br/>
              希望你能感受到我们的与众不同。</p>
            <a href="#"><i ></i>了解更多</a> </div>
        </li>
        <li style="background:url(__PUBLIC__/Home/Default/images/bg-sx.png) top center #3e3d49 no-repeat; display:none;"  id="banner6">
          <div class="viewport-inner bannermain6">
            <h1>双线西区</h1>
            <p>在西部部署的第一个双线数据中心<br/>
              解决南北互通以及更优惠的资源价格。</p>
            <a href="#"><i ></i>了解更多</a> </div>
        </li>
        <li style="background:url(__PUBLIC__/Home/Default/images/bg-nc.png) top center #3e3d49 no-repeat; display:none;"  id="banner7">
          <div class="viewport-inner bannermain7">
            <h1>云数据中心</h1>
            <p>覆盖全国的数据中心，无论您身处哪个区域<br/>
              我们都会为您提供优质的数据机房资源选择</p>
            <a href="#"><i ></i>了解更多</a> </div>
        </li>
      </ul>
      <div class="viewport-inner" id="hd">
        <div class="hd"> <i class="prev"></i>
          <ul>
            <li class="li1"> <span>BETA内测</span> </li>
            <li class="li2"> <span>港湾上线</span> </li>
            <li class="li3"> <span>云集模式</span> </li>
            <li class="li4"> <span>服务器集群</span> </li>
            <li class="li5"> <span>新版上线</span> </li>
            <li class="li6"> <span>双线西区</span> </li>
            <li class="li7"> <span>云数据中心</span> </li>
          </ul>
          <i class="li8"> </i> <i class="next"></i> <font class="font13">2013</font> <font class="font14">2014</font> <font class="font15">2015</font> </div>
      </div>
    </section>
    <div class="home-features">
      <div class="home-feature">
        <div class="viewport-inner viewport-inner1">
          <div class="feature-content f_left">
            <h2>轻松拥有自己的云数据中心资源</h2>
            <p>通过GW Cloud 实现虚拟云数据中心，您可以快速搭建属于自己的私有云服务；基于互联网的计算方式，共享的软硬件资源和信息可以按需提供给计算机和其他设备。</p>
            <a class="btn btn-primary btn-small" href="#" ><span class="icon icon-arrow-right"></span>了解更多...</a>
            <blockquote>
              <p>我们增加了西部云品牌服务后，带给我们忠实用户一个云数据中心的资源，更多产品服务让我们保持业务多元化，同时也增加了用户粘性，GangWanCloud资源动态调配及秒级响应让用户给我们一个有力的微笑。</p>
              <cite>田歌，IT陕西总编<span class="logo logo-91"></span></cite></blockquote>
          </div>
          <div class="feature-graph safe"></div>
        </div>
      </div>
      <div class="home-feature">
        <div class="viewport-inner viewport-inner2">
          <div class="feature-graph io"></div>
          <div class="feature-content">
            <h2>秒级调配您的资源  最大限度降低总拥有成本</h2>
            <p>港湾，不光给您的是云数据中心资源，所有计算、存储、网络资源都是秒级响应。如果初始资源不够用，可以弹性扩展伸缩。让您可以随时调整业务规模，无需考虑计费周期的限制。</p>
            <a class="btn btn-primary btn-small" href="#" ><span class="icon icon-arrow-right"></span>了解更多...</a>
            <blockquote>
              <p>港湾的计算能力使我们从容应对每日不同时段并发访问量峰谷相差数十倍的情况，灵活调配资源，节省TCO。</p>
              <cite>柯华松，好伙计创始人<span class="logo logo-xixun"></span></cite></blockquote>
          </div>
        </div>
      </div>
      <div class="home-feature">
        <div class="viewport-inner viewport-inner3">
          <div class="feature-content f_left">
            <h2>最大程度保障您的数据安全</h2>
            <p>私有网络提供100%二层隔离，在这个环境里，你的内部数据是非常安全的，黑客无法嗅探或者截获到你的数据。多重实时副本和备份可以保障即使在物理硬件彻底损坏时，数据也不会丢失，并且可以很快恢复业务。</p>
            <a class="btn btn-primary btn-small" href="#" ><span class="icon icon-arrow-right"></span>了解更多...</a>
            <blockquote>
              <p>金凰彩印对于后台系统安全性与可靠性的要求极其苛刻，GangWanCloud在硬件设备遇到灾难性
                故障时，通过异地多重实时副本确保业务系统仍能正常运行。</p>
              <cite>李增峰，金凰彩印CTO<span class="logo logo-togic"></span></cite></blockquote>
          </div>
          <div class="feature-graph billing"></div>
        </div>
      </div>
    </div>
    <div class="home-numbers">
      <div class="viewport-inner">
        <ul>
          <li><a class="number"><b>200<span class="unit">家</span></b>云数据中心</a>
            <div class="info"><b>200<span class="unit">家</span></b>云数据中心</div>
          </li>
          <li><a class="number"><b>600<span class="unit">G</span></b>带宽资源</a>
            <div class="info"><b>600<span class="unit">G</span></b>带宽资源</div>
          </li>
          <li><a class="number"><b>300<span class="unit">个</span></b>服务器集群</a>
            <div class="info"><b>300<span class="unit">个</span></b>服务器集群</div>
          </li>
          <li><a class="number"><b>5200<span class="unit">个</span></b>用户选择</a>
            <div class="info"><b>5200<span class="unit">个</span></b>用户选择</div>
          </li>
          <li><a class="number"><b>110<span class="unit">个</span></b>开放API</a>
            <div class="info"><b>110<span class="unit">个</span></b>开放API</div>
          </li>
        </ul>
      </div>
    </div>
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