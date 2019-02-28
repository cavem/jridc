<?php
/**
 * 云主机控制器
 * Enter description here ...
 */
class CloudAction extends HomeAction{
	public function index(){
		$this->redirect("/home/Cloud/buy");
	}
	public function buy(){
		$iptype=D('Cloud')->productiptype();//取出当前所有的在销售的IP线路
		$defaulttype=C('defaultiptype');//默认线路
		if ($_SESSION['uid']){
			$this->assign('isuser',1);
		}else{
			$this->assign('isuser',0);
		}
		$this->assign('defaulttype',$defaulttype);
		$this->assign('iptype',$iptype);
		$this->display('cloud');
	}
	public function ajaxcloudprice(){
		$year=I('get.year','0','htmlspecialchars');
		if ($year==999){
			$data=array(
			'Priceold'=>0,
			'Price'=>0,
			'YName'=>'',
			'YZheKou'=>''
			);
			echo json_encode($data);
			exit();
		}
		$id=I('get.id','0','intval');
		$cpu=I('get.cpu','0','htmlspecialchars');
		$mem=I('get.mem','0','htmlspecialchars');
		$disk=I('get.disk','0','htmlspecialchars');
		$ip=I('get.ip','0','htmlspecialchars');
		$qos=I('get.qos','0','htmlspecialchars');
		if (empty($id)) return false;
		$data=D('Cloud')->cloudprice($id,$cpu,$mem,$disk,$ip,$qos,$year,$_SESSION['uid']);
		echo json_encode($data);
		exit();
				
	}
	public function ajaxcloudname(){
		$cloudname=I('cloudname','','htmlspecialchars');
		if(empty($cloudname)) $this->error("云主机名不可以为空");
		Load('extend');
		$cloudname=remove_xss($cloudname);
		$cloud=D('cloud')->where(" cloudname = '%s'",$cloudname)->find();
		if ($cloud){
			$this->error("云主机名不可用");
		}else{
			$this->success("云主机名可用");
		}
		exit();
	}
	//查询出当前线路类型下 有哪些产品配置信息
	public function ajaxproduct(){
		$iptype=I('get.iptype','','intval');
		$product=D('cloud_product')->where("iptype = %d and status = %d ",$iptype,1)->order("sort desc")->select();
		echo json_encode($product);
		exit();
	}
	public function ajaxcloudinfo(){
		$id=I('get.id','','intval');
		$product=D('cloud_product')->where("id=%d and status=%d ",$id,1)->find();
		//允许试用
		$yeararr=array();
		/*
		if ($product["cantest"]){
			$yeararr["试用"]['value']=999;
			$yeararr["试用"]['yearname']="试用".$product["contestday"]."天";
		}*/
		if ($product["canmonth"]){
			$yeararr["月付"]['value']=$product["PAY_Month"];
			$yeararr["月付"]['yearname']="月付";
		}
		if ($product["canseason"]){
			$yeararr["季度付"]['value']=$product["PAY_Season"];
			$yeararr["季度付"]['yearname']="季度付";
		}
		if ($product["canhalfyear"]){
			$yeararr["半年付"]['value']=$product["PAY_halfyear"];
			$yeararr["半年付"]['yearname']="半年付";
		}
		$yeararr["年付"]['value']=$product["PAY_Nextyear"];
		$yeararr["年付"]['yearname']="年付";
		$yeararr["两年付"]['value']=$product["PAY_2year"];
		$yeararr["两年付"]['yearname']="两年付";
		$yeararr["三年付"]['value']=$product["PAY_3year"];
		$yeararr["三年付"]['yearname']="三年付";
		$yeararr["四年付"]['value']=$product["PAY_4year"];
		$yeararr["四年付"]['yearname']="四年付";
		$yeararr["五年付"]['value']=$product["PAY_5year"];
		$yeararr["五年付"]['yearname']="五年付";
		$prodcutapi=D('Cloudapi')->getprodcut($product['pid']);
		$data=array(
			'id'=>$product['id'],
			'Cloudtype'=>$product['Cloudtype'],
			'info'=>$product['info'],
			'infomore'=>$product['infomore'],
			'jfname'=>$product['jfname'],
			'iptype'=>$product['iptype'],
			'dsystem'=>$prodcutapi['value']['dsystem'],
			'dqos'=>$prodcutapi['value']['dqos'],
			'ddisk'=>$prodcutapi['value']['ddisk'],
			'mincpu'=>$prodcutapi['value']['mincpu'],
			'minmem'=>$prodcutapi['value']['minmem'],
			'mcpu'=>$prodcutapi['value']['mcpu'],
			'mmem'=>$prodcutapi['value']['mmem'],
			'mdisk'=>$prodcutapi['value']['mdisk'],
			'mip'=>$prodcutapi['value']['mip'],
			'mqos'=>$prodcutapi['value']['mqos'],
			'year'=>$yeararr,
			'iptypeid0'=>$prodcutapi['value']['iptypeid0'],
			'iptypeid1'=>$prodcutapi['value']['iptypeid1'],
			'cantestcpu'=>$product['cantestcpu'],
			'cantestmem'=>$product['cantestmem'],
			'cantestdisk'=>$product['cantestdisk'],
			'cantestqos'=>$product['cantestqos'],
		);
		echo json_encode($data);
		exit();
	}
	public function ajaxos(){
		$id=I('get.id','','intval');
		$ostypeid=I('get.ostypeid','2','intval');
		$product=D('cloud_product')->where("id=%d and status=%d",$id,1)->field('pid')->find();
		$prodcutapi=D('Cloudapi')->getprodcut($product['pid']);
		$cloudos=D('Cloudapi')->systemtemplate($prodcutapi['value']['masterid'],$prodcutapi['value']['cid'],$ostypeid);
		echo json_encode($cloudos['value']);
		exit();
	}
}
?>