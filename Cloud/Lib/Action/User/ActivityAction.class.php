<?php
/**
 * 活动机型
 * Enter description here ...
 */
class ActivityAction extends MainuserAction{
	public function add(){
		//验证登录
		if ($_SESSION['uid']){
			$this->assign('isuser',1);
		}else{
			$this->assign('isuser',0);
		}
		$id = I("id");
		$map['id'] = array("eq",$id);
		$map['starttime'] = array("lt",time());
		$map['endtime'] = array("gt",time());
		$data = M("cloud_activity")->where($map)->find();
		if(empty($data))$this->error("数据不存在");
		//查询开通总数对比
		$info=checkcloud($id);
		if ($info['status']==false){
			$this->error($info['value']);
		}
		//展示活动详情
		$this->assign("data",$data);
		$this->display();
	}
	//提交活动机型表单
	public function order(){
		if (!IS_POST)$this->error("数据错误");
		$ary_post=$_POST;
		$aid=$ary_post['id'];
		if (empty($aid))$this->error('活动ID错误 ');
		$cloudname=$ary_post['cloudname'];
		if (empty($cloudname))$this->error('云主机名不能为空 ');
		$cloudpassword=$ary_post['cloudpassword'];
		if (empty($cloudpassword))$this->error('云主机密码不能为空');
		$image_uuid=$ary_post['image_uuid'];
		if (empty($image_uuid))$this->error('操作系统不能为空 ');
		$map['id'] = array("eq",$aid);
		$map['starttime'] = array("lt",time());
		$map['endtime'] = array("gt",time());
		$cloudactivity = M("cloud_activity")->where($map)->find();
		if(empty($cloudactivity))$this->error("活动不存在或已结束");
		$product=D('cloud_product')->where(array('Cloudtype'=>$cloudactivity["Cloudtype"],'status'=>1))->field()->find();
		if(empty($product))$this->error("活动产品不存在");
		$checkcloudname=D('Cloud')->where(array('cloudname'=>$cloudname))->find();
		if(!empty($checkcloudname))$this->error("云主机名已存在");
		//计算当前开通的数量是不是已经超出当前活动的数量
		$info=checkcloud($aid);
		if ($info['status']==false){
			$this->error($info['value']);
		}
		//计算当前开通的数量是不是已经超出当前活动的数量end
		if ($cloudactivity['cycletext']=="PAY_Month")$year=$product['PAY_Month'];
		if ($cloudactivity['cycletext']=="PAY_Season")$year=$product['PAY_Season'];
		if ($cloudactivity['cycletext']=="PAY_halfyear")$year=$product['PAY_halfyear'];
		if ($cloudactivity['cycletext']=="PAY_1year")$year=$product['PAY_Nextyear'];
		if ($cloudactivity['cycletext']=="PAY_2year")$year=$product['PAY_2year'];
		if ($cloudactivity['cycletext']=="PAY_3year")$year=$product['PAY_3year'];
		if ($cloudactivity['cycletext']=="PAY_4year")$year=$product['PAY_4year'];
		if ($cloudactivity['cycletext']=="PAY_5year")$year=$product['PAY_5year'];
		//写入订单信息
		$data=array(
			'user_id'=>$this->uid,
			'username'=>$this->username,
			'ordernumber'=>getordcode(),
			'type'=>1,
			'ordertype'=>"云主机开通",
			'producttype'=>$product['Cloudtype'],
			'usermoney'=>$cloudactivity['money'],
			'year'=>$year,
			'masterid'=>$product['masterid'],
			'cid'=>$product['cid'],
			'cloudname'=>$cloudname,
			'cloudpassword'=>md5($cloudpassword),
			'ipnum'=>1,
			'cpunum'=>$cloudactivity['cpu'],
			'memnum'=>$cloudactivity['mem']*1024,
			'disknum'=>$cloudactivity['disk'],
			'qosnum'=>$cloudactivity['qos'],
			'dlip'=>$cloudactivity['iptype'],
			'image_uuid'=>$image_uuid,
			'whichProduct'=>"Cloud产品",
			'aid'=>$cloudactivity['id'],
			'addtime'=>time(),
			'status'=>1,
		);
		$order=D('order')->add($data);
		$this->success("订单提交成功",U('User/Order/show',array('id'=>$order)));
	}
	public function getsysos(){
		$id=I('id','','htmlspecialchars');
		$ostypeid=I('ostypeid','1','htmlspecialchars');
		$ca = D("cloud_activity")->where(array("id"=>$id))->find();
		$product=D('cloud_product')->where(array('Cloudtype'=>$ca["Cloudtype"],'status'=>1))->field('masterid,cid')->find();
		$cloudos=D('cloud_os')->where(array('ostype'=>$ostypeid,'status'=>1,'masterid'=>$product['masterid'],'cid'=>$product['cid']))->field()->select();
		echo json_encode($cloudos);
		exit();
	}

}
?>