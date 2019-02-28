<?php
/**
 * 云主机接口处理
 */
class CloudapiModel extends Model{
	protected $tableName = 'Cloud';	
	public function loginceshi(){
		$post['time']=time();
		$method="cloud/login";
		$result=$this->send($method, $post);
		if ($result['status']!='success') return $this->fail($result['info']);
		return $this->ok($result['value']);
	}
	public function cloudupdate($vm_id){
		$post['vm_id']=$vm_id;
		$method="cloud/update";
		$result=$this->send($method, $post);
		if ($result['status']!='success') return $this->fail($result['info']);
		return $this->ok($result['value']);
	}
	public function vmnetworkremoveip($vm_id,$vif_uuid,$ip_id){
		$post['vm_id']=$vm_id;
		$post['vif_uuid']=$vif_uuid;
		$post['ip_id']=$ip_id;
		$method="cloud/networkremoveip";
		$result=$this->send($method, $post);
		if ($result['status']!='success') return $this->fail($result['info']);
		return $this->ok($result['value']);
	}
	public function vmnetworkaddip($vm_id,$ipnum){
		$post['vm_id']=$vm_id;
		$post['ipnum']=$ipnum;
		$method="cloud/networkaddip";
		$result=$this->send($method, $post);
		if ($result['status']!='success') return $this->fail($result['info']);
		return $this->ok($result['value']);
	}
	public function vmnetworkupdate($vm_id,$vif_uuid,$qos){
		$post['vm_id']=$vm_id;
		$post['vif_uuid']=$vif_uuid;
		$post['qos']=$qos;
		$method="cloud/setqos";
		$result=$this->send($method, $post);
		if ($result['status']!='success') return $this->fail($result['info']);
		return $this->ok($result['value']);
	}
	public function vmsetdisk($vm_id,$disksize){
		$post['vm_id']=$vm_id;
		$post['disksize']=$disksize;
		$method="cloud/setdisk";
		$result=$this->send($method, $post);
		if ($result['status']!='success') return $this->fail($result['info']);
		return $this->ok($result['value']);
		
	}
	public function vmsetmem($vm_id,$mem){
		$post['vm_id']=$vm_id;
		$post['mem']=$mem;
		$method="cloud/setmem";
		$result=$this->send($method, $post);
		if ($result['status']!='success') return $this->fail($result['info']);
		return $this->ok($result['value']);
	
	}
	public function vmsetcpu($vm_id,$cpu){
		$post['vm_id']=$vm_id;
		$post['cpu']=$cpu;
		$method="cloud/setcpu";
		$result=$this->send($method, $post);
		if ($result['status']!='success') return $this->fail($result['info']);
		return $this->ok($result['value']);
	
	}
	/**
	 * 关机
	 * @param unknown_type $vm_id
	 */
	public function cloudstop($vm_id,$force=true){
		$post['vm_id']=$vm_id;
		$post['force']=$force;
		$method="cloud/stop";
		$result=$this->send($method, $post);
		if ($result['status']!='success') return $this->fail($result['info']);
		return $this->ok($result['value']);
	}
	/**
	 * 重启
	 * @param unknown_type $vm_id
	 */
	public function cloudreboot($vm_id,$force=true){
		$post['vm_id']=$vm_id;
		$post['force']=$force;
		$method="cloud/reboot";
		$result=$this->send($method, $post);
		if ($result['status']!='success') return $this->fail($result['info']);
		return $this->ok($result['value']);
	}
	/**
	 * 启动
	 * @param unknown_type $vm_id
	 */
	public function cloudstart($vm_id){
		$post['vm_id']=$vm_id;
		$method="cloud/start";
		$result=$this->send($method, $post);
		if ($result['status']!='success') return $this->fail($result['info']);
		return $this->ok($result['value']);
	}
	//快照list
	public function vmsnapshotlist($vm_id){
		$post['vm_id']=$vm_id;
		$method="cloud/snapshotlist";
		$result=$this->send($method, $post);
		if ($result['status']!='success') return $this->fail($result['info']);
		return $this->ok($result['value']);
	}
	public function repay($vm_id,$year){
		$post['vm_id']=$vm_id;
		$post['year']=$year;
		$method="cloud/repay";
		$result=$this->send($method, $post);
		if ($result['status']!='success') return $this->fail($result['info']);
		return $this->ok($result['value']);
	}
	/**
	 * 云主机存储列表
	 * @param unknown_type $masterid
	 * @param unknown_type $vm_id
	 */
	public function cloudstorage($vm_id){
		$post['vm_id']=$vm_id;
		$method="cloud/vmstorage";
		$result=$this->send($method, $post);
		if ($result['status']!='success') return $this->fail($result['info']);
		return $this->ok($result['value']);
	}
	
	/**
	 * 云主机网卡列表
	 * @param unknown_type $masterid
	 * @param unknown_type $vm_id
	 */
	public function cloudnetwork($vm_id){
		$post['vm_id']=$vm_id;
		$method="cloud/network";
		$result=$this->send($method, $post);
		if ($result['status']!='success') return $this->fail($result['info']);
		return $this->ok($result['value']);
	}
	/**
	 * 获取云主机的信息
	 * Enter description here ...
	 * @param unknown_type $masterid
	 * @param unknown_type $vm_id
	 */
	public function cloudinfo($vm_id){
		$post['vm_id']=$vm_id;
		$method="cloud/vminfo";
		$result=$this->send($method, $post);
		if ($result['status']!='success') return $this->fail($result['info']);
		return $this->ok($result['value']);
	}
	//获取系统模版
	public function systemtemplate($masterid,$cid,$ostype){
		$post['masterid']=$masterid;
		$post['cid']=$cid;
		$post['ostype']=$ostype;
		$method="cloud/systemtemplate"; //获取系统模版
		$result=$this->send($method, $post);
		if ($result['status']!='success') return $this->fail($result['info']);
		return $this->ok($result['value']);
	}
	public function getprodcut($pid){
		if (!empty($pid)){
			$post['pid']=$pid;
		}
		$method="cloud/product"; //获取配置产品信息
		$result=$this->send($method, $post);
		if ($result['status']!='success') return $this->fail($result['info']);
		return $this->ok($result['value']);
	}
	public function cloudcreate($pid,$vm_name,$password,$n_cpu,$m_memory,$bandwidth,$iptype,$data_disk_size,$vdi_uuid,$year){
		$post['pid']=$pid;
		$post['vm_name']=$vm_name;
		$post['password']=$password;
		$post['n_cpu']=$n_cpu;
		$post['m_memory']=$m_memory;
		$post['bandwidth']=$bandwidth;
		$post['iptype']=$iptype;
		$post['data_disk_size']=$data_disk_size;
		$post['vdi_uuid']=$vdi_uuid;
		$post['year']=$year;
		$method="cloud/create";
		$result=$this->send($method, $post);
		if ($result['status']!='success') return $this->fail($result['info']);
		return $this->ok($result['value']);
	}
	public function cloudcreateajax($pid,$vm_name,$password,$n_cpu,$m_memory,$bandwidth,$iptype,$data_disk_size,$vdi_uuid,$year,$orderid){
		$post['pid']=$pid;
		$post['vm_name']=$vm_name;
		$post['password']=$password;
		$post['n_cpu']=$n_cpu;
		$post['m_memory']=$m_memory;
		$post['bandwidth']=$bandwidth;
		$post['iptype']=$iptype;
		$post['data_disk_size']=$data_disk_size;
		$post['vdi_uuid']=$vdi_uuid;
		$post['year']=$year;
		$post['orderid']=$orderid;
		$method="cloud/ajaxcreate";
		$result=$this->send($method, $post);
		if ($result['status']!='success') return $this->fail($result['info']);
		return $this->ok($result['value']);
	}
	public function getsign($data,$apikey){
			unset($data['apiusername']);
		    unset($data['sign']);
		    $param_keys = array_keys($data);
		    sort($param_keys);
			foreach($param_keys as $k=>$v){
				$signpost.=$v.'='.urlencode($data[$v])."&";
			}
			$signpost .= 'apikey=' . $apikey;
			$sign = md5($signpost);
			return $sign;
	}
	//生成随机数
	public function randstrs()
	{   
		$length=20;
		$chars= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
		$max=strlen($chars)-1;   
		if(phpversion()<5.3){
			mt_srand((double)microtime()*1000000); 
		} 
		for($i=0;$i<$length;$i++){   
			$hash.=$chars[mt_rand(0,$max)];   
		}   
		return $hash;   
	}
	public function send($method,$post){
		$post['randomstring']=$this->randstrs();
		foreach($post as $key=>$value){
			$posturl.=$key.'='.urlencode($value)."&";
		}
		$sign=$this->getsign($post,C('APIKEY'));
		$posturl .= 'sign=' .$sign;
		$posturl=$posturl."&apiusername=".C('APIUSERNAME');
		$host=C('API_HOST').'/'.$method;
		$ch = curl_init($host);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_TIMEOUT,1800);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $posturl);
		$resp = curl_exec($ch);
		$error = curl_error($ch);
		if($error){
			Log::write($host.$posturl."/".$error, "API_Info_Err");
        	return json_decode('{"status": "failed", "info": "操作超时,如仍异常请联系技术人员"}', true);
		}
		curl_close($ch);
		return json_decode($resp, true);
	}
	public function ok($info){
		return array('status'=>'success','value'=>$info);//返回错误信息
	}
	public function fail($info){
		return array('status'=>'failed','value'=>$info);//返回错误信息
		
	}
	
}


