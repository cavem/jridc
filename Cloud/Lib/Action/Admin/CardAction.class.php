<?php
/**
 * 用户充值卡管理
 * Enter description here ...
 * @author Geyoulei
 */
class CardAction extends AdminAction{
	public function index(){
		$mod_card = M("user_card");
		//分页
		$ary_get = $this->_get();
		if (!empty($ary_get['username']))$where="u.username='".$ary_get['username']."'";
		$ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', C('PAGE_COUNT'));
		
		$count = $mod_card->table(C("DB_PREFIX")."user_card as c")->where($where)->count();
		
        $obj_page =$this->AdminPage($count, $ary_get['pageall']);
        $page = $obj_page->show();
        //查询数据
		$data = $mod_card->table(C("DB_PREFIX").'user_card as c')
				->join(C("DB_PREFIX")."user as u on u.user_id = c.user_id")
				->field("c.*,u.username")
				->where($where)
				->order("c.id desc")
        		->limit($obj_page->firstRow, $obj_page->listRows)
				->select();
        $this->assign("data", $data);
        $this->assign("page", $page);
		$this->display();
	}
	public function add(){
		if (IS_POST){
			$ary_post=$_POST;
			if(empty($ary_post['cmoney']) || !is_numeric($ary_post['cmoney']))$this->error("面值错误");
			if(empty($ary_post['cnumber']) || !is_numeric($ary_post['cnumber']))$this->error("数量错误");
			$data=array();
			for($i=0;$i<$ary_post['cnumber'];$i++){
				$data[$i]['cid']=$this->cardcode();
				$data[$i]['cpass']=md5(time().$i);//提取密码
				$data[$i]['cmoney']=$ary_post['cmoney'];
				$data[$i]['type']=$ary_post['type'];
				$data[$i]['addtime']=time();
				$data[$i]['status']=0;
			}
			$add_rs=D('user_card')->addAll($data);
			if(FALSE !== $add_rs){
				$this->success("添加成功",U('Admin/Card/index'));
			}else{
				$this->error("添加失败");
			}
			exit();
		}
		$this->display();
	}

	public function excel(){
		
		
		
	}
	//生成注册卡
	protected function cardcode(){
		$cardinfo=randstr(4,2)."-".randstr(4,2)."-".randstr(4,2)."-".randstr(4,2);
		$data=M('user_card')->where(array('cid'=>$cardinfo))->select();
		if ($data){
			$this->cardcode();
		}else{
			return $cardinfo;
		}
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
			$mod = M("user_card");
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