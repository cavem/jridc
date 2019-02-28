<?php
/**
 * 发送邮件模型
 * Enter description here ...
 * @author Geyoulei
 */
class SendmailModel extends Model{
	protected $tableName = 'mail_tpl';
	//发送云硬盘到期通知
	public function Senddiskexpire($username,$cloudname,$endtime){
		$emailtpl=D('Emailtpl')->where(array('key'=>'Diskexpire'))->find();//获取邮件模版
		$value=$emailtpl['value'];
		if (empty($emailtpl['issend'])) return false;
		$value = str_replace('{$username}',$username,$value);
		$value = str_replace('{$diskname}',$cloudname,$value);
		$value = str_replace('{$endtime}',convert_datefm($endtime,2),$value);
		$User=D('User')->where(array('username'=>$username))->find();
		if (empty($User))return false;
		if (empty($User['email']))return false;
		$result=$this->Send($User['email'], $emailtpl['name'], $value);
		D('Systemlog')->adddata($username,$value,2);//写入发送日志
		if ($result){
			return true;
		}else{
			return false;
		}		
	}
	//发送云硬盘续费邮件通知
	public function Senddiskrepay($username,$money,$cloudname,$starttime,$endtime){
		$emailtpl=D('Emailtpl')->where(array('key'=>'Diskrepay'))->find();//获取邮件模版
		$value=$emailtpl['value'];
		if (empty($emailtpl['issend'])) return false;
		$value = str_replace('{$username}',$username,$value);
		$value = str_replace('{$money}',$money,$value);
		$value = str_replace('{$diskname}',$cloudname,$value);
		$value = str_replace('{$oldtime}',convert_datefm($starttime,2),$value);
		$value = str_replace('{$newtime}',convert_datefm($endtime,2),$value);
		$User=D('User')->where(array('username'=>$username))->find();
		if (empty($User))return false;
		if (empty($User['email']))return false;
		$result=$this->Send($User['email'], $emailtpl['name'], $value);
		D('Systemlog')->adddata($username,$value);//写入发送日志
		if ($result){
			return true;
		}else{
			return false;
		}		
	}
	//发送云硬盘开通邮件通知
	public function Senddiskopen($username,$money,$cloudname,$starttime,$endtime){
		$emailtpl=D('Emailtpl')->where(array('key'=>'Diskopen'))->find();//获取邮件模版
		$value=$emailtpl['value'];
		if (empty($emailtpl['issend'])) return false;
		$value = str_replace('{$username}',$username,$value);
		$value = str_replace('{$money}',$money,$value);
		$value = str_replace('{$diskname}',$cloudname,$value);
		$value = str_replace('{$starttime}',convert_datefm($starttime,2),$value);
		$value = str_replace('{$endtime}',convert_datefm($endtime,2),$value);
		$User=D('User')->where(array('username'=>$username))->find();
		if (empty($User))return false;
		if (empty($User['email']))return false;
		$result=$this->Send($User['email'], $emailtpl['name'], $value);
		D('Systemlog')->adddata($username,$value);//写入发送日志
		if ($result){
			return true;
		}else{
			return false;
		}		
	}
	//发送到期通知
	public function Sendcloudexpire($username,$cloudname,$endtime){
		$emailtpl=D('Emailtpl')->where(array('key'=>'Cloudexpire'))->find();//获取邮件模版
		$value=$emailtpl['value'];
		if (empty($emailtpl['issend'])) return false;
		$value = str_replace('{$username}',$username,$value);
		$value = str_replace('{$cloudname}',$cloudname,$value);
		$value = str_replace('{$endtime}',convert_datefm($endtime,2),$value);
		$User=D('User')->where(array('username'=>$username))->find();
		if (empty($User))return false;
		if (empty($User['email']))return false;
		$result=$this->Send($User['email'], $emailtpl['name'], $value);
		D('Systemlog')->adddata($username,$value,2);//写入发送日志
		if ($result){
			return true;
		}else{
			return false;
		}		
	}
	//发送负载均衡到期通知
	public function Sendloadbexpire($username,$cloudname,$endtime){
		$emailtpl=D('Emailtpl')->where(array('key'=>'Loadbexpire'))->find();//获取邮件模版
		$value=$emailtpl['value'];
		if (empty($emailtpl['issend'])) return false;
		$value = str_replace('{$username}',$username,$value);
		$value = str_replace('{$cloudname}',$cloudname,$value);
		$value = str_replace('{$endtime}',convert_datefm($endtime,2),$value);
		$User=D('User')->where(array('username'=>$username))->find();
		if (empty($User))return false;
		if (empty($User['email']))return false;
		$result=$this->Send($User['email'], $emailtpl['name'], $value);
		D('Systemlog')->adddata($username,$value,2);//写入发送日志
		if ($result){
			return true;
		}else{
			return false;
		}		
	}	
	
	//发送续费邮件通知
	public function Sendcloudrepay($username,$money,$cloudname,$starttime,$endtime){
		$emailtpl=D('Emailtpl')->where(array('key'=>'Cloudrepay'))->find();//获取邮件模版
		$value=$emailtpl['value'];
		if (empty($emailtpl['issend'])) return false;
		$value = str_replace('{$username}',$username,$value);
		$value = str_replace('{$money}',$money,$value);
		$value = str_replace('{$cloudname}',$cloudname,$value);
		$value = str_replace('{$oldtime}',convert_datefm($starttime,2),$value);
		$value = str_replace('{$newtime}',convert_datefm($endtime,2),$value);
		$User=D('User')->where(array('username'=>$username))->find();
		if (empty($User))return false;
		if (empty($User['email']))return false;
		$result=$this->Send($User['email'], $emailtpl['name'], $value);
		D('Systemlog')->adddata($username,$value);//写入发送日志
		if ($result){
			return true;
		}else{
			return false;
		}		
	}
	//发送开通邮件通知
	public function Sendloadbopen($username,$money,$cloudname,$starttime,$endtime){
		$emailtpl=D('Emailtpl')->where(array('key'=>'Loadbopen'))->find();//获取邮件模版
		$value=$emailtpl['value'];
		if (empty($emailtpl['issend'])) return false;
		$value = str_replace('{$username}',$username,$value);
		$value = str_replace('{$money}',$money,$value);
		$value = str_replace('{$cloudname}',$cloudname,$value);
		$value = str_replace('{$starttime}',convert_datefm($starttime,2),$value);
		$value = str_replace('{$endtime}',convert_datefm($endtime,2),$value);
		$User=D('User')->where(array('username'=>$username))->find();
		if (empty($User))return false;
		if (empty($User['email']))return false;
		$result=$this->Send($User['email'], $emailtpl['name'], $value);
		D('Systemlog')->adddata($username,$value);//写入发送日志
		if ($result){
			return true;
		}else{
			return false;
		}		
	}
	//发送开通邮件通知
	public function Sendcloudopen($username,$money,$cloudname,$starttime,$endtime){
		$emailtpl=D('Emailtpl')->where(array('key'=>'Cloudopen'))->find();//获取邮件模版
		$value=$emailtpl['value'];
		if (empty($emailtpl['issend'])) return false;
		$value = str_replace('{$username}',$username,$value);
		$value = str_replace('{$money}',$money,$value);
		$value = str_replace('{$cloudname}',$cloudname,$value);
		$value = str_replace('{$starttime}',convert_datefm($starttime,2),$value);
		$value = str_replace('{$endtime}',convert_datefm($endtime,2),$value);
		$User=D('User')->where(array('username'=>$username))->find();
		if (empty($User))return false;
		if (empty($User['email']))return false;
		$result=$this->Send($User['email'], $emailtpl['name'], $value);
		D('Systemlog')->adddata($username,$value);//写入发送日志
		if ($result){
			return true;
		}else{
			return false;
		}		
	}
	//发送邮件激活信息
	public function sendactivation($uid, $username, $email, $code,$type=0){
		$emailtpl=D('Emailtpl')->where(array('key'=>'Emailactivation'))->find();//获取邮件模版
		$value=$emailtpl['value'];
		$webConf = D('Config')->getCfgByModule('WEBSITE');
		$config = json_decode($webConf['WEBSITE'], true);
		if (empty($type)){
			$url=$config['site_url'].U('Home/User/emailactivation',array('uid'=>$uid,'ukey'=>$code));
		}else{
			$url=$config['site_url'].U('Home/User/emailactivation',array('uid'=>$uid,'ukey'=>$code,'ekey'=>encode($email)));
		}
		$value = str_replace('{$username}',$username,$value);
		$value = str_replace('{$url}',$url,$value);
		$result=$this->Send($email, $emailtpl['name'], $value);
		D('Systemlog')->adddata($username,$value);//写入发送日志
		if ($result){
			return true;
		}else{
			return false;
		}		
	}
	//发送提取密码邮件
	public function sendpass($uid, $username, $email, $code){
		$emailtpl=D('Emailtpl')->where(array('key'=>'Getuserpass'))->find();//获取邮件模版
		$value=$emailtpl['value'];
		$webConf = D('Config')->getCfgByModule('WEBSITE');
		$config = json_decode($webConf['WEBSITE'], true);
		$url=$config['site_url'].U('Home/User/passwordset',array('uid'=>$uid,'code'=>$code));
		$value = str_replace('{$username}',$username,$value);
		$value = str_replace('{$url}',$url,$value);
		$result=$this->Send($email, $emailtpl['name'], $value);
		if ($result){
			return true;
		}else{
			return false;
		}		
		
	}
	//获取当前的短信模块是否开启还是关闭
	public function emailonoff($key){
		$emailtpl=D('Emailtpl')->where(array('key'=>$key))->find();
		if (!$emailtpl)return false;
		if ($emailtpl['issend'])return true;
		return false;
	}
	/**
	 * 邮件发送
	 * @param: $name[string]        接收人姓名
	 * @param: $email[string]       接收人邮件地址
	 * @param: $subject[string]     邮件标题
	 * @param: $content[string]     邮件内容
	 * @param: $type[int]           0 普通邮件， 1 HTML邮件
	 * @param: $notification[bool]  true 要求回执， false 不用回执
	 *
	 * @return boolean
	 */
	public function Send($email, $subject, $content, $type = 1, $notification=false){
		//获取当前邮件服务器的配置信息
		$webConf = D('Config')->getCfgByModule('WEBSITE');
		$config = json_decode($webConf['WEBSITE'], true);
		$cloud_name=$config['site_name'];
		$name='';//接收人姓名
		$mailConf = D('Config')->getCfgByModule('MAILSET');
		$ary_mailconf = json_decode($mailConf['MAILSET'], true);
	    if ($ary_mailconf['smtp_code'] != "utf-8")
	    {
	        $name      = cloud_iconv('utf-8', $ary_mailconf['smtp_code'], $name);
	        $subject   = cloud_iconv('utf-8', $ary_mailconf['smtp_code'], $subject);
	        $content   = cloud_iconv('utf-8', $ary_mailconf['smtp_code'], $content);
	        $cloud_name   = cloud_iconv('utf-8', $ary_mailconf['smtp_code'], $cloud_name);
	    }
 	   	$charset   =$ary_mailconf['smtp_code'];
 	   	if ($ary_mailconf['email_type']==1){
	 	    /* 邮件的头部信息 */
	        $content_type = ($type == 0) ?
	            'Content-Type: text/plain; charset=' . $charset : 'Content-Type: text/html; charset=' . $charset;
	        $content   =  base64_encode($content);
	        $headers = array();
	        $headers[] = 'Date: ' . gmdate('D, j M Y H:i:s') . ' +0000';
	        $headers[] = 'To: "' . '=?' . $charset . '?B?' . base64_encode($name) . '?=' . '" <' . $email. '>';
	        $headers[] = 'From: "' . '=?' . $charset . '?B?' . base64_encode($cloud_name) . '?='.'" <' . $ary_mailconf['mail_address'] . '>';
	        $headers[] = 'Subject: ' . '=?' . $charset . '?B?' . base64_encode($subject) . '?=';
	        $headers[] = $content_type . '; format=flowed';
	        $headers[] = 'Content-Transfer-Encoding: base64';
	        $headers[] = 'Content-Disposition: inline';
	 	   	if ($notification)
	        {
	            $headers[] = 'Disposition-Notification-To: ' . '=?' . $charset . '?B?' . base64_encode($shop_name) . '?='.'" <' . $GLOBALS['_CFG']['smtp_mail'] . '>';
	        }

	         /* 获得邮件服务器的参数设置 */
	        $params['host'] =$ary_mailconf['smtp'];
	        $params['port'] =$ary_mailconf['smtp_port'];
	        $params['user'] =$ary_mailconf['smtp_user'];
	        $params['pass'] =$ary_mailconf['smtp_pwd'];
	 	   	if (empty($params['host']) || empty($params['port']))
	        {
	            return false;
	        }else{
	        	
		        // 发送邮件
	            if (!function_exists('fsockopen'))
	            {
	                return false;
	            }
	            
	            $send_params['recipients'] = $email;
           		$send_params['headers']    = $headers;
           	 	$send_params['from']       =$ary_mailconf['mail_address'];
            	$send_params['body']       = $content;
	            $smtp=New Smtp($params);
		        if ($smtp->connect() && $smtp->send($send_params))
	            {
	                return true;
	            }else{
	               $err_msg = $smtp->error_msg();
	               return $err_msg;
	            }
	        }
 	   	}

	}
		
}