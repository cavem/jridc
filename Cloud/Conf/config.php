<?php
return array(
	'APP_GROUP_LIST' => 'Home,Admin,User,Wechat,Autorun,Sale', //项目分组设定
    'DEFAULT_GROUP'  => 'Home', //默认分组
	'DEFAULT_MODULE'     => 'Index', //默认模块
	 //URL设置
    'URL_MODEL'            => 1,//URL模式 1 伪静态 2 3兼容模式
    'URL_HTML_SUFFIX'      => 'html',
    'URL_ROUTER_ON'        => true,
    'URL_CASE_INSENSITIVE' => true, //url不区分大小写
	'SESSION_AUTO_START' => true, //是否开启session 
    'APP_AUTOLOAD_PATH' => '@.Common,@.TagLib,@.Wxpay', //类库
	'TAGLIB_PRE_LOAD'=>"Cloud",//自定义标签自动加载
    'LOAD_EXT_CONFIG' => 'db,version,wechat,agentapi,supprot', // 加载扩展配置文件   
    'TMPL_PARSE_STRING'=>array('__PUBLIC__'=>__ROOT__.'/Public'),
	'defaultiptype'   => 4, //默认线路
	'CLOUDOPEN'=>2, // 1默认同步 2异步开通
	'LOG_RECORD'=>TRUE,
	'VAR_FILTERS'=>'stripslashes,strip_tags,htmlentities,htmlspecialchars',
);
?>