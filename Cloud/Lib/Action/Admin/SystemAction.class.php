<?php
/**
 * 后台角色管理
 * Enter description here ...
 * @author Geyoulei
 * 2015-2-9-16dian
 */
class SystemAction extends AdminAction{
	public function index(){
		$ary_get = $this->_get();
		$ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', 10);
		$admin_access = D('Config')->getCfgByModule('ADMIN_ACCESS');
		$admin = M("admin");
		$count = $admin->join( C("DB_PREFIX")."role ON ".C("DB_PREFIX")."admin.role_id=".C("DB_PREFIX")."role.id")->where()->count();
		$obj_page = $this->AdminPage($count, $ary_get['pageall']);
		$page = $obj_page->show();
		$ary_data = $admin->join( C("DB_PREFIX")."role ON ".C("DB_PREFIX")."admin.role_id=".C("DB_PREFIX")."role.id")->where()->limit($obj_page->firstRow, $obj_page->listRows)->select();
		$this->assign("data",$ary_data);
		$this->assign("admin",$admin_access);
		$this->assign("page",$page);
		$this->display();
		
	}
	public function adminlog(){
		$ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', 10);
		import('ORG.Net.IpLocation');// 导入IpLocation类
		$Ip = new IpLocation(); // 实例化类
		$count = D("Adminlog")->counts();
		$obj_page = $this->AdminPage($count, $ary_get['pageall']);
		$page = $obj_page->show();
		
		$ary_data = D("Adminlog")->getall(null,'log_create desc',$obj_page->firstRow, $obj_page->listRows);
		if(!empty($ary_data) && is_array($ary_data)){
			foreach ($ary_data as $k=>$v){
				$ary_data[$k]['ip_location'] = $Ip->getlocation($v['log_ip']);
			}
		}
		$this->assign("data",$ary_data);
		$this->assign("page",$page);
		$this->display();
	}
	public function addadmin(){
		if (IS_POST){
			$ary_post = $this->_post();
			$u_name=$ary_post['u_name'];
			//查询当前用户是否存在
			$Admin=D('Admin');
			$admininfo=$Admin->getall(array('u_name'=>$u_name));
			if ($admininfo)$this->error('当前管理账号已经存在');
			if (empty($ary_post['u_password']))$this->error('管理员密码不能为空');
			if(!empty($ary_post['u_password'])){
				$ary_post['u_passwd']	= md5(trim($ary_post['u_password']));
			}
			$time = date("Y-m-d H:i:s");
			$ary_post['u_lastlogin_time']=$time;
			$ary_post['u_create_time']=$time;
			$ary_post['u_update_time']=$time;
			$ary_result =$Admin->adddata($ary_post);
			if(FALSE !== $ary_result){
				$this->success("管理员信息添加成功");
			}else{
				$this->error("管理员信息添加失败");
			}
			exit();
		}
		$role = D("Role");
		$ary_role = $role->getall(array('status'=>'1'));
		$this->assign("role",$ary_role);
		$this->display();
	}
	public function editadmin(){
		if (IS_POST){
			$ary_post = $this->_post();
			if(empty($ary_post['u_id']) && !isset($ary_post['u_id'])){
				$this->error("管理员信息不存在");
			}
			if(!empty($ary_post['u_password'])){
				$ary_post['u_passwd']	= md5(trim($ary_post['u_password']));
			}
			$ary_post['u_update_time']  = date("Y-m-d H:i:s");	
			$ary_result = D('Admin')->edit($ary_post['u_id'],$ary_post);
			if(FALSE !== $ary_result){
				$this->success("管理员信息更新成功");
			}else{
				$this->error("管理员信息更新失败");
			}
			exit();
		}
		
		$admin_access = D('Config')->getCfgByModule('ADMIN_ACCESS');
		$uid=intval(I('uid',0));
		
		if(!empty($uid) && $uid > 0){
			$role = D("Role");
			$ary_role = $role->getall(array('status'=>'1'));	
			$data=D('Admin')->Show($uid);
			$this->assign("admin",$admin_access);
			$this->assign("data",$data);
			$this->assign("role",$ary_role);
			$this->display();
			
		}else{
			$this->error("用户不存在，请重试！");
		}
		
	}
	public function deladmin(){
		$ary_get = $this->_get();
		if(!empty($ary_get['uid']) && isset($ary_get['uid'])){
			$admin_access = D('Config')->getCfgByModule('ADMIN_ACCESS');
			$uadmin=$admin_access['SYS_ADMIN'];
			//查询当前需要删除的管理员
			$data=D('Admin')->show($ary_get['uid']);
			if (!$data)$this->error("管理员不存在");
			$u_name=$data['u_name'];
			if ($u_name==$uadmin)$this->error("管理员账号禁止删除");
			$ary_result = D('Admin')->del($ary_get['uid']);
			if(FALSE !== $ary_result){
				$this->success("管理员删除成功");
			}  else {
				$this->error("管理员删除失败");
			}
		}else{
			$this->error("管理员不存在");
		}
		
	}
	public function adminpasswd(){
		if (IS_POST){
			$ary_post = $this->_post();
			if(!empty($ary_post['u_password'])){
				$ary_post['u_passwd']	= md5(trim($ary_post['u_password']));
			}
			$ary_post['u_update_time']  = date("Y-m-d H:i:s");	
			$ary_result = D('Admin')->edit($ary_post['u_id'],$ary_post);
			if(FALSE !== $ary_result){
				$this->success("管理员密码更新成功");
			}else{
				$this->error("管理员密码更新失败");
			}
			exit();
		}
		//查询基本信息
		$id = $_SESSION[C('USER_AUTH_KEY')];
		if(!empty($id)){
			$mod = D('admin');
			$admin_info = $mod->where("u_id = ".$id)->find();
			$this->assign('data',$admin_info);
			$this->display();
		}else{
			$this->error("主参数为空！");
		}
	}
}
?>