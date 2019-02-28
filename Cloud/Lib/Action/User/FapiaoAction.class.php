<?php
//发票管理
class FapiaoAction extends MainuserAction{
	public function index(){
		$ary_get = $this->_get();
		Load('extend');
		$ary_get['starttime'] = I('get.starttime','');
		$ary_get['starttime']=remove_xss($ary_get['starttime']);
		$ary_get['endtime'] = I('get.endtime','');
		$ary_get['endtime']=remove_xss($ary_get['endtime']);
		$ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', 10);
		$fapiao = D("fapiao");		
		$where="user_id=".$this->uid;
		if (!empty($ary_get['status'])){
			$ary_get['status']=I('get.status','0','intval');//预防sql注入
			$where=$where." and state=".$ary_get['status'];
		}
		if(!empty($ary_get['starttime'])){
			$starttime=str_replace("+"," ",$ary_get['starttime']);	
			$starttime=convert_datefm($starttime);
			$starttime=intval($starttime);//预防sql注入3-25
			$where=$where." and add_time >=".$starttime;
			$this->assign("starttime",date('Y-m-d', $starttime));
		}
		if(!empty($ary_get['endtime'])){
			$endtime=str_replace("+"," ",$ary_get['endtime']);	
			$endtime=convert_datefm($endtime);
			$endtime=intval($endtime);//预防sql注入3-25
			$where=$where." and add_time <=".($endtime+86400);
			$this->assign("endtime",date('Y-m-d', $endtime));
		}
		
		$count = $fapiao->where($where)->count();
		$obj_page = $this->UserPage($count, $ary_get['pageall']);
		$page = $obj_page->show();
		$ary_data = $fapiao->where($where)->limit($obj_page->firstRow, $obj_page->listRows)->order("fid desc")->select();
		foreach ($ary_data as $k=>$v){
			$ary_data[$k]['info']=unserialize($v['infoarr']);
		}
		$countmoney=D("fapiao")->where(array('user_id'=>$this->uid))->sum('money');//计算出当前总申请金额
		$this->assign("status",$ary_get['status']);
		$this->assign("data",$ary_data);
		$this->assign("filter",$ary_get);
		$this->assign("pageinfo",$page);
		$this->assign("countmoney",$countmoney);
		$this->display();
	}
	public function show(){
		$ary_get = $this->_get();
		if (!$ary_get['fid'])$this->error("数据错误");
		$fid=$ary_get['fid'];
		$fapiao = D("fapiao")->where(array('user_id'=>$this->uid,'fid'=>$fid))->find();
		if (!$fapiao)$this->error("数据不存在");
		$fapiao['info']=unserialize($fapiao['infoarr']);
		$this->assign("data",$fapiao);
		$this->display();
	}
	public function addnextsave(){
		if(!IS_POST)$this->error("数据错误");
		$data=$_POST;
		//xss数据库清理20160405
		foreach ($data as $pk=>$pv){
			$data[$pk]=remove_xss($pv);			
		}
		if (!$data)$this->error("数据错误");
		if (empty($data['tid']))$this->error("tid错误");
		if (empty($data['addressid']))$this->error("请选择收货地址");	
		if (!isset($data['ifkuaidi']))$this->error("请选择收取方式");
		$transaction = D("money_log");
		$where="user_id=".$this->uid." and isadd=1 and (type=1 or type=2) and id in(".$data['tid'].")";
		$countmoney=$transaction->where($where)->sum('usermoney');//计算出当前总申请金额
		if (!$countmoney)$this->error("金额计算错误");
		if ($countmoney<0)$this->error("金额错误");
		//计算金额
		//2015-7-3
		$Fapiao = D('Config')->getCfgByModule('FAPIAO');
		$Fapiaoconf = json_decode($Fapiao['FAPIAO'], true);	
		if (!empty($Fapiaoconf['MinFapiaomoney'])){
			if ($Fapiaoconf['MinFapiaomoney']>$countmoney){
				$this->error("申请发票金额不能小于".$Fapiaoconf['MinFapiaomoney']."元");
			}
		}
		$endmoney=($countmoney*$Fapiaoconf['FapiaoPaypercent'])/100;
		if ($data['ifkuaidi']==1){
			$endmoney=$endmoney+$Fapiaoconf['Fapiaokuai'];
		}
		$endmoney=round($endmoney,2);
		$userorder=$this->userinfo($this->uid);
		if ($endmoney>$userorder['usermoney']){
			$this->error("账户余额不够支付当前所付费用",U('User/Pay/index'));
		}
		//2015-7-3
		$dataarr['user_id']=$this->uid;
		$dataarr['money']=$countmoney;
		$dataarr['ifkuaidi']=$data['ifkuaidi'];
		$dataarr['tranarrid']=$data['tid'];
		$dataarr['state']=1;
		$dataarr['add_time']=time();
		
		//发票信息
		$datainfo=M('fapiao_info')->where(array('user_id'=>$this->uid))->select();
		if(!$datainfo)$this->error("请添加开票信息后在提交");
		$dataarr['infoarr']=serialize($datainfo[0]);
		//获取地址信息
		$ary_data = D("fapiao_dizhi")->where(array('user_id'=>$this->uid,'did'=>$data['addressid']))->find();
		if (!$ary_data)$this->error("寄送地址不存在");
		$region=M('region');
		$province=$region->where(array('id'=>$ary_data['province']))->find();
		$city=$region->where(array('id'=>$ary_data['city']))->find();
		$town=$region->where(array('id'=>$ary_data['town']))->find();
		$ary_data['provincename']=$province['name'];
		$ary_data['cityname']=$city['name'];
		$ary_data['townname']=$town['name'];
		$dataarr['addressee']=$ary_data['addressee'];
		$dataarr['phone']=$ary_data['phone'];
		$dataarr['pcts']=$ary_data['provincename'].'-'.$ary_data['cityname'].'-'.$ary_data['townname'].'-'.$ary_data['streetaddress'];
		$dataarr['Postcode']=$ary_data['Postcode'];
		//处理当前tid是否申请过
		$fapiao = D("fapiao")->where(array('user_id'=>$this->uid))->field('tranarrid')->select();
		$endstr="";
		foreach ($fapiao as $k=>$v){
			if ($endstr==""){
				$endstr=$v['tranarrid'];
			}else{
				$endstr=$endstr.",".$v['tranarrid'];
			}
		}	
		if (!empty($endstr)){
			$endstrarr=explode(',',$endstr);
			$tidstrarr=explode(',',$data['tid']);
			$arr=array_intersect($endstrarr,$tidstrarr);
			if(!empty($arr))$this->error("数据ID已存在");
		}
		mt_srand((double) microtime() * 1000000);
	 	$ordcode=date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
		$dataarr['fapiaonumber']=$ordcode;
		//处理当前tid是否申请过
		$addinfo=M('fapiao')->add($dataarr);
		//财务处理 
		if (!empty($endmoney)){
			$user_id=$this->uid;
	        $money=$endmoney;
	        $forwhat="在线申请发票扣除费用(".$endmoney."元,发票编号:)".$ordcode;
	        $whichProduct="发票申请";
	        $pid=$addinfo;//产品ID
	        $type=20;//1在线充值 2后台入款 3后台扣除 4开通扣除 5续费扣除 6升级配置 7降级配置   10充值卡$type 
	        $pingzheng="";
	        $acspace="用户区";
	        $isadd=2;//1入款 2出 $isadd
	        $ptype="发票申请";//产品类型
	        $orderid=$ordcode;
	        $paddtime=time(); 
	        $endtime=time();
	        D('User')->addmoney($user_id,$money,$forwhat,$whichProduct,$pid,$type,$pingzheng,$acspace,$isadd,$ptype,$orderid,$paddtime,$endtime);
		}
		//财务处理 结束
		$this->success('提交成功!',U('User/Fapiao/index'));	
	}
	public function addnext(){
		if(!IS_POST)$this->error("数据错误");
		$data=$_POST;
		foreach ($data as $pk=>$pv){
			$data[$pk]=remove_xss($pv);			
		}
		if (!$data['selectfid'])$this->error("数据错误");
		$tid=$data['selectfid'];//当前需要申请发票的财务ID
		$tidstr=implode(',', $tid);
		//处理当前tid是否申请过
		$fapiao = D("fapiao")->where(array('user_id'=>$this->uid))->field('tranarrid')->select();
		$endstr="";
		foreach ($fapiao as $k=>$v){
			if ($endstr==""){
				$endstr=$v['tranarrid'];
			}else{
				$endstr=$endstr.",".$v['tranarrid'];
			}
		}	
		if (!empty($endstr)){
			$endstrarr=explode(',',$endstr);
			$tidstrarr=explode(',',$tidstr);
			$arr=array_intersect($endstrarr,$tidstrarr);
			if(!empty($arr))$this->error("数据已存在");
		}
		$transaction = D("money_log");
		$where="user_id=".$this->uid." and isadd=1 and (type=1 or type=2) and id in(".$tidstr.")";
		$countmoney=$transaction->where($where)->sum('usermoney');//计算出当前总申请金额
		$data=M('fapiao_info')->where(array('user_id'=>$this->uid))->select();
		if(!$data)$this->error("请添加开票信息后在提交");
		$info=$data[0];
		if ($info['state']==1)$this->error("开票信息尚在审核中,暂时无法申请发票");
		//寄送地址
		$ary_data = D("fapiao_dizhi")->where(array('user_id'=>$this->uid))->select();
		$region=M('region');
		foreach ($ary_data as $k=>$v){
			$province=$region->where(array('id'=>$v['province']))->find();
			$city=$region->where(array('id'=>$v['city']))->find();
			$town=$region->where(array('id'=>$v['town']))->find();
			$ary_data[$k]['provincename']=$province['name'];
			$ary_data[$k]['cityname']=$city['name'];
			$ary_data[$k]['townname']=$town['name'];
		}
		
		$Fapiao = D('Config')->getCfgByModule('FAPIAO');
		$Fapiaoconf = json_decode($Fapiao['FAPIAO'], true);	
		if (!empty($Fapiaoconf['MinFapiaomoney'])){
			if ($Fapiaoconf['MinFapiaomoney']>$countmoney){
				$this->error("申请发票金额不能小于".$Fapiaoconf['MinFapiaomoney']."元");
			}
		}
		$paymoney=($countmoney*$Fapiaoconf['FapiaoPaypercent'])/100;
		$paymoney=round($paymoney,2);
		$this->assign("paymoney",$paymoney);
		$this->assign("Fapiaoconf",$Fapiaoconf);
		$this->assign("address",$ary_data);
		//寄送地址
		$this->assign("info",$info);
		$this->assign("count",count($tid));
		$this->assign("tid",$tidstr);
		$this->assign("countmoney",$countmoney);
		
		$this->display();
	}
	public function add(){
		$Fapiao = D('Config')->getCfgByModule('FAPIAO');
		$Fapiaoconf = json_decode($Fapiao['FAPIAO'], true);		
		$fapiao = D("fapiao")->where(array('user_id'=>$this->uid))->field('tranarrid')->select();
		$endstr="";
		foreach ($fapiao as $k=>$v){
			if ($endstr==""){
				$endstr=$v['tranarrid'];
			}else{
				$endstr=$endstr.",".$v['tranarrid'];
			}
		}
		$transaction = D("money_log");
		$where="user_id=".$this->uid." and isadd=1 and (type=1 or type=2)";
		if (!empty($endstr))$where="user_id=".$this->uid." and isadd=1 and (type=1 or type=2) and id not in(".$endstr.")";		
		$tdata=$transaction->where($where)->select();
		$countmoney=$transaction->where($where)->sum('usermoney');
		$this->assign("countmoney",$countmoney);
		$this->assign("Fapiaoconf",$Fapiaoconf);
		$this->assign("data",$tdata);
		$this->display();
	}
	//发票信息
	public function info(){
		$data=M('fapiao_info')->where(array('user_id'=>$this->uid))->select();
		if($data){
			$info=$data[0];
			$this->assign("data",$info);
			$this->display('infoupdate');
		}else{
			$this->display();
		}
	}
	public function infoupdate(){
		if(!IS_POST)$this->error("数据错误");
		$data=$_POST;
		foreach ($data as $pk=>$pv){
			$data[$pk]=remove_xss($pv);			
		}
		$file=$_FILES;
		if(!empty($file)){
			if($file['businesslicense']['error']==0 || $file['taxregistration']['error']==0 || $file['taxpayercopy']['error']==0){
			import('ORG.Net.UploadFile');
			$upload = new UploadFile();// 实例化上传类
			$upload->maxSize  = 3145728 ;// 设置附件上传大小
			$upload->saveRule  = time ;
			$upload->uploadReplace  = true ;// 设置附件上传大小
			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
			$uploadsaveurl='./Upload/fapiao/'.$this->username.'/'.date("Ymd").'/';//存放数据库地址
			$uploadsavepath=$uploadsaveurl;
			make_dir($uploadsavepath);//创建目录
			$upload->savePath = $uploadsavepath ;// 设置附件上传目录
			 if(!$upload->upload()) {// 上传错误提示错误信息
				$this->error($upload->getErrorMsg());
			 }else{// 上传成功 获取上传文件信息
				$info =  $upload->getUploadFileInfo();
			 }
			 import('ORG.Util.Image');
			 $Image = new Image();
			 foreach($info as $k=>$v){
				 $uploadsaveurl = str_replace("./","/",$uploadsaveurl);
				 if($v['key']=='businesslicense'){
				 	 $Image->water(ROOT_PATH.$uploadsaveurl.$v['savename'],ROOT_PATH.'Upload/water.png');
					 $data['businesslicense']=$uploadsaveurl.$v['savename'];
				 }
				 if($v['key']=='taxregistration'){
				 	 $Image->water(ROOT_PATH.$uploadsaveurl.$v['savename'],ROOT_PATH.'Upload/water.png');
					 $data['taxregistration']=$uploadsaveurl.$v['savename'];
				 }
				 if($v['key']=='taxpayercopy'){
				 	 $Image->water(ROOT_PATH.$uploadsaveurl.$v['savename'],ROOT_PATH.'Upload/water.png');
					 $data['taxpayercopy']=$uploadsaveurl.$v['savename'];
				 }
			 }
			 $data['state']=1;
			}
		}
		$id=$data['iid'];
		unset($data['iid']);
		$fapiaoinfo=M('fapiao_info');
		$updateinfo=$fapiaoinfo->where(array('user_id'=>$this->uid,'iid'=>$id))->save($data);
		$this->success('更新成功!',U('User/Fapiao/info'));
	}
	//添加信息保存
	public function doinfo(){
		if(!IS_POST)$this->error("数据错误");
		$data=$_POST;
		$infocount=D('fapiao_info')->where(array('user_id'=>$this->uid))->count();
		if ($infocount>0)$this->error('已存在开票信息');
		foreach ($data as $pk=>$pv){
			$data[$pk]=remove_xss($pv);			
		}
		if($data['opentype']==2 && $data['infotype']==2){
			$file=$_FILES;
			if(empty($file['businesslicense']["name"]))$this->error("请选择营业执照复印件");
			if(empty($file['taxregistration']["name"]))$this->error("请选择税务登记复印件");
			if(empty($file['taxpayercopy']["name"]))$this->error("请选择一般纳税人资格认证复印件");
			import('ORG.Net.UploadFile');
			$upload = new UploadFile();// 实例化上传类
			$upload->maxSize  = 3145728 ;// 设置附件上传大小
			$upload->saveRule  = time ;
			$upload->uploadReplace  = true ;// 设置附件上传大小
			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
			$uploadsaveurl='./Upload/fapiao/'.$this->username.'/'.date("Ymd").'/';//存放数据库地址
			$uploadsavepath=$uploadsaveurl;
			make_dir($uploadsavepath);//创建目录
			$upload->savePath = $uploadsavepath ;// 设置附件上传目录
			 if(!$upload->upload()) {// 上传错误提示错误信息
				$this->error($upload->getErrorMsg());
			 }else{// 上传成功 获取上传文件信息
				$info =  $upload->getUploadFileInfo();
			 }
			 import('ORG.Util.Image');
			 $Image = new Image();
			 foreach($info as $k=>$v){
				 $uploadsaveurl = str_replace("./","/",$uploadsaveurl);
				 if($v['key']=='businesslicense'){
				  	 $Image->water(ROOT_PATH.$uploadsaveurl.$v['savename'],ROOT_PATH.'Upload/water.png');
					 $data['businesslicense']=$uploadsaveurl.$v['savename'];
				 }
				 if($v['key']=='taxregistration'){
				 	$Image->water(ROOT_PATH.$uploadsaveurl.$v['savename'],ROOT_PATH.'Upload/water.png');
					 $data['taxregistration']=$uploadsaveurl.$v['savename'];
				 }
				 if($v['key']=='taxpayercopy'){
				 	 $Image->water(ROOT_PATH.$uploadsaveurl.$v['savename'],ROOT_PATH.'Upload/water.png');
					 $data['taxpayercopy']=$uploadsaveurl.$v['savename'];
				 }
			 }
			 $data['state']=1;
		}else{
			$data['state']=2;
		}
		$data['user_id']=$this->uid;
		$fapiaoinfo=M('fapiao_info');
		$addinfo=$fapiaoinfo->add($data);
		$this->success('添加成功!',U('User/Fapiao/info'));
	}
	public function address(){
		$ary_get=$_GET;
		$where="user_id=".$this->uid;
		$count = M("fapiao_dizhi")->where($where)->count();
		$obj_page =$this->UserPage($count,10);
        $pageinfo = $obj_page->show();
        $data = M("fapiao_dizhi")
			    ->where($where)
        		->limit($obj_page->firstRow, $obj_page->listRows)
				->order("did desc")
				->select();
		$region=M('region');
		foreach ($data as $k=>$v){
			$province=$region->where(array('id'=>$v['province']))->find();
			$city=$region->where(array('id'=>$v['city']))->find();
			$town=$region->where(array('id'=>$v['town']))->find();
			$data[$k]['provincename']=$province['name'];
			$data[$k]['cityname']=$city['name'];
			$data[$k]['townname']=$town['name'];
		}
		$this->assign("pageinfo",$pageinfo);
		$this->assign('data',$data);
		$this->display();
	}
	public function addressadd(){
		$province = M('Region')->where(array('pid'=>1))->select();
		$this->assign('province',$province);
		$this->display();
	}
	public function doaddressadd(){
		if(!IS_POST)$this->error("数据错误");
		$data=$_POST;
		$data['user_id']=$this->uid;
		$fapiaodizhi=D('fapiao_dizhi');
		if(!empty($data['isdefault'])){
			$fapiaodizhi->where(array('user_id'=>$this->uid))->save(array('isdefault'=>0));		
		}
		$addinfo=$fapiaodizhi->add($data);
		$this->success('添加成功!',U('User/Fapiao/address'));
	}
	public function addressedit(){
		$did=I('did','','htmlspecialchars');
		if (empty($did))$this->error("参数错误");
		$fapiaodizhi=M('fapiao_dizhi');
		$data=$fapiaodizhi->where(array('user_id'=>$this->uid,'did'=>$did))->find();
		$province = M('Region')->where(array('pid'=>1))->select();
		$province_id=$data['province'];
		$city_id=$data['city'];
		$city = M('Region')->where(array('pid'=>$province_id,'type'=>2))->select();
		$town = M('Region')->where(array('pid'=>$city_id,'type'=>3))->select();
		$this->assign('province',$province);
		$this->assign('city',$city);
		$this->assign('town',$town);
		$this->assign('data',$data);
		$this->display();
	}
	public function doaddressedit(){
		if(!IS_POST)$this->error("数据错误");
		$data=$_POST;
		foreach ($data as $pk=>$pv){
			$data[$pk]=remove_xss($pv);			
		}
		$fapiaodizhi=M('fapiao_dizhi');
		if(!empty($data['isdefault'])){
			$fapiaodizhi->where(array('user_id'=>$this->uid))->save(array('isdefault'=>0));		
		}else{
			$data['isdefault']=0;
		}
		$editinfo=$fapiaodizhi->where(array('user_id'=>$this->uid,'did'=>$data['did']))->save($data);
		$this->success('修改成功!',U('User/Fapiao/address'));
	}
	public function addressdel(){
		$did=I('did','','htmlspecialchars');
		if (empty($did))$this->error("参数错误");
		$fapiaodizhi=M('fapiao_dizhi');
		$data=$fapiaodizhi->where(array('user_id'=>$this->uid,'did'=>$did))->delete();
		$this->success('删除成功!',U('User/Fapiao/address'));
	}
}
?>