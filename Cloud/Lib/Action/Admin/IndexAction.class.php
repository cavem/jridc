<?php
class IndexAction extends Action{
    public function index(){
    	$this->redirect('Admin/Login/Login');
	}
	public function test(){
		echo "123";
		echo ROOT_PATH;
		echo __ROOT__;
		echo IS_CGI;
	}
	public function clear() {
		header("Content-type: text/html; charset=utf-8"); 
		import('ORG.Util.Dir');
		$obj_dir = new Dir;
		echo "清理开始";
		echo "<br>";
		G('begin');
		is_dir(DATA_PATH) && $obj_dir->delDir(DATA_PATH);
		is_dir(CACHE_PATH) && $obj_dir->delDir(CACHE_PATH);
		is_dir(TEMP_PATH) && $obj_dir->delDir(TEMP_PATH);
		@unlink(RUNTIME_FILE);
		is_dir(LOG_PATH) && $obj_dir->delDir(LOG_PATH);
		G('end');
		echo G('begin','end',6).'s';
		echo "<br>";
		echo G('begin','end','m').'kb';//内存开销
		echo "<br>";
		echo "清理结束";
	}
}
?>