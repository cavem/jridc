<?php
define("TOKEN", "weixin");
class WebAction extends MainwebAction{

	public function test(){
		$this->checklogin();
		$data = M('order')->where(array('user_id'=>5))->limit(0,10)->order(array('addtime'=>'desc'))->select();
		//处理连接地址
		foreach($data as $k=>$v){
			$data[$k]['url'] = U("Wechat/Web/orderdetail",array('id'=>$v['id']));
		}
		$this->assign("orderlist",$data);
		$this->display();
	}
	//获取用户基本信息 并转到指定页面
	public function OauthAndTo(){
		$appid = $this->wechat->appid;
		$appsecret = $this->wechat->appsecret;
		$to =!empty($_GET[to])?$_GET[to]:'index';
		$id =!empty($_GET[id])?$_GET[id]:'0';
		$scope = "snsapi_base";//不许用户确认
		$host = $_SERVER['HTTP_HOST'];
		Log::write($host,"wechat_info");
		$redirect_uri="http://".$host."/".U('Wechat/Oauth/index',array('to'=>$to,'id'=>$id));
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=$scope&state=1#wechat_redirect";
		header("Location:".$url);
	}
	//默认进入的控制中心，判断是否绑定，未绑定进入绑定页，绑定的进入个人中心
	public function index(){
//		$_SESSION['open_id'] = 'oYiflst459SIeo9bIhXVzkQ216K0';
		$open_id = $_SESSION['open_id'];//
		if($open_id){
			$mu = M("weixin_user")->where(array('wxid'=>$open_id))->find();
		}
		if(!$mu){//该微信用户未绑定 进入绑定
			header("Location:".U('Wechat/Web/bindding'));
		}else{//已绑定进入用户中心
			$_SESSION['user_id'] = $mu['uid'];
			$_SESSION['user_name'] = $mu['uname'];
			header("Location:".U('Wechat/Web/usercenter'));
		}
	}
	public function errorMsg($msg){
		$this->assign('msg',$msg);
		$this->display('errorMsg');
		exit();
	}
	//图文信息详情
	public function newsdetail(){
		$id = I('id','');
		if ($id){
			$mod_n=D('reply_news');
			$data = $mod_n->where("id = ".$id)->find();
			$this->assign("data",$data);
			$this->display();
		}else{
			$this->error('编号不能为空！');
		}
	}
	//绑定
	public function bindding(){
		if(IS_POST){
			$name = $_POST['uid'];
			$pass = $_POST['ups'];
			$rs = M("user")->where(array("username"=>$name))->find();
			if(!$rs){//提示信息错误
				$this->error('用户不存在！');
			}else{//执行绑定
				$u_pass = $rs['password'];
				$u_md5 = $rs['pwdhash'];
				if(md5($pass.$u_md5) != $u_pass){
					$this->error('密码错误！');
				}else{
					$u_data = array(
							'uid'=>$rs['user_id'],
							'uname'=>$name
						);
					//多公众号管理时，此处应将公众号原始id加入判断条件
					$rs_bind = M('weixin_user')->where(" wxid = '".$_SESSION['open_id']."'")->save($u_data);
					if(FALSE!==$rs_bind){//绑定成功 进入管理中心
						$_SESSION['user_id'] = $rs['user_id'];
						$_SESSION['user_name'] = $name;
						header("Location:".U('Wechat/Web/usercenter'));
					}else{
						$this->error('绑定失败！');
					}
				}
			}
		}
		//进入绑定页面
		$this->display();
	}
	//解除绑定
	public function unbindding(){
		if(IS_POST){
			$name = $_POST['uid'];
			$pass = $_POST['ups'];
			$rs = M("user")->where(array("username"=>$name))->find();
			if(!$rs){//提示信息错误
				$this->error('用户不存在！');
			}else{//执行绑定
				$u_pass = $rs['password'];
				$u_md5 = $rs['pwdhash'];
				if(md5($pass.$u_md5) != $u_pass){
					$this->error('密码错误');
				}else{
					$bind_user = M('weixin_user')->where(array('wxid'=>$_SESSION['open_id'],'uid'=>$rs['user_id']))->find();
					if(empty($bind_user)){
						$this->error('当前帐号未绑定');
					}
					$rs_bind = M('weixin_user')->where(array('wxid'=>$_SESSION['open_id'],'uid'=>$rs['user_id']))->delete();
					if(FALSE!==$rs_bind){//解除绑定成功 进入管理中心
						unset($_SESSION);
						$this->success('解除绑定成功',U('Wechat/Web/bindding'));
//						header("Location:".U('Wechat/Web/bindding'));
					}else{
						$this->error('解除绑定失败');
					}
				}
			}
		}
		//进入绑定页面
		$this->display();
	}
	
	//用户中心
	public function usercenter(){
		$this->checklogin();
		$data_info = M('user')->where(array('user_id'=>$_SESSION['user_id']))->find();
		if(!$data_info){
			header("Location:".U("/wechat/web/bindding"));
		}
		$this->assign("data_info",$data_info);
		$this->display();
	}

	//在线充值
	public function recharge(){
		$this->checklogin();
		if(IS_POST){
				$order_sn = 'wc' . date("YmdHis") . rand(1000,9999);
				$money = I('money');
				$open_id = $_SESSION['open_id'];
				$params = array(
					"user_id"=>$_SESSION['user_id'],
					"user_name"=>$_SESSION['user_name'],
					"open_id"=>$open_id,
					"order_sn"=>$order_sn,
					"money"=>$money,
					"descr"=>'商务平台微信充值。',
					"bank_type"=>'wechatpay',
					"add_time"=>time(),
				);
				$result = M('wechat_order')->add($params);
				$WIDout_trade_no = $order_sn;
				$WIDtotal_fee = $money;
				$log_id = $order_sn;
				$WIDsubject = '在线充值';
				$notifyUrl="http://".$_SERVER['HTTP_HOST']."/".U("/wechat/Wepay/notify_url");
				vendor('Wxpay.WxPayApi');
				vendor('Wxpay.WxPayJspay');
				$tools = new JsApiPay();
				$input = new WxPayUnifiedOrder();
				$input->SetBody("微信在线充值");
				$input->SetAttach("微信在线充值");
				$input->SetOut_trade_no($WIDout_trade_no);
				$input->SetTotal_fee($WIDtotal_fee*100);
				$input->SetGoods_tag("在线充值");
				$input->SetNotify_url($notifyUrl);
				$input->SetTrade_type("JSAPI");
				$input->SetOpenid($open_id);
				$order = WxPayApi::unifiedOrder($input);
				$jsApiParameters = $tools->GetJsApiParameters($order);
				//获取共享收货地址js函数参数
				$editAddress = $tools->GetEditAddressParameters();
				$this->assign("WIDtotal_fee",$WIDtotal_fee);
				$this->assign("jsApiParameters",$jsApiParameters);
				$this->assign("editAddress",$editAddress);
				//②、统一下单
				$this->display('apicall');
				exit();
	       }
	       $this->display();
	}
	
	//充值记录 **
	public function rechargeList(){
		$this->checklogin();
		$data = M('wechat_order')->where(array('user_id'=>$_SESSION['user_id'],'bank_type'=>'wechatpay'))->order(array('add_time'=>'desc'))->select();
		$this->assign("rechargelist",$data);
		$this->display();
	}
	//充值详情 **
	public function rechargedetail(){
		$this->checklogin();
		$id = I('id');
		$data = M('wechat_order')->where(array('user_id'=>$_SESSION['user_id'],'id'=>$id))->find();
		$this->assign("data_info",$data);
		$this->display();
	}
	
	//财务记录
	public function moneylog(){
		$this->checklogin();
		$data = M('money_log')->where(array('user_id'=>$_SESSION['user_id']))->limit(0,10)->order(array('addtime'=>'desc'))->select();
		//处理连接地址
		foreach($data as $k=>$v){
			$data[$k]['url'] = U("Wechat/Web/moneydetail",array('id'=>$v['id']));
		}
		$this->assign("trans_list",$data);
		$this->display();
	}
	//ajax获取更多财务列表
	public function moneymoreajax(){
		$page=$_GET['page'];
		if (!empty($page)){
			$endpage=intval($page)+1;
		}else{
			$endpage=1;
		}
		$data = M('money_log')->where(array('user_id'=>5))->limit(($endpage)*10,10)->order(array('addtime'=>'desc'))->select();
		//处理连接地址
		foreach($data as $k=>$v){
			$data[$k]['url'] = U("Wechat/Web/moneydetail",array('id'=>$v['id']));
			$data[$k]['addtimef'] = date("Y-m-d",$v['addtime']);
		}
		echo json_encode($data);
	}
	//财务详情
	public function moneydetail(){
		$this->checklogin();
		$id = I('id');
		$data = M('money_log')->where(array('user_id'=>$_SESSION['user_id'],'id'=>$id))->find();
		$this->assign("data_info",$data);
		$this->display();
	}
	//订单列表
	public function orderlist(){
		$this->checklogin();
		$data = M('order')->where(array('user_id'=>$_SESSION['user_id']))->limit(0,10)->order(array('addtime'=>'desc'))->select();
		//处理连接地址
		foreach($data as $k=>$v){
			$data[$k]['url'] = U("Wechat/Web/orderdetail",array('id'=>$v['id']));
		}
		$this->assign("orderlist",$data);
		$this->display();
	}
	//ajax获取更多订单列表
	public function ordermoreajax(){
		$page=$_GET['page'];
		if (!empty($page)){
			$endpage=intval($page)+1;
		}else{
			$endpage=1;
		}
		$data = M('order')->where(array('user_id'=>5))->limit(($endpage)*10,10)->order(array('addtime'=>'desc'))->select();
//		$data = M('system_log')->where()->limit(($endpage)*10,10)->order(array('addtime'=>'desc'))->select();
		//处理连接地址
		foreach($data as $k=>$v){
			$data[$k]['url'] = U("Wechat/Web/orderdetail",array('id'=>$v['id']));
			$data[$k]['addtimef'] = date("Y-m-d",$v['addtime']);
			$data[$k]['sql'] = $sql;
		}
		echo json_encode($data);
	}
	//订单详情
	public function orderdetail(){
		$this->checklogin();
		$id = I('id');
		$data = M('order')->where(array('user_id'=>$_SESSION['user_id'],'id'=>$id))->find();
		//判断个操作类型
		//开通
		if ($data['ordertype']=='云主机开通'){
			//系统类型
			$cloudos=D('cloud_os')->where(array('image_uuid'=>$data['image_uuid']))->find();
			$this->assign('cloudos',$cloudos);
			$tpl='cloud';
		}
		//续费
		if ($data['ordertype']=='云主机续费'){
			$tpl='cloudrepay';
			$qosnetwork=json_decode($data['qosnum'],true);
			$qosnetwork=$qosnetwork['value'];
			$data['qosnetwork']=$qosnetwork;
		}
		if ($data['ordertype']=='云主机开通'||$data['ordertype']=='云主机续费'){
			$cloudproduct=D('cloud_product')->where(array('Cloudtype'=>$data['producttype']))->find();
			if ($data['year']==999)$data['yearname']='试用';
			if ($data['year']==$cloudproduct['PAY_Month'])$data['yearname']='月付';
			if ($data['year']==$cloudproduct['PAY_Season'])$data['yearname']='季度付';
			if ($data['year']==$cloudproduct['PAY_halfyear'])$data['yearname']='半年付';
			if ($data['year']==$cloudproduct['PAY_Nextyear'])$data['yearname']='年付';
			if ($data['year']==$cloudproduct['PAY_2year'])$data['yearname']='二年';
			if ($data['year']==$cloudproduct['PAY_3year'])$data['yearname']='三年';
			if ($data['year']==$cloudproduct['PAY_4year'])$data['yearname']='四年';
			if ($data['year']==$cloudproduct['PAY_5year'])$data['yearname']='五年';
		}
		//升级
		if ($data['ordertype']=='云主机升级'){
			$tpl='cloudup';
		}
		//降级
		if ($data['ordertype']=='云主机调整'){
			$tpl='clouddown';
		}
		$this->assign("data_info",$data);
		$this->display();
	}

	//工单列表
	public function supportlist(){
		$this->checklogin();
		$data = M('support')->where(array('user_id'=>$_SESSION['user_id']))->order(array('last_update'=>'desc'))->select();
//		p($data,1);
		$this->assign("datas",$data);
		$this->display();
	}
	//提交工单
	public function supportadd(){
//		$this->checklogin();
		$mod = M('support');
		$m_apd = M('support_append');
		$m_img = M('support_images');
		if(IS_POST){
			$arr_post = $_POST;
			$arr_post['s_no'] = $this->getcode();
			$arr_post['user_id'] = $_SESSION['user_id'];
			$arr_post['status'] = 1;//默认状态为1，已提交
			$arr_post['add_time'] = time();
			$arr_post['last_update'] = time();
			$data_id = $mod->add($arr_post);
			if(FALSE !== $data_id){//工单成功提交
				//发送邮件通知管理员  新工单消息
				D('Support')->sta($data_id,1);
				//上传图片
				import('ORG.Net.UploadFile');
				$upload = new UploadFile();// 实例化上传类
				$i = 0;
				foreach ($_FILES as $key=>$file){
					$i++;
					if(!empty($file['name'])) {
						$upload->autoSub = true;
						$upload->subType =  'date';
						$upload->maxSize = 3145728 ;// 设置附件上传大小
						$upload->saveRule  = time().$i ;
						$upload->uploadReplace  = true ;// 设置附件上传大小
						$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
						$uploadsaveurl='Upload/Support/'.$_SESSION['user_name'].'/';//存放数据库地址
						$uploadsavepath=$uploadsaveurl;
						make_dir($uploadsavepath);//创建目录
						$upload->savePath = $uploadsavepath ;// 设置附件上传目录
						$info =  $upload->uploadOne($file);
						if(!$info){// 上传错误
							$this->error($upload->getErrorMsg());
						}else{ // 保存附件信息
							$uploadsaveurl = str_replace("./","/",$uploadsaveurl);
							$file=$uploadsaveurl.$info[0]['savename'];
							$date_img = array(
								'wo_id'=>$data_id,
								'path'=>$file,
							);
							$res_addimg = M('support_images')->add($date_img);
						}
					}
				}
//				$this->success("添加成功",U('wechat/web/supportdetail',array("id"=>$data_id)));
				header("Location:".U('Wechat/Web/supportdetail',array("id"=>$data_id)));
			}else{
				$this->error("添加失败");
			}
			exit();
		}
		//获取工单分类
		$mod_t = M('support_types');
		$types = $mod_t->where('pid=0')->order('sort')->select();
		foreach ($types as $k=>$v){
			$types[$k]['childs'] = $mod_t->where('pid='.$v['id'])->order("sort")->select();
		}
		$this->assign('types',$types);
		//获取产品：云主机列表
		$clouds = M("cloud")->where(array("user_id"=>$_SESSION['user_id']))->select();
		$this->assign('clouds',$clouds);
		$this->display();
	}
	//工单详情
	public function supportdetail(){
//		$this->checklogin();
		$id = I('id');
		if($id){
			$mod = M('support');
			$m_apd = M('support_append');
			$m_img = M('support_images');
			if(IS_POST){
				$arr_post['wo_id'] = $id;
				$arr_post['user_id'] = $_SESSION['user_id'];
				$arr_post['type'] = 1;//1客户追问，2回复
				$arr_post['content'] = $_POST['content'];
				$arr_post['add_time'] = time();
				$data_id = $m_apd->add($arr_post);
				if(FALSE !== $data_id){//追问成功后 添加图片到数据库 及 上传到服务器
					//发送邮件通知管理员  追问消息
					D('Support')->sta($id,2);
					//修改状态为追问
					$sp['status'] = 4;
					$sp['last_update'] = time();
					$rs1 = $mod->where(' id = '.$id)->save($sp);
					if(FALSE === $rs1){
						$this->error('保存回复失败！');
					}
					//上传图片
					import('ORG.Net.UploadFile');
					$upload = new UploadFile();// 实例化上传类
					$i = 0;
					foreach ($_FILES as $key=>$file){
						$i++;
						if(!empty($file['name'])) {
					        $upload->autoSub = true;
					        $upload->subType =  'date';
							$upload->maxSize = 3145728 ;// 设置附件上传大小
							$upload->saveRule  = time().$i ;
							$upload->uploadReplace  = true ;// 设置附件上传大小
							$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
							$uploadsaveurl='Upload/Support/'.$_SESSION['user_name'].'/';//存放数据库地址
							$uploadsavepath=$uploadsaveurl;
							make_dir($uploadsavepath);//创建目录
							$upload->savePath = $uploadsavepath ;// 设置附件上传目录
							$info =  $upload->uploadOne($file);
							if(!$info){//上传错误
							}else{//保存附件信息
								$uploadsaveurl = str_replace("./","/",$uploadsaveurl);
								$file=$uploadsaveurl.$info[0]['savename'];
								$date_img = array(
									'wo_id'=>$id,
									'apd_id'=>$data_id,
									'path'=>$file,
								);
								$res_addimg = $m_img->add($date_img);
							}
						}
					}
					header("Location:".U('Wechat/Web/supportdetail',array("id"=>$id)));
				}
			}
			$data = M('support')->where(array('user_id'=>$_SESSION['user_id'],'id'=>$id))->find();
//			$data = M('support')->where(array('id'=>$id))->find();//测试使用 记得还原
			if($data){
				$data['imgs'] = $m_img->where("wo_id = ".$id." and apd_id is null")->select();
				$this->assign("data",$data);
					
				$apd_data = $m_apd->where(array('wo_id'=>$id))->select();
				//查询工单追加信息对应的图片
				foreach($apd_data as $k=>$v){
					$img_date_apd = $m_img -> where(' apd_id = '.$v['id'])->order('id asc')->select();
					$apd_data[$k]['imgs']=$img_date_apd;
				}
				$this->assign("apds",$apd_data);
				$this->display();
			}else{
				$this->error("数据不存在");
			}
		}else{
			$this->error("参数错误");
		}
	}
	//评论 并关闭工单
	public function comment(){
		$id = I("id");
		if($id){
			$m_s = M("support");
			if(IS_POST){
				$_POST['status']=5;
				$_POST['last_update']=time();
				$rs = $m_s->save($_POST);
				$s_info = $m_s->where(array("id"=>$id))->find();
				
//				//发送邮件提醒用户
//				$pj = "非常感谢您的评价，您对我们的意见和建议，就是对我们最大的支持和帮助，感谢您留下宝贵的建议帮助我们改善服务品质！";
//				$sendobj = new \Common\Mobel\SendmailModel();
//				$rs = $sendobj->Send($this->email, "【评价完成】".$s_info['title'], $pj);
//				
//				//发送短信提醒用户
//		    	$sendobj = new \Common\Mobel\SendsmsModel();
//		    	//查询用户手机号
//		    	$c_info = M("customer_property")->where(array("cid"=>$this->uid))->find();
//			    $info=$sendobj->Send($c_info['phone'],$pj);
				header("Location:".U('Wechat/Web/supportdetail',array("id"=>$id)));
			}
		}
		$this->error("参数错误");
	}
	
	//优惠券列表
	public function couponlist(){
		$this->checklogin();
		$data = M('coupon')->where(array('user_id'=>$_SESSION['user_id']))->order(array('expire_time'=>'desc'))->select();
		foreach ($data as $k=>$v){
			if($v['expire_time']<time()){
				$data[$k]['expired'] = '1';
			}
		}
		$this->assign("datas",$data);
		$this->display();
	}
	//优惠券详情
	public function coupondetail(){
		$this->checklogin();
		$id = I('id');
		$data = M('coupon')->where(array('user_id'=>$_SESSION['user_id'],'id'=>$id))->find();
		if($data['expire_time']<time()){
			$data['expired'] = '1';
		}
		$this->assign("data",$data);
		$this->display();
	}
	//领取优惠券
	public function reccoupon(){
		//判断是否获取到openid
		$open_id = $_SESSION['open_id'];//
		if(!$open_id)$this->errorMsg("OpenId获取失败,请用微信访问。");

		//获取绑定用户信息
		$mu = M("weixin_user")->where(array('wxid'=>$open_id))->find();
		if(!$mu['uid'])$this->errorMsg("您的微信没有绑定任何帐号");

		//编号
		$id = !empty($_GET['id']) ? $_GET['id'] : $_POST['id'];
		if(!$id)$this->errorMsg("异常领取，领取编号不存在");

		//查询活动详情
		$wc = M("weixin_coupon")->where(array('id'=>$id))->find();
		if(!$wc)$this->errorMsg("优惠活动不存在");
		
		//判断该优惠券是否属于该用户
		if($wc[open_id]!=$open_id)$this->errorMsg("非法领取！");//应该记录非法领取微信号,列入黑名单

		//判断该批次当前绑定的用户是否已领取[一个用户可被多个微信号绑定，每个用户仅限领取一次]
		$used = M("weixin_coupon")->where(array('act_id'=>$wc[act_id],'user_id'=>$mu['uid'],'status'=>array('neq','1')))->find();
		if($used)$this->errorMsg("您绑定的帐号：【".$used['username']."】已经领取该优惠券。");
		
		//判断是否过期
		if(time()>$wc["expire_time"])$this->errorMsg("优惠券已过期");
		
		if(IS_POST){//点击领取
			//生成优惠券
			$data['type']=$wc['coupon_type'];
			$data['couponnum']=$this->cardcode();
			$data['secret']=md5(time());//提取密码
			$data['condition']=$wc['condition'];
			$data['couponmoney']=$wc['cmoney'];
			$data['kefuid']=!empty($wc['kid'])?$wc['kid']:0;
			$data['expire_time']=$wc['expire_time'];
			$data['user_id']=$wc['user_id'];
			$data['username']=$wc['username'];
			$data['addtime']=time();
			$data['status']=0;
			$data['remark']="微信发放优惠券";
			$add_rs=M('coupon')->add($data);
			if(FALSE !== $add_rs){//生成成功，将优惠券号码保存到微信发放信息，并修改信息状态为已领取
				$save_data = array(
						"couponnum"=>$data['couponnum'],
						"status"=>2,
						"rec_time"=>time()
					);
				$up_rs = M("weixin_coupon")->where(array('id'=>$id))->save($save_data);
				if(FALSE !== $up_rs){//成功提示
					$this->errorMsg("恭喜您成功领取优惠券！");
				}else{
					$this->errorMsg("领取失败！");
				}
			}else{
				//失败提示
				$this->error("生成优惠券失败！");
			}
		}
		$wc['exp_time'] = date("Y-m-d H:i:s",$wc['expire_time']);
		$this->assign("data",$wc);
		$this->assign("id",$id);
		$this->display();
	}
	//生成工单号
	function getcode(){
	 	mt_srand((double) microtime() * 1000000);
	 	$code = "sn_".date('YmdHis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
	 	//验证号码是否存在
		$m=M('support');
		$oldcode=$m->where("s_no='".$code."'")->getField('s_no');
		if($oldcode){
			$this->getordcode();
		}else{
			return $code;
		}
	}
	//生成注册卡
	public function cardcode(){
		$cardinfo=randstr(5,1)."-".randstr(5,1)."-".randstr(5,1)."-".randstr(5,1)."-".randstr(5,1);
		$data=M('coupon')->where(array('couponnum'=>$cardinfo))->select();
		if ($data){
			$this->cardcode();
		}else{
			return $cardinfo;
		}
	}
	
//云主机列表
	public function cloudList(){
		$this->checklogin();
		$data = M('cloud')->where(array('user_id'=>$_SESSION['user_id'],'endtime'=>array('gt',time())))->select();
		foreach ($data as $k=>$v){
			$cloud_auto=D('cloud_auto')->where(array('pid'=>$v['id']))->find();
			$result[$k]=$v;
			$result[$k]['isauto']=$cloud_auto['isauto'];			
		}
		$this->assign("listcloud",$result);
		$time_t = strtotime('+7 day',time());
		$data_jjdq = M('cloud')->where(array('user_id'=>$_SESSION['user_id'],'endtime'=>array('between',array(time(),$time_t))))->select();
		foreach ($data_jjdq as $kk=>$vv){
			$cloud_auto_s=D('cloud_auto')->where(array('pid'=>$v['id']))->find();
			$result_jjdq[$kk]=$vv;
			$result_jjdq[$kk]['isauto']=$cloud_auto_s['isauto'];			
		}
		$this->assign("listcloudjjdq",$result_jjdq);
		$this->display();
	}
	//云主机详情
	public function detail(){
		$this->checklogin();
		$id = I('id');
		$data = M('cloud')->where(array('user_id'=>$_SESSION['user_id'],'id'=>$id))->find();
		$this->assign("cloud_detail",$data);
		$this->display();
	}
	
	//主机概况
	public function info(){
		$this->checklogin();
		$id = I('id');
		$cloud = M('cloud')->where(array('user_id'=>$_SESSION['user_id'],'id'=>$id))->find();
		$cloud_product = M('cloud_product')->where(array('Cloudtype'=>$cloud['Cloudtype']))->find();
		$cloudvminfo=json_decode($cloud['vminfo'],true);
        $clouddiskinfo=json_decode($cloud['diskinfo'],true);
        $cloudipqosinfo=json_decode($cloud['ipqosinfo'],true);
        $vminfo=$cloudvminfo['value'];
    	$clouddiskinfo=$clouddiskinfo['value'];
    	$cloudipqosinfo=$cloudipqosinfo['value'];
    	$os_arr=explode('|', $vminfo[os_version]);
		$syscount=0;
    	$cloudcount=0;
    	foreach ($clouddiskinfo as $k=>$v){
    		if ($v['userdevice']==0){
    			$syscount=$v['virtual_size'];
    		}else{
    			$cloudcount+=$v['virtual_size'];
    		}
    	}
    	$cloudiparr=array();
    	foreach ($cloudipqosinfo as $k=>$v){
    		if ($v['shared']==false){
    			$cloudiparr[]=$v;
    		}
    	}
    	$vminfo[syscount]=$syscount;
    	$vminfo[cloudcount]=$cloudcount;
    	$vminfo[osname]=$os_arr[0];
		$this->assign("vminfo",$vminfo);
		$this->assign("diskinfo",$clouddiskinfo);
		$this->assign("ipqosinfo",$cloudipqosinfo);
		$this->assign("cloud",$cloud);
		$this->assign("cloud_product",$cloud_product);
		$this->display();
	}
	//开关机 重启 操作
	public function cloudhandel(){
		$this->checklogin();
		if (!IS_POST)$this->error("数据错误");
		$id = I('id');
		$act = I('act');
		$cloud = D("cloud")->where(array('user_id'=>$_SESSION['user_id'],'id'=>$id))->find();
		if(!empty($cloud)){
			if ($act=='start'){
				$result=D('Cloudapi')->cloudstart($cloud['masterid'],$cloud['vm_id']);
				if ($result['status']=='failed')$this->error($result['value']);
				$this->success("开机完成");
				exit();
			}
			if ($act=='reboot'){
				$result=D('Cloudapi')->cloudreboot($cloud['masterid'],$cloud['vm_id']);
				if ($result['status']=='failed')$this->error($result['value']);
				$this->success("操作完成");
				exit();
			}
			if ($act=='stop'){
				$result=D('Cloudapi')->cloudstop($cloud['masterid'],$cloud['vm_id']);
				if ($result['status']=='failed')$this->error($result['value']);
				$this->success("关机完成");
				exit();
			}
		}else {
			$this->error("云主机不存在");
		}
	}
	//实时查询云主机开关机状态
	public function vmstate(){
		$this->checklogin();
		$act=I('act','vmstate','htmlspecialchars');
		$id=I('id','0','htmlspecialchars');
		if (empty($id)){
			echo 0;
			exit;
		}
		//查询当前产品是否存在
		$cloud=D('Cloud')->where(array('id'=>$id,'user_id'=>$_SESSION['user_id']))->find();
		if (empty($cloud)){
			echo 0;
			exit;
		}
		$data=array();
		$data['id']=$cloud['id'];
		$data['statusinfo']=$cloud['status'];
		if ($act=='vmstate'){
			$vminfo=D('Cloudapi')->cloudinfo($cloud['masterid'],$cloud['vm_id']);//云主机详情
			if($vminfo){
				if($vminfo['status']=='success'){
					$dataarr=$vminfo['value'];
					$vmstatus="运行中";
    				if (strtoupper($dataarr['power_state'])=="RUNNING")$vmstatus="运行中";
    				if (strtoupper($dataarr['power_state'])=="HALTED")$vmstatus="已关机";
    				if (strtoupper($dataarr['power_state'])=="BUILDING")$vmstatus="创建中";
    				if (strtoupper($dataarr['power_state'])=="ERROR")$vmstatus="故障中";
    				if (strtoupper($dataarr['power_state'])=="EXCEPT")$vmstatus="故障中";
					$data['cloudstatus']=true;
					$data['info']=$vmstatus;
					$data['power_state']=$dataarr['power_state'];
					echo json_encode($data);
					exit;
				}else{
					$data['cloudstatus']=false;
					$data['info']=$vminfo['value'];
					echo json_encode($data);
					exit;
				}
			}
			$data['cloudstatus']=false;
			$data['info']="nocloud";
			echo json_encode($data);
			exit;
		}
	}
	//性能监控
	public function monitor(){
		$this->checklogin();
		$proid=I('proid','5','htmlspecialchars');
		$id=I('id','0','htmlspecialchars');
		if (empty($id)){
			echo "id错误";
			exit();
		}
		$time=time();
		if ($proid==86400){
			$starttime=strtotime("-1 year", $time);
			$this->assign('starttime',$starttime);
			$this->assign('endtime',$time);
		}
		if ($proid==3600){
			$starttime=strtotime("-7 day", $time);
			$this->assign('starttime',$starttime);
			$this->assign('endtime',$time);
		}
    	$cloud=D('Cloud')->where(array('id'=>$id,'user_id'=>$_SESSION['user_id']))->find();
    	$cloudvminfo=json_decode($cloud['vminfo'],true);
    	$vminfo=$cloudvminfo['value'];
    	$this->assign('vminfo',$vminfo);
		$this->assign('porid',$proid);
		$this->assign('id',$id);
		$this->display();
		
	}
	public function monitorajax(){
		$this->checklogin();
		$proid=I('proid','5','htmlspecialchars');
		$id=I('id','0','htmlspecialchars');
		if (empty($id)){
			echo "";
			exit();
		}
		$cloud=D('Cloud')->where(array('id'=>$id,'user_id'=>$_SESSION['user_id']))->find();
		$per=D('Cloudapi')->performancestatisticsvm($cloud['masterid'],$cloud['vm_id'],$proid);
		if ($per['status']!='success')return $this->error($per['value']);
		//查询出所有CPU
		$cpuarr=array();
		$memarr=array();
		$vbdarr=array();
		$vifarr=array();
		foreach ($per['value'] as $k=>$v){
			if (strstr($k,'cpu')){
				$cpuarr[$k]=$v;
			}
			if (strstr($k,'vif')){
				$vifarr[$k]=$v;
			}
			if ($k=='mem'){
				$memarr[$k]=$v;
			}
			if (strstr($k,'vbd')){
				$vbdarr[$k]=$v;
			}
		}
		$data['cpu']=$cpuarr;
		$data['mem']=$memarr;
		$data['disk']=$vbdarr;
		$data['qos']=$vifarr;
		echo json_encode($data);		
	}
	protected function checklogin(){
		if (empty($_SESSION['user_id']))$this->redirect('Wechat/Web/bindding');
	}
	//自动续费
	public function autorepay(){
		$id=I('id','0','htmlspecialchars');
		$cloud=D('cloud')->where(array('id'=>$id,'user_id'=>$_SESSION['user_id']))->find();
		if(!$cloud)$this->error('云主机不存在');
		$this->assign('cloud',$cloud);
		if ($cloud['status']=='已删除')$this->error("已删除云主机禁止管理");
		if ($cloud['status']=='配置中')$this->error("配置中云主机禁止管理");
		if ($cloud['status']=='开通失败')$this->error("配置中云主机禁止管理");
		$cloudauto=D('cloud_auto')->where(array('pid'=>$cloud['id'],'user_id'=>$_SESSION['user_id']))->find();
		if(!$cloudauto){
			$autoid=D('cloud_auto')->add(array('pid'=>$cloud['id'],'user_id'=>$_SESSION['user_id'],'isauto'=>0,'duration'=>1,'numtime'=>1));
			$autodata=D('cloud_auto')->where(array('id'=>$autoid))->find();
			$this->assign('data',$autodata);
		}else{
			$this->assign('data',$cloudauto);
		}
		$this->display();
	}
	public function doautorepay(){
		if (!IS_POST)$this->error('数据错误');
		$ary_post=$_POST;
		$data=array(
			'isauto'=>$ary_post['isauto'],
		);
		D('cloud_auto')->where(array('pid'=>$ary_post['pid'],'id'=>$ary_post['id'],'user_id'=>$_SESSION['user_id']))->save($data);
		$this->display();
		//$this->success('操作成功',U('Wechat/web/cloudList'));		
	}
}


?>