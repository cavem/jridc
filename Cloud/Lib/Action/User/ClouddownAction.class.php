<?php
//云主机降级
class ClouddownAction extends MainuserAction{
	public  function index(){
		$id=I('id','0','htmlspecialchars');
		$cloud=D('cloud')->where(array('id'=>$id,'user_id'=>$this->uid,'username'=>$this->username))->find();
		if(!$cloud)$this->error('云主机不存在');
		if ($cloud['status']=='已删除')$this->error("已删除云主机禁止管理");
		if ($cloud['istest']=='y')$this->error("试用主机禁止减配");
		if ($cloud['endtime']<time())$this->error("已过期主机禁止减配");
		if (!empty($cloud['isrebate'])){//使用折扣期间禁止减少配置
			if ($cloud['isrebatetime']>time())$this->error("折扣期间禁止减配");
		}
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
		//计算剩余天数
		$countdays=count_days($cloud['endtime'],strtotime("0 day"));
		$cloudpre=$this->usercloudpre($this->uid);
		$cloudproduct['moneydisk']=$cloudproduct['moneydisk']*$cloudpre;
		//2015-12-25
		$cloudproduct['moneycpu']=user_jieti_per($cloudproduct['moneycpu'],$cloudpre);
		$cloudproduct['moneymemory']=user_jieti_per($cloudproduct['moneymemory'],$cloudpre);
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
		foreach($cloudipqosinfo as $kk=>$vv){
			if($vv['shared']==false){
				foreach($vv['ip_infos'] as $kkk=>$vvv){
					if($vvv['iptype']!=$prodcutapi['value']['iptypeid1']){
						$cloudipqosinfos[]=$vvv['iptype'];
					}
				}
			}
		}
		$this->assign('cloudipqosinfo',$cloudipqosinfo);
		$this->assign('countdays',$countdays);
		$this->assign('cpunum',$cpunum);
		$this->assign('memorynum',$memorynum/1024);
		$this->assign('disknum',$cloudcount);
		$this->assign('ipnum',$ipnum);
		$this->assign('ipnumber',$ipcount);
		$this->assign('iptypeid0',$prodcutapi['value']['iptypeid0']);
		$this->assign('iptypeid1',$prodcutapi['value']['iptypeid1']);
		$this->display();
	}
	public function downhandle(){
		$id=I('id','0','htmlspecialchars');
		$act=I('act','','htmlspecialchars');
		$num=I('num','0','htmlspecialchars');
		if (empty($id))$this->error("ID错误");
		if (empty($act))$this->error("参数错误");
		if (empty($num))$this->error("调整大小不能为空");
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
		$countdays=count_days($cloud['endtime'],strtotime("0 day"));
		$cloudpre=$this->usercloudpre($this->uid);
		$cloudproduct['moneydisk']=$cloudproduct['moneydisk']*$cloudpre;
		$cloudproduct['moneyip']=$cloudproduct['moneyip']*1;
		if ($act=='cpu'){
			$resulttop=D('Cloudapi')->cloudstop($cloud['vm_id']);
			$cpuprice=D('Cloud')->count_price($cloudproduct['moneycpu'],$cpunum)-D('Cloud')->count_price($cloudproduct['moneycpu'],($cpunum-$num));
			$endmoney=($cpuprice/365)*$countdays*$cloudpre;
			$endmoney=round($endmoney,2);
		}
		if ($act=='mem'){
			$resulttop=D('Cloudapi')->cloudstop($cloud['vm_id']);
			$endmems=($memorynum/1024)-$num;
			$memprice=D('Cloud')->count_price($cloudproduct['moneymemory'],($memorynum/1024))-D('Cloud')->count_price($cloudproduct['moneymemory'],$endmems);
			$endmoney=($memprice/365)*$countdays*$cloudpre;
			$endmoney=round($endmoney,2);
		}
		if ($act=='disk'){
			$resulttop=D('Cloudapi')->cloudstop($cloud['vm_id']);
			$money=$cloudproduct['moneydisk'];
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
			$qosprice=D('Cloud')->count_price($cloudproduct['moneyqos'],$qos/128)-D('Cloud')->count_price($cloudproduct['moneyqos'],$num);
			$endmoney=($qosprice/365)*$countdays*$cloudpre;
			$endmoney=round($endmoney,2);
		}
		if ($act=='ip'){
			$deleteip=I('deleteip','0','htmlspecialchars');
			if (empty($deleteip))$this->error("删除的IP不能为空");
			$deletemac=I('deletemac','0','htmlspecialchars');
			if (empty($deletemac))$this->error("删除的mac地址不能为空");
			$ip_id_arr=I('ip_id','0','htmlspecialchars');
			if (empty($ip_id_arr))$this->error("删除的IP不能为空");
			foreach ($cloudipqosinfo as $k=>$v){
				if ($v['MAC']==$deletemac){
					$vif_uuid=$v['uuid'];
				}
			}
			if (empty($vif_uuid))$this->error("网卡vif_uuid错误");
			$money=$cloudproduct['moneyip'];
			$endmoney=($money/365)*$countdays*1;
			$endmoney=round($endmoney,2);
		}
		$ordernumber=getordcode();
		$data=array(
			'user_id'=>$this->uid,
			'username'=>$this->username,
			'ordernumber'=>$ordernumber,
			'type'=>1,
			'ordertype'=>"云主机调整",
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
				$endcpu=$cpunum-$num;
				$result=D('Cloudapi')->vmsetcpu($cloud['vm_id'],$endcpu);
				if ($result['status']=='failed')$this->error($result['value']);
				$data['cpunum']=$num;
				$data['logfor']='用户区调整云主机CPU(ID'.$cloud['id'].')（IP:'.$num.'核)';
				$forwhat="调整云主机".$cloud['cloudname'].")(产品ID".$cloud['id']."(订单号".$ordernumber.")".$act.'(IP:'.$num.'核)';
			break;
			case 'mem':
				$endmem=$memorynum-($num*1024);
				$result=D('Cloudapi')->vmsetmem($cloud['vm_id'],$endmem);
				if ($result['status']=='failed')$this->error($result['value']);
				$data['memnum']=$num*1024;
				$data['logfor']='用户区调整云主机MEM(ID'.$cloud['id'].')（内存:'.$num.'G)';
				$forwhat="调整云主机".$cloud['cloudname'].")(产品ID".$cloud['id']."(订单号".$ordernumber.")".$act.'(内存:'.$num.'G)';
			break;
			case 'qos':
				$result=D('Cloudapi')->vmnetworkupdate($cloud['vm_id'],$vif_uuid,$num*128);
				if ($result['status']=='failed')$this->error($result['value']);
				$data['qosnum']=$num;
				$data['logfor']='用户区调整云主机带宽(产品ID:'.$cloud['id'].')（带宽:'.$num.'M)';
				$forwhat="调整云主机".$cloud['cloudname'].")(产品ID".$cloud['id']."(订单号".$ordernumber.")".$act.'(带宽:'.$num.'M)';
			break;
			case 'ip':
				$ip_id_str=implode(",",$ip_id_arr);
				$result=D('Cloudapi')->vmnetworkremoveip($cloud['vm_id'],$vif_uuid,$ip_id_str);
				if ($result['status']=='failed')$this->error($result['value']);
				$data['ipnum']=1;
				$data['logfor']='用户区调整云主机IP(ID'.$cloud['id'].')（IP:'.$deleteip.')';
				$forwhat="调整云主机".$cloud['cloudname'].")(产品ID".$cloud['id']."(订单号".$ordernumber.")".$act.'(IP:'.$deleteip.')';
				
			break;
		}	
		$order=D('order')->add($data);
		$user_id=$this->uid;
        $money=$endmoney;
        $whichProduct="Cloud产品";
        $pid=$cloud['id'];//产品ID
        $type=7;//1在线充值 2后台入款 3后台扣除 4开通扣除 5续费扣除 6升级配置 7降级配置   10充值卡$type
        $pingzheng="";
        $acspace="用户区";
        $isadd=1;//1入款 2出 $isadd
        $ptype=$cloudproduct['Cloudtype'];//产品类型
        $orderid=$ordernumber;//交易号或者订单号
        $paddtime=$cloud['starttime']; //产品开通时间 $paddtime
        $endtime=$cloud['endtime'];  //产品到期时间 $pendtime 
        D('User')->addmoney($user_id,$money,$forwhat,$whichProduct,$pid,$type,$pingzheng,$acspace,$isadd,$ptype,$orderid,$paddtime,$endtime);
		sleep(5);
        D('Cloud')->updatevminfo($cloud['id'],$cloud['vm_id']);
		D('Cloud')->updatediskinfo($cloud['id'],$cloud['vm_id']);
		D('Cloud')->updateipqosinfo($cloud['id'],$cloud['vm_id']);
        $this->success("调整完成");
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
		$countdays=count_days($cloud['endtime'],strtotime("0 day"));
		$cloudvminfo=json_decode($cloud['vminfo'],true);
		$vminfo=$cloudvminfo['value'];
		$memory=$vminfo['memory'];
		$memory=$memory/1024;
		$memprice=D('Cloud')->count_price($cloudproduct['moneymemory'],$memory)-D('Cloud')->count_price($cloudproduct['moneymemory'],($memory-$num));
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
		$countdays=count_days($cloud['endtime'],strtotime("0 day"));
		$cloudvminfo=json_decode($cloud['vminfo'],true);
		$vminfo=$cloudvminfo['value'];
		$cpu=$vminfo['cpu'];
		$endcpu=($cpu-$num);
		$cpuprice=D('Cloud')->count_price($cloudproduct['moneycpu'],$cpu)-D('Cloud')->count_price($cloudproduct['moneycpu'],$endcpu);
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
		$countdays=count_days($cloud['endtime'],strtotime("0 day"));
		$qosprice=D('Cloud')->count_price($cloudproduct['moneyqos'],($qoscount))-D('Cloud')->count_price($cloudproduct['moneyqos'],$amountqos);
		$cloudpre=$this->usercloudpre($this->uid);
		$endmoney=($qosprice/365)*$countdays*$cloudpre;
		$endmoney=round($endmoney,2);
		$this->success($endmoney);
	}
}
?>