<?php
//订单管理
set_time_limit(0);
class OrderAction extends MainuserAction{
	public function index(){
		$ary_get=$_GET;
		$where="user_id=".$this->uid." and username='".$this->username."'";
		$ary_get['ordernumber'] = I('get.ordernumber','');
		$ary_get['type'] = I('get.type',0,'intval');
		$ary_get['status'] = I('get.status',0,'intval');
		$ary_get['starttime'] = I('get.starttime','');
		$ary_get['endtime'] = I('get.endtime','');
		if(!empty($ary_get['ordernumber']))$where=$where." and ordernumber =".$ary_get['ordernumber'];
		if(!empty($ary_get['type']))$where=$where." and type =".$ary_get['type'];
		if(!empty($ary_get['status']))$where=$where." and status =".$ary_get['status'];
		if(!empty($ary_get['starttime'])){
			$starttime=str_replace("+"," ",$ary_get['starttime']);	
			$starttime=convert_datefm($starttime);
			$where=$where." and addtime >=".$starttime;
			$this->assign("starttime",date('Y-m-d', $starttime));
		}
		if(!empty($ary_get['endtime'])){
			$endtime=str_replace("+"," ",$ary_get['endtime']);	
			$endtime=convert_datefm($endtime);
			$where=$where." and addtime <=".($endtime+86400);
			$this->assign("endtime",date('Y-m-d', $endtime));
		}
		$count = M("order")->where($where)->count();
		$obj_page =$this->UserPage($count,10);
        $pageinfo = $obj_page->show();
        $data = M("order")
			    ->where($where)
        		->limit($obj_page->firstRow, $obj_page->listRows)
				->order("addtime desc")
				->select();
		$this->assign("pageinfo",$pageinfo);
		$this->assign('data',$data);
		$this->display();
	}
	public function show(){
		$id=I('get.id','','intval');
		if (empty($id))$this->error("参数错误");
		$data=D('order')->where(array('id'=>$id,'user_id'=>$this->uid,'username'=>$this->username))->find();
		if (empty($data))$this->error("数据不存在");
		if ($data['type']==1){
			//活动机型转到活动订单页面
			$cloudos=D('cloud_os')->where(array('image_uuid'=>$data['image_uuid']))->find();
			$cloudproduct=D('cloud_product')->where(array('Cloudtype'=>$data['producttype']))->find();
			$tpl='cloud';
			if ($data['ordertype']=='云主机开通'){
				$tpl='cloud';
			}
			if ($data['ordertype']=='云主机续费'){
				$tpl='cloudrepay';
				$qosnetwork=json_decode($data['qosnum'],true);
				$qosnetwork=$qosnetwork['value'];
				$data['qosnetwork']=$qosnetwork;
			}
			if ($data['ordertype']=='云主机调整'){
				$tpl='clouddown';
			}
			if ($data['ordertype']=='云主机升级'){
				$tpl='cloudup';
			}
			if ($data['year']==888)$data['yearname']='自动支付';
			if ($data['year']==999)$data['yearname']='试用';
			if ($data['year']==$cloudproduct['PAY_Month'])$data['yearname']='月付';
			if ($data['year']==$cloudproduct['PAY_Season'])$data['yearname']='季度付';
			if ($data['year']==$cloudproduct['PAY_halfyear'])$data['yearname']='半年付';
			if ($data['year']==$cloudproduct['PAY_Nextyear'])$data['yearname']='年付';
			if ($data['year']==$cloudproduct['PAY_2year'])$data['yearname']='二年';
			if ($data['year']==$cloudproduct['PAY_3year'])$data['yearname']='三年';
			if ($data['year']==$cloudproduct['PAY_4year'])$data['yearname']='四年';
			if ($data['year']==$cloudproduct['PAY_5year'])$data['yearname']='五年';
			
			
			//查找未到期 而且可以使用的优惠券
			$map['status']=array('eq',0);
			$map['type']=array('eq','Cloud产品');
			$map['user_id']=array('eq',$this->uid);
			$map['condition']=array('elt',$data['usermoney']);
			if(empty($data['aid'])){//不属于huo'dong
				$coupon=D('coupon')->where($map)->select();
			}
			$this->assign('data',$data);
			$this->assign('coupon',$coupon);
			$this->assign('cloudos',$cloudos);
			$this->assign('cloudproduct',$cloudproduct);
			$this->display($tpl);
			exit();
		}
		$this->display();
	}
	public function close(){
		$id=I('get.id','','htmlspecialchars');
		if (empty($id))$this->error("参数错误");
		$data=D('order')->where(array('id'=>$id,'user_id'=>$this->uid,'username'=>$this->username))->find();
		if (empty($data))$this->error("数据不存在");
		if ($data['status']==1){
			D('order')->where(array('id'=>$id,'user_id'=>$this->uid,'username'=>$this->username))->save(array('status'=>3));
			$this->success("成功取消");
		}else{
			$this->error("当前订单禁止取消");
		}
	}
	/**
	 * 云主机开通
	 */
	public function cloudopen(){
		if (!IS_POST)$this->error("数据错误");
		$id=I('post.id',0,'intval');
		$coupon = I('post.coupon','');
		if(empty($id))$this->error("参数错误");
		$order=D('order')->where(array('id'=>$id,'user_id'=>$this->uid,'username'=>$this->username))->find();
		if (empty($order))$this->error("数据不存在");
		if ($order['type']<>1)$this->error("类型错误");
		if ($order['status']<>1)$this->error("当前订单状态错误");
		$product=D('cloud_product')->where(array('Cloudtype'=>$order['producttype'],'status'=>1))->find();
		if (empty($product))$this->error("产品配置不存在");
		if ($order['year']==999 && $product['cantest']==0)$this->error("很抱歉,本产品不可以试用！");
		$starttime=time();
		$endtime=D('Cloud')->cloudyear($product['id'],$order['year'],$starttime);
		if ($order['year']==999){
			$istest='y';
		}else{
			$istest='n';
		}
		$userorder=$this->userinfo($this->uid);
		$endmoney=$order['usermoney'];//当前订单金额
		if(!empty($coupon)){
			$coupon=D('coupon')->where(array('id'=>$coupon,'status'=>0,'user_id'=>$this->uid))->find();
			if ($coupon){
				$endmoney=$endmoney-$coupon['couponmoney'];
				if ($endmoney<0)$endmoney=0;		
			}
		}
		if ($endmoney>$userorder['usermoney']){
			$this->error("账户余额不够支付当前云主机订单",U('User/Pay/index'));
		}
		$pid=$order['cid'];
		$vm_name=$order['cloudname'];
		$password=$order['cloudpassword'];
		$n_cpu=$order['cpunum'];
		$m_memory=$order['memnum'];
		$bandwidth=$order['qosnum'];
		$iptype=$order['dlip'];
		$data_disk_size=$order['disknum'];
		$vdi_uuid=$order['image_uuid'];
		$year=D('Cloud')->getyear($product['id'],$order['year']);
		//创建云主机
		if (C('CLOUDOPEN')==1){
			$cloudcreate=D('Cloudapi')->cloudcreate($pid,$vm_name,$password,$n_cpu,$m_memory,$bandwidth,$iptype,$data_disk_size,$vdi_uuid,$year);
			if ($cloudcreate['status']=='failed')$this->error($cloudcreate['value']);
			$vm_id=$cloudcreate['value'];
		}else {
			$orderid=$order['ordernumber'];
			$cloudcreate=D('Cloudapi')->cloudcreateajax($pid,$vm_name,$password,$n_cpu,$m_memory,$bandwidth,$iptype,$data_disk_size,$vdi_uuid,$year,$orderid);
			if ($cloudcreate['status']=='failed')$this->error($cloudcreate['value']);
			$vm_id=0;
		}
		$clouddata=array(
			'user_id'=>$this->uid,
			'username'=>$this->username,
			'Cloudtype'=>$product['Cloudtype'],
			'cid'=>$product['pid'],
			'cloudname'=>$order['cloudname'],
			'cloudpassword'=>md5($order['cloudpassword']),
			'vm_id'=>$vm_id,
			'osname'=>$cloudos['osname'],
			'isrebate'=>$order['isrebate'],
			'isrebatetime'=>$endtime,
			'istest'=>$istest,
			'starttime'=>$starttime,
			'endtime'=>$endtime
		);
		if (C('CLOUDOPEN')==1){
			$clouddata['status']="正常";
		}else {
			$clouddata['status']="配置中";
		}		
		$cloudid=D('Cloud')->add($clouddata);
		if (C('CLOUDOPEN')==1){
			D('Cloud')->updatevminfo($cloudid,$vm_id);//云主机详情
			D('Cloud')->updatediskinfo($cloudid,$vm_id);//云主机存储列表
			D('Cloud')->updateipqosinfo($cloudid,$vm_id);//云主机存储列表
		}
		//更新云主机订单
		$logfor='用户区开通云主机云主机（ID:'.$cloudid.')'.$logfor_a;
		//优惠券处理
		if(!empty($coupon)){
			$coupon=D('coupon')->where(array('id'=>$coupon,'status'=>0,'user_id'=>$this->uid))->find();
			if ($coupon){
				$logfor='用户区开通云主机云主机（ID:'.$cloudid.')(优惠券:'.$coupon['couponnum'].')';			
			}
		}
		//优惠券处理结束
		$orderdata=array(
			'logfor'=>$logfor,
			'pid'=>$cloudid,
			'status'=>2,
			'starttime'=>$starttime,
			'endtime'=>$endtime,
		);
		$orderupdate=D('order')->where(array('id'=>$id,'user_id'=>$this->uid,'username'=>$this->username))->save($orderdata);
	    $user_id=$this->uid;
        $money=$endmoney;
        //优惠券处理
        $forwhat="在线开通云主机".$order['cloudname']."(订单号:".$order['ordernumber'].")".$logfor_a;
		if(!empty($ary_post['coupon'])){
			$coupon=D('coupon')->where(array('id'=>$ary_post['coupon'],'status'=>0,'user_id'=>$this->uid))->find();
			if ($coupon){
			 $forwhat="在线开通云主机".$order['cloudname']."(订单号:".$order['ordernumber'].")(优惠券:".$coupon['couponnum'].")";
			}
		}
		//优惠券处理结束
        $whichProduct="Cloud产品";
        $pid=$cloudid;//产品ID
        $type=4;//1在线充值 2后台入款 3后台扣除 4开通扣除 5续费扣除 6升级配置 7降级配置   10充值卡$type
        $pingzheng="";
        $acspace="用户区";
        $isadd=2;//1入款 2出 $isadd
        $ptype=$product['Cloudtype'];//产品类型
        $orderid=$order['ordernumber'];//交易号或者订单号
        $paddtime=$starttime; //产品开通时间 $paddtime
        $endtime=$endtime;  //产品到期时间 $pendtime 
        D('User')->addmoney($user_id,$money,$forwhat,$whichProduct,$pid,$type,$pingzheng,$acspace,$isadd,$ptype,$orderid,$paddtime,$endtime);
		//修改优惠券状态
		if(!empty($ary_post['coupon'])){
			$coupon=D('coupon')->where(array('id'=>$ary_post['coupon'],'status'=>0,'user_id'=>$this->uid))->find();
			if ($coupon){
			 	D('coupon')->where(array('id'=>$ary_post['coupon'],'user_id'=>$this->uid))->save(array('status'=>1,'usetime'=>time(),'ip'=>getip(),'remark'=>$forwhat));
			}
		}
		D('Sendsms')->Sendcloudopen($this->username,$money,$order['cloudname'],$starttime,$endtime);
		D('Sendmail')->Sendcloudopen($this->username,$money,$order['cloudname'],$starttime,$endtime);
        $this->success("云主机开通成功",U('User/Cloud/index'));
	}
	/**
	 * 添加云主机订单
	 */
	public function cloud(){
		if (!IS_POST)$this->error("数据提交错误");
		$id=I('post.id',0,'intval');
		$id=remove_xss($id);
		$cpunum=I('post.cpunum','');
		$cpunum=remove_xss($cpunum);
		$memnum=I('post.memnum','');
		$memnum=remove_xss($memnum);
		$disknum=I('post.disknum','');
		$disknum=remove_xss($disknum);
		$qosnum=I('post.qosnum','');
		$qosnum=remove_xss($qosnum);
		$iptype=I('post.ipnum','');
		$iptype=remove_xss($iptype);
		$cloudname=I('post.cloudname','');
		$cloudname=remove_xss($cloudname);
		$cloudpassword=I('post.cloudpassword','');
		$cloudpassword=remove_xss($cloudpassword);
		$imageuuid=I('post.imageuuid','');
		$imageuuid=remove_xss($imageuuid);
		$isrebate=I('post.isrebate','');
		$isrebate=remove_xss($isrebate);
		$year=I('post.year','');
		$year=remove_xss($year);
		if (empty($id) || !is_numeric($id))$this->error("产品ID错误");
		if (empty($cpunum) || !is_numeric($cpunum))$this->error("CPU个数不能为空");
		if (empty($memnum) || !is_numeric($memnum))$this->error("内存大小不能为空");
		if (empty($iptype) || !is_numeric($iptype))$this->error("IP类型错误");
		if (empty($imageuuid))$this->error("请选择操作系统");
		$produc=D('cloud_product')->where(array('id'=>$id,'status'=>1))->find();
		if (empty($produc))$this->error("产品配置不存在");
		
		if ($year==999){
			if($cpunum>$produc['cantestcpu']){
				$this->error("试用主机IP核不能大于".$produc['cantestcpu']."核");
			}
			if($memnum>($produc['cantestmem']*1024)){
				$this->error("试用主机内存不能大于".$produc['cantestmem']."G");
			}
			if($disknum>$produc['cantestdisk']){
				$this->error("试用主机硬盘不能大于".$produc['cantestdisk']."G");
			}
			if($qosnum>$produc['cantestqos']){
				$this->error("试用主机带宽不能大于".$produc['cantestqos']."M");
			}
		}
		$cloutcloudname=D('cloud')->where("cloudname='%s'",$cloudname)->count();
		if ($cloutcloudname>0)$this->error("云主机名已经存在");
		
		$checkyear=D('Cloud')->checkyear($id,$year);//2017-1-22
		if (empty($checkyear))$this->error("数据提交错误");//2017-1-22
		
		$cloudmoeny=D('Cloud')->cloudprice($id,$cpunum,$memnum,$disknum,$iptype,$qosnum,$year,$_SESSION['uid']);
		if (empty($cloudmoeny))$this->error("金额获取失败");
		if ($isrebate=='y'){
			$isrebateend=1;
			$usermoney=$cloudmoeny['Price'];
		}else{
			$isrebateend=0;
			$usermoney=$cloudmoeny['Priceold'];
		}
		//查询当前客户已经试用云主机产品
		if ($year==999){
			$clouttest=D('cloud')->where(array('user_id'=>$this->uid,'istest'=>'y'))->count();
			if ($clouttest>0)$this->error("当前用户已经有试用的云主机");
		}
		if ($year==999)$usermoney=0;
		$data=array(
			'user_id'=>$this->uid,
			'username'=>$this->username,
			'ordernumber'=>getordcode(),
			'type'=>1,
			'ordertype'=>"云主机开通",
			'producttype'=>$produc['Cloudtype'],
			'usermoney'=>$usermoney,
			'year'=>$year,
			'cid'=>$produc['pid'],
			'cloudname'=>$cloudname,
			'cloudpassword'=>$cloudpassword,
			'ipnum'=>1,
			'cpunum'=>$cpunum,
			'memnum'=>$memnum,
			'disknum'=>$disknum,
			'qosnum'=>$qosnum,
			'dlip'=>$iptype,
			'image_uuid'=>$imageuuid,
			'whichProduct'=>"Cloud产品",
			'isrebate'=>$isrebateend,
			'addtime'=>time(),
			'status'=>1,
		);
		$order=D('order')->add($data);
		$this->success("订单提交成功",U('User/Order/show',array('id'=>$order)));
	}

}
?>