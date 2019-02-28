<?php
/**
 * 文章管理
 * Enter description here ...
 * @author Geyoulei
 */
class ArticleAction extends AdminAction{
	//文章列表
	public function index(){
		$Mod=D('article');
		$mod_cate=M('article_category');
		$ary_get = $this->_get();
		$cid = $ary_get['cid'];
		if (!empty($cid)){
		 	$typedata=M('article_category')->where(array('status'=>1))->select();
			$cids = GetSonIds($typedata,$cid,true);
			$where="a.cid in(".$cids.")";
        	$this->assign("prm_cid",$cid);
		}
		//分页
		$ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', C('PAGE_COUNT'));
		$count = $Mod->table(C("DB_PREFIX")."article AS a")
						 ->where($where)
						 ->count();
        $obj_page =$this->AdminPage($count, $ary_get['pageall']);
        $page = $obj_page->show();
        $this->assign("page", $page);
        //获取文章列表
		$data = $Mod->table(C("DB_PREFIX")."article a")
				->join('cloud_article_category as c on c.id = a.cid')
				->field('a.*,c.title as cate')
				->order('create_time desc')
        		->where($where)
        		->limit($obj_page->firstRow, $obj_page->listRows)
				->select();
		//获取文章分类
		$cates = $mod_cate->where('pid = 0 and status = 1')->order('sort asc')->select();
		foreach ($cates as $k=>$v){
			$cates[$k]['childs'] = $mod_cate->where('pid = '.$v["id"].' and status = 1')->order('sort asc')->select();
		}
		$this->assign('cates',$cates);
		$this->assign('data',$data);
		$this->display();
	}
	//添加文章
	public function add(){
		$Mod=M('article');
		$mod_cate=M('article_category');
		if (IS_POST){
			$arr_post = $this->_post();
			$arr_post['create_time']=time();
			$arr_post['update_time']=time();
			$add_rs = $Mod->add($arr_post);
			if(FALSE !== $add_rs){
				$this->success("添加成功",U('Admin/Article/index'));
			}else{
				$this->error("添加失败");
			}
			exit();
		}
		//获取文章分类
		$cates = $mod_cate->where('pid = 0 and status = 1')->order('sort asc')->select();
		foreach ($cates as $k=>$v){
			$cates[$k]['childs'] = $mod_cate->where('pid = '.$v["id"].' and status = 1')->order('sort asc')->select();
		}
		$this->assign('cates',$cates);
		$this->display();
	}
	//编辑文章
	public function edit(){
		$id = I('id','','htmlspecialchars');
		if ($id){
			$Mod=M('article');
			$mod_cate=M('article_category');
			if (IS_POST){
				$arr_post = $this->_post();
				$arr_post['update_time']=time();
				$add_rs = $Mod->save($arr_post);
				if(FALSE !== $add_rs){
					$this->success("编辑成功",U('Admin/Article/index'));
				}else{
					$this->error("编辑失败");
				}
				exit();
			}
			$data = $Mod->where(array('id'=>$id))->find();
			$this->assign('data',$data);
			//获取文章分类
			$cates = $mod_cate->where('pid = 0 and status = 1')->order('sort asc')->select();
			foreach ($cates as $k=>$v){
				$cates[$k]['childs'] = $mod_cate->where('pid = '.$v["id"].' and status = 1')->order('sort asc')->select();
			}
			$this->assign('cates',$cates);
			$this->display();
		}else{
			$this->error('编号不能为空!');
		}
	}
	//删除文章
	public function del(){
		$id = I('id','','htmlspecialchars');
		if ($id){
			$mod=D('article');
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
	//文章分类
	public function cate(){
		$Mod=M('article_category');
		$data = $Mod->order('sort asc')->select();
		$tree = new Tree();
		$tree->icon = array('│ ','├─ ','└─ ');
		$tree->nbsp = '&nbsp;&nbsp;&nbsp;';
		$array = array();
		if(!empty($data) && is_array($data)){
			foreach($data as $vl){
				if ($vl['status']){
					$vl['str_status'] = '<img  src="__PUBLIC__/Admin/images/icons/icon_1.png" alt="启用" title="启用">';
				}else{
					$vl['str_status'] = '<img  src="__PUBLIC__/Admin/images/icons/icon_0.png" alt="停用" title="停用">';
				}
				$vl['parentid_node'] = ($vl['pid'])? ' class="child-of-node-'.$vl['pid'].'"' : '';
				$vl['edit_url'] =	U('/Admin/Article/cateedit',array('id'=>$vl['id'])); 
				$vl['del_url']	=	U('/Admin/Article/catedel',array('id'=>$vl['id'])); 
				$array[] = $vl;
			}
			$str = "<tr id='list_\$id' \$parentid_node>
						<td>\$id</td>
                        <td>\$spacer\$title</td>
                        <td class='align-center'>\$sort</td>
                        <td class='align-center'>\$str_status</td>
                        <td class='align-center'>
                        <a href='\$edit_url' class='edit'>编辑</a>
						&nbsp;
						<a href='\$del_url' class='confirmbutton'>删除</a>
                        </td>
                    </tr>";
			
			$tree->init($array);
			$list = $tree->get_tree(0, $str);
			$this->assign('list', $list);
		}
		$this->assign('cates',$data);
		$this->display();
	}
	//添加分类
	public function cateadd(){
		$Mod=M('article_category');
		if (IS_POST){
			$arr_post = $this->_post();
			$add_rs = $Mod->add($arr_post);
			if(FALSE !== $add_rs){
				$this->success("添加成功",U('Admin/Article/cate'));
			}else{
				$this->error("添加失败");
			}
			exit();
		}
		$cates = $Mod->where('pid = 0 and status = 1')->select();
		$this->assign('parents',$cates);
		$this->display();
	}
	//编辑分类
	public function cateedit(){
		$id = I('id','','htmlspecialchars');
		if ($id){
			$Mod=M('article_category');
			if (IS_POST){
				$arr_post = $this->_post();
				if($arr_post['status']==0){
					$arr_childs = $Mod->where(array("pid"=>$id,"status"=>'1'))->select();
					if($arr_childs)$this->error('仍有可用的子分类，请先处理子分类');
				}
				$add_rs = $Mod->save($arr_post);
				if(FALSE !== $add_rs){
					$this->success("编辑成功",U('Admin/Article/cate'));
				}else{
					$this->error("编辑失败");
				}
				exit();
			}
			$data = $Mod->where(array('id'=>$id))->find();
			$this->assign('data',$data);
			$parents = $Mod->where('pid = 0 and status = 1')->select();
			$this->assign('parents',$parents);
			$this->display();
		}else{
			$this->error('编号不能为空!');
		}
	}
	//删除分类
	public function catedel(){
		$id = I('id','','htmlspecialchars');
		if ($id){
			$mod=D('article_category');
			$arr_childs = $mod->where(array("pid"=>$id))->select();
			if($arr_childs)$this->error('请先删除子分类');
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