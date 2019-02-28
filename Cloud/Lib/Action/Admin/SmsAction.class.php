<?php
/**
 * 短信接口设置
 * Enter description here ...
 * @author Geyoulei
 *
 */
class SmsAction extends AdminAction{
    public function index(){
    	$Sms=D('Sms')->getall();
    	$this->assign('Sms',$Sms);
    	$this->display();
	}
	public function set(){
		$id=I('id',0);
		if (!empty($id)){
			$data=array('sms_default'=>0);
			D('Sms')->save($data);//清理成所有的默认短信接口
			D('Sms')->edit($id,array('sms_default'=>1));
			 $this->success("设置成功");
		}else{
			$this->error("ID错误");
		}
		
	}
	//设置验证码信息
	public function edit(){
		if (IS_POST){
			 $data=$_POST;
			 $sms_id=$data['sms_id'];
			 if (!empty($sms_id)){
			 	$info=D('Sms')->Show($sms_id);
			 	if ($info){
			 		 $config = json_decode($info['sms_value'], true);
			 		 $adddata=array();
			 		 foreach ($config as $k=>$v){
			 		 	$adddata[$k]=$data[$k];
			 		 }
			 		 $sms_value = json_encode($adddata);
			 		 D('Sms')->edit($sms_id,array('sms_value'=>$sms_value));
			 		 $this->success("保存成功",U("Admin/Sms/index"));
			 	}
			 }
			 exit();
		}	
		$id=I('id',0);
		if (!empty($id)){
			  $data=D('Sms')->Show($id);
			  $config = json_decode($data['sms_value'], true);
			  $this->assign('data',$data);
			  $this->assign('config',$config);
			  $this->display();
		}else {
			
			$this->error("ID错误");
			
		}
	}
	
}
?>