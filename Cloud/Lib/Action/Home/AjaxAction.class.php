<?php
/**
 * Ajax
 */
class AjaxAction extends HomeAction{
	public function sendmail(){
		$get_arr=$_REQUEST;
		if ($get_arr['test_address']){
			$title    = '邮件测试';
			$content  = '这是一封测试邮件信息';
			$rdata=D('Sendmail')->send($get_arr['test_address'],$title,$content);
			if($rdata){
				$this->success("恭喜你！测试通过");
			}else{
				$this->error("测试失败，请确认您的邮箱已经开启的smtp服务并且配置信息均填写正确");
			}
		}else{
				$this->error("测试邮件地址不能为空");
		}
	}
	public function sendmaila(){
			$title    = '邮件测试';
			$content  = '这是一封测试邮件信息';
			$rdata=D('Sendmail')->send('3401146@qq.com',$title,$content);
			p($rdata);
	}
	/**
	 * 获取地区信息
	 */
	public function getregion(){
		$Region=M("Region");
		$map['pid']=$_REQUEST["pid"];
		$map['type']=$_REQUEST["type"];
		$list=$Region->where($map)->select();
		echo json_encode($list);
	}
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
				$products = M("cloud")->where(array("user_id"=>$_SESSION['uid']))->field("id,cloudname as name,Cloudtype as type")->select();
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
}
?>