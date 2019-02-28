<?php
/**
 * 优惠券管理
 * @author Geyoulei
 * 
 */
class CouponAction extends AdminAction{
    public function index(){
    	$mod = M('coupon');
		$ary_get = $this->_get();
		//查询条件
		$username = $ary_get['username'];
		if (!empty($username)){
		 	$typedata=M('user')->where(array('status'=>1,'username'=>$username))->find();
			$id = $typedata['user_id'];
			$where="c.user_id = ".$id;
			$this->assign("prm_uname",$username);
		}
		//分页
		if (!empty($ary_get['username']))$where="c.username='".$ary_get['username']."'";
		$ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', C('PAGE_COUNT'));
		$count = $mod->table(C("DB_PREFIX")."coupon as c")->where($where)->count();
        $obj_page =$this->AdminPage($count, $ary_get['pageall']);
        $page = $obj_page->show();
    	$this->assign('page',$page);
        //查询数据
    	$data = $mod->table(C("DB_PREFIX").'coupon as c')
				->join(C("DB_PREFIX")."user as u on u.user_id = c.user_id")
				->field("c.*,u.username as user_name")
				->where($where)
				->order("c.id desc")
        		->limit($obj_page->firstRow, $obj_page->listRows)
				->select();
    	$this->assign('data',$data);
		$this->display();
	}
	public function add(){
		if(IS_POST){
			$ary_post=$_POST;
			if(empty($ary_post['cmoney']) || !is_numeric($ary_post['cmoney']))$this->error("面值错误");
			if(empty($ary_post['cnumber']) || !is_numeric($ary_post['cnumber']))$this->error("数量错误");
			$data=array();
			for($i=0;$i<$ary_post['cnumber'];$i++){
				$data[$i]['type']=$ary_post['type'];
				$data[$i]['couponnum']=$this->cardcode();
				$data[$i]['secret']=md5(time().$i);//提取密码
				$data[$i]['condition']=$ary_post['condition'];
				$data[$i]['couponmoney']=$ary_post['cmoney'];
				$data[$i]['kefuid']=$ary_post['kid'];
				$data[$i]['expire_time']=convert_datefm($ary_post['expire_time']);
				$data[$i]['addtime']=time();
				$data[$i]['status']=0;
			}
			$add_rs=D('coupon')->addAll($data);
			if(FALSE !== $add_rs){
				$this->success("添加成功",U('Admin/Coupon/index'));
			}else{
				$this->error("添加失败");
			}
			exit();
		}
		//获取客服
		$mod_kefu=M('kefu');
		$mod_kr=M('kefu_rank');
		$krs = $mod_kr->select();
		foreach ($krs as $k=>$v){
			$kfs = $mod_kefu->where('rank_id = '.$v['rank_id'])->select();
			$krs[$k]['kefus'] = $kfs;
		}
		$this->assign('kefus',$krs);
		$this->display();
	}
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
			$mod = M("coupon");
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
	public function excel(){
		$this->display();
	
	}
	//生成注册卡
	protected function cardcode(){
		$cardinfo=randstr(5,1)."-".randstr(5,1)."-".randstr(5,1)."-".randstr(5,1)."-".randstr(5,1);
		$data=M('coupon')->where(array('couponnum'=>$cardinfo))->select();
		if ($data){
			$this->cardcode();
		}else{
			return $cardinfo;
		}
	}
}
?>