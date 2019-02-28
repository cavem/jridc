<?php
/**
 +------------------------------------------------------------------------------
 * 基于角色的数据库方式验证类
 +------------------------------------------------------------------------------
 * @category   ORG
 * @package  ORG
 * @subpackage  Util
 * @author    Terry <573992533@qq.com>
 +------------------------------------------------------------------------------
 */
class Arbac {
    // 认证方法
    static public function authenticate($map,$model='') {

        if(empty($model)) $model =  C('USER_AUTH_MODEL');
        //使用给定的Map进行认证
        return M($model,C('DB_PREFIX'),'DB_CUSTOM')->where($map)->find();
    }

    //用于检测用户权限的方法,并保存到Session中
    static function saveAccessList($authId=null) {
        if(null===$authId)   $authId = $_SESSION[C('USER_AUTH_KEY')];
        // 如果使用普通权限模式，保存当前用户的访问权限列表
        // 对管理员开发所有权限
    //echo "<pre>";print_r(C('USER_AUTH_KEY'));exit;
        if(C('USER_AUTH_TYPE') !=2 && !$_SESSION[C('ADMIN_AUTH_KEY')] )

            $_SESSION['_ACCESS_LIST'] = Arbac::getAccessList($authId);

        return ;
    }

  // 取得模块的所属记录访问权限列表 返回有权限的记录ID数组
  static function getRecordAccessList($authId=null,$module='') {
        if(null===$authId)   $authId = $_SESSION[C('USER_AUTH_KEY')];
        if(empty($module))  $module = MODULE_NAME;
        //获取权限访问列表
        $accessList = Arbac::getModuleAccessList($authId,$module);
        return $accessList;
  }

    //检查当前操作是否需要认证
    static function checkAccess()
    {
      //如果项目要求认证，并且当前模块需要认证，则进行权限认证
      if( C('USER_AUTH_ON') ){
      $_module  = array();
      $_action  = array();
            if("" != C('REQUIRE_AUTH_MODULE')) {
                //需要认证的模块
                $_module['yes'] = explode(',',strtoupper(C('REQUIRE_AUTH_MODULE')));
            }else {
                //无需认证的模块
                $_module['no'] = explode(',',strtoupper(C('NOT_AUTH_MODULE')));
            }

            //检查当前模块是否需要认证
            if((!empty($_module['no']) && !in_array(strtoupper(MODULE_NAME),$_module['no'])) || (!empty($_module['yes']) && in_array(strtoupper(MODULE_NAME),$_module['yes']))) {

        if("" != C('REQUIRE_AUTH_ACTION')) {
          //需要认证的操作
          $_action['yes'] = explode(',',strtoupper(C('REQUIRE_AUTH_ACTION')));
        }else {
          //无需认证的操作
          $_action['no'] = explode(',',strtoupper(C('NOT_AUTH_ACTION')));
        }

        //检查当前操作是否需要认证
        if((!empty($_action['no']) && !in_array(strtoupper(ACTION_NAME),$_action['no'])) || (!empty($_action['yes']) && in_array(strtoupper(ACTION_NAME),$_action['yes']))) {
          return true;
        }else {
          return false;
        }
            }else {
                return false;
            }
        }
        return false;
    }

  // 登录检查
  static public function checkLogin() {
        //检查当前操作是否需要认证
        if(Arbac::checkAccess()) {
            //检查认证识别号
            if(!$_SESSION[C('USER_AUTH_KEY')]) {
                if(C('GUEST_AUTH_ON')) {
                    // 开启游客授权访问
                    if(!isset($_SESSION['_ACCESS_LIST']))
                        // 保存游客权限
                        Arbac::saveAccessList(C('GUEST_AUTH_ID'));
                }else{
                    // 禁止游客访问跳转到认证网关
                    redirect(PHP_FILE.C('USER_AUTH_GATEWAY'));
                }
            }
        }
        return true;
  }

    //权限认证的过滤器方法
    static public function AccessDecision($appName=APP_NAME)
    {

        //检查是否需要认证
        if(Arbac::checkAccess()) {
            //存在认证识别号，则进行进一步的访问决策
            $accessGuid   =   md5($appName.MODULE_NAME.ACTION_NAME);

            if(empty($_SESSION[C('ADMIN_AUTH_KEY')])) {
                if(C('USER_AUTH_TYPE')==2) {
                    //加强验证和即时验证模式 更加安全 后台权限修改可以即时生效
                    //通过数据库进行访问检查
                    $accessList = Arbac::getAccessList($_SESSION[C('USER_AUTH_KEY')]);
          $_SESSION['_ACCESS_LIST'] = $accessList;

                }else {
                    // 如果是管理员或者当前操作已经认证过，无需再次认证
                    if( $_SESSION[$accessGuid]) {
                        return true;
                    }
                    //登录验证模式，比较登录后保存的权限访问列表
                    $accessList = $_SESSION['_ACCESS_LIST'];

                }

                //判断是否为组件化模式，如果是，验证其全模块名
        $module = defined('P_MODULE_NAME')?  P_MODULE_NAME   :   MODULE_NAME;
        $auth_type = C("AUTH_TYPE");

        if(!isset($accessList[strtoupper($module)][strtoupper(ACTION_NAME)]))
        {
                  //进行相关模块或操作公共授权的判断
                  if(isset($accessList[$auth_type[2]][strtoupper(ACTION_NAME)]))
                  {
                    $_SESSION[$accessGuid]  = true;
                  }
                  elseif (isset($accessList[strtoupper($module)][$auth_type[1]]))
                  {
                    $_SESSION[$accessGuid]  = true;
                  }
                  else
                  {
                      $_SESSION[$accessGuid]  =   false;
                      return false;
                  }

                }
                else {
                    $_SESSION[$accessGuid]  = true;
                }
            }else{
                //管理员无需认证
        return true;
      }
        }
        return true;
    }
    /**
     +----------------------------------------------------------
     * 取得当前认证号的所有权限列表
     +----------------------------------------------------------
     * @param integer $authId 用户ID
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
    static public function getAccessList($authId)
    {
        //echo "<pre>";print_r($authId);exit;
        // Db方式权限数据
        $db     =   Db::getInstance(C('RBAC_DB_DSN'));
        $auth_type = C("AUTH_TYPE");
        $table = array('role'=>C("DB_PREFIX").C('RBAC_ROLE_TABLE'),'user'=>C("DB_PREFIX").C('RBAC_USER_TABLE'),'access'=>C("DB_PREFIX").C('RBAC_ACCESS_TABLE'),'node'=>C("DB_PREFIX").C('RBAC_NODE_TABLE'));
        $sql    =   "select node.auth_type,node.id,node.action,node.action_name,node.module,node.module_name from ".
                    $table['role']." as role,".
                    $table['user']." as user,".
                    $table['access']." as access ,".
                    $table['node']." as node ".
                    "where user.u_id='{$authId}' and user.role_id=role.id and access.role_id=role.id and role.status=1 and access.node_id=node.id and node.status=1";

       $apps =   $db->query($sql);        
       require_once(dirname(dirname(dirname(__FILE__))).'/Conf/Admin/authoritys.php');
       $access =  array();
       foreach($apps as $key=>$app)
      {
      $appId  = $app['id'];
      $module_name   =   $app['module'];
      $action_name   =   $app['action'];
      $o_module_name = strtoupper($module_name);
      $o_action_name = strtoupper($action_name);
      $l_module_name = strtolower($module_name);
      $l_action_name = strtolower($action_name);

      // 读取项目的模块权限
            if($app['auth_type']==0) //节点授权
            {
              $access[$o_module_name][$o_action_name] =  true;

        if(isset($authoritys['all'][$l_action_name]))
        {
          $authoritys_list = $authoritys['all'][$l_action_name];
          foreach($authoritys_list as $authority_item)
          {
            $access[$o_module_name][strtoupper($authority_item)] =  true;
          }
        }

        if(isset($authoritys['actions'][$l_module_name][$l_action_name]))
        {
          $authoritys_list = $authoritys['actions'][$l_module_name][$l_action_name];
          foreach($authoritys_list as $authority_item)
          {
            $access[$o_module_name][strtoupper($authority_item)] =  true;
          }
        }
            }

          if($app['auth_type']==1) //模块授权
            {
              $access[$o_module_name][strtoupper($auth_type[$app['auth_type']])] = true;
            }

          if($app['auth_type']==2) //操作授权
            {
              $access[strtoupper($auth_type[$app['auth_type']])][$o_action_name] = true;
      }
        }

        //不需要认证的模块
        if(!empty($authoritys['no']) && is_array($authoritys['no'])){
          foreach($authoritys['no'] as $module_name=>$action_name){
              $o_module_name = strtoupper($module_name);         
              $o_action_name = strtoupper(key($action_name));
              foreach($action_name as $keymodule=>$valaction){
                $keymodule = strtoupper($keymodule);
                $access[$o_module_name][$keymodule] =  true;
              }
          }
        }
        return $access;
    }

  // 读取模块所属的记录访问权限
  static public function getModuleAccessList($authId,$module) {
        // Db方式
        $db     =   Db::getInstance(C('RBAC_DB_DSN'));
        $table = array('role'=>C("DB_PREFIX").C('RBAC_ROLE_TABLE'),'user'=>C("DB_PREFIX").C('RBAC_USER_TABLE'),'access'=>C("DB_PREFIX").C('RBAC_ACCESS_TABLE'));
        $sql    =   "select access.node_id from ".
                    $table['role']." as role,".
                    $table['user']." as user,".
                    $table['access']." as access ".
                    "where user.id='{$authId}' and user.role_id=role.id and access.role_id=role.id and role.status=1 and  access.module='{$module}' and access.status=1";
        $rs =   $db->query($sql);
        $access = array();
        foreach ($rs as $node){
            $access[] = $node['node_id'];
        }
  }
}