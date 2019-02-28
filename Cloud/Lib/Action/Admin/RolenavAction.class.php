<?php
/**
 * 后台权限菜单管理
 * Enter description here ...
 * @author Geyoulei
 * 2015-2-6-16dian
 */
class RolenavAction extends AdminAction{
	public function index(){
		$where=" 1=1 ";
		$Rolenav=D('Rolenav');
		$data = $Rolenav->getall($where,' sort asc');
		$this->assign("data",$data);
		$this->display();
	}
	/**
	 * 添加菜单
	 */
	public function add(){
		if (IS_POST){
			if (D('Rolenav')->adddata($_POST)){
				$this->success('操作成功');
			}else{
				$this->error('操作失败');
			}
			exit();	
		}
		$this->display();
	}
	/**
	 * 编辑菜单
	 */
	public function edit(){
		if (IS_POST){
			$data=$_POST;
			$id=$data['id'];
			$info=D('Rolenav')->edit($id,$data);
			if ($info){
				$this->success('操作成功');
			}else{
				$this->error('操作失败');
			}	
			exit();	
		}//编辑保存结束
		$id=I('id','');
		$ary_get = $this->_get();
		$data=D('Rolenav')->Show($ary_get['id']);
		$this->assign("data",$data);
		$this->display();
		
	}
	/**
	 * 删除菜单
	 */
	public function del(){
		$id=I('id','');
		if ($id){
			$Rolenode=D('Rolenode');
			$datanode = $Rolenode->getall(array('nav_id'=>$id));
			if ($datanode)$this->error('先删除当前菜单下的节点信息!');
			if (D('Rolenav')->del($id)){
				$this->success('操作成功');
			}else{
				$this->error('操作失败');
			}	
		}else{
			
			$this->error('删除项不 能为空!');
		}
	}
}
?>