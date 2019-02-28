<?php
$adminconfig =  array(
	'DEFAULT_THEME'         =>'Default',    //默认模板
    'TMPL_ACTION_ERROR' => 'Common:jump',//错误提示页面
    'TMPL_ACTION_SUCCESS' => 'Common:jump',//正确提示页面
 	//开启权限
    'USER_AUTH_ON' => true,//是否开启权限验证
    'USER_AUTH_TYPE' => 2, // 默认认证类型 1 登录认证 2 实时认证
    'ADMIN_AUTH_KEY' => 'admin',//超级管理员识别号
    'AUTH_TYPE' => array('NODE', 'MODULE', 'ACTION'), //授权类型的常量
    'USER_AUTH_MODEL' => 'Admin', // 默认验证数据表模型
    'USER_AUTH_KEY' => 'AdminClouds', // 用户认证SESSION标记
    'AUTH_PWD_ENCODER' => 'md5', // 用户认证密码加密方式
    'USER_AUTH_GATEWAY' => 'Admin/Login/Login', // 默认认证网关
    'NOT_AUTH_MODULE' => 'Login,Index,Ajax', // 默认无需认证模块2016-4-11
    'GUEST_AUTH_ON' => false, // 是否开启游客授权访问
    'GUEST_AUTH_ID' => 0, // 游客的用户ID
    'RBAC_ROLE_TABLE' => 'role',
    'RBAC_USER_TABLE' => 'admin',
    'RBAC_ACCESS_TABLE' => 'role_access',
    'RBAC_NODE_TABLE' => 'role_node',
	'PAGE_COUNT'=>15,//分页设置
);
$adminconfig=array_merge(include 'update.php',$adminconfig);
$adminconfig=array_merge(include 'checkip.php',$adminconfig);
return $adminconfig;
?>