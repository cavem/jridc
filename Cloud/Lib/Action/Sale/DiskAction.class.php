<?php
/**
 * 云硬盘
 * Enter description here ...
 * @author Geyoulei
 */
class DiskAction extends SaleadminAction{
	public function index(){
		$mod = M("disk");
		$ary_get = $this->_get();
		$userid=$this->userid($this->kid);
		$where=" 1=1 and c.user_id in (".$userid.")";
		$ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', C('PAGE_COUNT'));
		if (!empty($ary_get['username'])){
			$where=$where." and c.username='".$ary_get['username']."'";
			$this->assign("username", $ary_get['username']);
		}
		if (!empty($ary_get['cloudname'])){
			$where=$where." and c.cloudname='".$ary_get['cloudname']."'";
			$this->assign("cloudname", $ary_get['cloudname']);
		}
		
		if (!empty($ary_get['status']))$where=$where." and c.status='".urldecode($ary_get['status'])."'";
		if (!empty($ary_get['istest']))$where=$where." and c.istest='".$ary_get['istest']."'";
		if (!empty($ary_get['day'])){
			$contestday=$ary_get['day'];
			$endtime=strtotime("+$contestday day", time());
			$where=$where." and c.endtime<'".$endtime."'";
		}	
		$count = $mod->table(C("DB_PREFIX")."disk as c")->where($where)->count();
        $obj_page =$this->AdminPage($count, $ary_get['pageall']);
        $page = $obj_page->show();
		$data = $mod->table(C("DB_PREFIX").'disk as c')
				->join(C("DB_PREFIX")."disk_product as cp on cp.Disktype = c.Disktype")
				->join(C("DB_PREFIX")."user as u on u.user_id = c.user_id")
				->join(C("DB_PREFIX")."user_rank as uk on uk.rank_id = u.user_rank")
				->join(C("DB_PREFIX")."kefu as k on k.id = u.kid")
				->field("c.*,u.username,k.kefuname,uk.rank_name,cp.jfname")
				->where($where)
        		->limit($obj_page->firstRow, $obj_page->listRows)
				->select();
        $this->assign("data", $data);
        $this->assign("page", $page);
		$this->display();
		
	}
	public function manage(){
		$id=I('id','0','htmlspecialchars');
		if (!empty($id)){
			$disk=D('Disk')->where(array('id'=>$id))->find();
			if (!$disk){
				$this->redirect("Admin/Disk/index");
			}
			$user=D('user')->where(array('user_id'=>$disk['user_id']))->find();
			$disklist=D('Disk')->where(array('user_id'=>$user['user_id'],'id'=>$disk['id']))->select();
			$diskproduct=D('disk_product')->where(array())->select();
			$Cloud=D('Cloud')->where(array('masterid'=>$disk['masterid'],'cid'=>$disk['cid'],'status'=>'正常'))->select();	
			$this->assign('cloud',$Cloud);
			$this->assign('diskproduct',$diskproduct);
			$this->assign('disklist',$disklist);
			$this->assign('user',$user);
			$this->assign('id',$id);
			$this->assign('disk',$disk);
			$this->display();
			exit();
		}else{
			$username=I('username','','htmlspecialchars');
			$diskname=I('diskname','','htmlspecialchars');
			if (!empty($username)){
				$user=D('user')->where(array('username'=>$username))->find();
				$disklist=D('disk')->where(array('username'=>$username))->select();
			}
			if (!empty($cloudname)){
				$disklist=D('disk')->where(array('diskname'=>$cloudname))->select();
			}
			if (!empty($cloudname) && !empty($username)){
				$disklist=D('disk')->where(array('diskname'=>$cloudname,'username'=>$username))->select();
			}
			$this->assign('user',$user);
			$this->assign('disklist',$disklist);
			$this->display();
			exit();
		}
	}
}
?>