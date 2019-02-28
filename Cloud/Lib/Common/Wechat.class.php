<?php
class Wechat{
	public $appid;
	public $appsecret;
	public $testtext;
	public function _initialize(){
		$this->testtext=123;
	}
	//接入验证
	public function valid()
	{
		$echoStr = $_GET["echostr"];   
		if($this->checkSignature()){
			echo $echoStr;
			exit;
		}
	}
	private function checkSignature()
	{
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
	//处理事件推送
	public function responseMsg(){
		$wechatmsg = new Wechatmsg();
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		if (empty($postStr))exit();
		$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
		//处理粉丝
		$this->fansOpt($postObj);
		$this->userOpt($postObj);
		//消息处理
		switch(trim($postObj->MsgType)){//消息类型
			case "text"://用户发送文字消息	执行文字检索自动回复内容
				$resultStr = $wechatmsg->msgs($postObj->Content,$postObj->ToUserName,$postObj->FromUserName);
				break;
			case "event"://事件消息 关注、扫码、点击菜单等
				$resultStr = $wechatmsg->handleEvent($postObj);
				break;
			default:
				$resultStr = "Unknow msg type: ".trim($postObj->MsgType);
				break;
		}
		echo $resultStr;
	}
	//处理用户
	public function userOpt($postObj){
		$open_id = $postObj->FromUserName;
		$wechat_id = $postObj->ToUserName;
		$m_user = M("weixin_user");
		$user = $m_user->where("wxid = '".$open_id."' and wechat_id = '".$wechat_id."'")->find();
		if(!$user){//如果用户是首次关注
			//获取微信用户基本信息
			$weixin_config = M("weixin_config")->where('id = 1')->find();
			$access_token = $this->get_access_token($weixin_config['appid'],$weixin_config['appsecret']);
			$wechat_info = $this->getWechatUserInfo($open_id,$access_token);
			$nickname = $wechat_info['nickname'];
			$data_user = array(
					"wechat_id"	=>	"$wechat_id",
					"wxid"	=>	"$open_id",
					"nickname"	=>	"$nickname",
					"subscribe"	=>	0,
					"upd_time"	=>	time(),
					"add_time"	=>	time()
				);
			$m_user->add($data_user);
		}else{
			$data_user = array(
					"upd_time"	=>	time()
				);
			$m_user->where("wxid = '".$open_id."' and wechat_id = '".$wechat_id."'")->save($data_user);
		}
	}
	//处理粉丝
	public function fansOpt($postObj){
		$open_id = $postObj->FromUserName;
		$wechat_id = $postObj->ToUserName;
		$m_fans = M("weixin_fans");
		$fans = $m_fans->where("open_id = '".$open_id."'")->find();
		if(!$fans){
			//获取微信用户基本信息
			$weixin_config = M("weixin_config")->where('id = 1')->find();
			$access_token = $this->get_access_token($weixin_config['appid'],$weixin_config['appsecret']);
			$wechat_info = $this->getWechatUserInfo($open_id,$access_token);
			$nickname = $wechat_info['nickname'];
			$data_fans = array(
					"wechat_id"	=>	"$wechat_id",
					"nickname"	=>	"$nickname",
					"open_id"	=>	"$open_id",
					"subscribe"	=>	0,
					"upd_time"	=>	time(),
					"add_time"	=>	time()
				);
			$m_fans->add($data_fans);
		}else{
			$data_fans = array(
					"upd_time"	=>	time()
				);
			$m_fans->where("open_id = '".$open_id."'")->save($data_fans);
		}
	}
	//获取用户基本信息
	public function getWechatUserInfo($open_id,$access_token){
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$open_id";
		return $this->http_post_json($url);
	}
	//创建自定义菜单
	public function createMenu($ACCESS_TOKEN,$data){
		$url="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$ACCESS_TOKEN;
		return $this->http_post_json($url,preg_replace("#\\\u([0-9a-f]+)#ie", "iconv('UCS-2', 'UTF-8', pack('H4', '\\1'))",$data));
	}
	//发送模版消息测试接口
	public function sendMsg($data = array()){
		$template_id = 'bZ6x24-NBQ7JTzsOz2ljCpEGell5sJxEn41uTYCIr1Y';
		$template_id = !empty($data['tempid'])?$data['tempid']:$template_id;
		$postdata = C($template_id);
		if(empty($postdata))return false;
		foreach ($postdata as $k=>$v){
			$postdata[$k]['value']=$data[$k];
		}
		$user = M("weixin_user")->where(array('uid'=>$data['user_id']))->find();
		$rs_send = $this->sendTempMsg($user['wxid'],$template_id,$postdata);
		return $rs_send;
	}
	//发送模版消息
	public function sendTempMsg($touser,$template_id,$postdata,$topclolor='#FF0000',$url='')
	{
		$post_data = array(
			'touser' => $touser,
			'template_id' =>$template_id,
			'data' =>$postdata,
			'topcolor' => '#666',
			'url' => $url,
		);
		$weixin_config = M("weixin_config")->where('id = 1')->find();
		$access_token = $this->get_access_token($weixin_config['appid'],$weixin_config['appsecret']);
		$qrcode_url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $access_token;
		$post = $this->http_post_json($qrcode_url, json_encode($post_data));
		return $post[errmsg];
	}
	//获取全局access_token 微信后台推送消息时使用
	public function get_access_token($appid,$secret){
		$wechat_config = M('weixin_config')->where(array('id'=>1))->find();
		$ss = time()-$wechat_config['at_addtime'];
		if($ss<=5400){//access_token有效时限为2小时，此处取1.5小时
			return $wechat_config['access_token'];
		}else{
			$url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;
			$json=$this->http_get_json($url);
			$data=json_decode($json,true);
			if($data['access_token']){
				return $data['access_token'];
			}else{
				return $data['expires_in']."access_token";
			}
		}
	}
	
	public  function http_get_json($url){	
	   $ch = curl_init();  
	   curl_setopt($ch, CURLOPT_URL,$url);  
	   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
	   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  
	   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
	   $result = curl_exec($ch);  
	   curl_close($ch);  
	   return $result;	
	}
	public function http_post_json($url,$data){
		 $ch = curl_init(); 
		 curl_setopt($ch, CURLOPT_URL,$url); 
		 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		 curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		 curl_setopt($ch, CURLOPT_AUTOREFERER, 1); 
		 curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		 $tmpInfo = curl_exec($ch); 
		 if (curl_errno($ch)) {  
			echo 'Errno'.curl_error($ch);
		 }
		 curl_close($ch); 
		 $arr= json_decode($tmpInfo,true);
		 return $arr;
	}
}