<?php
/**
 * 短信发送接口
 * Enter description here ...
 * @author Geyoulei
 * 北京创世漫道科技有限公司
 */
class Smszucp{
	 //发送短信信息
	 static public function Sendsms($config=array()){
	 	 if (empty($config))return false;
	 	 $flag = 0; 
	 	 $sms_value=json_decode($config['sms_value'], true);	 	
	 	 $argv = array( 
         'sn'=>$sms_value['Sms_sn'], ////替换成您自己的序列号
		 'pwd'=>strtoupper(md5($sms_value['Sms_sn'].$sms_value['Sms_pwd'])), //此处密码需要加密 加密方式为 md5sn+password) 32位大写
		 'mobile'=>$config['mobile'],//手机号 多个用英文的逗号隔开 post理论没有长度限制.推荐群发一次小于等于10000个手机号
		 'content'=>urlencode($config['content'].$sms_value['sign']),//短信内容
		 'ext'=>'',		
		 'stime'=>'',
		 'rrid'=>''
		 ); 
	 	 foreach ($argv as $key=>$value) { 
          if ($flag!=0) { 
                         $params .= "&"; 
                         $flag = 1; 
          } 
        	 $params.= $key."="; $params.= urlencode($value); 
        	 $flag = 1; 
          } 
         $length = strlen($params); 
         //创建socket连接 
         //$fp = fsockopen("sdk2.entinfo.cn",8060,$errno,$errstr,10) or exit($errstr."--->".$errno); 
		 $fp = fsockopen("sdk2.zucp.net",8060,$errno,$errstr,10);
		 if(!$fp){
			 Log::write($errstr."--->".$errno,"SMS_Err");
			 return false;
		 }
         //构造post请求的头 
         $header = "POST /webservice.asmx/mdSmsSend_u HTTP/1.1\r\n"; 
         $header .= "Host:sdk2.entinfo.cn\r\n"; 
         $header .= "Content-Type: application/x-www-form-urlencoded\r\n"; 
         $header .= "Content-Length: ".$length."\r\n"; 
         $header .= "Connection: Close\r\n\r\n"; 
         //添加post的字符串 
         $header .= $params."\r\n"; 
         //发送post的数据 
         fputs($fp,$header); 
         $inheader = 1; 
          while (!feof($fp)) { 
                         $line = fgets($fp,1024); //去除请求包的头只显示页面的返回数据 
                         if ($inheader && ($line == "\n" || $line == "\r\n")) { 
                                 $inheader = 0; 
                          } 
                          if ($inheader == 0) { 
                                // echo $line; 
                          } 
          } 
		 preg_match('/<string xmlns=\"http:\/\/tempuri.org\/\">(.*)<\/string>/',$line,$str);
		 $result=explode("-",$str[1]);
	     if(count($result)>1){
	     return false;
	     }else{
	     return true;
	     }
	 }
	 //修改密码
	  static public function UdpPwd($newpass){
	  	$Sms=D('Sms')->where(array('sms_key'=>'Smszucp'))->find();
	 	$sms_value=json_decode($Sms['sms_value'], true);
	 	$flag = 0; 
		$argv = array( 
	         'sn'=>$sms_value['Sms_sn'], //替换成您自己的序列号
			 'pwd'=>$sms_value['Sms_pwd'],//替换成您自己的密码
			 'newpwd'=>$newpass,//新密码
		); 
		//构造要post的字符串 
		foreach ($argv as $key=>$value) { 
	          if ($flag!=0) { 
	                         $params .= "&"; 
	                         $flag = 1; 
	          } 
	         $params.= $key."="; $params.= urlencode($value); 
	         $flag = 1; 
          } 
         $length = strlen($params); 
         //创建socket连接 
         $fp = fsockopen("sdk2.entinfo.cn",8060,$errno,$errstr,10) or exit($errstr."--->".$errno); 
         //http://sdk.entinfo.cn:8060/webservice.asmx?op=UDPPwd
         //构造post请求的头 
         $header = "POST /webservice.asmx/UDPPwd HTTP/1.1\r\n"; 
         $header .= "Host:sdk2.entinfo.cn\r\n"; 
         $header .= "Content-Type: application/x-www-form-urlencoded\r\n"; 
         $header .= "Content-Length: ".$length."\r\n"; 
         $header .= "Connection: Close\r\n\r\n"; 
         //添加post的字符串 
         $header .= $params."\r\n"; 
         //发送post的数据 
         fputs($fp,$header); 
         $inheader = 1; 
         while (!feof($fp)) { 
                         $line = fgets($fp,1024); //去除请求包的头只显示页面的返回数据 
                         if ($inheader && ($line == "\n" || $line == "\r\n")) { 
                                 $inheader = 0; 
                          } 
                          if ($inheader == 0) { 
                                // echo $line; 
                          } 
          } 
	       $line=str_replace("<string xmlns=\"http://tempuri.org/\">","",$line);
	       $line=str_replace("</string>","",$line);
		   $result=explode(chr(32),$line);
		    if(count($result)>1)
		    return $result[1];
			else
			return '修改失败:'.$result[1];
	  	
	  }
	 //获取账户余额
	 static public function GetBalance(){
	 	$Sms=D('Sms')->where(array('sms_key'=>'Smszucp'))->find();
	 	$sms_value=json_decode($Sms['sms_value'], true);
		$flag = 0; 
		$argv = array( 
	         'sn'=>$sms_value['Sms_sn'], //替换成您自己的序列号
			 'pwd'=>$sms_value['Sms_pwd'],//替换成您自己的密码
		); 
		//构造要post的字符串 
		foreach ($argv as $key=>$value) { 
	          if ($flag!=0) { 
	                         $params .= "&"; 
	                         $flag = 1; 
	          } 
	         $params.= $key."="; $params.= urlencode($value); 
	         $flag = 1; 
          } 
         $length = strlen($params); 
         //创建socket连接 
         $fp = fsockopen("sdk2.entinfo.cn",8060,$errno,$errstr,10) or exit($errstr."--->".$errno); 
         //构造post请求的头 
         $header = "POST /webservice.asmx/GetBalance HTTP/1.1\r\n"; 
         $header .= "Host:sdk2.entinfo.cn\r\n"; 
         $header .= "Content-Type: application/x-www-form-urlencoded\r\n"; 
         $header .= "Content-Length: ".$length."\r\n"; 
         $header .= "Connection: Close\r\n\r\n"; 
         //添加post的字符串 
         $header .= $params."\r\n"; 
         //发送post的数据 
         fputs($fp,$header); 
         $inheader = 1; 
         while (!feof($fp)) { 
                         $line = fgets($fp,1024); //去除请求包的头只显示页面的返回数据 
                         if ($inheader && ($line == "\n" || $line == "\r\n")) { 
                                 $inheader = 0; 
                          } 
                          if ($inheader == 0) { 
                                // echo $line; 
                          } 
          } 
	       $line=str_replace("<string xmlns=\"http://tempuri.org/\">","",$line);
	       $line=str_replace("</string>","",$line);
		   $result=explode("-",$line);
		    if(count($result)>1)
		    return '发送失败返回值为:'.$line;
			else
			return '余额为:'.$line;
	 }
}