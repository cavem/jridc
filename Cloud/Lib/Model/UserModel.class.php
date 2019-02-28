<?php
/**
 * 用户模型
 * Enter description here ...
 * @author Geyoulei
 */
class UserModel extends Model{
	protected $tableName = 'user';
	
	/**
	 * 
	 * @param 用户ID $uid
	 * @param 金钱 $money
	 * @param 说明 $forwhat
	 * @param 产品类型 $whichProduct
	 * @param 产品ID $pid
	 * @param 1在线充值 2后台入款 3后台扣除 4开通扣除 5续费扣除 6升级配置 7降级配置   10充值卡$type
	 * @param 凭证 $pingzheng
	 * @param 用户区 或管理区 $acspace
	 * @param 1入款 2出 $isadd
	 */
	public function addmoney($uid,$money,$forwhat,$whichProduct,$pid,$type,$pingzheng,$acspace,$isadd,$ptype="",$orderid=0,$paddtime="",$endtime=""){
		$model=M('user');
		$datav=$model->where(array('user_id'=>$uid))->find();
		$oldmeney=$datav['usermoney'];
	    if(intval($isadd)==1){
			$endmoney=$oldmeney+$money;
		}
		if(intval($isadd)==2){
			$endmoney=$oldmeney-$money;
		}
		$data=array(
			'usermoney'=>$endmoney
		);
		$model->where(array('user_id'=>$uid))->save($data);
		//处理财务记录
		$user_id=$datav['user_id'];
		$username=$datav['username'];
		$usermoney=$money;
		$oldusermoney=$oldmeney;
		$newusermoney=$endmoney;
		D('Moneylog')->adddata($user_id,$username,$usermoney,$oldusermoney,$newusermoney,$forwhat,$whichProduct,$pid,$type,$pingzheng,$acspace,$isadd,$ptype,$orderid,$paddtime,$endtime);
	}
	//验证密码是否正确
	public function checkpass($uid,$username,$password){
		$User=M('user')->where(array('user_id'=>$uid,'username'=>$username))->find();
		if (!$User) return false;
		$pwd=md5($password.$User['pwdhash']);
		if ($pwd==$User['password']){
			return true;
		}else{
			return false;
		}
	}
	//修改密码
	public function updatapassword($uid,$username,$password){
		$User=M('user')->where(array('user_id'=>$uid,'username'=>$username))->find();
		if (!$User) return false;
		$pwd=md5($password.$User['pwdhash']);
		$updata= M('user')->where(array('user_id'=>$uid,'username'=>$username))->save(array('password'=>$pwd));
		return $updata;
	}
	
	//联系人列表
	public function contact($uid){
		return M('user_contact')->where(array('user_id'=>$uid))->select();
	
	}
	//联系人删除
	public function contactdel($uid,$id){
		return M('user_contact')->where(array('user_id'=>$uid,'id'=>$id))->delete();
	
	}
	//保存联系人
	public function addcontact($name,$email,$mobi,$position,$uid){
		$data=array(
		'name'=>$name,
		'email'=>$email,
		'mobi'=>$mobi,
		'position'=>$position,
		'user_id'=>$uid,
		);
		return M('user_contact')->add($data);
	}
}