<?php
class DomainwhiteAction extends AdminAction{
    public function index(){
		$ary_get=$_GET;
		$ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', C('PAGE_COUNT'));
		$where=" 1=1 ";
		if(!empty($ary_get['username']))$where=$where." and username ='".$ary_get['username']."'";
		if(!empty($ary_get['domain']))$where=$where." and domain =".$ary_get['domain'];
		$count = M("domainwhite")->where($where)->count();
		$obj_page =$this->AdminPage($count,$ary_get['pageall']);
        $pageinfo = $obj_page->show();
        $data = M("domainwhite")
			    ->where($where)
        		->limit($obj_page->firstRow, $obj_page->listRows)
				->order("id desc")
				->select();
		foreach ($data as $k=>$v){
			if ($v[typeid]==1){
				$cloud=D('Cloud')->where(array('id'=>$v['typevalue']))->find();
				$data[$k]['pname']=$cloud['cloudname'];
			}
		}		
		$this->assign("page",$pageinfo);
		$this->assign('data',$data);
		$this->assign('username',$ary_get['username']);
		$this->assign('domain',$ary_get['domain']);
		$this->display();
	}
	
	public function edit(){
		if (IS_POST){
			$arr_post = $this->_post();
			$add_rs = D('domainwhite')->where('id='.$arr_post['id'])->save($arr_post);
			if(FALSE !== $add_rs){
				$this->success("编辑成功",U('Admin/Domainwhite/index'));
			}else{
				$this->error("编辑失败");
			}	
			exit();
		}
		$ids = I('id','');
		if($ids){
			$beian_code = M("domainwhite");
			$data = $beian_code->where("id =".$ids)->find();
			if ($data[typeid]==1){
				$cloud=D('Cloud')->where(array('id'=>$data['typevalue']))->find();
				$data['name']=$cloud['cloudname'];
				$ipqosinfo=json_decode($cloud['ipqosinfo']);
				if ($ipqosinfo->status=="success"){
					foreach ($ipqosinfo->value as $kk=>$vv){
						if (!$vv->shared){
							foreach ($vv->ip_infos as $kkk=>$vvv){
								$endip.=$vvv->ip."<br>";
							}
						}
					}
				}
				$data['ip']=$endip;
			}
			$this->assign("data",$data);
			$this->display();
		}
	}
	
	public function del(){
		$ids = I('id','');
		if($ids){
			$mod = M("domainwhite");
			$info = $mod->where("id in (".$ids.")")->delete();
			if(false!=$info){
				$this->success("删除成功");
			}else{
				$this->error("删除失败");
			}
		}else{
			$this->error('参数为空');
		}
	}
}
?>