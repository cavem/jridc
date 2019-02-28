<?php
/**
 * 单页管理
 * Enter description here ...
 * @author Geyoulei
 */
class PageAction extends AdminAction{
	//单页列表
	public function index(){
		$Mod=D('page');
		$ary_get = $this->_get();
		//查询条件
		if (!empty($ary_get['prm_title'])){
			$where="p.title like '%".$ary_get['prm_title']."%'";
        	$this->assign("prm_title",$ary_get['prm_title']);
		}
        //获取单页列表
		$data = $Mod->table(C("DB_PREFIX")."page p")
				->field('p.*')
				->order('id asc')
        		->where($where)
				->select();
		$this->assign('data',$data);
		$this->display();
	}
	//添加单页
	public function add(){
		$Mod=M('page');
		if (IS_POST){
			$arr_post = $this->_post();
			if(!$arr_post['temp']){
				unset($arr_post['temp']);
			}
			$add_rs = $Mod->add($arr_post);
			if(FALSE !== $add_rs){
				$this->success("添加成功",U('Admin/Page/index'));
			}else{
				$this->error("添加失败");
			}
			exit();
		}
		$this->display();
	}
	//编辑单页
	public function edit(){
		$id = I('id','','htmlspecialchars');
		if ($id){
			$Mod=M('page');
			if (IS_POST){
				$arr_post = $this->_post();
				$add_rs = $Mod->save($arr_post);
				if(FALSE !== $add_rs){
					$this->success("编辑成功",U('Admin/Page/index'));
				}else{
					$this->error("编辑失败");
				}
				exit();
			}
			$data = $Mod->where(array('id'=>$id))->find();
			$this->assign('data',$data);
			$this->display();
		}else{
			$this->error('编号不能为空!');
		}
	}
	//删除单页
	public function del(){
		$id = I('id','','htmlspecialchars');
		if ($id){
			$mod=D('page');
			$ary_result = $mod->where(array('id'=>$id))->delete();
			if ($ary_result){
				$this->success('操作成功');
			}else{
				$this->error('操作失败');
			}	
		}else{
			$this->error('删除项不能为空!');
		}
	}
}
?>