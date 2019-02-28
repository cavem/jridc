<?php
/**
 * 新闻控制器
 * @author minran
 */
class NewsAction extends HomeAction{
	public function index(){
		$mod = M("article");
		$id = I("get.id","0",'intval');//点击的分类
		if(!$id){
			$cate = M("article_category")->find();
			$id = $cate['id'];
		}
		//用于左侧菜单
		$cates = $this->getCates();
		$this->assign('cates',$cates);
		//获取需要显示的大分类编号
		$showid = $this->getShowCateId($id);
		$this->assign("showcateid",$showid);
		//查询该分类下的文章
		//获取该分类及子分类编号
	 	$typedata=M('article_category')->where(array('status'=>1))->select();
		$cids = GetSonIds($typedata,$id,true);
		//分页
		$ary_get = $this->_get();
		$ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', 10);
		$count = $mod->where("cid in (".$cids.")")->count();
		$obj_page =$this->HomePage($count, $ary_get['pageall']);
		$pageinfo = $obj_page->show();
		$this->assign("pageinfo",$pageinfo);
		//查询文章列表
		$news = M("article")
			->where("cid in (".$cids.")")
			->limit($obj_page->firstRow, $obj_page->listRows)
			->order("update_time desc")
			->select();
		
		$this->assign("newslist",$news);
		$this->display();
	}
	//显示详情
	public function detail(){
		$mod = M('article');
		$id = I('get.id','0','intval');
		if(!$id)$this->redirect("/home/index/index");//如果未获取到id
		$data = $mod->where('id =%d',$id)->find();
		if(!$data['content'])$this->redirect("/home/index/index");//如果查询内容为空
		$this->assign('data',$data);
		//用于左侧菜单
		$cates = $this->getCates();
		$this->assign('cates',$cates);
		//获取需要显示的大分类编号
		$showid = $this->getShowCateId($data['cid']);
		$this->assign("showcateid",$showid);
		$this->display();
	}
	//获取所有文章分类
	function getCates(){
		$cates = M("article_category")->where('pid = 0 and status = 1')->order('sort asc')->select();
		foreach ($cates as $k=>$v){
			$cates[$k]['childs']=M("article_category")->where('pid = '.$v['id'].' and status = 1')->select();
		}
		return $cates;
	}
	//获取需要显示的分类编号
	function getShowCateId($id){
		$showid = $id;
		$cate = M("article_category")->where("id = ".$id)->find();
		if($cate["pid"]){
			$showid = $cate["pid"];
		}
		return $showid;
	}
}
?>



