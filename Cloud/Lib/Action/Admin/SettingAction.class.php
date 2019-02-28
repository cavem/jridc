<?php
/**
 * 设置全局信息
 * Enter description here ...
 * @author Geyoulei
 *
 */
class SettingAction extends AdminAction{
    public function index(){
		//读取配置文件
		
    	$webConf = D('Config')->getCfgByModule('WEBSITE');
		$config = json_decode($webConf['WEBSITE'], true);
		$data['webConf'] = $config;
		$vercodeConf = D('Config')->getCfgByModule('CODE_SET');
		if(!empty($vercodeConf) && is_array($vercodeConf)){
			$vercodeConf['RECODESIZE'] = json_decode($vercodeConf['RECODESIZE'],true);
			$vercodeConf['BACODESIZE'] = json_decode($vercodeConf['BACODESIZE'],true);
		}
		$data['vercode'] = $vercodeConf;
		//邮箱设置
		$mailConf = D('Config')->getCfgByModule('MAILSET');
		$ary_mailconf = json_decode($mailConf['MAILSET'], true);
		$data['mailconf'] = $ary_mailconf;
		//支付方式
		$Alipay = D('Config')->getCfgByModule('Alipay');
		$ary_alipayconf = json_decode($Alipay['Alipay'], true);
		$data['Alipay'] = $ary_alipayconf;
		//支付方式
		$regset = D('Config')->getCfgByModule('REGSET');
		$ary_sconf = json_decode($regset['REGSET'], true);
		//发票配置
		$Fapiao = D('Config')->getCfgByModule('FAPIAO');
		$ary_fapiaoconf = json_decode($Fapiao['FAPIAO'], true);
		$data['Fapiao'] = $ary_fapiaoconf;
		
		$data['regconf'] = $ary_sconf;
		//2015-10-27 12-45更新自动续费费率
		$autofile=CONF_PATH."Autorun/config.php";
		if (is_file($autofile)) {
			$autovalue=include $autofile;
		} else {
			$autovalue  =array('autoday'=>180);
		}
		$data['Auto'] = $autovalue;
		$this->assign($data);
		$this->display();
	}
	//设置全局配置信息
	public function setconfig(){
		$ary_post = $this->_post();
		if(!empty($ary_post) && is_array($ary_post)){
			$module = "WEBSITE";
			$key = "WEBSITE";
			$value = json_encode($ary_post);
			$desc = "站点信息配置";
			$config = D("Config")->setConfig($module,$key,$value,$desc);
			if(FALSE !== $config){
				$this->success("保存成功",U("Admin/Setting/index",'#tabs-1'));
			}else{
				$this->error("保存失败");
			}
		}else{
			$this->error("数据有误");
		}
	}
	//设置验证码信息
	public function setcode(){
		$ary_post = $this->_post();
		$SysSeting = D('Config');
		if(!isset($ary_post['MREGISTER']) && empty($ary_post['MREGISTER'])){
			$ary_post['MREGISTER'] = '0';
		}
		if(!isset($ary_post['RELOGIN']) && empty($ary_post['RELOGIN'])){
			$ary_post['RELOGIN'] = '0';
		}
		if(!isset($ary_post['BALOGIN']) && empty($ary_post['BALOGIN'])){
			$ary_post['BALOGIN'] = '0';
		}
		if(!isset($ary_post['REWIDTH']) && empty($ary_post['REWIDTH']) && intval($ary_post['REWIDTH']) =='0'){
			$ary_post['REWIDTH'] = '70';
		}
		if(!isset($ary_post['REHEIGHT']) && empty($ary_post['REHEIGHT']) && intval($ary_post['REHEIGHT']) =='0'){
			$ary_post['REHEIGHT'] = '30';
		}
		if(!isset($ary_post['BAWIDTH']) && empty($ary_post['BAWIDTH']) && intval($ary_post['BAWIDTH']) =='0'){
			$ary_post['BAWIDTH'] = '100';
		}
		if(!isset($ary_post['BAHEIGHT']) && empty($ary_post['BAHEIGHT']) && intval($ary_post['BAHEIGHT']) =='0'){
			$ary_post['BAHEIGHT'] = '38';
		}
		$recodesize = array(
            'width' => $ary_post['REWIDTH'],
            'height' => $ary_post['REHEIGHT']
		);
		$ary_post['RECODESIZE'] = json_encode($recodesize);
		$becodesize = array(
            'width' => $ary_post['BAWIDTH'],
            'height' => $ary_post['BAHEIGHT']
		);
		$ary_post['BACODESIZE'] = json_encode($becodesize);
		$SysSeting->setConfig('CODE_SET', 'MREGISTER', $ary_post['MREGISTER'], '会员注册');
		$SysSeting->setConfig('CODE_SET', 'RELOGIN', $ary_post['RELOGIN'], '前台登陆');
		$SysSeting->setConfig('CODE_SET', 'BALOGIN', $ary_post['BALOGIN'], '后台登陆');
		$SysSeting->setConfig('CODE_SET', 'BUILDTYPE', $ary_post['BUILDTYPE'], '验证码生成类型') ;
		$SysSeting->setConfig('CODE_SET', 'EXPANDTYPE', $ary_post['EXPANDTYPE'], '选择验证码文件类型');
		$SysSeting->setConfig('CODE_SET', 'RECODESIZE', $ary_post['RECODESIZE'], '前台验证码图片大小');
		$SysSeting->setConfig('CODE_SET', 'BACODESIZE', $ary_post['BACODESIZE'], '后台验证码图片大小');
		$SysSeting->setConfig('CODE_SET', 'RECODENUMS', $ary_post['RECODENUMS'], '前台验证码字数');
		$SysSeting->setConfig('CODE_SET', 'BACODENUMS', $ary_post['BACODENUMS'], '后台验证码字数');
		$this->success("保存成功",U("Admin/Setting/index",'#tabs-2'));
	}
	//设置email
	public function setemail(){
		$ary_post = $this->_post();
		if(!empty($ary_post) && is_array($ary_post)){
			$module = "MAILSET";
			$key = "MAILSET";
			$value = json_encode($ary_post);
			$desc = "站点信息配置";
			$config = D("Config")->setConfig($module,$key,$value,$desc);
			if(FALSE !== $config){
				$this->success("保存成功",U("Admin/Setting/index",'#tabs-3'));
			}else{
				$this->error("保存失败");
			}
		}else{
			$this->error("数据有误");
		}
	}
	public function setuser(){
		$ary_post = $this->_post();
		if(!empty($ary_post) && is_array($ary_post)){
			$module = "REGSET";
			$key = "REGSET";
			if ($ary_post['regv']==3){
				//$ary_post['regcode']=1;
			}
			$value = json_encode($ary_post);
			$desc = "注册信息设置";
			$config = D("Config")->setConfig($module,$key,$value,$desc);
			if(FALSE !== $config){
				$this->success("保存成功",U("Admin/Setting/index",'#tabs-5'));
			}else{
				$this->error("保存失败");
			}
		}else{
			$this->error("数据有误");
		}
	}
	
	//设置支付宝信息
	public function setalipay(){
		$ary_post = $this->_post();
		if(!empty($ary_post) && is_array($ary_post)){
			$module = "Alipay";
			$key = "Alipay";
			$value = json_encode($ary_post);
			$desc = "支付宝配置";
			$config = D("Config")->setConfig($module,$key,$value,$desc);
			if(FALSE !== $config){
				$this->success("保存成功",U("Admin/Setting/index",'#tabs-4'));
			}else{
				$this->error("保存失败");
			}
		}else{
			$this->error("数据有误");
		}
		
	}
	//设置发票信息
	public function setfapiao(){
		$ary_post = $this->_post();
		if(!empty($ary_post) && is_array($ary_post)){
			$module = "FAPIAO";
			$key = "FAPIAO";
			$value = json_encode($ary_post);
			$desc = "发票配置";
			$config = D("Config")->setConfig($module,$key,$value,$desc);
			if(FALSE !== $config){
				$this->success("保存成功",U("Admin/Setting/index",'#tabs-6'));
			}else{
				$this->error("保存失败");
			}
		}else{
			$this->error("数据有误");
		}
		
	}
	public function setautoday(){
		$ary_post = $this->_post();
		if(!empty($ary_post) && is_array($ary_post)){
			$autoday=$ary_post['autoday'];
			if($autoday<1){
				$this->error("数值在1-365之间");
			}
			if($autoday>365){
				$this->error("数值在1-365之间");
			}
			$data=array('autoday'=>$autoday);
			F('config',$data,CONF_PATH."Autorun/");//写入配置文件
			$this->success("保存成功",U("Admin/Setting/index",'#tabs-7'));
		}else{
			$this->error("数据有误");
		}
		
	}
	//更新cache
	public function cache(){
		$this->display();
	}
	//清理缓存
	public function clear(){
		$type = $this->_get('type','trim');
		$obj_dir = new Dir();
		switch ($type) {
			case 'field':
				is_dir(DATA_PATH) && $obj_dir->delDir(DATA_PATH);
				break;
			case 'tpl':
				is_dir(CACHE_PATH) && $obj_dir->delDir(CACHE_PATH);
				break;
			case 'data':
				is_dir(DATA_PATH) && $obj_dir->delDir(DATA_PATH);
				is_dir(TEMP_PATH) && $obj_dir->delDir(TEMP_PATH);
				break;
			case 'runtime':
				@unlink(RUNTIME_FILE);
				break;
			case 'logs':
				is_dir(LOG_PATH) && $obj_dir->del(LOG_PATH);
				break;
			case 'Xenapi':
				unset($_SESSION['Xenapi']);
				unset($_SESSION['Xenapikey']);
				break;
		}
		$this->ajaxReturn(1);
	}
}
?>