<?php
header("Content-type: text/html; charset=utf-8");
require_once ROOT_PATH."ThinkPHP/Extend/Vendor/Wxpay/WxPayApi.php";
require_once ROOT_PATH.'ThinkPHP/Extend/Vendor/Wxpay/WxPayNotify.php';
class WepayAction extends Action{
	public function notify_url(){
		$notify = new PayNotifyCallBack();
		$notify->Handle(false);
	}
}
class PayNotifyCallBack extends WxPayNotify
{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}
	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		Log::write("call back:" . json_encode($data));
		$notfiyOutput = array();
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}
		$wechat_order=M('wechat_order')->where(array('order_sn'=>$data["out_trade_no"]))->find();
		if(empty($wechat_order)){
			$msg = "订单查询失败01";
			return false;
		}
		if($wechat_order['status']==1){
			$msg = "订单状态错误";
			return false;
		}else{
			$wechatorder=M('wechat_order')->where(array('order_sn'=>$data["out_trade_no"]))->save(array('status'=>1));
			//入账
		   $user_id=$wechat_order['user_id'];
		   $money=$wechat_order['money'];
		   $forwhat="微信在线充值(交易号:".$data["out_trade_no"].")";
		   $whichProduct="";
		   $pid=0;
		   $type=1;
		   $pingzheng="";
		   $acspace="微信充值";
		   $isadd=1;
		   $ptype="";//产品类型
		   $orderid="";//交易号或者订单号
		   $paddtime=time();
		   $endtime=time();
		   D('User')->addmoney($user_id,$money,$forwhat,$whichProduct,$pid,$type,$pingzheng,$acspace,$isadd,$ptype,$orderid,$paddtime,$endtime);
		}
		return true;
	}
}
?>