<?php
/**
 +------------------------------------------------------------------------------
 * 升级类
 +------------------------------------------------------------------------------
 * @author    Terry <3401146@qq.com>
 +------------------------------------------------------------------------------
 */
class Webupdate {
	/**
	 * $updatefile="yagentweb.zip";
    	$up=new Webupdate();
    	$downfile=$up->down("http://ydown.gwidc.net/yagentweb.zip",ROOT_PATH."Upload/update/".$updatefile);
    	if (empty($downfile))$this->error('获取更新文件失败');
    	$zipinfo = new Pclzip(ROOT_PATH."Upload/update/".$updatefile);
    	if ($zipinfo->extract(PCLZIP_OPT_PATH, ROOT_PATH."Upload/update",PCLZIP_OPT_REMOVE_PATH, '') == 0) {
			$this->error($archive->errorInfo(true));
        }
        //获取更新文件列表
        $fileinfo=$up->get_filenamesbydir(ROOT_PATH."Upload/update/".str_replace('.zip','',$updatefile));
    	$up->update(ROOT_PATH."Upload/update/".str_replace('.zip','',$updatefile),ROOT_PATH);
    	$up->delfile(ROOT_PATH."Upload/update/".str_replace('.zip','',$updatefile));
	 */
	//下载升级文件
	function down($fileurl,$savename){
		ob_start();
		@set_time_limit(3000);//设置该页面最久执行时间为300秒
		$file = fopen ($fileurl, "rb");
		if ($file) {
			$filesize = -1;
		    $headers = get_headers($fileurl, 1);
		    if ((!array_key_exists("Content-Length", $headers))) $filesize=0;
		    $filesize = $headers["Content-Length"];
		    if ($filesize != -1) {
		        
		    }
		    $newf = fopen ($savename, "wb");
		    $downlen=0;
		    if ($newf) {
		        while(!feof($file)) {
		            $data=fread($file, 1024 * 8 );//默认获取8K
		            $downlen+=strlen($data);//累计已经下载的字节数
		            fwrite($newf, $data, 1024 * 8 );
		            ob_flush();
		            flush();
		        }
		    }
		    if ($file) {
		        fclose($file);
		    }
		    if ($newf) {
		        fclose($newf);
		    }
		}
		return $downlen;
	}
	function update($source,$destination){
		$sql=$this->read_file($source."/sql/update.sql");
		if (!empty($sql)){
			$conn = @mysql_connect(C('DB_HOST'),C('DB_USER'),C('DB_PWD'));
			if (!$conn){
				return  "数据库连接失败";
			}
			mysql_query("SET NAMES 'utf8', character_set_client=binary, sql_mode='', interactive_timeout=3600;");
			mysql_select_db(C('DB_NAME'),$conn); 
			$sqlarr = explode(";\n",$sql);
			foreach ($sqlarr as $sql) {
				mysql_query($sql);
			}	
		}
		//移动复制目录
		$this->copyDir($source."/web", $destination);
		return "成功";
	}
	function delfile($source){
		$this->delDir($source);
		unlink($source.".zip");
	}
	function get_allfiles($path,&$files) {  
	    if(is_dir($path)){  
	        $dp = dir($path);  
	        while ($file = $dp ->read()){  
	            if($file !="." && $file !=".."){  
	                $this->get_allfiles($path."/".$file, $files);  
	            }  
	        }  
	        $dp ->close();  
	    }  
	    if(is_file($path)){  
	        $files[] =  $path;  
	    }  
	}  
    //获取目录下所有文件
	function get_filenamesbydir($dir){  
	    $files =  array();  
	    $this->get_allfiles($dir,$files);  
	    return $files;  
	}  
     
	//删除目录
	function delDir($directory,$subdir=true)
	{
		if (is_dir($directory) == false)
		{
			exit("The Directory Is Not Exist!");
		}
		$handle = opendir($directory);
		while (($file = readdir($handle)) !== false)
		{
			if ($file != "." && $file != "..")
			{
			is_dir("$directory/$file")?
				$this->delDir("$directory/$file"):
				unlink("$directory/$file");
			}
		}
		if (readdir($handle) == false)
		{
			closedir($handle);
			rmdir($directory);
		}
	}
	function read_file($file){  
	    if (file_exists($file) and is_readable($file)) {
			if (function_exists('file_get_contents')) {
				$content = file_get_contents($file);
			} else {
				$fp = fopen($file, 'r');
				while (!feof($fp)) {
					$content = fgets($fp, 1024);
				}
				fclose($fp);
			}
			return $content;
		}
	}
	//移动覆盖目录
	function copyDir($source, $destination)
	{
		if (is_dir($source) == false)
		{
			exit("The Source Directory Is Not Exist!");
		}
		if (is_dir($destination) == false)
		{
			mkdir($destination, 0700);
		}
		$handle=opendir($source);
		while (false !== ($file = readdir($handle)))
		{
			if ($file != "." && $file != "..")
			{
				is_dir("$source/$file")?
				$this->copyDir("$source/$file", "$destination/$file"):
				copy("$source/$file", "$destination/$file");
			}
		}
		closedir($handle);
	}
}