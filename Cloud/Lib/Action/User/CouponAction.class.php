<?php
//优惠券管理
class CouponAction extends MainuserAction{
	public function index(){
		$ary_get=$_GET;
		$where="user_id=".$this->uid;
		if(!empty($ary_get['starttime'])){
			$starttime=str_replace("+"," ",$ary_get['starttime']);	
			$starttime=convert_datefm($starttime);
			$where=$where." and usetime >=".$starttime;
			$this->assign("starttime",date('Y-m-d', $starttime));
		}
		if(!empty($ary_get['endtime'])){
			$endtime=str_replace("+"," ",$ary_get['endtime']);	
			$endtime=convert_datefm($endtime);
			$where=$where." and usetime <=".($endtime+86400);
			$this->assign("endtime",date('Y-m-d', $endtime));
		}
		$count = M("coupon")->where($where)->count();
		$obj_page =$this->UserPage($count,10);
        $pageinfo = $obj_page->show();
        $data = M("coupon")
			    ->where($where)
        		->limit($obj_page->firstRow, $obj_page->listRows)
				->order("expire_time desc")
				->select();
		$this->assign("pageinfo",$pageinfo);
		$this->assign('data',$data);
		$this->display();
	}
	public function docoupon(){
		 if (!IS_POST)$this->error("数据错误");
		 $ary_post=$_POST;
		 if (empty($ary_post['couponnum']))$this->error("优惠码不能为空 ");
		 $couponnum=$ary_post['couponnum'];
		 $coupon=D('coupon')->where(array('couponnum'=>$couponnum,'status'=>0))->find();
		 if(!$coupon)$this->error("优惠码不存在");
		 if ($coupon['user_id']==$this->uid)$this->error("优惠券已领取");
		 if ($coupon['status']==1)$this->error("优惠券已使用");
		 if ($coupon['expire_time']<time())$this->error("优惠券已失效");
		 $couponinfo=D('coupon')->where(array('couponnum'=>$couponnum))->save(array('user_id'=>$this->uid,'username'=>$this->username));
		 $this->success("领取成功",U('User/Coupon/index'));
	}
				
}
?>