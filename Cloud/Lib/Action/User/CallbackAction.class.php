<?php
class CallbackAction extends Action{
	public function index(){
		echo "index";
		exit;
	}
	public function docall(){
		if(!IS_POST){
			echo 0;
			exit();
		}
		$json=json_encode($_POST);
		Log::write($json,"Cloud_Info");
		$orderid=$_POST['aorderid'];
		$status=$_POST['status'];
		$task_id=$_POST['task_id'];
		$value=$_POST['value'];
		$vm_id=$_POST['vm_id'];
		$info=$_POST['info'];
		$order=D('order')->where(array('ordernumber'=>$orderid))->find();
		if (empty($order)){
			echo 0;
			exit();
		}
		if (empty($order['pid'])){
			echo 0;
			exit();
		}
		$cloud=D('cloud')->where(array('id'=>$order['pid']))->find();
		if (!empty($cloud['vm_id'])){
			echo 0;
			exit();
		}
		if ($cloud['status']=='正常'){
			echo 0;
			exit();
		}
		if ($cloud['status']=='已删除'){
			echo 0;
			exit();
		}
		if ($status!='success'){
			//修改订单信息和云主机信息为失败
			D('order')->where(array('ordernumber'=>$orderid))->save(array('status'=>2,'statusinfo'=>$info));
			D('cloud')->where(array('id'=>$order['pid']))->save(array('status'=>'开通失败','statusinfo'=>$info));
			Log::write("order:".$orderid."(info:".$info.")","Cloud_ERR");
			echo $task_id;
			exit(); 
		}
		$starttime=time();
		$product=D('cloud_product')->where(array('Cloudtype'=>$order['producttype'],'status'=>1))->find();
		$endtime=D('Cloud')->cloudyear($product['id'],$order['year'],$starttime);
		D('order')->where(array('ordernumber'=>$orderid))->save(array('status'=>2,'statusinfo'=>''));
		D('cloud')->where(array('id'=>$order['pid']))->save(array('vm_id'=>$vm_id,'status'=>'正常'));
		echo $task_id;
		exit(); 	
	}
	
}
?>