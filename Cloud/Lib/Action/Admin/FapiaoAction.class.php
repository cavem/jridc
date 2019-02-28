<?php
/**
 * 发票管理
 * @author minran
 *
 */
class FapiaoAction extends AdminAction{
	//发票列表
	public function index(){
		$mod = M("fapiao");
		$ary_get = $this->_get();
		//查询条件
		$username = $ary_get['username'];
		if (!empty($username)){
		 	$typedata=M('user')->where(array('status'=>1,'username'=>$username))->find();
			$id = $typedata['user_id'];
			$where="f.user_id = ".$id;
			$this->assign("prm_uname",$username);
		}
		//分页
		$ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', C('PAGE_COUNT'));
		$count = $mod->table(C("DB_PREFIX")."fapiao as f")->where($where)->count();
		$obj_page =$this->AdminPage($count, $ary_get['pageall']);
		$page = $obj_page->show();
		$this->assign("page", $page);
		//查询数据
		$data = $mod->table(C("DB_PREFIX").'fapiao as f')
				->join(C("DB_PREFIX").'user as u on u.user_id = f.user_id')
				->field("f.*,u.username")
				->where($where)
				->limit($obj_page->firstRow, $obj_page->listRows)
				->select();
		$this->assign("data", $data);
		$this->display();
	}
	//编辑发票申请
	public function edit(){
		$Mod=M('fapiao');
		if (IS_POST){
			$arr_post = $this->_post();
			$id = $arr_post['id'];
			$add_rs = $Mod->where('fid ='.$id)->save($arr_post);
			if(FALSE !== $add_rs){
				$this->success("编辑成功",U('Admin/Fapiao/index'));
			}else{
				$this->error("编辑失败");
			}
			exit();
		}
		
		$id = I('id','','htmlspecialchars');
		if ($id){
			$data = $Mod->table(C("DB_PREFIX")."fapiao as f")
				->join(C("DB_PREFIX").'user as u on u.user_id = f.user_id')
				->field('f.*,u.username')
				->where(array('fid'=>$id))
				->find();
			//开票信息
			$data['info'] = unserialize($data['infoarr']);
			//所属财务
			$data['trans'] = M('money_log')->where('id in ('.$data['tranarrid'].")")->select();
			$this->assign('data',$data);
			$this->display();
		}else{
			$this->error('编号不能为空!');
		}
	}

	public function del(){
		$id = I('id','');
		if($id){
			$mod = M("fapiao");
			$info = $mod->where("fid = ".$id)->delete();
			if(false!=$info){
				$this->success("删除成功");
			}else{
				$this->error("删除失败");
			}
		}else{
			$this->error('参数为空');
		}
	}
	
	//纳税人列表
	public function tax(){
		$mod = M("fapiao_info");
		$ary_get = $this->_get();
		//查询条件
		$username = $ary_get['username'];
		if (!empty($username)){
		 	$typedata=M('user')->where(array('status'=>1,'username'=>$username))->find();
			$id = $typedata['user_id'];
			$where="f.user_id = ".$id;
			$this->assign("prm_uname",$username);
		}
		//分页
		$ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', C('PAGE_COUNT'));
		$count = $mod->table(C("DB_PREFIX")."fapiao_info as f")->where($where)->count();
		$obj_page =$this->AdminPage($count, $ary_get['pageall']);
		$page = $obj_page->show();
		$this->assign("page", $page);
		//查询数据
		$data = $mod->table(C("DB_PREFIX").'fapiao_info as f')
				->join(C("DB_PREFIX").'user as u on u.user_id = f.user_id')
				->field("f.*,u.username")
				->where($where)
				->limit($obj_page->firstRow, $obj_page->listRows)
				->select();
		$this->assign("data", $data);
		$this->display();
	}

	//纳税人编辑
	public function taxedit(){
		$Mod=M('fapiao_info');
		if (IS_POST){
			$arr_post = $this->_post();
			$id = $arr_post['id'];
			$add_rs = $Mod->where('iid ='.$id)->save($arr_post);
			if(FALSE !== $add_rs){
				$this->success("编辑成功",U('Admin/Fapiao/tax'));
			}else{
				$this->error("编辑失败");
			}
			exit();
		}
		
		$id = I('id','','htmlspecialchars');
		if ($id){
			$data = $Mod->table(C("DB_PREFIX")."fapiao_info as i")
				->join(C("DB_PREFIX").'user as u on u.user_id = i.user_id')
				->field('i.*,u.username')
				->where(array('iid'=>$id))
				->find();
			$this->assign('data',$data);
			$this->display();
		}else{
			$this->error('编号不能为空!');
		}
	}
	//删除纳税人
	public function taxdel(){
		$id = I('id','');
		if($id){
			$mod = M("fapiao_info");
			$info = $mod->where("iid = ".$id)->delete();
			if(false!=$info){
				$this->success("删除成功");
			}else{
				$this->error("删除失败");
			}
		}else{
			$this->error('参数为空');
		}
	}
	//寄送地址列表
	public function address(){
		$mod = M("fapiao_dizhi");
		$ary_get = $this->_get();;
		$username = $ary_get['username'];
		if (!empty($username)){
		 	$typedata=M('user')->where(array('status'=>1,'username'=>$username))->find();
			$id = $typedata['user_id'];
			$where="f.user_id = ".$id;
			$this->assign("prm_uname",$username);
		}
		//分页
		$ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', C('PAGE_COUNT'));
		$count = $mod->table(C("DB_PREFIX")."fapiao_dizhi as f")->where($where)->count();
		$obj_page =$this->AdminPage($count, $ary_get['pageall']);
		$page = $obj_page->show();
		$this->assign("page", $page);
		//查询数据
		$data = $mod->table(C("DB_PREFIX").'fapiao_dizhi as f')
				->join(C("DB_PREFIX").'user as u on u.user_id = f.user_id')
				->join(C("DB_PREFIX").'region as r1 on r1.id = f.province')
				->join(C("DB_PREFIX").'region as r2 on r2.id = f.city')
				->join(C("DB_PREFIX").'region as r3 on r3.id = f.town')
				->field("f.*,u.username,r1.name as p,r2.name as c,r3.name as t")
				->where($where)
				->limit($obj_page->firstRow, $obj_page->listRows)
				->select();
		$this->assign("data", $data);
		$this->display();
	}
	
	public function addressdel(){
		$id = I('id','');
		if($id){
			$mod = M("fapiao_dizhi");
			$info = $mod->where("did = ".$id)->delete();
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