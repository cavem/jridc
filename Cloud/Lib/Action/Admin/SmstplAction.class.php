<?php
/**
 * 短信模板设置
 * Enter description here ...
 * @author Geyoulei
 *
 */
class SmstplAction extends AdminAction{
    public function index(){
  		$ary_data = D('Smstpl')->getall("",'sort asc');
  		$this->assign("Smstpl",$ary_data);
    	$this->display();
	}
	public function edit(){
		if (IS_POST){
			$data=$_POST;
			if (D('Smstpl')->edit($data['key'],$data)){
				$this->success("修改成功");
			}else{
				$this->error("修改失败");
			}
			exit();
		}
		$key=I('id',"");
		if ($key){
			$data=D('Smstpl')->show($key);
			$this->assign('data',$data);
			$this->display();
		}else{
			$this->error("ID错误");
		}
		
	}
	
}
?>