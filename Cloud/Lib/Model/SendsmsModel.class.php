<?php
/**
 * 发送短信模型
 * Enter description here ...
 * @author Geyoulei
 */
class SendsmsModel extends Model{
	protected $tableName = 'sms_tpl';
	//发送云主机到期提醒
	public function Senddiskexpire($username,$cloudname,$endtime){
		$config=$this->smsconfig('Diskexpire');//获取短信模板的配置信息
		if (!$config)return false;
		if (!$config['issend'])return false;
		$value=$config['value'];//获取短信内容
		$value = str_replace('{$username}',$username,$value);
		$value = str_replace('{$diskname}',$cloudname,$value);
		$value = str_replace('{$endtime}',convert_datefm($endtime,2),$value);
		$User=D('User')->where(array('username'=>$username))->find();
		if (empty($User))return false;
		if (empty($User['mobi']))return false;
		$mobile=$User['mobi'];
		$config['mobile']=$mobile;
		$config['content']=$value;
		$Sendobj=New $config['sms_key']();
		$result=$Sendobj->Sendsms($config);
		//写入系统日志
		D('Systemlog')->adddata($username,'手机号'.$mobile.'内容:'.$value,2);
	}
	//发送云硬盘续费信息
	public function Senddiskrepay($username,$money,$cloudname,$starttime,$endtime){
		$config=$this->smsconfig('Diskrepay');//获取短信模板的配置信息
		if (!$config)return false;
		if (!$config['issend'])return false;
		$value=$config['value'];//获取短信内容
		$value = str_replace('{$username}',$username,$value);
		$value = str_replace('{$money}',$money,$value);
		$value = str_replace('{$diskname}',$cloudname,$value);
		$value = str_replace('{$oldtime}',convert_datefm($starttime,2),$value);
		$value = str_replace('{$newtime}',convert_datefm($endtime,2),$value);
		$User=D('User')->where(array('username'=>$username))->find();
		if (empty($User))return false;
		if (empty($User['mobi']))return false;
		$mobile=$User['mobi'];
		$config['mobile']=$mobile;
		$config['content']=$value;
		$Sendobj=New $config['sms_key']();
		$result=$Sendobj->Sendsms($config);
		//写入系统日志
		D('Systemlog')->adddata($username,'手机号'.$mobile.'内容:'.$value);
	}
	//发送云硬盘开通信息
	public function Senddiskopen($username,$money,$cloudname,$starttime,$endtime){
		$config=$this->smsconfig('Diskopen');//获取短信模板的配置信息
		if (!$config)return false;
		if (!$config['issend'])return false;
		
		$value=$config['value'];//获取短信内容
		$value = str_replace('{$username}',$username,$value);
		$value = str_replace('{$money}',$money,$value);
		$value = str_replace('{$diskname}',$cloudname,$value);
		$value = str_replace('{$starttime}',convert_datefm($starttime,2),$value);
		$value = str_replace('{$endtime}',convert_datefm($endtime,2),$value);
		$User=D('User')->where(array('username'=>$username))->find();
		if (empty($User))return false;
		if (empty($User['mobi']))return false;
		$mobile=$User['mobi'];
		$config['mobile']=$mobile;
		$config['content']=$value;
		$Sendobj=New $config['sms_key']();
		$result=$Sendobj->Sendsms($config);
		//写入系统日志
		D('Systemlog')->adddata($username,'手机号'.$mobile.'内容:'.$value);
	}
	//发送负载均衡到期提醒
	public function Sendloadbexpire($username,$cloudname,$endtime){
		$config=$this->smsconfig('Loadbexpire');//获取短信模板的配置信息
		if (!$config)return false;
		if (!$config['issend'])return false;
		$value=$config['value'];//获取短信内容
		$value = str_replace('{$username}',$username,$value);
		$value = str_replace('{$cloudname}',$cloudname,$value);
		$value = str_replace('{$endtime}',convert_datefm($endtime,2),$value);
		$User=D('User')->where(array('username'=>$username))->find();
		if (empty($User))return false;
		if (empty($User['mobi']))return false;
		$mobile=$User['mobi'];
		$config['mobile']=$mobile;
		$config['content']=$value;
		$Sendobj=New $config['sms_key']();
		$result=$Sendobj->Sendsms($config);
		//写入系统日志
		D('Systemlog')->adddata($username,'手机号'.$mobile.'内容:'.$value,2);
	}
	//发送云主机到期提醒
	public function Sendcloudexpire($username,$cloudname,$endtime){
		$config=$this->smsconfig('Cloudexpire');//获取短信模板的配置信息
		if (!$config)return false;
		if (!$config['issend'])return false;
		$value=$config['value'];//获取短信内容
		$value = str_replace('{$username}',$username,$value);
		$value = str_replace('{$cloudname}',$cloudname,$value);
		$value = str_replace('{$endtime}',convert_datefm($endtime,2),$value);
		$User=D('User')->where(array('username'=>$username))->find();
		if (empty($User))return false;
		if (empty($User['mobi']))return false;
		$mobile=$User['mobi'];
		$config['mobile']=$mobile;
		$config['content']=$value;
		$Sendobj=New $config['sms_key']();
		$result=$Sendobj->Sendsms($config);
		//写入系统日志
		D('Systemlog')->adddata($username,'手机号'.$mobile.'内容:'.$value,2);
	}
	//发送云主机续费信息
	public function Sendcloudrepay($username,$money,$cloudname,$starttime,$endtime){
		$config=$this->smsconfig('Cloudrepay');//获取短信模板的配置信息
		if (!$config)return false;
		if (!$config['issend'])return false;
		$value=$config['value'];//获取短信内容
		$value = str_replace('{$username}',$username,$value);
		$value = str_replace('{$money}',$money,$value);
		$value = str_replace('{$cloudname}',$cloudname,$value);
		$value = str_replace('{$oldtime}',convert_datefm($starttime,2),$value);
		$value = str_replace('{$newtime}',convert_datefm($endtime,2),$value);
		$User=D('User')->where(array('username'=>$username))->find();
		if (empty($User))return false;
		if (empty($User['mobi']))return false;
		$mobile=$User['mobi'];
		$config['mobile']=$mobile;
		$config['content']=$value;
		$Sendobj=New $config['sms_key']();
		$result=$Sendobj->Sendsms($config);
		//写入系统日志
		D('Systemlog')->adddata($username,'手机号'.$mobile.'内容:'.$value);
	}
	//发送云主机开通信息
	public function Sendcloudopen($username,$money,$cloudname,$starttime,$endtime){
		$config=$this->smsconfig('Cloudopen');//获取短信模板的配置信息
		if (!$config)return false;
		if (!$config['issend'])return false;
		
		$value=$config['value'];//获取短信内容
		$value = str_replace('{$username}',$username,$value);
		$value = str_replace('{$money}',$money,$value);
		$value = str_replace('{$cloudname}',$cloudname,$value);
		$value = str_replace('{$starttime}',convert_datefm($starttime,2),$value);
		$value = str_replace('{$endtime}',convert_datefm($endtime,2),$value);
		$User=D('User')->where(array('username'=>$username))->find();
		if (empty($User))return false;
		if (empty($User['mobi']))return false;
		$mobile=$User['mobi'];
		$config['mobile']=$mobile;
		$config['content']=$value;
		$Sendobj=New $config['sms_key']();
		$result=$Sendobj->Sendsms($config);
		//写入系统日志
		D('Systemlog')->adddata($username,'手机号'.$mobile.'内容:'.$value);
	}
	//发送负载均衡开通信息
	public function Sendloadbopen($username,$money,$cloudname,$starttime,$endtime){
		$config=$this->smsconfig('Loadbopen');//获取短信模板的配置信息
		if (!$config)return false;
		if (!$config['issend'])return false;
		
		$value=$config['value'];//获取短信内容
		$value = str_replace('{$username}',$username,$value);
		$value = str_replace('{$money}',$money,$value);
		$value = str_replace('{$cloudname}',$cloudname,$value);
		$value = str_replace('{$starttime}',convert_datefm($starttime,2),$value);
		$value = str_replace('{$endtime}',convert_datefm($endtime,2),$value);
		$User=D('User')->where(array('username'=>$username))->find();
		if (empty($User))return false;
		if (empty($User['mobi']))return false;
		$mobile=$User['mobi'];
		$config['mobile']=$mobile;
		$config['content']=$value;
		$Sendobj=New $config['sms_key']();
		$result=$Sendobj->Sendsms($config);
		//写入系统日志
		D('Systemlog')->adddata($username,'手机号'.$mobile.'内容:'.$value);
	}
	//发送验证码短信验证码
	public function sendcode($mobile,$scode="code"){
		$config=$this->smsconfig('Registercode');//获取短信模板的配置信息
		if (!$config)return false;
		$value=$config['value'];//获取短信内容
		$randnumber=randstr(6,2);//生成随机数
		$value = str_replace('{$Code}',$randnumber,$value);
		$config['mobile']=$mobile;
		$config['content']=$value;
		$Sendobj=New $config['sms_key']();
		$result=$Sendobj->Sendsms($config);
		//写入系统日志
		D('Systemlog')->adddata($mobile,'手机号'.$mobile.'内容:'.$value);
		if ($result){
			$_SESSION[$scode]=$randnumber;//code存入到Session
			return true;
		}else{
			return false;
		}
	}
	/**
	 * 发送短信
	 * Enter description here ...
	 * @param 手机号码 $mobile
	 * @param 需要发送的短信模板 $key
	 */
	public function Send($mobile,$content){
		$config=$this->smscfg();
		$config['mobile']=$mobile;
		$config['content']=$content;	
		$Sendobj=New $config['sms_key']();
		$result=$Sendobj->Sendsms($config);
		//写入系统日志
		D('Systemlog')->adddata($username,'手机号'.$mobile.'内容:'.$value);
		return true;
	}
	//获取当前的短信模块是否开启还是关闭
	public function smsonoff($key){
		$Smstpl=D('Smstpl')->where(array('key'=>$key))->find();
		if (!$Smstpl)return false;
		if ($Smstpl['issend'])return true;
		return false;
	}
	//获取当前短信的配置信息和短信模板
	protected function smsconfig($key){
		$Sms=D('Sms')->where(array('sms_default'=>1))->find();
		if (!$Sms)return false;
		$Smstpl=D('Smstpl')->where(array('key'=>$key))->find();
		if (!$Smstpl)return false;
		return array_merge($Sms,$Smstpl);
	}
	protected function smscfg(){
		$Sms=D('Sms')->where(array('sms_default'=>1))->find();
		return $Sms;
	}
		
}