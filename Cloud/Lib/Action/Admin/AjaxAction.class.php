<?php
/**
 * 后台Ajax
 * Enter description here ...
 * @author Geyoulei
 * 2015-2-6-16dian
 */
class AjaxAction extends AdminAction{
	public function checkadmin(){
		$u_name=I('u_name');
		$ary_result = D("Admin")->getall(array('u_name'=>$u_name));
		if(!empty($ary_result) && is_array($ary_result)){
			$this->ajaxReturn("用户名已存在");
		}else{
			$this->ajaxReturn(true);
		}
	}
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
	//获取资源池信息
	public function getpool(){
		$id=I('id','');
		if ($id){
			$pool=D('Cloudapi')->listresourcepool($id);
			$this->ajaxReturn($pool);
		}else{
			$this->ajaxReturn(array('status'=>'fail','info'=>'ID错误'));
		}
	}
	//获取操作系统
	public function getimage(){
		$id=I('id','');
		$cid=I('cid','');
		if ($id && $cid){
			$temp=D('Cloudapi')->listresourcetemplate($id,$cid);
			if ($temp['status']=='success'){
				$endtemp=array();
				$endtemp['status']='success';
				foreach ($temp['value'] as $kk =>$vv){
					if (!strstr($vv['name_description'],"NLBServer")){
						$endtemp['value'][]=$vv;
					}					
				}
				$this->ajaxReturn($endtemp);
			}
			$this->ajaxReturn($temp);
		}else{
			$this->ajaxReturn(array('status'=>'fail','info'=>'ID错误或Cid错误'));
		}
	}
	public function getimageloadb(){
		$id=I('id','');
		$cid=I('cid','');
		if ($id && $cid){
			$temp=D('Cloudapi')->listresourcetemplate($id,$cid);
			if ($temp['status']=='success'){
				$endtemp=array();
				$endtemp['status']='success';
				foreach ($temp['value'] as $kk =>$vv){
					if (strstr($vv['name_description'],"NLBServer")){
						$endtemp['value'][]=$vv;
					}					
				}
				$this->ajaxReturn($endtemp);
			}
			$this->ajaxReturn($temp);
		}else{
			$this->ajaxReturn(array('status'=>'fail','info'=>'ID错误或Cid错误'));
		}
	}
	//获取光驱镜像
	public function getcdimage(){
		$id=I('id','');
		$cid=I('cid','');
		if ($id && $cid){
			$pool=D('Cloudapi')->listresourcepool($id,$cid);
			if ($pool['status']=='success'){
				$cluster_id=$pool['value']['cluster_id'];
			}else{
				$this->ajaxReturn(array('status'=>'fail','info'=>'资源池获取失败'));	
			}
			$temp=D('Cloudapi')->liststorageiso($id,$cid);
			$this->ajaxReturn($temp);
			
		}else{
			$this->ajaxReturn(array('status'=>'fail','info'=>'ID错误或Cid错误'));
		}
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
}
?>