<?php
/**
 * 后台权限节点管理
 * Enter description here ...
 * @author Geyoulei
 * 2015-2-6-16dian
 */
class RolenodeAction extends AdminAction{
	public function index(){
		$ary_get = $this->_get();
		$nav_id=$ary_get['nav_id'];
		$module=$ary_get['module'];
    	$where=' 1 ';
        if(!empty($nav_id))$where =$where.' and '.C("DB_PREFIX").'role_node.nav_id='.$nav_id;
        if(!empty($module))$where =$where.' and '.C("DB_PREFIX")."role_node.module='".$module."'";
        $ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', 10);
        $count = D('Rolenode')->counts($where);
        $obj_page =$this->AdminPage($count, $ary_get['pageall']);
        $page = $obj_page->show();
        $ary_data = D('Rolenode')->getall($where,'id asc,sort asc',$obj_page->firstRow, $obj_page->listRows);
        $navs=D('Rolenav')->getall(array('status'=>1));
        $this->assign("navs",$navs);
        $action=D('Rolenode')->getall(array('auth_type'=>1));
        $this->assign("action",$action);
        $this->assign("module",$module);
        $this->assign("nav_id",$nav_id);
        $this->assign("filter",$ary_get);
        $this->assign("data", $ary_data);
        $this->assign("page", $page);
        $this->display();
		
	}
	public function  add(){
		if (IS_POST){
			$data=$_POST;
			$model = D('Rolenode');
			if (false === $data = $model->create()) {
	            $this->error($model->getError());
	        }
			if ($data['module'] == "" && $data['action'] != "") {
	            $data['auth_type'] = 2;
	        } elseif ($data['module'] != "" && $data['action'] == "") {
	            $data['auth_type'] = 1;
	        } else {
	            $data['auth_type'] = 0;
	        }
			$count = $model->counts(array('module' => $data['module'], 'action' => $data['action']));
	        if ($count > 0) {
	            $this->error("添加的节点已经存在");
	        }
			if ($model->adddata($data)){
				$this->success('操作成功');
			}else{
				$this->error('操作失败');
			}
			exit();	
		}
		$navs=D('Rolenav')->getall(array('status'=>1));
		$this->assign('navs',$navs);
		$this->display();
	}
	public function edit(){
		if (IS_POST){
			$ary_request = $this->_request();
			$model = D('Rolenode');
		    $data = $model->create();
	        if (false === $data = $model->create()) {
	            $this->error($model->getError());
	        }
	        if (!empty($data) && is_array($data)) {
		        if ($data['module_name'] == '') {
	                $data['module_name'] = $data['module'];
	            }
	            if ($ary_request['module'] == "" && $ary_request['action'] != "") {
	                $data['auth_type'] = 2;
	            } elseif ($ary_request['module'] != "" && $ary_request['action'] == "") {
	                $data['auth_type'] = 1;
	            } else {
	                $data['auth_type'] = 0;
	            }
		        $where = array();
	            $where['module']   = $ary_request['module'];
	            $where['action']   = $ary_request['action'];
	            $where['id']   = array('NEQ',$ary_request['id']);
	            $count = D('Rolenode')->where($where)->count();
	            //echo D('Rolenode')->getLastSql();
	            //exit();
	            if ($count > 0) {
	                $this->error("添加的节点已经存在");
	            }
		        //保存当前数据
	            $list = $model->where(array('id' => $ary_request['id']))->save($data);
	            if (false !== $list) {
	                $this->success("节点编辑成功");
	            } else {
	                $this->error("节点编辑失败");
	            }
	        }else{
	        	  $this->error("数据错误");
	        }
			exit();
		}
		$node_id = intval($this->_get("id"));
		$data= D('Rolenode')->getById($node_id);
   	    $nav=D('Rolenav')->getall(array('status'=>1));
		$this->assign('navs',$nav);
		$this->assign('data',$data);
        $this->display();
	}
	public function del(){
		 $id = intval($this->_get('id'));
		 $model = D('Rolenode');
		  if(!empty($id) && $id > 0){
		 		$where = array();
                $where[C('DB_PREFIX').'role_node.id']     = $id;
                $where[C('DB_PREFIX').'role.status']     = '1';
                $count = $model
                        ->join(" ".C('DB_PREFIX')."role_access on ".C('DB_PREFIX')."role_access.node_id=".C('DB_PREFIX')."role_node.id")
                        ->join(" ".C('DB_PREFIX')."role on ".C('DB_PREFIX')."role_access.role_id=".C('DB_PREFIX')."role.id")
                        ->where($where)->count();
	            if($count > 0){
	                $this->error("节点已经被使用，不可删除");
	            }
                $list = $model->where(array('id'=>$id))->delete();
	            if(false !== $list){
	                M("RoleAccess")->where(array('node_id'=>$id))->delete();
	                $this->success("删除成功");
	            }else{
	                $this->success("删除失败");
	            }
		  }else{
		  	$this->error("数据错误");
		  }
	}
}
?>