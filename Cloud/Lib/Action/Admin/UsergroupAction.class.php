<?php
class UsergroupAction extends AdminAction{
    public function index(){
		$mod = M("user_rank");
		$info = $mod->select();
		$this->assign('data',$info);
		$this->display();
	}
	public function add(){
		if(IS_POST){
			$ary_post = $this->_post();
			$mod = M("user_rank");
			$info = $mod->add($ary_post);
			if(false!=$info){
				$this->success("添加成功");
			}else{
				$this->error('添加失败');
			}
			exit();
		}
		$this->display();
	}
	public function edit(){
		$id = I('rank_id','','htmlspecialchars');
		if ($id){
			$Mod=M('user_rank');
			if (IS_POST){
				$arr_post = $this->_post();
				$add_rs = $Mod->save($arr_post);
				if(FALSE !== $add_rs){
					$this->success("编辑成功",U('Admin/Usergroup/index'));
				}else{
					$this->error("编辑失败");
				}
				exit();
			}
			$data = $Mod->where(array('rank_id'=>$id))->find();
			$this->assign('data',$data);
			$this->display();
		}else{
			$this->error('编号不能为空!');
		}
	}
	public function del(){
		$id = I('rank_id','');
		if($id){
			$mod_user = M("user");
			$mod_rank = M("user_rank");
			$user = $mod_user->where("user_rank = ".$id)->find();
			if(!$user){
				$info = $mod_rank->where("rank_id = ".$id)->delete();
				if(false!=$info){
					$this->success("删除成功");
				}else{
					$this->error("删除失败");
				}
			}else{
				$this->error("请先处理该组下的用户");
			}
		}else{
			$this->error('参数为空');
		}
	}
}
?>