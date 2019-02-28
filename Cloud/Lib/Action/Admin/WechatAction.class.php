<?php
/**
 * 微信管理
 * Enter description here ...
 * @author Geyoulei
 * 
 */
class WechatAction extends AdminAction{
   public function admin(){
   	$_SESSION['Wechatadmin']=$_SESSION['admin_name'];
   	$this->redirect('Wechat/Admin/index');
   	
   }
}
?>