<?php
//网页授权验证
class OauthAction extends Action{
	public function index(){
		if(isset($_GET['code']))
		{
			$code= $_GET['code'];
			$to= !empty($_GET['to'])?$_GET['to']:'index';
			$id =!empty($_GET['id'])?$_GET['id']:'0';
			$wechat_info = M("weixin_config")->where(array('id'=>1))->find();
			if($wechat_info) {
				$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$wechat_info['appid']}&secret={$wechat_info['appsecret']}&code={$code}&grant_type=authorization_code";
				$ret = $this->curlPost_new($url);
				$retdata=json_decode($ret);
				if($retdata){
					$openid = $retdata->openid;
					$_SESSION['open_id'] = $retdata->openid;
					if(id){//领取优惠券详情时使用
						$u = U('/Wechat/Web/'.$to,array('id'=>$id));
					}else{
						$u = U('/Wechat/Web/'.$to);
					}
					$posturl="http://".$_SERVER['HTTP_HOST']."/".$u;
					header('location: '.$posturl);
					exit;
				}
			}
		}
	}
	function curlPost_new($url){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		if ($errorno){
			return array('rt' => false, 'errorno' => $errorno);
		}else{
			return $tmpInfo;
		}
	}
}