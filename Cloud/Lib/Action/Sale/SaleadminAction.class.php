<?php
class SaleadminAction extends Action{
	protected $kid;
	public function _initialize() {
		 import('ORG.Util.Session');
		 $this->doCheckLogin();
		 $this->kid=$_SESSION ['kefuid'];
         import('ORG.Util.Page');
	}
	/**
	 * 验证用户登录
	 */
	protected function doCheckLogin(){
	 	if(!$_SESSION['kefuid'] || empty($_SESSION['kefuname']) || empty($_SESSION['kefukey']) || empty($_SESSION['kefusign']) ){
        	$this->redirect('Sale/Login/Login');
        }
        if ($_SESSION['kefusign']<>sha1($_SESSION['kefuname'].$_SESSION['kefukey'].$_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR'])){
        	$this->redirect('Sale/Login/Login');
        }
	}
	//取出当前客服下的所有用户ID
	public function userid($kid){
		$data=D('user')->where(array('kid'=>$kid))->Field('user_id')->select();
		foreach ($data as $k=>$v){
			$endarr[$k]=$v['user_id'];
		}
		$uidstr=implode(',',$endarr);
		return $uidstr;
	}
	//验证当前用户是否属于本客服
	public function usercheck($user_id,$kid){
		$dataid=D('user')->where(array('user_id'=>$user_id))->getField('kid');
		if ($dataid==$kid){
			return true;
		}else{
			return false;
		}
		
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
}
?>