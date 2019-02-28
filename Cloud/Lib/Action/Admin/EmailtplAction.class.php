<?php
/**
 * 邮件模板设置
 * Enter description here ...
 * @author Geyoulei
 *
 */
class EmailtplAction extends AdminAction{
    public function index(){
    	$ary_data = D('Emailtpl')->getall("",'sort asc');
  		$this->assign("Emailtpl",$ary_data);
    	$this->display();
	}
	public function edit(){
	if (IS_POST){
			$data=$_POST;
			if (D('Emailtpl')->edit($data['key'],$data)){
				$this->success("修改成功");
			}else{
				$this->error("修改失败");
			}
			exit();
		}
		$key=I('id',"");
		if ($key){
			$data=D('Emailtpl')->show($key);
			$this->assign('data',$data);
			$this->display();
		}else{
			$this->error("ID错误");
		}
	}
	
}
?>