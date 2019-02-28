<?php
class CenterAction extends MainuserAction{
	public function index(){
		//查询用户设定
		$rem_set = M("support_remind")->where(array("user_id"=>$this->uid))->find();
		$this->assign("remind",$rem_set);
		//查询绑定的微信号
		$band_wechats = M("weixin_user")->where(array("uid"=>$this->uid))->count();
//		p(M("weixin_user")->getLastSql());
//		p($band_wechats,1);
		$this->assign("bw",$band_wechats);
		$this->display();
	}
	//设置开关工单提醒
	public function setSupT(){
		Load('extend');
		$type = I("get.type",'');
		$type=remove_xss($type);
		$status = I("get.status",0,'intval');
		$status=remove_xss($status);
		$m_sr = M("support_remind");
		$m_wu = M("weixin_user");
		switch ($type){
			case 'sms':
				$rm = $m_sr->where(array("user_id"=>$this->uid))->find();
				if($rm){//如果存在 则修改
					$rs = $m_sr->where(array("user_id"=>$this->uid))->save(array("sms"=>$status));
				}else{//不存在 添加
					$save_data['user_id'] = $this->uid;
					$save_data['sms'] = $status;
					$rs = $m_sr->add($save_data);
				}
				break;
			case 'email':
				$rm = $m_sr->where(array("user_id"=>$this->uid))->find();
				if($rm){//如果存在 则修改
					$rs = $m_sr->where(array("user_id"=>$this->uid))->save(array("email"=>$status));
				}else{//不存在 添加
					$save_data['user_id'] = $this->uid;
					$save_data['email'] = $status;
					$rs = $m_sr->add($save_data);
				}
				break;
			case 'wechat':
				$id = I("id",0,'intval');//获取传值过来的绑定编号  weixin_user
				$rm = $m_wu->where("uid=%d and id=%d",$this->uid,$id)->find();
				if($rm){//如果存在 则修改
					$rs = $m_wu->where(array("uid"=>$this->uid,"id"=>$id))->save(array("remind"=>$status));
				}
				break;
		}
		if($rs !== FALSE){//执行成功
			$this->success("设置成功！");
		}else{
			$this->error("设置失败！");
		}
	}
	public function account(){
		$this->display();
	}
	/**
	 * 联系人
	 */
	public function contactinfo(){
		$contact=D('User')->contact($this->uid);
		$this->assign('user_contact',$contact);
		$this->display();
	}
	public function contactinfodel(){
		$id=I('id','','intval');
		if (empty($id))$this->error("参数错误");
		if (D('User')->contactdel($this->uid,$id)){
			$this->success("操作成功");
		}else{
			$this->error("删除失败");
		}
	}
	/**
	 * 联系人保存
	 */
	public function docontactinfo(){
		if (!IS_POST)$this->error("数据错误");
		$ary_post=$_POST;
		foreach ($ary_post as $pk=>$pv){
			$ary_post[$pk]=remove_xss($pv);			
		}
		$ary_post['name'] = I('post.name','');
		$ary_post['email'] = I('post.email','');
		$ary_post['mobi'] = I('post.mobi','');
		$ary_post['position'] = I('post.position','');
		if (empty($ary_post['name']))$this->error("联系人不能为空");
		if (empty($ary_post['email']))$this->error("邮箱不能为空");
		if (empty($ary_post['mobi']))$this->error("手机号码不能为空");
		if (D('User')->addcontact($ary_post['name'],$ary_post['email'],$ary_post['mobi'],$ary_post['position'],$this->uid)){
			$this->success("添加成功");
		}else{
			$this->error("添加失败");
		}
		
	}
	/**
	 * 基本信息
	 */
	public function  basicdata(){
		$province = M('Region')->where(array('pid'=>1))->select();
		$userinfo=D('user')->where(array('username'=>$this->username,'user_id'=>$this->uid))->find();
		$province_id=$userinfo['province'];
		$city_id=$userinfo['city'];
		$city = M('Region')->where(array('pid'=>$province_id,'type'=>2))->select();
		$town = M('Region')->where(array('pid'=>$city_id,'type'=>3))->select();
		$this->assign('province',$province);
		$this->assign('city',$city);
		$this->assign('town',$town);
		$this->display();
	}
	public function dobasicdata(){
		if (!IS_POST)$this->error("数据错误");
		$ary_post=$_POST;
		//xss数据库清理20160405
		foreach ($ary_post as $pk=>$pv){
			$ary_post[$pk]=remove_xss($pv);			
		}
		$ary_post['conname'] = I('post.conname','');
		$ary_post['concode'] = I('post.concode','');
		$ary_post['province'] = I('post.province',0,'intval');
		$ary_post['city'] = I('post.city',0,'intval');
		$ary_post['town'] = I('post.town',0,'intval');
		$ary_post['address'] = I('post.address','');
		$ary_post['zipcode'] = I('post.zipcode',0,'intval');
		$ary_post['tel'] = I('post.tel','');
		$userinfo=D('user')->where(array('username'=>$this->username,'user_id'=>$this->uid))->save($ary_post);
		$this->success("修改成功");
	}
	/**
	 * 获取地区信息
	 * Enter description here ...
	 */
	public function getregion(){
		$Region=M("Region");
		$map['pid']=$_REQUEST["pid"];
		$map['type']=$_REQUEST["type"];
		$list=$Region->where($map)->select();
		echo json_encode($list);
	}
	/**
	 * 邮箱验证
	 */
	public function email(){
		
		$this->display();
	}
	public function doemail(){
		if (!IS_POST)$this->error("数据错误");
		$ary_post=$_POST;
		$ary_post['email'] = I('post.email','');
		if (empty($ary_post['email']))$this->error("email不能为空");
		$userinfo=D('user')->where(array('username'=>$this->username,'user_id'=>$this->uid))->find();
		if (!$userinfo)$this->error("用户不存在");
		$codekey=strtotime(date('Y-m-d H'));
		$code=md5($userinfo['user_id'].$userinfo['username'].$ary_post['email'].$userinfo['pwdhash'].$userinfo['regtime'].$codekey);
		$sendmail=D('Sendmail')->sendactivation($userinfo['user_id'],$userinfo['username'],$ary_post['email'],$code,1);
		if (!$sendmail)$this->error("发送失败");
		$this->success('发送成功',U('Home/User/login'));
		exit();
		
	}
	/**
	 * 修改手机
	 */
	public function mobile(){
		
		$this->display();
		
	}
	
	/**
	 * 公众号绑定
	 */
	public function wechat(){
		$m = M("weixin_user");
		$wechats = $m->where(array("uid"=>$this->uid))->select();
		$this->assign("wechats",$wechats);
		$this->display();
	}
	/**
	 * 解除绑定的微信号
	 */
	public function unbinding(){
		$m = M("weixin_user");
		$wxid = I("wxid");
		if($wxid){
			$rs = $m->where(array("wxid"=>$wxid))->save(array("uid"=>'0',"uname"=>""));
			if($rs!==FALSE){
				$this->success("解绑成功",U("user/center/wechat"));
			}else{
				$this->error("解绑失败",U("user/center/wechat"));
			}
		}
	}
	/**
	 * 修改保存
	 */
	public function domobile(){
		if (!IS_POST)$this->error("数据错误");
		$ary_post = $this->_post();
		//xss数据库清理20160405
		foreach ($ary_post as $pk=>$pv){
			$ary_post[$pk]=remove_xss($pv);			
		}
		$ary_post['mobi'] = I('post.mobi','');
		$ary_post['mobicode'] = I('post.mobicode',0,'intval');	
	    if (empty($ary_post['mobi'])) {
	            $this->error('手机号不能为空！');
	    }else if (empty($ary_post['mobicode'])) {
	            $this->error('手机验证码不能为空！');
	    }
		if (!$_SESSION['checkmobiusercode']){
				$this->error("手机验证码错误");
		}else{
			if ($_SESSION['checkmobiusercode']==$ary_post['mobicode']){
				
			}else{
					$this->error("手机验证码错误");
			}
		}
		$data=array(
				'mobi'=>$ary_post['mobi'],
				'mobiv'=>1
		);
		$where=array('username'=>$this->username,'user_id'=>$this->uid);
		$infos=D('user')->where($where)->data($data)->save();
		if ($infos){
        	 $this->success("验证成功",U('User/Center/index'));
        }else{
        	 $this->error("验证错误");
        }
	}
/**
	 * 发送手机验证码
	 * Enter description here ...
	 */
	public function sendmobicode(){
		$mobi=I('post.mobi','');
		if (empty($mobi))$this->error("号码不能为空");
		unset($_SESSION['checkmobiusercode']);
		$Sendinfo=D('Sendsms')->sendcode($mobi,'checkmobiusercode');
		if ($Sendinfo){
			$this->success("发送成功");
		}else{
			$this->error("发送失败");
		}
	}
	
	/**
	 * 修改密码
	 */
	public function password(){
		$this->display();
	}
	/**
	 * 修改保存
	 */
	public function dopassword(){
		if (!IS_POST)$this->error("数据错误");
		$ary_post=$_POST;
		foreach ($arr_post as $pk=>$pv){
				$arr_post[$pk]=remove_xss($pv);			
			}
		$ary_post['passwordold'] = I('post.passwordold','');
		$ary_post['password'] = I('post.password','');
		$ary_post['password1'] = I('post.password1','');
		if (empty($ary_post['passwordold']))$this->error("旧密码不能为空");
		if (empty($ary_post['password']))$this->error("新密码不能为空");
		if (empty($ary_post['password1']))$this->error("确认密码不能为空");
		if ($ary_post['password']<>$ary_post['password1'])$this->error("密码不相同");
		$checkpass=D('User')->checkpass($this->uid,$this->username,$ary_post['passwordold']);
		if (!$checkpass)$this->error("旧密码错误");
		$updatepass=D('User')->updatapassword($this->uid,$this->username,$ary_post['password']);
		$user=D('User')->where(array('user_id'=>$this->uid))->find();
		 D('Userlog')->adddata($this->username,1,"修改密码");
		$this->success("修改成功");
	}
	public function dologout(){	
		 	if(isset($_SESSION['uid'])){
	            unset($_SESSION['uid']);
	            unset($_SESSION['username']);
	            unset($_SESSION['userkey']);
	            unset($_SESSION);
	            session_destroy();
	            $this->success("成功退出", U('Home/User/login'));
	        }else{
	            $this->error(L('成功退出'),U('Home/User/login'));
	        }
	}
}
?>