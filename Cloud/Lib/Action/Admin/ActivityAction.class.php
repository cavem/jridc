<?php
/**
 * 活动机型
 */
class ActivityAction extends AdminAction{
    public function index(){
    	$mod = M('cloud_activity');
		$ary_get = $this->_get();
		//查询条件
		$name = $ary_get['name'];
		if (!empty($name)){
			$where = " ca.name like '%$name%'";
			$this->assign("prm_name",$name);
		}
		//分页
		$ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', C('PAGE_COUNT'));
		$count = $mod->table(C("DB_PREFIX")."cloud_activity as ca")->where($where)->count();
        $obj_page =$this->AdminPage($count, $ary_get['pageall']);
        $page = $obj_page->show();
    	$this->assign('page',$page);
    	
        //查询数据
    	$data = $mod->table(C("DB_PREFIX")."cloud_activity as ca")
				->where($where)
				->order(" orderby asc")
        		->limit($obj_page->firstRow, $obj_page->listRows)
				->select();
    	$this->assign('data',$data);
		$zktexts = $this->getTexts();//获取
		$this->assign('zktexts',$zktexts);
		$this->display();
	}
	//添加活动机型
	public function add(){
		if(IS_POST){
			$_POST['starttime'] = convert_datefm($_POST['starttime']);
			$_POST['endtime'] = convert_datefm($_POST['endtime']);
			$_POST['addtime'] = time();
			$rs = M("cloud_activity")->add($_POST);
			if($rs!==FALSE){
				header("location:".U("admin/activity/index"));
			}
		}
		//获取产品列表
		$prods = M("cloud_product")->select();
		$this->assign("products",$prods);
		$this->display();
	}
	//编辑活动机型
	public function edit(){
		$id = I('id');
		if(empty($id))$this->error("参数错误");
		$m = M("cloud_activity");
		if(IS_POST){
			$_POST['starttime'] = convert_datefm($_POST['starttime']);
			$_POST['endtime'] = convert_datefm($_POST['endtime']);
			$rs = $m->where(array("id"=>$id))->save($_POST);
//			p(getLastSql(),1);
			if($rs!==FALSE){
				header("location:".U("admin/activity/index"));
			}
			exit();
		}
		//获取产品信息
		$data = $m->where(array("id"=>$id))->find();
		if(empty($data))$this->error("数据不存在");
		$this->assign("data",$data);
		//获取产品列表
		$prods = M("cloud_product")->select();
		$this->assign("products",$prods);
		$this->display();
	}
	
	//删除活动机型
	public function del(){
		if (IS_POST){
			$ary_post=$_POST;
			unset($ary_post['_URL_']);
			unset($ary_post['all']);
			$ids="";
			foreach ($ary_post as $k=>$v){
				if ($ids){
					$ids=$ids.",".$v;
				}else{
					$ids=$v;
				}
			}
		}else{
			$ids = I('id','');
		}
		if($ids){
			$mod = M("cloud_activity");
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
	function getTexts(){
		$zktexts = array(
				"PAY_Month"=>'月',
				"PAY_Season"=>'季度',
				"PAY_halfyear"=>'半年',
				"PAY_1year"=>'1年',
				"PAY_2year"=>'2年',
				"PAY_3year"=>'3年',
				"PAY_4year"=>'4年',
				"PAY_5year"=>'5年',
			);
		return $zktexts;
	}
}
?>