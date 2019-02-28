<?php
/**
 * 后台权限角色管理
 * Enter description here ...
 * @author Geyoulei
 * 2015-2-6-16dian
 */
class RoleAction extends AdminAction{
	
	public function  index(){
		$role = D('Role');
        $ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', 10);
        $count = $role->counts();
        $obj_page = $this->AdminPage($count, $ary_get['pageall']);
        $page = $obj_page->show();
        $ary_data = $role->getall('','',$obj_page->firstRow, $obj_page->listRows);
        $this->assign("data", $ary_data);
        $this->assign("page", $page);
        $this->display();
	}
	public function  add(){
		if (IS_POST){
	        $role = D('Role');
	        $roleAccess = M("role_access");
	        $data = $role->create();
	        if (false === $data = $role->create()) {
	            $this->error($role->getError());
	        }
			$role->startTrans();//事务开始
			 //保存当前数据对象
			$list = $role->add($data);
			if (false !== $list) {
			 	 $node_ids = $this->_request("access_node");
			 	 if(!empty($node_ids) && is_array($node_ids)) {
			 	 	foreach ($node_ids as $node_id) {
                    $access['role_id'] = $list;
                    $access['node_id'] = $node_id;
                    $ary_result = $roleAccess->add($access);
                    if(FALSE === $ary_result){
                        $role->rollback();//不成功，回滚
                        $this->error("数据添加失败");
                    }
               		 }
             	   $role->commit();//成功
             	   $this->success("数据添加成功");
			 	}else {
	                $role->rollback();//不成功，回滚
	                $this->error("请选择控制权限");
         		}
			}else{
			  $role->rollback();//不成功，回滚
          	  $this->error("数据添加失败");
			}
			exit();
		}
		//取出所有模块
        $modules = D("Rolenode")->getall("status = 1 and is_show=1 and auth_type = 1");
        if (!empty($modules) && is_array($modules)) {
            foreach ($modules as $key => $val) {
            	$actions=D("Rolenode")->getall("status=1 and is_show=1 and auth_type = 0 and module='" . $val['module'] . "'");
                if (!empty($actions) && is_array($actions)) {
                    $modules[$key]['actions'] = $actions;
                }
            }
        }
        $this->assign('access_list', $modules);
		$this->display();
	}
	public function edit(){
		if (IS_POST){
			$ary_request = $this->_request();
			$role = D('Role');
	        $roleAccess = M("role_access");
	        $data = $role->create();
	        if (false === $data = $role->create()) {
	            $this->error($role->getError());
	        }
	        $where = array();
	        $where['id'] = array("NEQ",$ary_request['id']);
	        $where['name']     = $ary_request["name"];
	        $count = $role->where($where)->count();
	        if($count > 0){
	            $this->error("更新的角色已经存在");
	        }
		    //保存当前数据对象
	        $list = $role->where(array('id'=>$ary_request['id']))->save($data);
	        if(false !== $list){
	            $roleAccess->where(array('role_id'=>$ary_request['id']))->delete();
	            $node_ids = $ary_request['access_node'];
	            if (!empty($node_ids) && is_array($node_ids)) {
	                foreach ($node_ids as $node_id) {
	                    $access['role_id'] = $ary_request['id'];
	                    $access['node_id'] = $node_id;
	                    $ary_result = $roleAccess->add($access);
	                    if(FALSE === $ary_result){
	                        $role->rollback();//不成功，回滚
	                        $this->error("数据更新失败");
	                    }
	                    
	                }
	                $this->success("更新成功");
	            } else {
	                $this->error("请选择控制权限");
	            }
	        }else{
	            $this->error("更新失败");
	        }		
			exit();
		}
		$role = D('Role');
        $ary_get = $this->_get();
        $vo = $role->getById($ary_get['id']);
        $this->assign("vo", $vo);
        $roleAccess = M("role_access");
		$role_access = $roleAccess->field("node_id")->where("role_id=" . $ary_get['id'])->select();
		$node_ids = array();
        if (!empty($role_access) && is_array($role_access)) {
            foreach ($role_access as $access) {
                array_push($node_ids, $access['node_id']);
            }
        }
        //取出模块授权
        $modules = D("RoleNode")->where("status = 1 and auth_type = 1")->select();
        if (!empty($modules) && is_array($modules)) {
            foreach ($modules as $k => $v) {
                $actions = D("RoleNode")->where("status=1 and auth_type = 0 and module='" . $v['module'] . "'")->select();
                if ($actions) {
                    $modules[$k]['actions'] = $actions;
                }
            }
            foreach ($modules as $mk => $module) {
                if (in_array($module['id'], $node_ids)) {
                    $modules[$mk]['checked'] = true;
                } else {
                    $modules[$mk]['checked'] = false;
                }
                foreach ($module['actions'] as $ak => $action) {
                    $checkall = true;

                    if (in_array($action['id'], $node_ids)) {
                        $modules[$mk]['actions'][$ak]['checked'] = true;
                    } else {
                        $checkall = false;
                        $modules[$mk]['actions'][$ak]['checked'] = false;
                    }
                }

                if ($checkall) {
                    $modules[$mk]['checkall'] = true;
                } else {
                    $modules[$mk]['checkall'] = false;
                }
            }
        }
        $this->assign('access_list', $modules);
        $this->assign('role', $vo);
        $this->display(); 
	}
	public function del(){
		 $id = intval($this->_get('id'));
		 $role = D('Role');
		 if(!empty($id) && $id > 0){
			$where = array();
            $where[C('DB_PREFIX').'admin.role_id']     = $id;
            $where[C('DB_PREFIX').'admin.u_status']     = '1';
            $count = $role->join(" ".C('DB_PREFIX')."admin on ".C('DB_PREFIX')."admin.role_id=".C('DB_PREFIX')."role.id")->where($where)->count();
            if($count > 0){
                $this->error("角色已经被使用，不可删除");
            }
            //保存当前数据对象
            $list = $role->where(array('id'=>$id))->delete();
            if(false !== $list){
                M("role_access")->where(array('role_id'=>$id))->delete();
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