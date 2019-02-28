<?php
/**
 * Ajax
 */
class AjaxAction extends MainwebAction{
	/**
	 * 获取工单类型对应的内容
	 */
	public function getWOtc(){
		$mod=M("Support_types");
		$id=$_REQUEST["id"];
		if($id){
			$content=$mod->where('id='.$id)->find();
		}
		echo json_encode($content['content']);
	}
	/**
	 * 获取工单类型对应的内容
	 */
	public function getProducts(){
		$mod=M("Support_types");
		$id=$_REQUEST["id"];
		switch ($id){
			case 1://云主机
				$products = M("cloud")->where(array("user_id"=>$_SESSION['user_id']))->field("id,cloudname as name,Cloudtype as type")->select();
				break;
			case 2://备案
				$products = '';
				break;
			case 3://托管租用
				$products = '';
				break;
		}
		echo json_encode($products);
	}
	public function gettcontent(){
		$id = I("id");
		if(!empty($id)){
			$data = "这是获取模版返回信息";
			$data = M("weixin_tmsg_config")->where(array("id"=>$id))->find();
			$fields = unserialize($data['content']);
			echo json_encode($fields);
		}
	}
}
?>