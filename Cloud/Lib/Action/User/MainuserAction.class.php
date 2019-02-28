<?php
class MainuserAction extends Action{
	public $uid;
	public $username;
 	public function _initialize() {
	 	import('ORG.Util.Session');
	 	import('ORG.Util.Page');
        import('ORG.Util.Tree');
        import('ORG.Util.Dir');
        Load('extend');
		$this->doCheckLogin();
        $webConf = D('Config')->getCfgByModule('WEBSITE');
        $config = json_decode($webConf['WEBSITE'], true);
        $data['Config'] = $config;
        if (empty($config['site_status'])){
        	echo "网站升级关闭中!!";
        	exit();
        }
        $this->assign('Web',$data);
		if (!is_numeric($_SESSION['uid']))$this->redirect("/home/user/login");
		$user_info = M("user")->where('user_id = '.$_SESSION['uid'])->find();
		if (!$user_info)$this->redirect("/home/user/login");
		if (empty($user_info['status']))$this->redirect("/home/user/login");
		$this->uid=$user_info['user_id'];
		$this->username=$user_info['username'];
		$this->assign('user_info',$user_info);
		$this->assign('user_rank',$this->userrank($user_info['user_rank']));
		$this->assign('user_kefu',$this->userkefu($user_info['kid'],$user_info['user_id']));
 		if(!checkusername($this->username)){//验证用户名格式
       		$this->redirect("/home/user/login");
       	}
		$this->cloud_magic_quotes();
	}
	/**
	 * 验证用户登录
	 */
	protected function doCheckLogin(){
	 	if(!$_SESSION['uid'] || empty($_SESSION['username']) || empty($_SESSION['userkey']) || empty($_SESSION['sign']) ){
        	$this->redirect("/home/user/login");
        }
        if ($_SESSION['sign']<>sha1($_SESSION['username'].$_SESSION['userkey'].$_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR'])){
        	$this->redirect("/home/user/login");
        }
	}
	/**
	 * 用户组
	 */
	protected function userrank($rank_id){
		return D('user_rank')->where(array('rank_id'=>$rank_id))->find();
	}
	//用户信息
	protected function userinfo($uid){
		return  M("user")->where('user_id = '.$uid)->find();
	}
	//用户折扣
	protected function usercloudpre($uid){
		$user=M("user")->where('user_id = '.$uid)->find();
		$userank=D('user_rank')->where(array('rank_id'=>$user['user_rank']))->field('cloud_pers')->find();
		return $userank['cloud_pers'];
	}
	protected function userdiskpre($uid){
		$user=M("user")->where('user_id = '.$uid)->find();
		$userank=D('user_rank')->where(array('rank_id'=>$user['user_rank']))->field('disk_pres')->find();
		return $userank['disk_pres'];
	}
	/**
	 * 当前用户的客服
	 */
	protected  function userkefu($k_id,$uid){
		
		$kefu=D('kefu')->where(array('id'=>$k_id))->find();
		if ($kefu) return $kefu;
		$kefunew=D('kefu')->order("rand()")->find();
		D('user')->where(array('user_id'=>$uid))->save(array('kid'=>$kefunew['id']));
		return $kefunew;
	}
 	/**
     * 前台统一分页 Mr
     */
    public function UserPage($count, $pagesize) {
        $page = new Page($count, $pagesize);
        $page->setConfig("header", "条");
        $page->setConfig('theme', '共%totalRow%%header%&nbsp;%nowPage%/%totalPage%页&nbsp;%first%&nbsp;%upPage%&nbsp;%prePage%&nbsp;%linkPage%&nbsp;%nextPage%&nbsp;%downPage%&nbsp;%end%');
		return $page;
    }
	/**
     * +----------------------------------------------------------
     * 交互数据转义操作
     * +----------------------------------------------------------
     */
    protected function cloud_magic_quotes() {
        if (!@ get_magic_quotes_gpc()) {
            $_GET = $_GET ? $this->addslashes_deep($_GET) : '';
            $_POST = $_POST ? $this->addslashes_deep($_POST) : '';
            $_COOKIE = $this->addslashes_deep($_COOKIE);
            $_REQUEST = $this->addslashes_deep($_REQUEST);
        }
    }
  	/**
     * +----------------------------------------------------------
     * 递归方式的对变量中的特殊字符进行转义
     * +----------------------------------------------------------
     */
    protected function addslashes_deep($value) {
        if (empty($value)) {
            return $value;
        }
        
        if (is_array($value)) {
            foreach ((array) $value as $k => $v) {
                unset($value[$k]);
                $k = addslashes($k);
                if (is_array($v)) {
                    $value[$k] = $this->addslashes_deep($v);
                } else {
                    $value[$k] = addslashes($v);
                }
            }
        } else {
            $value = addslashes($value);
        }
        
        return $value;
    }
    
    /**
     * +----------------------------------------------------------
     * 递归方式的对变量中的特殊字符去除转义
     * +----------------------------------------------------------
     */
    protected function stripslashes_deep($value) {
        if (empty($value)) {
            return $value;
        }
        
        if (is_array($value)) {
            foreach ((array) $value as $k => $v) {
                unset($value[$k]);
                $k = stripslashes($k);
                if (is_array($v)) {
                    $value[$k] = $this->stripslashes_deep($v);
                } else {
                    $value[$k] = stripslashes($v);
                }
            }
        } else {
            $value = stripslashes($value);
        }
        return $value;
    }
}
?>