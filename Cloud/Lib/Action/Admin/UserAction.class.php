<?php
class UserAction extends AdminAction{
    public function index(){
		$mod=D('user');
		$mod_rank=M('user_rank');
		$ary_get = $this->_get();
		$where = " 1 ";
		$rid = $ary_get['user_rank'];
		if (!empty($rid)){
			$where="u.user_rank = ".$rid."";
        	$this->assign("prm_rid",$rid);
		}
		$uname = $ary_get['username'];
		if (!empty($uname)){
			$where="u.username like '%".$uname."%'";
        	$this->assign("prm_uname",$uname);
		}
   		if(!empty($ary_get['starttime'])){
			$starttime=str_replace("+"," ",$ary_get['starttime']);	
			$starttime=convert_datefm($starttime);
			$where=$where." and u.regtime >=".$starttime;
			$this->assign("starttime",date('Y-m-d', $starttime));
		}
		if(!empty($ary_get['endtime'])){
			$endtime=str_replace("+"," ",$ary_get['endtime']);	
			$endtime=convert_datefm($endtime);
			$where=$where." and u.regtime <=".($endtime+86400);
			$this->assign("endtime",date('Y-m-d', $endtime));
		}
		//分页
		$ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', C('PAGE_COUNT'));
		$count = $mod->table(C("DB_PREFIX")."user AS u")
						 ->where($where)
						 ->count();
        $obj_page =$this->AdminPage($count, $ary_get['pageall']);
        $page = $obj_page->show();
        $this->assign("page", $page);
        //查询数据
		$data = $mod->table(C("DB_PREFIX")."user as u")
			->join(C("DB_PREFIX")."user_rank as r on r.rank_id = u.user_rank")
			->join(C("DB_PREFIX")."kefu as k on k.id = u.kid")
			->field('u.*,r.rank_name,k.kefuname')
			->where($where)
			->order("u.user_id desc")
        	->limit($obj_page->firstRow, $obj_page->listRows)
			->select();
//		p($mod->getLastSql());p($data);//die();
		$this->assign('data',$data);
		//获取分类
		$ranks = $mod_rank->select();
		$this->assign('ranks',$ranks);
		$this->display();
	}
	public function add(){
		$this->display();
	}
	public function edit(){
		$id = I('id','','htmlspecialchars');
		$mod=M('user');
		//除用户名 id  不进行编辑外 其他均可编辑
		if (IS_POST){//执行修改
			$arr_post = $this->_post();
			if($arr_post['formtype']=='userinfo'||$arr_post['formtype']=='userrank'){
				$add_rs = $mod->where('user_id ='.$id)->save($arr_post);
			}
			if($arr_post['formtype']=='userpass'){
				$user_info = M("user")->where('user_id = '.$id)->find();
				$password = $arr_post['password'];
				$add_rs = D('User')->updatapassword($id,$user_info['username'],$password);
			}
			if($arr_post['formtype']=='usercontact'){
				$add_rs = D('user_contact')->where(array('id'=>$arr_post['id']))->save($arr_post);
			}
			if(FALSE !== $add_rs){
				$this->success("编辑成功",U('Admin/User/index'));
			}else{
				$this->error("编辑失败");
			}
			exit();
		}
		
		//编辑前查询数据
		if ($id){
			$data = $mod->where(array('user_id'=>$id))->find();
			$this->assign('data',$data);
			//获取客服
			$mod_kefu=M('kefu');
			$mod_kr=M('kefu_rank');
			$krs = $mod_kr->select();
			foreach ($krs as $k=>$v){
				$kfs = $mod_kefu->where('rank_id = '.$v['rank_id'])->select();
				$krs[$k]['kefus'] = $kfs;
			}
			$this->assign('kefus',$krs);
			//获取省市区
			$mod_rgn = M('region');
			//省
			$province = $mod_rgn->where('pid=1')->select();
			$this->assign('provinces',$province);
			//市
			if(!$data['province']){//默认设置为北京
				$data['province'] = 2;
			}
			$city = $mod_rgn->where('pid='.$data['province'])->select();
			$this->assign('citys',$city);
			//区
			if(!$data['city']){//默认设置为北京-
				$data['city'] = 52;
			}
			$town = $mod_rgn->where('pid='.$data['city'])->select();
			$this->assign('towns',$town);
			//获取分类
			$mod_rank=M('user_rank');
			$ranks = $mod_rank->select();
			$this->assign('ranks',$ranks);
			//获取联系人
			$mod_contact=M('user_contact');
			$contacts = $mod_contact->where('user_id = '.$id)->select();
			$this->assign('contacts',$contacts);
			$this->display();
		}else{
			$this->error('参数不能为空!');
		}
	}
	public function del(){
		$id = I('id','','htmlspecialchars');
		$actiondel= I('actiondel','userdel','htmlspecialchars');
		if ($actiondel=="userdel"){
			if ($id){
				$mod=D('user');
				//验证
				$ary_result = $mod->where(array('user_id'=>$id))->delete();
				if ($ary_result){
					$this->success('操作成功');
				}else{
					$this->error('操作失败');
				}	
			}else{
				$this->error('删除项不能为空!');
			}
			exit();
		}
		if ($actiondel=="usercontact"){
			if ($id){
				$mod=D('user_contact');
				//验证
				$ary_result = $mod->where(array('id'=>$id))->delete();
				if ($ary_result){
					$this->success('操作成功');
				}else{
					$this->error('操作失败');
				}	
			}else{
				$this->error('删除项不能为空!');
			}
			exit();
		}
	}
}
?>