<?php
/**
 * 云主机模型
 */
class CloudModel extends Model{
	/**
	 * 获取时间
	 * @param 产品ID $pid
	 * @param 时间段 $year
	 * @param 当前时间 $starttime
	 */
	public function cloudyear($pid,$year,$starttime){
		$cloudproduct=D('cloud_product')->where(array('id'=>$pid))->field()->find();
		
		if ($cloudproduct['PAY_Nextyear']==$year){
			$endtime=strtotime("+1 year", $starttime);
		}
		if ($cloudproduct['PAY_2year']==$year){
			$endtime=strtotime("+2 year", $starttime);
		}
		if ($cloudproduct['PAY_3year']==$year){
			$endtime=strtotime("+3 year", $starttime);
		}
		if ($cloudproduct['PAY_4year']==$year){
			$endtime=strtotime("+4 year", $starttime);
		}
		if ($cloudproduct['PAY_5year']==$year){
			$endtime=strtotime("+5 year", $starttime);
		}
		if ($cloudproduct['PAY_Month']==$year){
			$endtime=strtotime("+31 day", $starttime);
		}
		if ($cloudproduct['PAY_Season']==$year){
			$endtime=strtotime("+92 day", $starttime);
		}
		if ($cloudproduct['PAY_halfyear']==$year){
			$endtime=strtotime("+182 day", $starttime);
		}
		if ($year==999){
			$contestday=$cloudproduct['contestday'];
			$endtime=strtotime("+$contestday day", $starttime);
		}
		return $endtime;
	}
	public function getyear($pid,$year){
		$cloudproduct=D('cloud_product')->where(array('id'=>$pid))->field()->find();
		if ($cloudproduct['PAY_Nextyear']==$year){
			$endyear=1;
		}
		if ($cloudproduct['PAY_2year']==$year){
			$endyear=2;
		}
		if ($cloudproduct['PAY_3year']==$year){
			$endyear=3;
		}
		if ($cloudproduct['PAY_4year']==$year){
			$endyear=4;
		}
		if ($cloudproduct['PAY_5year']==$year){
			$endyear=5;
		}
		if ($cloudproduct['PAY_Month']==$year){
			$endyear=6;
		}
		if ($cloudproduct['PAY_Season']==$year){
			$endyear=7;
		}
		if ($cloudproduct['PAY_halfyear']==$year){
			$endyear=8;
		}
		return $endyear;
	
	
	}
	/**
	 * 更新产品的云主机信息
	 * @param unknown_type $pid
	 * @param unknown_type $vm_id
	 */
	public function updatevminfo($pid,$vm_id){
		$cloud=D('Cloud')->where(array('id'=>$pid,'vm_id'=>$vm_id))->find();
		$vminfo=D('Cloudapi')->cloudinfo($cloud['vm_id']);//云主机详情
		if ($vminfo['status']!='failed')$updatedata['vminfo']=json_encode($vminfo);
		D('Cloud')->where(array('id'=>$pid,'vm_id'=>$vm_id))->save($updatedata);
	}
	/**
	 * 更新产品的云主机硬盘信息
	 * @param unknown_type $pid
	 * @param unknown_type $vm_id
	 */
	public function updatediskinfo($pid,$vm_id){
		$cloud=D('Cloud')->where(array('id'=>$pid,'vm_id'=>$vm_id))->find();
		$diskinfo=D('Cloudapi')->cloudstorage($cloud['vm_id']);
		if ($diskinfo['status']!='failed')$updatedata['diskinfo']=json_encode($diskinfo);
		D('Cloud')->where(array('id'=>$pid,'vm_id'=>$vm_id))->save($updatedata);
	}
	/**
	 * 更新产品的云主机带宽和IP信息
	 * @param unknown_type $pid
	 * @param unknown_type $vm_id
	 */
	public function updateipqosinfo($pid,$vm_id){
		$cloud=D('Cloud')->where(array('id'=>$pid,'vm_id'=>$vm_id))->find();
		$ipqosinfo=D('Cloudapi')->cloudnetwork($cloud['vm_id']);//云主机详情
		if ($ipqosinfo['status']!='failed')$updatedata['ipqosinfo']=json_encode($ipqosinfo);
		D('Cloud')->where(array('id'=>$pid,'vm_id'=>$vm_id))->save($updatedata);
	}
	//获取IP类型下的产品
	public function productiptype(){
		$isdefalt=C('defaultiptype');
		$cloudproduct=D('cloud_product')->where(array('status'=>1))->field("iptype")->select();
		foreach ($cloudproduct as $v){
	         $iptype[] = $v['iptype'];
	    }
		$iptype = array_unique($iptype);    //去掉重复的字符串,也就是重复的一维数组
		$iparr=array();
	    foreach ($iptype as $k => $v){
	    	if ($v==1)$ipname="BGP";
	    	if ($v==2)$ipname="香港";
	    	if ($v==3)$ipname="双线";
	    	if ($v==4)$ipname="电信";
	    	if ($v==5)$ipname="联通";
	    	if ($v==6)$ipname="国外";
	    	if ($v==7)$ipname="移动";
	    	if ($v==8)$ipname="高防";
	    	$iparr[$v]['iptype'] =$v;
	    	$iparr[$v]['ipname'] =$ipname;
	    	if ($isdefalt==$v){
	    		$iparr[$v]['ipdefalt'] =1;
	    	}else{
	    		$iparr[$v]['ipdefalt'] =0;
	    	}
	    }
		return 	$iparr;
	}
	/*
		云主机一年的价格。不计算折扣的情况
		ID 产品ID
	*/
	public function cloudrepayprice($id,$cpu,$mem,$disk,$ipnum,$qos,$user_id){
			$year=1;
			if ($user_id){
					 $user=D('user')->where(array('user_id'=>$user_id))->find();
					 if (!$user) return false;
					 //查询当前用户组的user_rank
					 $user_rank=D('user_rank')->where(array('rank_id'=>$user['user_rank']))->find();
					 if (!$user_rank) return false;
					 $cloud_pre=$user_rank['cloud_pers']  ;
				}else{
					 $cloud_pre=1;
			}
			$cloudproduct=D('cloud_product')->where(array('id'=>$id))->find();
			$prodcutapi=D('Cloudapi')->getprodcut($cloudproduct['pid']);
			//计算基础价格
			$usermoney=$cloudproduct['usermoney']*$cloud_pre*$year;
			$cpumoney=$this->count_price($cloudproduct['moneycpu'],$cpu)*$cloud_pre*$year;//阶梯定价
			$memmoney=$this->count_price($cloudproduct['moneymemory'],($mem/1024))*$cloud_pre*$year;//阶梯定价
			$diskmoney=$cloudproduct['moneydisk']*$cloud_pre*$year*($disk-$prodcutapi['value']['ddisk']);
			if ($diskmoney<0)$diskmoney=0;
			$qosmoney=0;
			foreach($qos as $kk=>$vv){
				if($vv['shared']==false){
					$qos=$vv['qos']/128;  
					$qosmoney=$qosmoney+($this->count_price($cloudproduct['moneyqos'],$qos)-$this->count_price($cloudproduct['moneyqos'], $prodcutapi['value']['dqos']))*$cloud_pre*$year;
				}
			}
			if ($qosmoney<0)$qosmoney=0;
			$ipmoney=$cloudproduct['moneyip']*$year*$ipnum;
			$endmoney=$usermoney+$cpumoney+$memmoney+$diskmoney+$qosmoney+$ipmoney;
			$endmoney=round($endmoney,2);
			return $endmoney;
	}
	//计算云主机价格开通时使用
	public function cloudprice($id,$cpu,$mem,$disk,$ip,$qos,$year,$user_id){
			//首先判断用户是否登录
			if ($user_id){
				 $user=D('user')->where(array('user_id'=>$user_id))->find();
				 if (!$user) return false;
				 //查询当前用户组的user_rank
				 $user_rank=D('user_rank')->where(array('rank_id'=>$user['user_rank']))->find();
				 if (!$user_rank) return false;
				 $cloud_pre=$user_rank['cloud_pers']  ;
			}else{
				 $cloud_pre=1;
			}
			$cloudproduct=D('cloud_product')->where(array('id'=>$id))->find();
			$prodcutapi=D('Cloudapi')->getprodcut($cloudproduct['pid']);
			$usermoney=$cloudproduct['usermoney']*$cloud_pre*$year;
			$cpumoney=$this->count_price($cloudproduct['moneycpu'],$cpu)*$cloud_pre*$year;//阶梯定价
			$memmoney=$this->count_price($cloudproduct['moneymemory'],($mem/1024))*$cloud_pre*$year;//阶梯定价
			$diskmoney=$cloudproduct['moneydisk']*$cloud_pre*$year*($disk-$prodcutapi['value']['ddisk']);
			if ($diskmoney<0)$diskmoney=0;
			$qosmoney=($this->count_price($cloudproduct['moneyqos'],$qos)-$this->count_price($cloudproduct['moneyqos'],$prodcutapi['value']['dqos']))*$cloud_pre*$year;//阶梯定价
			if ($qosmoney<0)$qosmoney=0;
			if ($ip==1){
				$ipmoney=$cloudproduct['moneyip']*$year;
			}else{
			    $ipmoney=0;
			}
			$endmoney=$usermoney+$cpumoney+$memmoney+$diskmoney+$qosmoney+$ipmoney;
			$endmoney=round($endmoney,2);
			if ($endmoney<0)return false;
			if($year==$cloudproduct['PAY_Month'])$YZheKouTest="PAY_Month";
			if($year==$cloudproduct['PAY_Season'])$YZheKouTest="PAY_Season";
			if($year==$cloudproduct['PAY_halfyear'])$YZheKouTest="PAY_halfyear";
			if($year==$cloudproduct['PAY_Nextyear'])$YZheKouTest="PAY_1year";
			if($year==$cloudproduct['PAY_2year'])$YZheKouTest="PAY_2year";
			if($year==$cloudproduct['PAY_3year'])$YZheKouTest="PAY_3year";
			if($year==$cloudproduct['PAY_4year'])$YZheKouTest="PAY_4year";
			if($year==$cloudproduct['PAY_5year'])$YZheKouTest="PAY_5year";
			$cloudzk=D('cloud_zk')->where(array('productid'=>$id,'zktext'=>$YZheKouTest,'status'=>1))->find();
			if ($cloudzk){
				$data=array(
				'Priceold'=>$endmoney,
				'Price'=>round($cloudzk['zkpre']*$endmoney,2),
				'YName'=>$cloudzk['zkname'],
				'YZheKou'=>$cloudzk['zkpre']
				);
			}else{
				$data=array(
				'Priceold'=>$endmoney,
				'Price'=>$endmoney,
				'YName'=>'',
				'YZheKou'=>''
				);
			}
			return $data;
	}
	/**
	 * 阶梯求价格
	 * Enter description here ...
	 * @param unknown_type $price_str
	 * @param unknown_type $str_num
	 */
	public function count_price($price_str,$str_num) {
		$price_str_arr = explode('|',$price_str);
		$arr=array();
		foreach($price_str_arr as $key=>$value){
			$value_arr=explode('<=',$value); //创建数组
			$arr[$value_arr[0]]=$value_arr[1];
		}
		$arr_new=array_filter($arr);
		$calculated_count = 0;
		$price_count = 0;
		$_value = 0;
		foreach($arr_new as $key=>$value) {
			$_count = $str_num - $key;
			if($_count < 0){
				$_value = $value;
				continue;
			}
			$_count -= $calculated_count;
			$calculated_count += $_count;
			$price_count += $_count * $_value;
			$_value = $value;
		}
		$price_count += ($str_num - $calculated_count) * $value;
		return $price_count;
	}
	/**
	 * 验证周期是否正确 
	 * @param  $pid
	 * @param  $year
	 */
	public function checkyear($pid,$year){
		$cloudproduct=D('cloud_product')->where(array('id'=>$pid))->find();
		if (empty($cloudproduct))return false;
		$check=false;
		if($year==$cloudproduct['PAY_Month'])$check=true;
		if($year==$cloudproduct['PAY_Season'])$check=true;
		if($year==$cloudproduct['PAY_halfyear'])$check=true;
		if($year==$cloudproduct['PAY_Nextyear'])$check=true;
		if($year==$cloudproduct['PAY_2year'])$check=true;
		if($year==$cloudproduct['PAY_3year'])$check=true;
		if($year==$cloudproduct['PAY_4year'])$check=true;
		if($year==$cloudproduct['PAY_5year'])$check=true;
		if($year==999)$check=true;
		return $check;
	}
	
}