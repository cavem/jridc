<?php
/**
 * 邀请码模型
 */
class InviteModel extends Model{
	protected $tableName = 'invite';
	public function checkinvite($code){
		$result=$this->where(array('usecode'=>$code))->find();
		if ($result){
			return false;
		}else{
			return true;
		}
	}
}