<?php
if (ini_get('magic_quotes_gpc')) {
	function stripslashesRecursive(array $array){
		foreach ($array as $k => $v) {
			if (is_string($v)){
				$array[$k] = stripslashes($v);
			} else if (is_array($v)){
				$array[$k] = stripslashesRecursive($v);
			}
		}
		return $array;
	}
	$_GET = stripslashesRecursive($_GET);
	$_POST = stripslashesRecursive($_POST);
}
ini_set('session.name','PHPSESSID_RSDL');
define('APP_NAME', 'Cloud');
define('APP_PATH', './Cloud/');
define('APP_DEBUG', true);
define('ROOT_PATH',dirname(__FILE__).'/');
require './ThinkPHP/ThinkPHP.php';