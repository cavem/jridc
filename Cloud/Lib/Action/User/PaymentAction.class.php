<?php
header('Content-type:text/html;charset=utf-8');
class PaymentAction extends Action{
    public function _initialize(){
    	vendor('Alipay.Corefunction');
        vendor('Alipay.Md5function');
        vendor('Alipay.Notify');
        vendor('Alipay.Submit');
    }
	//支付成功
    public function ok(){
   		echo "成功";
   		exit();
    }
    //支付失败
    public function fail(){
    	echo "失败";
    	exit();
    }
    public function doalipay(){
    	//创建订单信息
    	if (!$_SESSION['uid'])$this->error("用户不存在");
		$uid=$_SESSION['uid'];
		$user=M('user')->where(array('user_id'=>$uid))->find();
		if (!$user)$this->error("用户不存在");		
    	/**************************请求参数**************************/
    	$subject =$user['username']."在线充值(ID:".$user['user_id'].")";// $_POST['ordsubject']; //订单名称
        //支付类型
        $payment_type = "1";
        $webconfig=$this->webconfig();
        $alipay_config=$this->alipayconfig();
        $selleremail=$alipay_config['selleremail'];//支付宝帐号
        unset($alipay_config['selleremail']);
        $alipay_config['sign_type']=strtoupper('MD5');
        $alipay_config['input_charset']=strtolower('utf-8');
        $alipay_config['cacert']=getcwd().'\\cacert.pem';
        $alipay_config['transport']='http';
        //服务器异步通知页面路径
        $notify_url =$webconfig['site_url'].U('User/Payment/notifyurl');
        $return_url = $webconfig['site_url'].U('User/Payment/returnurl');     
        $seller_email = $selleremail; //卖家支付宝帐户
        $out_trade_no =$this->getordcode();   // $_POST['trade_no'];//必填      
        $total_fee = I('post.money',0,'');//必填  //付款金额
        $show_url = I('post.ordshow_url','');//   //商品展示地址
        $anti_phishing_key = "";
        $exter_invoke_ip =getip();
		$parameter = array(
				"service" => "create_direct_pay_by_user",
				"partner" => trim($alipay_config['partner']),
				"payment_type"	=> $payment_type,
				"notify_url"	=> $notify_url,
				"return_url"	=> $return_url,
				"seller_email"	=> $seller_email,
				"out_trade_no"	=> $out_trade_no,
				"subject"	=> $subject,
				"total_fee"	=> $total_fee,
				"body"	=> $body,
				"show_url"	=> $show_url,
				"anti_phishing_key"	=> $anti_phishing_key,
				"exter_invoke_ip"	=> $exter_invoke_ip,
				"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);
		$data=array(
			'user_id'=>$user['user_id'],
			'username'=>$user['username'],
			'ordid'=>$out_trade_no,
			'ordtime'=>time(),
			'ordtitle'=>$subject,
			'ordfee'=>$total_fee,
			'payment_type'=>$payment_type,
			);
		   $alipay_order=$this->addorderhandle($data);
			if ($alipay_order){
				//建立请求
				$alipaySubmit = new AlipaySubmit($alipay_config);
				$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认支付");
				echo $html_text;
			}else{
				$this->error("创建订单失败");
			}
    }
	/******************************
	        服务器异步通知页面方法
	        其实这里就是将notify_url.php文件中的代码复制过来进行处理
    *******************************/
    function notifyurl(){
         //这里还是通过C函数来读取配置项，赋值给$alipay_config
        $alipay_config=$this->alipayconfig();
        $selleremail=$alipay_config['selleremail'];//支付宝帐号
        unset($alipay_config['selleremail']);
        $alipay_config['sign_type']=strtoupper('MD5');
        $alipay_config['input_charset']=strtolower('utf-8');
        $alipay_config['cacert']=getcwd().'\\cacert.pem';
        $alipay_config['transport']='http';
        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        if($verify_result) {
            //验证成功
           //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
           $out_trade_no   = I('post.out_trade_no','');      //商户订单号
           $trade_no       = I('post.trade_no','');          //支付宝交易号
           $trade_status   = I('post.trade_status','');      //交易状态
           $total_fee      = I('post.total_fee','');         //交易金额
           $notify_id      = I('post.notify_id','');         //通知校验ID。
           $notify_time    = I('post.notify_time','');       //通知的发送时间。格式为yyyy-MM-dd HH:mm:ss。
           $buyer_email    = I('post.buyer_email','');       //买家支付宝帐号；
             $parameter = array(
             "out_trade_no"     => $out_trade_no, //商户订单编号；
             "trade_no"     => $trade_no,     //支付宝交易号；
             "total_fee"     => $total_fee,    //交易金额；
             "trade_status"     => $trade_status, //交易状态
             "notify_id"     => $notify_id,    //通知校验ID。
             "notify_time"   => $notify_time,  //通知的发送时间。
             "buyer_email"   => $buyer_email,  //买家支付宝帐号；
           );
           if(I('post.trade_status','') == 'TRADE_FINISHED') {
           }else if (I('post.trade_status','') == 'TRADE_SUCCESS') {                        
           	   if(!$this->checkorderstatus($out_trade_no)){
         	     	 //获取订单新信息里面的客户信息 处理
         	     	 $alipay_order=M('alipay_order')->where(array('ordid'=>$out_trade_no))->find();
         	     	 if ($alipay_order){
		         	   $user_id=$alipay_order['user_id'];
		         	   $money=$alipay_order['ordfee'];
		         	   $forwhat="在线充值(支付宝交易号:".$trade_no.")";
		         	   $whichProduct="";
		         	   $pid=0;
		         	   $type=1;
		         	   $pingzheng="";
		         	   $acspace="用户区";
		         	   $isadd=1;
		         	   $ptype="";//产品类型
		         	   $orderid="";//交易号或者订单号
		         	   $paddtime=time();
		         	   $endtime=time();
             		   D('User')->addmoney($user_id,$money,$forwhat,$whichProduct,$pid,$type,$pingzheng,$acspace,$isadd,$ptype,$orderid,$paddtime,$endtime);
         	     	}
         	     	  //获取订单新信息里面的客户信息 结束
	         	    $this->orderhandle($parameter); 
               }
            }
                echo "success";        //请不要修改或删除
         }else {
                //验证失败
                echo "fail";
        }    
    }
   		 /*
   	   	 页面跳转处理方法；
       	这里其实就是将return_url.php这个文件中的代码复制过来，进行处理； 
        */
    function returnurl(){
       //头部的处理跟上面两个方法一样，这里不罗嗦了！
        $alipay_config=$this->alipayconfig();
        $selleremail=$alipay_config['selleremail'];//支付宝帐号
        unset($alipay_config['selleremail']);
        $alipay_config['sign_type']=strtoupper('MD5');
        $alipay_config['input_charset']=strtolower('utf-8');
        $alipay_config['cacert']=getcwd().'\\cacert.pem';
        $alipay_config['transport']='http';
        $alipayNotify = new AlipayNotify($alipay_config);//计算得出通知验证结果
        $verify_result = $alipayNotify->verifyReturn();
        if($verify_result) {
	        //验证成功
	       //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
	        $out_trade_no   = I('get.out_trade_no','');      //商户订单号
	        $trade_no       = I('get.trade_no','');          //支付宝交易号
	        $trade_status   = I('get.trade_status','');      //交易状态
	        $total_fee      = I('get.total_fee','');         //交易金额
	        $notify_id      = I('get.notify_id','');         //通知校验ID。
	        $notify_time    = I('get.notify_time','');       //通知的发送时间。
	        $buyer_email    = I('get.buyer_email','');       //买家支付宝帐号；
	        $parameter = array(
	            "out_trade_no"     => $out_trade_no,      //商户订单编号；
	            "trade_no"     => $trade_no,          //支付宝交易号；
	            "total_fee"      => $total_fee,         //交易金额；
	            "trade_status"     => $trade_status,      //交易状态
	            "notify_id"      => $notify_id,         //通知校验ID。
	            "notify_time"    => $notify_time,       //通知的发送时间。
	            "buyer_email"    => $buyer_email,       //买家支付宝帐号
        );
		
		 if(I('get.trade_status','') == 'TRADE_FINISHED' || I('get.trade_status','') == 'TRADE_SUCCESS') {
		        if(!$this->checkorderstatus($out_trade_no)){
		        	 //获取订单新信息里面的客户信息 处理
         	     	 $alipay_order=M('alipay_order')->where(array('ordid'=>$out_trade_no))->find();
         	     	 if ($alipay_order){
               		   $user_id=$alipay_order['user_id'];
		         	   $money=$alipay_order['ordfee'];
		         	   $forwhat="在线充值(支付宝交易号:".$trade_no.")";
		         	   $whichProduct="";
		         	   $pid=0;
		         	   $type=1;
		         	   $pingzheng="";
		         	   $acspace="用户区";
		         	   $isadd=1;
             		   $ptype="";//产品类型
		         	   $orderid="";//交易号或者订单号
             		   $paddtime=time();//产品开通时间
		         	   $endtime=time(); //产品到期时间
             		   D('User')->addmoney($user_id,$money,$forwhat,$whichProduct,$pid,$type,$pingzheng,$acspace,$isadd,$ptype,$orderid,$paddtime,$endtime);
         	     	}
         	     	  //获取订单新信息里面的客户信息 结束
		             $this->orderhandle($parameter);  //进行订单处理，并传送从支付宝返回的参数；
		    }
		        $this->redirect('User/Payment/ok');//跳转到配置项中配置的支付成功页面；
		    }else {
		        echo "trade_status=".I('get.trade_status','');
		        $this->redirect('User/Payment/fail');//跳转到配置项中配置的支付失败页面；
		    }
		 }else {
		    //验证失败
		    //如要调试，请看alipay_notify.php页面的verifyReturn函数
		   	 echo "支付失败！";
		 }
 	}
 	/**
 	 * 获取商务配置信息
 	 * Enter description here ...
 	 */
    protected function webconfig(){
    	$webConf = D('Config')->getCfgByModule('WEBSITE');
        return json_decode($webConf['WEBSITE'], true);
    }
    /**
     * 获取支付宝配置信息
     * Enter description here ...
     */
    protected function alipayconfig(){
    	$AlipayConf = D('Config')->getCfgByModule('Alipay');
    	return json_decode($AlipayConf['Alipay'], true);
    	
    }
    /**
	 * 生成不重复订单号
	 * Enter description here ...
	 */
 	protected function getordcode(){
	 	mt_srand((double) microtime() * 1000000);
	 	$ordcode=date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
	    $Ord=M('alipay_order');
	    $oldcode=$Ord->where("ordid='".$ordcode."'")->getField('ordid');
	    if($oldcode){
	     	   $this->getordcode();
	    }else{
	        return $ordcode;
	    }
	 }
	 /**
	  * 获取订单号状态
	  * Enter description here ...
	  * @param unknown_type $ordid
	  */
 	protected function checkorderstatus($ordid){
	    $Ord=M('alipay_order');
	    $ordstatus=$Ord->where('ordid='.$ordid)->getField('ordstatus');
	    if($ordstatus==1){
	        return true;
	    }else{
	        return false;    
	    }
	 }
	/**
	 * 添加订单修改
	 * Enter description here ...
	 * @param $parameter
	 */
	protected function orderhandle($parameter){
	    $ordid=$parameter['out_trade_no'];
	    $data['payment_trade_no']      =$parameter['trade_no'];
	    $data['payment_trade_status']  =$parameter['trade_status'];
	    $data['payment_notify_id']     =$parameter['notify_id'];
	    $data['payment_notify_time']   =$parameter['notify_time'];
	    $data['payment_buyer_email']   =$parameter['buyer_email'];
	    $data['ordstatus']             =1;
	    $Ord=M('alipay_order');
	    $Ord->where('ordid='.$ordid)->save($data);
	 } 
	 /**
	  * 添加订单
	  * Enter description here ...
	  * @param unknown_type $parameter
	  */
	 protected function addorderhandle($data){
	 	$alipay_order=M('alipay_order')->add($data);
	 	return $alipay_order;
	 } 
}
?>