<?php
/**
 * 用户日志
 * Enter description here ...
 * @author Geyoulei
 */
class UserlogModel extends Model{
	protected $tableName = 'user_log';
	/**
	 * 添加日志
	 * Enter description here ...
	 */
	public function adddata($user_id,$username,$type=1,$forwhat=null){
		$data=array(
		'user_id'=>$user_id,
		'username'=>$username,
		'type'=>$type,
		'logintime'=>time(),
		'forwhat'=>$forwhat,
		'ip'=>getip()
		);
	    $info=$this->add($data);
	    return $info;
	}
	/**
	 * 删除
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	public function del($id){
		if ($id){
			$info=$this->where(array($this->getPk()=>$id))->delete();
		    return $info;
		}else{
			return false;
		}
	}
}