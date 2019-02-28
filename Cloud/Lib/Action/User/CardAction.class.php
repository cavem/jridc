<?php
//充值卡管理
class CardAction extends MainuserAction{
	public function index(){
		$ary_get=$_GET;
		$where="user_id=".$this->uid." and username='".$this->username."'";
		$count = M("user_card")->where($where)->count();
		$obj_page =$this->UserPage($count,10);
        $pageinfo = $obj_page->show();
        $data = M("user_card")
			    ->where($where)
        		->limit($obj_page->firstRow, $obj_page->listRows)
				->order("usetime desc")
				->select();
		$this->assign("pageinfo",$pageinfo);
		$this->assign('data',$data);
		$this->display();
	}
	public function payin(){
	 if (!IS_POST)$this->error("数据错误");
	 $ary_post=$_POST;
	 foreach ($arr_post as $pk=>$pv){
			$arr_post[$pk]=remove_xss($pv);			
	 }
	 if (empty($ary_post['cid']))$this->error("卡号不能为空 ");
	 if (empty($ary_post['cpass']))$this->error("卡密不能为空 ");
	 $user_card=D('user_card')->where(array('cid'=>$ary_post['cid'],'cpass'=>$ary_post['cpass'],'status'=>0,'user_id'=>0))->find();
	 if (!$user_card)$this->error("卡密或密码不正确 ");
            $user_id=$this->uid;
            $money=$user_card['cmoney'];
            $forwhat="充值卡充值(卡号:".$user_card['cid'].")";
            $whichProduct="";
            $pid=0;
            $type=10;
            $pingzheng="";
            $acspace="用户区";
            $isadd=1;
            $ptype="";//产品类型
            $orderid="";//交易号或者订单号
            $paddtime=time();//产品开通时间
            $endtime=time(); //产品到期时间
            D('User')->addmoney($user_id,$money,$forwhat,$whichProduct,$pid,$type,$pingzheng,$acspace,$isadd,$ptype,$orderid,$paddtime,$endtime);
            //更改卡的状态
            $ary_result=D('user_card')
            		   ->where(array('cid'=>$user_card['cid'],'cpass'=>$user_card['cpass']))
            		   ->save(array('user_id'=>$this->uid,'username'=>$this->username,'status'=>1,'usetime'=>time()));
           $this->success("充值成功");
	}
}
?>