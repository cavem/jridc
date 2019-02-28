<?php
//用户组阶梯定价计算折扣后费用2015-12-25
function user_jieti_per($price_str,$str_num) {
		$price_str_arr = explode('|',$price_str);
		$arr=array();
		foreach($price_str_arr as $key=>$value){
			$value_arr=explode('<=',$value); //创建数组
			$arr[$value_arr[0]]=$value_arr[1]*$str_num;
		}
		$arr_new=array_filter($arr);
		$endstr="";
		foreach($arr_new as $key=>$value) {
			if (empty($endstr)){
				$endstr=$key.'<='.$value;
			}else{
				$endstr=$endstr.'|'.$key.'<='.$value;
			}		
		}	
		return $endstr;
}
function p($data,$isdie = 0){
	echo "<pre>";
	header("Content-type: text/html; charset=utf-8"); 
	print_r($data);
	echo "<pre>";
	if($isdie)die();
}
//修复名字格式错误2016-3-24
function checkusername($username){
	if(preg_match("/^[0-9a-zA-Z_]+$/",$username))
	{
		if (strlen($username)>15 or strlen($username)<4){
			return false;
		}
		return true;
	}else{
		return false;
	}
}
//验证邮箱
function checkemail($email){
	if(preg_match("/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/",$email))
	{
		return true;
	}else{
		return false;
	}
}
function checkqq($qq){
	if(preg_match("/^\d{5,12}$/",$qq))
	{
		return true;
	}else{
		return false;
	}

}
function checkmobi($mobi){
	if(preg_match("/^(((13[0-9]{1})|(14[0-9]{1})|(17[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/",$mobi))
	{
		if (strlen($mobi)<>11){
		return false;
		}
		return true;
	}else{
		return false;
	}
}

//微信处理
function is_weixin()
{ 
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
        return true;
    }  
        return false;
}
function getLastSql(){
	echo M()->getLastSql();
}
function checkcloud($aid){	
		$map['id'] = array("eq",$aid);
		$map['starttime'] = array("lt",time());
		$map['endtime'] = array("gt",time());
		$cloud_activity=D('cloud_activity')->where($map)->find();
		if (empty($cloud_activity)){			
				return array('status'=>false,'value'=>'活动已结束或不存在');
		}else{
			$num=$cloud_activity['number'];
			$endnum=D('cloud_activity_value')->where(array('aid'=>$cloud_activity['id']))->count();
			if ($endnum<$num){
				return array('status'=>true,'value'=>'');
			}else{
				return array('status'=>false,'value'=>'已超出活动数量限制');
				
			}
		}
	}
	//验证活动配置信息和订单信息是否符合
 function checkapeizhi($aid,$rid){
		$map['id'] = array("eq",$aid);
		$map['starttime'] = array("lt",time());
		$map['endtime'] = array("gt",time());
		$cloudactivity=D('cloud_activity')->where($map)->find();
		if (empty($cloudactivity)){			
				return array('status'=>false,'value'=>'活动已结束或不存在');
		}else{
			$order=D('order')->where(array('id'=>$rid))->find();
			if ($order['cpunum']<>$cloudactivity['cpu']){
				
				return array('status'=>false,'value'=>'活动已更改请从新提交订单');
			}
			if ($order['memnum']<>$cloudactivity['mem']*1024){
				
				return array('status'=>false,'value'=>'活动已更改请从新提交订单');
			}
			if ($order['disknum']<>$cloudactivity['disk']){
				
				return array('status'=>false,'value'=>'活动已更改请从新提交订单');
			}
			if ($order['qosnum']<>$cloudactivity['qos']){
				
				return array('status'=>false,'value'=>'活动已更改请从新提交订单');
			}
			if ($order['dlip']<>$cloudactivity['iptype']){
				return array('status'=>false,'value'=>'活动已更改请从新提交订单');
			}
			if ($order['usermoney']<>$cloudactivity['money']){
				return array('status'=>false,'value'=>'活动已更改请从新提交订单');
			}
			$product=D('cloud_product')->where(array('Cloudtype'=>$cloudactivity["Cloudtype"],'status'=>1))->field()->find();
			if ($cloudactivity['cycletext']=="PAY_Month")$year=$product['PAY_Month'];
			if ($cloudactivity['cycletext']=="PAY_Season")$year=$product['PAY_Season'];
			if ($cloudactivity['cycletext']=="PAY_halfyear")$year=$product['PAY_halfyear'];
			if ($cloudactivity['cycletext']=="PAY_1year")$year=$product['PAY_Nextyear'];
			if ($cloudactivity['cycletext']=="PAY_2year")$year=$product['PAY_2year'];
			if ($cloudactivity['cycletext']=="PAY_3year")$year=$product['PAY_3year'];
			if ($cloudactivity['cycletext']=="PAY_4year")$year=$product['PAY_4year'];
			if ($cloudactivity['cycletext']=="PAY_5year")$year=$product['PAY_5year'];
			if ($order['year']<>$year){
				return array('status'=>false,'value'=>'活动已更改请从新提交订单');
			}
			return array('status'=>true,'value'=>'');
		}
	}
function getordcode(){
	 	mt_srand((double) microtime() * 1000000);
	 	$ordcode=date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
	    $Ord=M('order');
	    $oldcode=$Ord->where("ordernumber='".$ordcode."'")->getField('ordernumber');
	    if($oldcode){
	     	   $this->getordcode();
	    }else{
	        return $ordcode;
	    }
}
/**
 * 获取客户端IP地址
 */
function getClientIP() {
	static $ip = NULL;
	if ( $ip !== NULL )
	return $ip;
	if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$arr = explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] );
		$pos = array_search( 'unknown', $arr );
		if ( false !== $pos )
		unset( $arr[$pos] );
		$ip = trim( $arr[0] );
	} elseif ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	// IP地址合法验证
	$ip = ( false !== ip2long( $ip ) ) ? $ip : '0.0.0.0';
	return $ip;
}

/**
 * 二维数组排序 根据键值
 * Enter description here ...
 * @param unknown_type $arr
 * @param unknown_type $keys
 * @param unknown_type $type
 */
function array_sort($arr,$keys,$type='asc'){ 
	 $keysvalue = $new_array = array();
	 foreach ($arr as $k=>$v){
	 	 $keysvalue[$k] = $v[$keys];
	 }
	 if($type == 'asc'){
	  	asort($keysvalue);
	 }else{
	 	arsort($keysvalue);
	 }
	 reset($keysvalue);
	 foreach ($keysvalue as $k=>$v){
	 	 $new_array[] = $arr[$k];
	 }
	 return $new_array; 
}
/**
 * 时间转换
 * Enter description here ...
 * @param unknown_type $noword
 * @param unknown_type $content
 */
function convert_datefm($date,$format=1,$separator="-")
{
	if ($format=="2")
	{
		return date("Y-m-d H:i:s", $date);
	}
	else
	{
		$datenew=$date;
		$datey=date('Y',strtotime($datenew));
		$datem=date('m',strtotime($datenew));
		$dated=date('d',strtotime($datenew));
		$dateH=date('H',strtotime($datenew));
		$datei=date('i',strtotime($datenew));
		$dates=date('s',strtotime($datenew));
		return mktime($dateH,$datei,$dates,$datem,$dated,$datey);
	}
}
/**
 * 时间差
 * Enter description here ...
 * @param unknown_type $a
 * @param unknown_type $b
 */
function count_days($a,$b){
	$a_dt=getdate($a);
	$b_dt=getdate($b);
	$a_new=mktime(12,0,0,$a_dt['mon'],$a_dt['mday'],$a_dt['year']);
	$b_new=mktime(12,0,0,$b_dt['mon'],$b_dt['mday'],$b_dt['year']);
	return round(($a_new-$b_new)/86400);
}
//获取子级ID
function GetSonIds($arr,$id,$addthis=true)
	{
	    $GLOBALS['idArray'] = array();
	    GetSonIdsLogic($id,$arr,$addthis);
	    $rquery = join(',',$GLOBALS['idArray']);
	    $rquery = preg_replace("/,$/", '', $rquery); 
	    return $rquery;
	}
	//递归逻辑
function GetSonIdsLogic($id,$sArr,$addthis=false)
	{
	    if($id!=0 && $addthis)
	    {
	        $GLOBALS['idArray'][$id] = $id;
	    }
	    if(is_array($sArr))
	    {
	        foreach($sArr as $k=>$v)
	        {
	            if( $v['pid']==$id)
	            {
	                 GetSonIdsLogic($v['id'],$sArr,true);
	            }
	        }
	    }
	}

/**
 * 创建目录
 * Enter description here ...
 */
function make_dir($path)
{
	if(!file_exists($path))
	{
		make_dir(dirname($path));
		@mkdir($path,0777);
		@chmod($path,0777);
	}
}
/**
 * 生成随机数
 * Enter description here ...
 * @param $length
 */
function randstr($length=6,$type=1)
{   
	$hash='';
	if ($type==1){
		$chars= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
	}else{
		$chars= '1234567890';
	}
	$max=strlen($chars)-1;   
	if(phpversion()<5.3){
		mt_srand((double)microtime()*1000000); 
	} 
	for($i=0;$i<$length;$i++){   
		$hash.=$chars[mt_rand(0,$max)];   
	}   
	return $hash;   
}
//获取客服端IP
function getip()
{
	if (getenv('HTTP_CLIENT_IP') and strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown')) {
		$onlineip=getenv('HTTP_CLIENT_IP');
	}elseif (getenv('HTTP_X_FORWARDED_FOR') and strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),'unknown')) {
		$onlineip=getenv('HTTP_X_FORWARDED_FOR');
	}elseif (getenv('REMOTE_ADDR') and strcasecmp(getenv('REMOTE_ADDR'),'unknown')) {
		$onlineip=getenv('REMOTE_ADDR');
	}elseif (isset($_SERVER['REMOTE_ADDR']) and $_SERVER['REMOTE_ADDR'] and strcasecmp($_SERVER['REMOTE_ADDR'],'unknown')) {
		$onlineip=$_SERVER['REMOTE_ADDR'];
	}
	preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/",$onlineip,$match);
	return $onlineip = $match[0] ? $match[0] : 'unknown';
}
function cloud_iconv($source_lang, $target_lang, $source_string = '')
{
    static $chs = NULL;
    /* 如果字符串为空或者字符串不需要转换，直接返回 */
    if ($source_lang == $target_lang || $source_string == '' || preg_match("/[\x80-\xFF]+/", $source_string) == 0)
    {
        return $source_string;
    }
    if ($chs === NULL)
    {
        $chs = new Chinese();
    }
    return $chs->Convert($source_lang, $target_lang, $source_string);
}
/**
 * 简单对称加密算法之加密
 * @param String $string 需要加密的字串
 * @param String $skey 加密EKY
 * @author Anyon Zou <zoujingli@qq.com>
 * @date 2013-08-13 19:30
 * @update 2014-10-10 10:10
 * @return String
 */
 function encode($string = '', $skey = 'Cloudgw') {
    $strArr = str_split(base64_encode($string));
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key < $strCount && $strArr[$key].=$value;
    return str_replace(array('=', '+', '/'), array('O0O0O', 'o000o', 'oo00o'), join('', $strArr));
 }
 /**
 * 简单对称加密算法之解密
 * @param String $string 需要解密的字串
 * @param String $skey 解密KEY
 * @author Anyon Zou <zoujingli@qq.com>
 * @date 2013-08-13 19:30
 * @update 2014-10-10 10:10
 * @return String
 */
 function decode($string = '', $skey = 'Cloudgw') {
    $strArr = str_split(str_replace(array('O0O0O', 'o000o', 'oo00o'), array('=', '+', '/'), $string), 2);
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key <= $strCount && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
    return base64_decode(join('', $strArr));
 }