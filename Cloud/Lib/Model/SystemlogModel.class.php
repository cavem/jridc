<?php
/**
 * 系统日志
 * Enter description here ...
 * @author Geyoulei
 */
class SystemlogModel extends Model{
	protected $tableName = 'system_log';
	/**
	 * 添加角色
	 * Enter description here ...
	 */
	public function adddata($username,$forwhat,$type=1){
		$data=array(
		'username'=>$username,
		'type'=>$type,
		'addtime'=>time(),
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