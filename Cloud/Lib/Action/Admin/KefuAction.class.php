<?php
class KefuAction extends AdminAction{
	
    public function index(){
		$mod_user = M("kefu");
		//分页
		$ary_get = $this->_get();
		$ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', C('PAGE_COUNT'));
		$count = $mod_user->table(C("DB_PREFIX")."kefu as k")->count();
        $obj_page =$this->AdminPage($count, $ary_get['pageall']);
        $page = $obj_page->show();
        //查询数据
		$data = $mod_user->table(C("DB_PREFIX").'kefu as k')
				->join(C("DB_PREFIX").'kefu_rank as r on r.rank_id = k.rank_id')
				->field("k.*,r.rank_name")
        		->limit($obj_page->firstRow, $obj_page->listRows)
				->select();
        $this->assign("data", $data);
        $this->assign("page", $page);
		$this->display();
	}
	
	public function add(){
		$mod_user = M("kefu");
		if (IS_POST){
			$arr_post = $this->_post();
			$add_rs = $mod_user->add($arr_post);
			if(FALSE !== $add_rs){
				$this->success("添加成功",U('Admin/Kefu/index'));
			}else{
				$this->error("添加失败");
			}
			exit();
		}
		$mod_rank = M("kefu_rank");
		$ranks = $mod_rank->select();
		$this->assign("ranks",$ranks);
		$this->display();
	}
	
	public function edit(){
		$id = I('id','');
		$Mod=M('kefu');
		if (IS_POST){
			$arr_post = $this->_post();
			$add_rs = $Mod->where('id='.$id)->save($arr_post);
			if(FALSE !== $add_rs){
				$this->success("编辑成功",U('Admin/Kefu/index'));
			}else{
				$this->error("编辑失败");
			}
			exit();
		}
		$mod_rank = M("kefu_rank");
		$ranks = $mod_rank->select();
		$this->assign("ranks",$ranks);
		$data = $Mod->where("id = $id")->find();
		$this->assign("data",$data);
		$this->display();
	}
	
	public function del(){
		$id = I('id','');
		if($id){
			$mod_user = M("kefu");
			$info = $mod_user->where("id = ".$id)->delete();
			if(false!=$info){
				$this->success("删除成功");
			}else{
				$this->error("删除失败");
			}
		}else{
			$this->error('参数为空');
		}
	}
	//客服组
    public function rank(){
		$Mod=M('kefu_rank');
		$data = $Mod->select();
		$this->assign("data",$data);
		$this->display();
	}
	
	public function rankadd(){
		$Mod=M('kefu_rank');
		if (IS_POST){
			$arr_post = $this->_post();
			$rank_name = $arr_post['rank_name'];
			$rs = $Mod->where("rank_name = '$rank_name'")->find();
			if($rs)$this->error("该名已存在");
			$add_rs = $Mod->add($arr_post);
			if(FALSE !== $add_rs){
				$this->success("添加成功",U('Admin/Kefu/rank'));
			}else{
				$this->error("添加失败");
			}
			exit();
		}
		$this->display();
	}
	
	public function rankedit(){
		$id = I('rank_id','');
		$Mod=M('kefu_rank');
		if (IS_POST){
			$arr_post = $this->_post();
			$rank_name = $arr_post['rank_name'];
			$rs = $Mod->where("rank_name = '$rank_name'")->find();
			if($rs)$this->error("该名已存在");
			$add_rs = $Mod->save($arr_post);
			if(FALSE !== $add_rs){
				$this->success("编辑成功",U('Admin/Kefu/rank'));
			}else{
				$this->error("编辑失败");
			}
			exit();
		}
		$data = $Mod->where("rank_id = $id")->find();
		$this->assign("data",$data);
		$this->display();
	}
	
	public function rankdel(){
		$id = I('rank_id','');
		if($id){
			$mod_kefu = M("kefu");
			$mod_rank = M("kefu_rank");
			$user = $mod_kefu->where("rank_id = ".$id)->find();
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