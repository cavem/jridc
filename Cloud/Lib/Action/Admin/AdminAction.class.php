<?php
class AdminAction extends Action{
	protected $_name = '';
	private $tops = array();
    private $menus = array();
	public function _initialize() {
		
		 import('ORG.Util.Session');
		 $this->doCheckLogin();
		 $this->_name = $this->getActionName();
		 $ary_get = $this->_get();
         $module = $ary_get['_URL_'][1] ? $ary_get['_URL_'][1] : "Main";
         $action = $ary_get['_URL_'][2] ? $ary_get['_URL_'][2] : "index";
         if(!empty($module) && !empty($action)){
         	$array_where = array();
            $array_where['action'] = $action;
            $array_where['module'] = $module;
            $array_where['status'] = '1';
            $array_where['is_show'] = '1';
            $rolenode = D("role_node")->where($array_where)->order('sort asc')->find();
            if(!empty($rolenode) && is_array($rolenode)){
                $navid = $rolenode['nav_id'];
            }else{
                $node = D("role_node")->where(array('module'=>$module,'action'=>array('NEQ',''),'status'=>'1'))->order('sort asc')->find();
                $navid = $node['nav_id'];
                $module = $node['module'];
                $action = $node['action'];
            }
         }
	     $this->assign("modulename",$module);
	     $this->assign("actionname",$action);
	     $this->assign("navid",$navid);
	     $navname = D("role_nav")->where(array('id' => $navid))->find();
	     session("navname", $navname['name']);
	             $rolenav = M('role_nav')->field(C('DB_PREFIX') . 'role_nav.name,' . C('DB_PREFIX') . 'role_node.*')
                ->join(C('DB_PREFIX') . 'role_node ON ' . C('DB_PREFIX') . 'role_nav.id = ' . C('DB_PREFIX') . 'role_node.`nav_id`')
                ->where(C('DB_PREFIX') . 'role_nav.id =  "' . $navid . '" AND ' . C('DB_PREFIX') . 'role_node.`action` =  "' . $action . '" AND ' . C('DB_PREFIX') . 'role_node.`module` =  "' . $module . '"')
                ->find();
		if (!empty($rolenav) && is_array($rolenav)) {
            cookie("menuid", $rolenav['id']);
        }
	    $admin_access = D('Config')->getCfgByModule('ADMIN_ACCESS');
        if (intval($admin_access['EXPIRED_TIME']) > 0 && Session::isExpired()) {
            unset($_SESSION[C('USER_AUTH_KEY')]);
            unset($_SESSION);
            session_destroy();
        }
        if (intval($admin_access['EXPIRED_TIME']) > 0) {
            Session::setExpire(time() + $admin_access['EXPIRED_TIME'] * 60);
        }
	    if (C('USER_AUTH_ON') && !in_array(MODULE_NAME, explode(',', C('NOT_AUTH_MODULE')))) {
            $rbac = new Arbac();
           // p($rbac->AccessDecision());
            if (!$rbac->AccessDecision()) {
                   //检查认证识别号
                if (!$_SESSION [C('USER_AUTH_KEY')]) {
                    //跳转到认证网关
                    redirect(PHP_FILE . C('USER_AUTH_GATEWAY'));
                }
                // 没有权限 抛出错误
                if (C('RBAC_ERROR_PAGE')) {
                    // 定义权限错误页面
                    redirect(C('RBAC_ERROR_PAGE'));
                } else {
                    if (C('GUEST_AUTH_ON')) {
                        $this->assign('jumpUrl', PHP_FILE . C('USER_AUTH_GATEWAY'));
                    }
                    // 提示错误信息
                    $this->error('权限错误！');
                }
            }
        }
      
        $this->getTop();
        $this->getMenus($navid);
		import('ORG.Util.Page');
		import('ORG.Util.Tree');
        import('ORG.Util.Dir');
        $this->assign('ISRewrite',C('URL_MODEL'));
	}
	/**
     * 获取顶部导航信息
     */
    public function getTop() {
        $tops = D('role_nav')->where('status=1')->field('id,name,cssstyle')->order("sort ASC")->select();
        if(!empty($tops) && is_array($tops)){
            foreach ($tops as &$val){
                $where = array();
                $where['action'] = array('NEQ','');
                $where['nav_id'] = $val['id'];
                $where['is_show'] = '1';
                $where['status'] = '1';
                $where['auth_type'] = array('NEQ','1');
                $rolenode = D("role_node")->where($where)->order('sort asc')->find();
                $val['module'] = $rolenode['module'];
                $val['action'] = $rolenode['action'];
                $val['rn_id'] = $rolenode['id'];
                $val['nav_id'] = $rolenode['nav_id'];
            }
        }
        $this->tops = $tops;
        $this->assign('tops', $tops);
    }
 /**
     * 获取左侧菜单信息
     */
    public function getMenus($menuid) {
        $menus = array();
        if (session(C("ADMIN_AUTH_KEY"))) {
            $id = intval($menuid);
            $where = array();
            $where['status'] = '1';
            $where['nav_id'] = $menuid;
            $where['is_show'] = '1';
            $where['auth_type'] = 0;
            $no_modules = explode(',', strtoupper(C('NOT_AUTH_MODULE')));
            $access_list = $_SESSION['_ACCESS_LIST'];
            $node_list = D("role_node")->where($where)->field('id,action,action_name,module,module_name,nav_id,sort')->order(array('sort' => 'ASC'))->select();
           // p($node_list);
            //getLastSql();
            if (!empty($node_list) && is_array($node_list)) {
                foreach ($node_list as $key => $node) {
                    $menus[$node['module']]['nodes'][] = array_unique($node);
                    $menus[$node['module']]['name'] = $node['module_name'];
                    $menus[$node['module']]['module'] = $node['module'];
                    if ((isset($access_list[strtoupper($node['module'])]['MODULE']) || isset($access_list[strtoupper($node['module'])][strtoupper($node['action'])])) || $_SESSION['administrator'] || in_array(strtoupper($node['module']), $no_modules)) {
                        if (!in_array($node['id'], $menus[$node['module']]['nodes'][$key])) {
                            $menus[$node['module']]['nodes'][] = array_unique($node);
                        }
                        $menus[$node['module']]['name'] = $node['module_name'];
                        $menus[$node['module']]['module'] = $node['module'];
                    }
                }
            }
            $_SESSION['menu_' . $id . '_' . $_SESSION[C('USER_AUTH_KEY')]] = $menus;
        } else {
            $menus = $this->getOrdinaryPermissions($menuid);
        }
        //重组数组
        $menusnew=array();
        foreach ($menus as $key =>$v){
        	$rolenode=D("role_node")->where(array('auth_type'=>1,'module'=>$v['module'],'is_show'=>1))->find();
        	if ($rolenode){
        		$menusnew[]=$v;
        	}
        }
        $this->menus = $menusnew;
        $this->assign("menus", $menusnew);
        return $menus;
    }
	/**
     * 获取普通管理员的权限
     */
    public function getOrdinaryPermissions($menuid) {
        //取出当前用户的权限
        $u_id = $_SESSION[C('USER_AUTH_KEY')];
        $where = array();
        $where[C('DB_PREFIX')."admin.u_id"] = $u_id;
        $where[C('DB_PREFIX')."role_node.is_show"] = "1";
        $arr_access_list = D("role_node")
                           ->field(array(C('DB_PREFIX')."role_node.id,".C('DB_PREFIX')."role_node.action,".C('DB_PREFIX')."role_node.action_name,".C('DB_PREFIX')."role_node.module,".C('DB_PREFIX')."role_node.module_name,".C('DB_PREFIX')."role_node.nav_id"))
                           ->join(" ".C('DB_PREFIX')."role_access on ".C('DB_PREFIX')."role_access.node_id=".C('DB_PREFIX')."role_node.id")
                           ->join(" ".C('DB_PREFIX')."admin on ".C('DB_PREFIX')."role_access.role_id=".C('DB_PREFIX')."admin.role_id")
                           ->where($where)
                           ->select();
        $data_menu = array();
        if(!empty($arr_access_list) && is_array($arr_access_list)){
            foreach($arr_access_list as $keymenu=>$valmenu){
                if(!empty($valmenu['action'])){
                    $data_menu[$valmenu['module']][$valmenu['action']] = $valmenu;
                }else{
                    $role_data = D("role_node")->where(array('is_show'=>'1','status'=>'1','module'=>$valmenu['module'],'action'=>array('NEQ','')))->select();
                    if(!empty($role_data) && is_array($role_data)){
                        foreach($role_data as $keyrl=>$valrl){
                            $data_menu[$valmenu['module']][$valrl['action']] = $valrl;
                        }
                    }
                }
                
            }
        }
        
        $id = intval($menuid);
        $menus = array();
        $where = array();
        $where['status'] = '1';
        $where['nav_id'] = $menuid;
        $where['is_show'] = '1';
        $where['auth_type'] = 0;
        $no_modules = explode(',', strtoupper(C('NOT_AUTH_MODULE')));
        $access_list = $_SESSION['_ACCESS_LIST'];
        $node_list = D("role_node")->where($where)->field('id,action,action_name,module,module_name,nav_id')->order(array('sort' => 'ASC'))->select();
        if(!empty($node_list) && is_array($node_list)){
            foreach($node_list as $keydata=>$valdata){
                if($data_menu[$valdata['module']][$valdata['action']]['action'] != $valdata['action']){
                    unset($node_list[$keydata]);
                }else{
                    $menus[$valdata['module']]['nodes'][$valdata['action']] = array_unique($valdata);
                    $menus[$valdata['module']]['name'] = $valdata['module_name'];
                    $menus[$valdata['module']]['module'] = $valdata['module'];
                    if ((isset($access_list[strtoupper($valdata['module'])]['MODULE']) || isset($access_list[strtoupper($valdata['module'])][strtoupper($valdata['action'])])) || $_SESSION['administrator'] || in_array(strtoupper($valdata['module']), $no_modules)) {
                        if (!in_array($valdata['id'], $menus[$valdata['module']]['nodes'][$key])) {
                            $menus[$valdata['module']]['nodes'][$valdata['action']] = array_unique($valdata);
                        }
                        $menus[$valdata['module']]['name'] = $valdata['module_name'];
                        $menus[$valdata['module']]['module'] = $valdata['module'];
                    }
                }
                $_SESSION['menu_' . $id . '_' . $_SESSION[C('USER_AUTH_KEY')]] = $menus;
            }
        }
        return $menus;
    }
    /**
     * 后台统一分页
     */
    public function AdminPage($count, $pagesize) {
        $page = new Page($count, $pagesize);
        $page->setConfig("header", "条");
        $page->setConfig('theme', '<ul><li class="pageSelect">共%totalRow%%header%&nbsp;%nowPage%/%totalPage%页&nbsp;%first%&nbsp;%upPage%&nbsp;%prePage%&nbsp;%linkPage%&nbsp;%nextPage%&nbsp;%downPage%&nbsp;%end%</li></ul>');
        return $page;
    }
 	/**
     * 判断用户是否登陆
     */
    public function doCheckLogin() {
		if (!session(C('USER_AUTH_KEY')) or empty($_SESSION[C('USER_AUTH_KEY')."admagentkey"])) {
            $int_port = "";
            if ($_SERVER["SERVER_PORT"] != 80) {
                $int_port = ':' . $_SERVER["SERVER_PORT"];
            }
            $string_request_uri = "http://" . $_SERVER["SERVER_NAME"] . $int_port . $_SERVER['REQUEST_URI'];
            $this->error('登陆超时', U('Admin/Login/Login') . '?doUrl=' . urlencode($string_request_uri));
        } else {
	        if (md5($_SESSION['admin_name'].get_client_ip())<>$_SESSION[C('USER_AUTH_KEY')."admagentkey"]){
	        	$int_port = "";
          		if ($_SERVER["SERVER_PORT"] != 80) {
	                $int_port = ':' . $_SERVER["SERVER_PORT"];
	            }
	            $string_request_uri = "http://" . $_SERVER["SERVER_NAME"] . $int_port . $_SERVER['REQUEST_URI'];
	            $this->error('登陆超时', U('Admin/Login/Login') . '?doUrl=' . urlencode($string_request_uri));
	        }
            $this->admin = session(C('USER_AUTH_KEY'));
        }
    }
}
?>