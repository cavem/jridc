<?php
class TextAction extends HomeAction{
	public function index(){
		$endresult=$this->selectcode('324324324324324');
		p($endresult);
	}	
	protected function selectcode($Code){
		$url="124.127.118.180:9080";
		$posturl="http://".$url."/icpv3.0/private/QueryServer";
		$account="test";
		$pwd="test";
		$serverCode=$Code;
		$randomString=randstr(20);
		$sign=md5($serverCode.$pwd.$randomString);
		$postdata=array(
			'serverCode'=>$serverCode,
			'randomString'=>$randomString,
			'sign'=>$sign,
			'account'=>$account
		);
		return  $this->postcurl($posturl,$postdata);
	}
	
	protected  function postcurl($posturl,$postdata){
		foreach($postdata as $key=>$value){
			$post.=$key.'='.urlencode($value)."&";
		}
		$post = trim($post, '&');
		echo $post;
	    $ch = curl_init($posturl);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$resp = curl_exec($ch);
		$error = curl_error($ch);
		if($error){
			echo $error;exit;
		}
		curl_close($ch);
		return $resp;
	}
}
?>