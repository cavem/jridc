<?php
//云主机付款
class CloudrepayAction extends MainuserAction{
	public function index(){
		$id=I('id','0','htmlspecialchars');
		$cloud=D('cloud')->where(array('id'=>$id,'user_id'=>$this->uid,'username'=>$this->username))->find();
		if(!$cloud)$this->error('云主机不存在');
		if ($cloud['status']=='已删除')$this->error("已删除云主机禁止管理");
		$cloudproduct=D('cloud_product')->where(array('Cloudtype'=>$cloud['Cloudtype']))->find();
		if (!$cloudproduct)$this->error("产品配置不存在!");
		$prodcutapi=D('Cloudapi')->getprodcut($cloudproduct['pid']);
		if($prodcutapi['status']=='failed')$this->error($prodcutapi['value']);
		$cloudvminfo=json_decode($cloud['vminfo'],true);
		if($cloudvminfo['status']=='failed')$this->error($cloudvminfo['value']);
        $clouddiskinfo=json_decode($cloud['diskinfo'],true);
		if($clouddiskinfo['status']=='failed')$this->error($clouddiskinfo['value']);
        $cloudipqosinfo=json_decode($cloud['ipqosinfo'],true);
		if($cloudipqosinfo['status']=='failed')$this->error($cloudipqosinfo['value']);
        $vminfo=$cloudvminfo['value'];
    	$clouddiskinfo=$clouddiskinfo['value'];
    	$cloudipqosinfo=$cloudipqosinfo['value'];
		$cpunum=$vminfo['cpu'];
		$memorynum=$vminfo['memory'];
		$disknum=0;		
		foreach($clouddiskinfo as $k=>$v){
			if(!empty($v['userdevice'])){
				if ($v['utype']=='clouddisk'){
					$disknum=$disknum+$v['virtual_size'];
				}else{
					$disknum=$disknum+$v['virtual_size'];
				}
			}
		}
		$iparr= explode(',',$prodcutapi['value']['iptypeid0']);
		$ipcount=count($iparr);
		$iptypearr=array();
		foreach($cloudipqosinfo as $kk=>$vv){
			if($vv['shared']==false){
				foreach($vv['ip_infos'] as $kkk=>$vvv){
					if($vvv['iptype']!=$prodcutapi['value']['iptypeid1']){
						$iptypearr[]=$vvv['iptype'];
					}
				}
			}
		}
		$iptypecount=count($iptypearr);
		$ipnum=$iptypecount/$ipcount;
		$money=D('Cloud')->cloudrepayprice($cloudproduct['id'],$cpunum,$memorynum,$disknum,$ipnum,$cloudipqosinfo,$this->uid);
		if($cloud['repaymoney']){
			$money=$cloud['repaymoney'];
		}
		$map['status']=array('eq',0);
		$map['type']=array('eq','Cloud产品');
		$map['user_id']=array('eq',$this->uid);
		$map['condition']=array('elt',$money);
		if($cloud['istest']=='y'){//不属于活动机型 
			$coupon=D('coupon')->where($map)->select();
			$this->assign('coupon',$coupon);
		}
		$this->assign('money',$money);
		$this->assign('cloudproduct',$cloudproduct);
		$this->assign('cloud',$cloud);
		$this->display();		
	}
	public function dorepay(){
		$id=I('id','0','htmlspecialchars');
		$year=I('year','1','htmlspecialchars');	
		$cloud=D('cloud')->where(array('id'=>$id,'user_id'=>$this->uid,'username'=>$this->username))->find();
		if(!$cloud)$this->error('云主机不存在');
		if ($cloud['status']=='已删除')$this->error("已删除云主机禁止管理");
		$cloudproduct=D('cloud_product')->where(array('Cloudtype'=>$cloud['Cloudtype']))->find();
		if (!$cloudproduct)$this->error("产品配置不存在!");
		$prodcutapi=D('Cloudapi')->getprodcut($cloudproduct['pid']);
		if($prodcutapi['status']=='failed')$this->error($prodcutapi['value']);
		$cloudvminfo=json_decode($cloud['vminfo'],true);
		if($cloudvminfo['status']=='failed')$this->error($cloudvminfo['value']);
        $clouddiskinfo=json_decode($cloud['diskinfo'],true);
		if($clouddiskinfo['status']=='failed')$this->error($clouddiskinfo['value']);
        $cloudipqosinfo=json_decode($cloud['ipqosinfo'],true);
		if($cloudipqosinfo['status']=='failed')$this->error($cloudipqosinfo['value']);
        $vminfo=$cloudvminfo['value'];
    	$clouddiskinfo=$clouddiskinfo['value'];
    	$cloudipqosinfo=$cloudipqosinfo['value'];
		$cpunum=$vminfo['cpu'];
		$memorynum=$vminfo['memory'];
		$disknum=0;
		foreach($clouddiskinfo as $k=>$v){
			if(!empty($v['userdevice'])){
				$disknum=$disknum+$v['virtual_size'];
			}
		}
		$iparr= explode(',',$prodcutapi['value']['iptypeid0']);
		$ipcount=count($iparr);
		$iptypearr=array();
		foreach($cloudipqosinfo as $kk=>$vv){
			if($vv['shared']==false){
				foreach($vv['ip_infos'] as $kkk=>$vvv){
					if($vvv['iptype']!=$prodcutapi['value']['iptypeid1']){
						$iptypearr[]=$vvv['iptype'];
					}
				}
			}
		}
		$iptypecount=count($iptypearr);
		$ipnum=$iptypecount/$ipcount;
		$money=D('Cloud')->cloudrepayprice($cloudproduct['id'],$cpunum,$memorynum,$disknum,$ipnum,$cloudipqosinfo,$this->uid);
		if($cloud['repaymoney']){
			$money=$cloud['repaymoney'];
		}
		$endmoney=$money*$year;
		$endmoney=round($endmoney,2);
		$couponid=I('coupon','0','htmlspecialchars');
		if (!empty($couponid)){
			$coupon=D('coupon')->where(array('id'=>$couponid,'status'=>0,'user_id'=>$this->uid))->find();
			if ($coupon){
				$endmoney=$endmoney-$coupon['couponmoney'];
				if ($endmoney<0)$endmoney=0;		
			}		
		}
		$userorder=$this->userinfo($this->uid);
		if ($endmoney>$userorder['usermoney']){
			$this->error("账户余额不够支付当前云主机延期费用");
		}
		$starttime=$cloud['endtime'];
		$endtime=D('Cloud')->cloudyear($cloudproduct['id'],$year,$starttime);
		$ordernumber=getordcode();
		$logfor='用户区延期云主机云主机ID'.$cloud['id'];	
		$forwhat="延期云主机".$cloud['cloudname']."(订单号".$ordernumber.")";
		//优惠券处理
		if(!empty($couponid)){
			$coupon=D('coupon')->where(array('id'=>$couponid,'status'=>0,'user_id'=>$this->uid))->find();
			if ($coupon){
				$logfor='用户区延期试用云主机（ID:'.$cloud['id'].')(优惠券:'.$coupon['couponnum']."金额".$coupon['couponmoney'].'元)';
				$forwhat="用户区延期试用云主机".$cloud['cloudname']."(订单号".$ordernumber.")(优惠券:".$coupon['couponnum']."金额".$coupon['couponmoney'].'元)';
			}
		}
		$yearend=D('Cloud')->getyear($cloudproduct['id'],$year);
		$repay=D('Cloudapi')->repay($cloud['vm_id'],$yearend);
		if($repay['status']=='failed')$this->error($repay['value']);	
		$data=array(
			'user_id'=>$this->uid,
			'username'=>$this->username,
			'ordernumber'=>$ordernumber,
			'type'=>1,
			'ordertype'=>"云主机续费",
			'producttype'=>$cloudproduct['Cloudtype'],
			'usermoney'=>$endmoney,
			'year'=>$year,
			'cid'=>$cloudproduct['pid'],
			'cloudname'=>$cloud['cloudname'],
			'cloudpassword'=>$cloud['cloudpassword'],
			'ipnum'=>$ipnum,
			'cpunum'=>$cpunum,
			'memnum'=>$memorynum,
			'disknum'=>$disknum,
			'qosnum'=>$cloud['ipqosinfo'],
			'dlip'=>1,
			'image_uuid'=>"",
			'whichProduct'=>"Cloud产品",
			'isrebate'=>0,
			'addtime'=>time(),
			'logfor'=>$logfor,
			'pid'=>$cloud['id'],
			'status'=>2,
			'starttime'=>$starttime,
			'endtime'=>$endtime,
		);
		$order=D('order')->add($data);
		$user_id=$this->uid;
        $money=$endmoney;
        $whichProduct="Cloud产品";
        $pid=$cloud['id'];//产品ID
        $type=5;//1在线充值 2后台入款 3后台扣除 4开通扣除 5续费扣除 6升级配置 7降级配置   10充值卡$type
        $pingzheng="";
        $acspace="用户区";
        $isadd=2;//1入款 2出 $isadd
        $ptype=$cloudproduct['Cloudtype'];//产品类型
        $orderid=$ordernumber;//交易号或者订单号
        $paddtime=$starttime; //产品开通时间 $paddtime
        $endtime=$endtime;  //产品到期时间 $pendtime 
        D('User')->addmoney($user_id,$money,$forwhat,$whichProduct,$pid,$type,$pingzheng,$acspace,$isadd,$ptype,$orderid,$paddtime,$endtime);
		
        
        
        
        $update=D('Cloud')->where(array('id'=>$cloud['id'],'user_id'=>$this->uid,'username'=>$this->username))->save(array('endtime'=>$endtime,'status'=>'正常','istest'=>'n','emailstatus'=>null,'smsstatus'=>null,'wechatstatus'=>null));
		//处理续费接口
		
		
		
		
		
		
		
		
		D('Sendsms')->Sendcloudrepay($this->username,$endmoney,$cloud['cloudname'],$cloud['endtime'],$endtime);
		//发送邮件
		D('Sendmail')->Sendcloudrepay($this->username,$endmoney,$cloud['cloudname'],$cloud['endtime'],$endtime);
		$this->success("续费成功",U('User/Cloud/index'));
	}
	public function repayprice(){
		$money=I('money','0','htmlspecialchars');
		$year=I('year','1','htmlspecialchars');
		$id=I('id','0','htmlspecialchars');
		$endmoney=$money*$year;
		$endmoney=round($endmoney,2);
		$cloud=D('cloud')->where(array('id'=>$id,'user_id'=>$this->uid,'username'=>$this->username))->find();
		$map['status']=array('eq',0);
		$map['type']=array('eq','Cloud产品');
		$map['user_id']=array('eq',$this->uid);
		$map['condition']=array('elt',$endmoney);
		if($cloud['istest']=='y'){
			$coupon=D('coupon')->where($map)->select();
		}
		$data=array(
			'money'=>$endmoney,
			'coupon'=>$coupon,
		);
		echo json_encode($data);
		exit();
	}
}
?>