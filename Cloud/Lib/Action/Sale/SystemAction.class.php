<?php
class SystemAction extends SaleadminAction{
    public function passwd(){
    	$this->display();
	}
	public function savepasswd(){
		$ary_post=$_POST;		
		if (!empty($ary_post['u_password'])){
			$data['kefuloginpass']=$ary_post['u_password'];
			$kefu=D('kefu')->where(array('id'=>$this->kid))->save($data);
			if ($kefu != false){
				$this->success("修改成功");
				exit();
			}
		}
		$this->error("操作失败");
		
		
	}
}
?>