<?php
/**
 * 用户日志
 * Enter description here ...
 * @author Geyoulei
 */
class MoneylogModel extends Model{
	protected $tableName = 'money_log';
	/**
	 * 写入财务记录
	 * @param 用户ID 后台管理员入款为0 $user_id
	 * @param 用户名 后台用户为管理员名称 $username
	 * @param 入款金额 $usermoney
	 * @param 旧金额 $oldusermoney
	 * @param 新金额 $newusermoney
	 * @param 说明 $forwhat
	 * @param 产品 $whichProduct
	 * @param 产品ID $pid
	 * @param 类型 1在线充值 2后台入款 3后台扣除 4开通扣除 5续费扣除 6升级配置 7降级配置 $type
	 * @param 凭证 $pingzheng
	 * @param 用户区或管理区 $acspace
	 * @param 金钱类型 1入 2出 $isadd
	 * @param 产品类型 $ptype
	 * @param 订单编号 $orderid
	 * @param 产品开通时间 $paddtime
	 * @param 产品到期时间 $pendtime 
	 */
	public function adddata($user_id,$username,$usermoney,$oldusermoney,$newusermoney,$forwhat,$whichProduct,$pid=0,$type,$pingzheng,$acspace,$isadd,$ptype="",$orderid=0,$paddtime="",$pendtime=""){
		if (empty($paddtime))$paddtime=time();
		if (empty($pendtime))$pendtime=time();
		$data=array(
			'user_id'=>$user_id,
			'username'=>$username,
			'usermoney'=>$usermoney,
			'oldusermoney'=>$oldusermoney,
			'newusermoney'=>$newusermoney,
			'forwhat'=>$forwhat,
			'whichProduct'=>$whichProduct,
			'pid'=>$pid,
			'type'=>$type,
			'pingzheng'=>$user_id,
			'acspace'=>$acspace,
			'isadd'=>$isadd,
			'ptype'=>$ptype,
			'orderid'=>$orderid,
			'ip'=>getip(),
			'paddtime'=>$paddtime,
			'pendtime'=>$pendtime,
			'addtime'=>time()				
		);
		return $this->add($data);
		
	}
	
	
}