<?php
class LogAction extends AdminAction{
    public function index(){
  		$ary_get=$_GET;
		$where=" 1=1 ";
		if (!empty($ary_get['username']))$where=$where." and username='".$ary_get['username']."'";
		$this->assign("username", $ary_get['username']);
		if(!empty($ary_get['starttime'])){
			$starttime=str_replace("+"," ",$ary_get['starttime']);	
			$starttime=convert_datefm($starttime);
			$where=$where." and addtime >=".$starttime;
			$this->assign("starttime",date('Y-m-d', $starttime));
		}
		if(!empty($ary_get['endtime'])){
			$endtime=str_replace("+"," ",$ary_get['endtime']);	
			$endtime=convert_datefm($endtime);
			$where=$where." and addtime <=".($endtime+86400);
			$this->assign("endtime",date('Y-m-d', $endtime));
		}
  		if(!empty($ary_get['forwhat'])){
			$where=$where." and forwhat like '%".$ary_get['forwhat']."%'";
			$this->assign("forwhat",$ary_get['forwhat']);
		}
		$mod = M("system_log");
		$ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', C('PAGE_COUNT'));
		$count = $mod->table(C("DB_PREFIX")."system_log")->where($where)->count();
        $obj_page =$this->AdminPage($count, $ary_get['pageall']);
        $page = $obj_page->show();
        //查询数据
		$data = $mod->table(C("DB_PREFIX").'system_log')
				->where($where)
        		->limit($obj_page->firstRow, $obj_page->listRows)
        		->order("s_id desc")
				->select();
        $this->assign("data", $data);
        $this->assign("page", $page);
		$this->display();
	}
}
?>