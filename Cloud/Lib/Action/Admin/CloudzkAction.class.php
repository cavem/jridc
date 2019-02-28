<?php
/**
 * 云主机管理
 */
class CloudzkAction extends AdminAction{
	public function index(){
		$mod = M('cloud_zk');
		$data = $mod->table(C('DB_PREFIX').'cloud_zk as zk')
				->join(C('DB_PREFIX').'cloud_product as p on p.id = zk.productid')
				->field('zk.*,p.Cloudtype as p_name')
				->select();
		$this->assign('data',$data);
		$zktexts = $this->getTexts();
		$this->assign('zktexts',$zktexts);
		$this->display();
	}
	//添加
	public function add(){
		if(IS_POST){
			$arr_post = $this->_post();
			//验证该种类型的折扣是否已存在
			$zk = M("cloud_zk")->where('productid = '.$arr_post['productid']." and zktext = '".$arr_post['zktext']."'")->find();
			if($zk)$this->error('该产品已存在该类型的折扣');
			$prods = M("cloud_zk")->add($arr_post);
			if($prods!=FALSE){
				$this->success('添加成功');
			}else{
				$this->error('添加失败');
			}
			exit();
		}
		//获取产品列表
		$prods = M("cloud_product")->select();
		$this->assign("products",$prods);
		$this->display();
	}
	public function edit(){
		$id = I("id");
		if(!id)$this->error('参数错误');
		if(IS_POST){
			$arr_post = $this->_post();
			$prods = M("cloud_zk")->where('id = '.$id)->save($arr_post);
			if($prods!=FALSE){
				$this->success('编辑成功',U("Admin/Cloudzk/index"));
			}else{
				$this->error('编辑失败');
			}
			exit();
		}
		//详细信息
		$data = M("cloud_zk")->table(C('DB_PREFIX').'cloud_zk as zk')
				->join(C('DB_PREFIX')."cloud_product as p on p.id = zk.productid")
				->field('zk.*,p.Cloudtype as p_name')
				->where('zk.id = '.$id)
				->find();
		$this->assign("data",$data);
		//类型数组
		$zktexts = $this->getTexts();
		$this->assign('zktexts',$zktexts);
		$this->display();
	}
	public function del(){
		$id = I("id");
		if(!id)$this->error('参数错误');
		$data = M("cloud_zk")->where('id = '.$id)->delete();
		if($data!=FALSE){
			$this->success('删除成功',U("Admin/Cloudzk/index"));
		}else{
			$this->error('删除失败');
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