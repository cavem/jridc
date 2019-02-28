<?php
/**
 * 云主机主控管理
 * Enter description here ...
 * @author Geyoulei
 */
class CloudconfigAction extends AdminAction{
	public function product(){
		$ary_get = $this->_get();
		$ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', C('PAGE_COUNT'));
		$cloudcdos=D('cloud_product');
		$count = $cloudcdos->table(C("DB_PREFIX")."cloud_product AS O")
						 ->where($where)
						 ->count();
        $obj_page =$this->AdminPage($count, $ary_get['pageall']);
        $page = $obj_page->show();
        $ary_data=$cloudcdos->table(C("DB_PREFIX")."cloud_product AS O")
        				  ->field("O.*")
        				  ->where($where)
        				  ->limit($obj_page->firstRow, $obj_page->listRows)
        				  ->order('O.sort desc')
        				  ->select();
		$this->assign('ostype',$ary_get['ostype']);
        $this->assign("data", $ary_data);
        $this->assign("page", $page);
		$this->display();
	}
	public function productadd(){
		if (IS_POST){
			$ary_post = $this->_post();
			$Cloudtype=$ary_post['Cloudtype'];
			if (empty($Cloudtype))$this->error("产品名不能为空");
			$Cinfo=D('cloud_product')->where(array('Cloudtype'=>$Cloudtype))->find();
			if ($Cinfo)$this->error("产品已存在");
			$ary_result=D('cloud_product')->add($ary_post);
			if(FALSE !== $ary_result){
				$this->success("添加成功");
			}else{
				$this->error("添加失败");
			}
			exit();
		}
		//获取产品类型
		$prodcut=D('Cloudapi')->getprodcut();
		$this->assign('prodcut',$prodcut);
		$this->display();
	}
	public function productedit(){
		if (IS_POST){
			$data=$_POST;
			$id=$data['id'];
			unset($data['id']);
			if(empty($data['canmonth']))$data['canmonth']=0;
			if(empty($data['canseason']))$data['canseason']=0;
			if(empty($data['canhalfyear']))$data['canhalfyear']=0;
			if(empty($data['cantest']))$data['cantest']=0;
			$info=D('cloud_product')->where(array('id'=>$id))->save($data);
			if (FALSE !==$info){
				 $this->success('操作成功');
			}else{
				 $this->error('操作失败');
			}
			exit();	
		}
		$id=I('id','');
		$ary_get = $this->_get();
		$data=D('cloud_product')->where(array('id'=>$id))->find();
		$prodcut=D('Cloudapi')->getprodcut();
		$this->assign('prodcut',$prodcut);
		$this->assign("data",$data);
		$this->display();
	}
	public function productdel(){
		$id=I('id','');
		if ($id){
			$cloudproduct=D('cloud_product');
			$info=$cloudproduct->where(array('id'=>$id))->find();
			if(!$info)$this->error("产品不存在");
			$map['Cloudtype']=array('eq',$info['Cloudtype']);
			$map['status']=array('neq','已删除');
			$cloud=D('Cloud')->where($map)->select();
			if ($cloud)$this->error("当前产品配置有正在使用的云产品");
			$ary_result=$cloudproduct->where(array('id'=>$id))->delete();
			if ($ary_result){
				$this->success('操作成功');
			}else{
				$this->error('操作失败');
			}	
		}else{
			$this->error('删除项不能为空!');
		}
	}
	public function productname(){
		if (IS_POST){
			$ary_post = $this->_post();
			$id = $ary_post['id'];
			$name_o = $ary_post['name_o'];
			$name_n = $ary_post['name_n'];
			if($name_o == $name_n)$this->error('新旧名称相同');
			$data['Cloudtype'] = $name_n;
			$info=D('cloud_product')->where(array('id'=>$id))->save($data);
			if(FALSE !==$info){
				$info2=D('cloud')->where(array('Cloudtype'=>$name_o))->save($data);
				$info3=D('order')->where(array('producttype'=>$name_o,'type'=>1))->save(array('producttype'=>$name_n));
				$info4=D('money_log')->where(array('ptype'=>$name_o,'whichProduct'=>'Cloud产品'))->save(array('ptype'=>$name_n));
				if(FALSE !==$info2){
					$this->success('修改成功');
				}else{
					$this->error('修改失败');
				}
				$this->success('名称修改成功');
			}else{
				$this->error('操作失败');
			}
			exit();
		}
		$id=I('id','');
		if ($id){
			$cloudproduct=D('cloud_product');
			$info=$cloudproduct->where(array('id'=>$id))->find();
			if(!$info)$this->error("产品不存在");
			$this->assign('data',$info);
			$this->display('');
		}else{
			$this->error('参数为空');
		}
	}

	public function productinfo(){
		header("Content-type: text/html; charset=utf-8"); 
		$pid=I('pid','');
		$prodcut=D('Cloudapi')->getprodcut($pid);
		if(!empty($pid)){
				echo "机房名称:".$prodcut['value']['Cloudtype'];
				echo "(产品类型:".$prodcut['value']['Cloudtype'].')';
				echo "(基础价格:".$prodcut['value']['usermoney'].')';
				echo "(CPU单价:".$prodcut['value']['moneycpu'].')';
				echo "(内存单价:".$prodcut['value']['moneymemory'].')';
				echo "(带宽单价:".$prodcut['value']['moneyqos'].')';
				echo "(硬盘单价:".$prodcut['value']['moneydisk'].')';
				echo "(IP单价:".$prodcut['value']['moneyip'].')';
				echo "(默认带宽:".$prodcut['value']['dqos'].')';
				echo "(默认硬盘:".$prodcut['value']['ddisk'].')';
				echo "(默认系统盘:".$prodcut['value']['dsystem'].')';
				echo "<br>";
			
		}else{
			foreach ($prodcut['value'] as $k=>$v){
				echo "机房名称:".$v['Cloudtype'];
				echo "(产品类型:".$v['Cloudtype'].')';
				echo "(基础价格:".$v['usermoney'].')';
				echo "(CPU单价:".$v['moneycpu'].')';
				echo "(内存单价:".$v['moneymemory'].')';
				echo "(带宽单价:".$v['moneyqos'].')';
				echo "(硬盘单价:".$v['moneydisk'].')';
				echo "(IP单价:".$v['moneyip'].')';
				echo "(默认带宽:".$v['dqos'].')';
				echo "(默认硬盘:".$v['ddisk'].')';
				echo "(默认系统盘:".$v['dsystem'].')';
				echo "<br>";
			}
		}
		
	}
}
?>