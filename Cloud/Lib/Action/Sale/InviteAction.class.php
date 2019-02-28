<?php
class InviteAction extends SaleadminAction{
    public function index(){
    	
		$mod_user = M("invite");
		$ary_get = $this->_get();
		$where=" 1=1 and i.kefuid = ".$this->kid;	
		if (!empty($ary_get['username']))$where=" u.username='".$ary_get['username']."'";
		$ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', C('PAGE_COUNT'));
		$count = $mod_user->table(C("DB_PREFIX")."invite as i")->where($where)->count();
        $obj_page =$this->AdminPage($count, $ary_get['pageall']);
        $page = $obj_page->show();
        //查询数据
		$data = $mod_user->table(C("DB_PREFIX").'invite as i')
				->join(C("DB_PREFIX")."kefu as k on k.id = i.kefuid")
				->join(C("DB_PREFIX")."user as u on u.user_id = i.userid")
				->field("i.*,k.kefuname,u.username")
				->order("i.id desc")
				->where($where)
        		->limit($obj_page->firstRow, $obj_page->listRows)
				->select();
        $this->assign("data", $data);
        $this->assign("page", $page);
		$this->display();
	}
	
	public function add(){
		if(IS_POST){
			$mod_i = D('Invite');
			$arr_post = $this->_post();
			$gs = $arr_post['gs'];//添加的个数
			$dataList = array();
			for($i=0;$i<$gs;$i++){
				$usecode=md5(time().randstr().$i);
				$usecode=substr($usecode,rand(1,20),8);//随机截取当前的字符串的8位
				if (!$mod_i->checkinvite($usecode)){
					continue;
				}
				$data = array(
					"usecode"=>$usecode,
					"useday"=>$arr_post['useday'],
					"addtime"=>time(),
					"kefuid"=>$arr_post['kefuid'],
				);
				$dataList[$i]=$data;
			}
			$add_rs = $mod_i->addAll($dataList);
			if(FALSE !== $add_rs){
				$this->success("添加成功",U('Admin/Invite/index'));
			}else{
				$this->error("添加失败");
			}
			exit();
		}
		//查询客服列表
		$mod_k = M('kefu');
		$kefus = $mod_k->select();
		$this->assign("kefus",$kefus);
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
			$mod = M("invite");
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