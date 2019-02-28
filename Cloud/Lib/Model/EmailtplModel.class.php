<?php
/**
 * 邮件模板
 * Enter description here ...
 * @author Geyoulei
 */
class EmailtplModel extends Model{
	protected $tableName = 'email_tpl';
	/**
	 * 添加角色
	 * Enter description here ...
	 */
	public function adddata($data=array()){
	    $info=$this->add($data);
	    return $info;
	}
	/**
	 * 编辑菜单
	 * Enter description here ...
	 */
	public function edit($id,$data=array()){
		if ($data){
			if ($id){
				unset($data[$this->getPk()]);
				$info=$this->where(array($this->getPk()=>$id))->save($data);
				if (FALSE !==$info){
				  return true;
				}else{
					return false;
				}
				
			}else{
				return false;
			}
			
		}else{
			return false;
		}
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
	/**
	 * 详细信息
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	public function Show($id){
		$data=$this->where(array($this->getPk()=>$id))->find();
	    return $data;
	}
	/**
	 * 添加查询分页总数
	 * Enter description here ...
	 * @param $where
	 */
	public function counts($where){
	 	$count=$this->where($where)->count();
		return $count;
	}
	/**
	 * 查询列表
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	public function getall($where=null,$order=null,$firstRow=null,$listRows=null){
		$data=$this->where($where)->order($order)->limit($firstRow,$listRows)->select();
	    return $data;
	}	
}