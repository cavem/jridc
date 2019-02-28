<?php
//云主机升级
class CloudupAction extends MainuserAction{
	public function index(){
		$id=I('id','0','htmlspecialchars');
		$cloud=D('cloud')->where(array('id'=>$id,'user_id'=>$this->uid,'username'=>$this->username))->find();
		if(!$cloud)$this->error('云主机不存在');
		if ($cloud['status']=='已删除')$this->error("已删除云主机禁止管理");
		if ($cloud['istest']=='y')$this->error("试用主机禁止增配");
		if ($cloud['endtime']<time())$this->error("已过期主机禁止增配");
		$cloudproduct=D('cloud_product')->where(array('Cloudtype'=>$cloud['Cloudtype']))->find();		
		if (!$cloudproduct)$this->error("产品配置不存在!");	
		$prodcutapi=D('Cloudapi')->getprodcut($cloudproduct['pid']);
		if($prodcutapi['status']=='failed')$this->error($prodcutapi['value']);
		D('Cloud')->updatevminfo($cloud['id'],$cloud['vm_id']);
		D('Cloud')->updatediskinfo($cloud['id'],$cloud['vm_id']);
		D('Cloud')->updateipqosinfo($cloud['id'],$cloud['vm_id']);
		$cloudvminfo=json_decode($cloud['vminfo'],true);
		if($cloudvminfo['status']=='failed')$this->error($cloudvminfo['value']);
        $clouddiskinfo=json_decode($cloud['diskinfo'],true);
		if($clouddiskinfo['status']=='failed')$this->error($clouddiskinfo['value']);
        $cloudipqosinfo=json_decode($cloud['ipqosinfo'],true);
		if($cloudipqosinfo['status']=='failed')$this->error($cloudipqosinfo['value']);
        $vminfo=$cloudvminfo['value'];
    	$clouddiskinfo=$clouddiskinfo['value'];
    	$cloudipqosinfo=$cloudipqosinfo['value'];
		$cpunum=$vminfo['cpu'];//CPU个数
		$memorynum=$vminfo['memory'];//内存大小
		$syscount=0;
    	$cloudcount=0;
    	foreach ($clouddiskinfo as $k=>$v){
    		if ($v['userdevice']==0){
    			$syscount=$v['virtual_size'];
    		}else{
				$cloudcount+=$v['virtual_size'];			
    		}
    	}
    	//计算需要续费的IP个数 
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
		$countdays=count_days($cloud['endtime'],strtotime("-1 day"));
		$cloudpre=$this->usercloudpre($this->uid);
		//2015-12-25
		$cloudproduct['moneycpu']=user_jieti_per($cloudproduct['moneycpu'],$cloudpre);
		$cloudproduct['moneymemory']=user_jieti_per($cloudproduct['moneymemory'],$cloudpre);
		$cloudproduct['moneydisk']=$cloudproduct['moneydisk']*$cloudpre;
		$cloudproduct['moneyip']=$cloudproduct['moneyip']*1;
		$cloudproduct['dqos']=$prodcutapi['value']['dqos'];
		$cloudproduct['ddisk']=$prodcutapi['value']['ddisk'];
		$cloudproduct['mcpu']=$prodcutapi['value']['mcpu'];
		$cloudproduct['mmem']=$prodcutapi['value']['mmem'];
		$cloudproduct['mdisk']=$prodcutapi['value']['mdisk'];
		$cloudproduct['mip']=$prodcutapi['value']['mip'];
		$cloudproduct['mqos']=$prodcutapi['value']['mqos'];
		$this->assign('id',$id);
		$this->assign('cloudproduct',$cloudproduct);
		$this->assign('cloud',$cloud);
		$this->assign('cloudipqosinfo',$cloudipqosinfo);
		$this->assign('countdays',$countdays);
		$this->assign('cpunum',$cpunum);
		$this->assign('memorynum',$memorynum/1024);
		$this->assign('disknum',$cloudcount);
		$this->assign('ipnum',$ipnum);
		$this->display();
	}
	public function uphandle(){
		$id=I('id','0','htmlspecialchars');
		$act=I('act','','htmlspecialchars');
		$num=I('num','0','htmlspecialchars');
		if (empty($id))$this->error("ID错误");
		if (empty($act))$this->error("参数错误");
		if (empty($num))$this->error("数量不能为空");
		$cloud=D('cloud')->where(array('id'=>$id,'user_id'=>$this->uid,'username'=>$this->username))->find();
		if(!$cloud)$this->error('云主机不存在');
		if ($cloud['status']=='已删除')$this->error("已删除云主机禁止管理");
		$cloudproduct=D('cloud_product')->where(array('Cloudtype'=>$cloud['Cloudtype']))->find();
		if (!$cloudproduct)$this->error("产品配置不存在!");
		$prodcutapi=D('Cloudapi')->getprodcut($cloudproduct['pid']);
		if($prodcutapi['status']=='failed')$this->error($prodcutapi['value']);		
		D('Cloud')->updatevminfo($cloud['id'],$cloud['vm_id']);
		D('Cloud')->updatediskinfo($cloud['id'],$cloud['vm_id']);
		D('Cloud')->updateipqosinfo($cloud['id'],$cloud['vm_id']);
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
		$countdays=count_days($cloud['endtime'],strtotime("-1 day"));
		$cloudpre=$this->usercloudpre($this->uid);
		$cloudproduct['moneydisk']=$cloudproduct['moneydisk']*$cloudpre;
		$cloudproduct['moneyip']=$cloudproduct['moneyip']*1;
		if ($act=='cpu'){
			$resulttop=D('Cloudapi')->cloudstop($cloud['vm_id']);
			$cpuprice=D('Cloud')->count_price($cloudproduct['moneycpu'],($cpunum+$num))-D('Cloud')->count_price($cloudproduct['moneycpu'],$cpunum);
			$endmoney=($cpuprice/365)*$countdays*$cloudpre;
			$endmoney=round($endmoney,2);
		}
		if ($act=='mem'){
			$resulttop=D('Cloudapi')->cloudstop($cloud['vm_id']);
			$memoryend=$memorynum/1024;
			$memprice=D('Cloud')->count_price($cloudproduct['moneymemory'],($memoryend+$num))-D('Cloud')->count_price($cloudproduct['moneymemory'],$memoryend);
			$endmoney=($memprice/365)*$countdays*$cloudpre;
			$endmoney=round($endmoney,2);
		}
		if ($act=='disk'){
			$resulttop=D('Cloudapi')->cloudstop($cloud['vm_id']);
			$money=$cloudproduct['moneydisk'];
			$endmoney=($money/365)*$countdays*$num;
			$endmoney=round($endmoney,2);
		}
		if ($act=='ip'){
			$money=$cloudproduct['moneyip'];
			$endmoney=($money/365)*$countdays*$num;
			$endmoney=round($endmoney,2);
		}
		if ($act=='qos'){
			$vif_uuid=I('vif_uuid','0','htmlspecialchars');
			if (empty($vif_uuid))$this->error("vif_uuid不能为空");
			foreach ($cloudipqosinfo as $k=>$v){
					if ($v['shared']==false and $v['uuid']==$vif_uuid){
						$qos=$v['qos'];
						$mac=$v['MAC'];
					}
			}			
			$endqos=($qos+$num*128)/128;
			$qosprice=D('Cloud')->count_price($cloudproduct['moneyqos'],$endqos)-D('Cloud')->count_price($cloudproduct['moneyqos'],$qos/128);
			$endmoney=($qosprice/365)*$countdays*$cloudpre;
			$endmoney=round($endmoney,2);
			
		}
		$userorder=$this->userinfo($this->uid);
		if ($endmoney>$userorder['usermoney']){
			$this->error("账户余额不够支付当前操作");
		}
		$ordernumber=getordcode();
		$data=array(
			'user_id'=>$this->uid,
			'username'=>$this->username,
			'ordernumber'=>$ordernumber,
			'type'=>1,
			'ordertype'=>"云主机升级",
			'producttype'=>$cloudproduct['Cloudtype'],
			'usermoney'=>$endmoney,
			'year'=>$countdays,
			'cid'=>$cloudproduct['pid'],
			'cloudname'=>$cloud['cloudname'],
			'cloudpassword'=>$cloud['cloudpassword'],
			'dlip'=>1,
			'image_uuid'=>"",
			'whichProduct'=>"Cloud产品",
			'isrebate'=>0,
			'addtime'=>time(),
			'pid'=>$cloud['id'],
			'status'=>2,
			'starttime'=>$cloud['starttime'],
			'endtime'=>$cloud['endtime'],
		);
		switch ($act){
			case 'cpu':
				$endcpu=$cpunum+$num;
				$result=D('Cloudapi')->vmsetcpu($cloud['vm_id'],$endcpu);
				if ($result['status']=='failed')$this->error($result['value']);
				$data['cpunum']=$num;
				$data['logfor']='用户区升级云主机CPU(产品ID:'.$cloud['id'].')(CPU:'.$num.'核)';
			break;
			case 'mem':
				$endmem=$memorynum+($num*1024);
				$result=D('Cloudapi')->vmsetmem($cloud['vm_id'],$endmem);
				if ($result['status']=='failed')$this->error($result['value']);
				$data['memnum']=$num*1024;
				$data['logfor']='用户区升级云主机MEM(产品ID:'.$cloud['id'].')(内存:'.$num.'G)';
			break;
			case 'disk':
					$result=D('Cloudapi')->vmsetdisk($cloud['vm_id'],$num);
					if ($result['status']=='failed')$this->error($result['value']);
					$data['disknum']=$num;
					$data['logfor']='用户区升级云主机DISK(产品ID:'.$cloud['id'].')(存储:'.$num.'G)';
			break;
			case 'qos':
				$result=D('Cloudapi')->vmnetworkupdate($cloud['vm_id'],$vif_uuid,$endqos*128);
				if ($result['status']=='failed')$this->error($result['value']);
				$data['qosnum']=$num;
				$data['logfor']='用户区升级云主机带宽(产品ID:'.$cloud['id'].')(带宽:'.$num.'M)';
			break;
			case 'ip':
				$result=D('Cloudapi')->vmnetworkaddip($cloud['vm_id'],$num);
				if ($result['status']=='failed')$this->error($result['value']);
				$data['ipnum']=$num;
				$data['logfor']='用户区升级云主机IP(产品ID:'.$cloud['id'].')(IP:'.$num.'个)';
			break;
		}	
		$order=D('order')->add($data);
		$user_id=$this->uid;
        $money=$endmoney;
        $forwhat="升级云主机(".$cloud['cloudname'].")(产品ID".$cloud['id'].")(订单号".$ordernumber.")".$act;
        $whichProduct="Cloud产品";
        $pid=$cloud['id'];//产品ID
        $type=6;//1在线充值 2后台入款 3后台扣除 4开通扣除 5续费扣除 6升级配置 7降级配置   10充值卡$type
        $pingzheng="";
        $acspace="用户区";
        $isadd=2;//1入款 2出 $isadd
        $ptype=$cloudproduct['Cloudtype'];//产品类型
        $orderid=$ordernumber;//交易号或者订单号
        $paddtime=$cloud['starttime']; //产品开通时间 $paddtime
        $endtime=$cloud['endtime'];  //产品到期时间 $pendtime 
        D('User')->addmoney($user_id,$money,$forwhat,$whichProduct,$pid,$type,$pingzheng,$acspace,$isadd,$ptype,$orderid,$paddtime,$endtime);
		sleep(5);
        D('Cloud')->updatevminfo($cloud['id'],$cloud['vm_id']);
		D('Cloud')->updatediskinfo($cloud['id'],$cloud['vm_id']);
		D('Cloud')->updateipqosinfo($cloud['id'],$cloud['vm_id']);
        $this->success("升级完成");
	}
	public function memprice(){
		$id=I('id','0','htmlspecialchars');
		$num=I('num','0','htmlspecialchars');
		if (empty($id))$this->error("ID错误");
		$cloud=D('cloud')->where(array('id'=>$id,'user_id'=>$this->uid,'username'=>$this->username))->find();
		if(!$cloud)$this->error('云主机不存在');
		if ($cloud['status']=='已删除')$this->error("已删除云主机禁止管理");
		$cloudproduct=D('cloud_product')->where(array('Cloudtype'=>$cloud['Cloudtype']))->find();
		if (!$cloudproduct)$this->error("产品配置不存在!");
		$countdays=count_days($cloud['endtime'],strtotime("-1 day"));
		$cloudvminfo=json_decode($cloud['vminfo'],true);
		$vminfo=$cloudvminfo['value'];
		$memory=$vminfo['memory'];
		$memory=$memory/1024;
		$memprice=D('Cloud')->count_price($cloudproduct['moneymemory'],($memory+$num))-D('Cloud')->count_price($cloudproduct['moneymemory'],$memory);
		$cloudpre=$this->usercloudpre($this->uid);
		$endmoney=($memprice/365)*$countdays*$cloudpre;
		$endmoney=round($endmoney,2);
		$this->success($endmoney);
	}
	public function cpuprice(){
		$id=I('id','0','htmlspecialchars');
		$num=I('num','0','htmlspecialchars');
		if (empty($id))$this->error("ID错误");
		$cloud=D('cloud')->where(array('id'=>$id,'user_id'=>$this->uid,'username'=>$this->username))->find();
		if(!$cloud)$this->error('云主机不存在');
		if ($cloud['status']=='已删除')$this->error("已删除云主机禁止管理");
		$cloudproduct=D('cloud_product')->where(array('Cloudtype'=>$cloud['Cloudtype']))->find();
		if (!$cloudproduct)$this->error("产品配置不存在!");
		$countdays=count_days($cloud['endtime'],strtotime("-1 day"));
		$cloudvminfo=json_decode($cloud['vminfo'],true);
		$vminfo=$cloudvminfo['value'];
		$cpu=$vminfo['cpu'];
		$cpuprice=D('Cloud')->count_price($cloudproduct['moneycpu'],($cpu+$num))-D('Cloud')->count_price($cloudproduct['moneycpu'],$cpu);
		$cloudpre=$this->usercloudpre($this->uid);
		$endmoney=($cpuprice/365)*$countdays*$cloudpre;
		$endmoney=round($endmoney,2);
		$this->success($endmoney);
	}
	public function qosprice(){
		$id=I('id','0','htmlspecialchars');
		$amountqos=I('amountqos','0','htmlspecialchars');
		$qoscount=I('qoscount','0','htmlspecialchars');
		$vif_uuid=I('vif_uuid','','htmlspecialchars');
		if (empty($id))$this->error("ID错误");
		if (empty($vif_uuid))$this->error("网卡错误");
		$cloud=D('cloud')->where(array('id'=>$id,'user_id'=>$this->uid,'username'=>$this->username))->find();
		if(!$cloud)$this->error('云主机不存在');
		if ($cloud['status']=='已删除')$this->error("已删除云主机禁止管理");
		$cloudproduct=D('cloud_product')->where(array('Cloudtype'=>$cloud['Cloudtype']))->find();
		if (!$cloudproduct)$this->error("产品配置不存在!");
		$countdays=count_days($cloud['endtime'],strtotime("-1 day"));
		
		$qosprice=D('Cloud')->count_price($cloudproduct['moneyqos'],($amountqos+$qoscount))-D('Cloud')->count_price($cloudproduct['moneyqos'],$qoscount);
		$cloudpre=$this->usercloudpre($this->uid);
		$endmoney=($qosprice/365)*$countdays*$cloudpre;
		$endmoney=round($endmoney,2);
		$this->success($endmoney);
	}
}
?>