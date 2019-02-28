<?php
class BeianAction extends AdminAction{
	
    public function index(){
		$ary_get=$_GET;
		$ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', C('PAGE_COUNT'));
		$where=" 1=1 ";
		if(!empty($ary_get['id']))$where=$where." and pid =".$ary_get['id'];
		if(!empty($ary_get['type']))$where=$where." and type =".$ary_get['type'];
		if(!empty($ary_get['status']))$where=$where." and status =".$ary_get['status'];
		if(!empty($ary_get['code']))$where=$where." and code ='".$ary_get['code']."'";
		if(!empty($ary_get['username']))$where=$where." and username ='".$ary_get['username']."'";
		if(!empty($ary_get['utypeid']))$where=$where." and utypeid =".$ary_get['utypeid'];
		if(!empty($ary_get['utype']))$where=$where." and utype ='".$ary_get['utype']."'";
		$count = M("beian_code")->where($where)->count();
		$obj_page =$this->AdminPage($count,$ary_get['pageall']);
        $pageinfo = $obj_page->show();
        $data = M("beian_code")
			    ->where($where)
        		->limit($obj_page->firstRow, $obj_page->listRows)
				->order("id desc")
				->select();
		foreach ($data as $k=>$v){
			if ($v[type]==1){
				$cloud=D('Cloud')->where(array('id'=>$v['pid']))->find();
				$data[$k]['name']=$cloud['cloudname'];
				$endip="";
				$ipqosinfo=json_decode($cloud['ipqosinfo']);
				if ($ipqosinfo->status=="success"){
					foreach ($ipqosinfo->value as $kk=>$vv){
						if (!$vv->shared){
							foreach ($vv->ip_infos as $kkk=>$vvv){
								$endip.=$vvv->ip."<br>";
							}
						}
					}
				}
				$data[$k]['ip']=$endip;
				
			}
		}		
		$this->assign("page",$pageinfo);
		$this->assign('data',$data);
		$this->assign('type',$ary_get['type']);
		$this->assign('status',$ary_get['status']);
		$this->assign('code',$ary_get['code']);
		$this->assign('username',$ary_get['username']);
		$this->display();
	}
	//购买用户的服务号列表记录
	public function plist(){
		$ary_get=$_GET;
		$ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', C('PAGE_COUNT'));
		$where=" 1=1 ";
		if(!empty($ary_get['username']))$where=$where." and username ='".$ary_get['username']."'";
		$count = M("beian_product_list")->where($where)->count();
		$obj_page =$this->AdminPage($count,$ary_get['pageall']);
        $pageinfo = $obj_page->show();
        $data = M("beian_product_list")
			    ->where($where)
        		->limit($obj_page->firstRow, $obj_page->listRows)
				->order("id desc")
				->select();
		$this->assign("page",$pageinfo);
		$this->assign('data',$data);
		$this->assign('username',$ary_get['username']);
		$this->display();
	}
	//备案服务号产品配置
	public function pconfig(){
		if (IS_POST){
			$beianconfig=D('beian_product')->where(array('product_id'=>1))->save($_POST);
			if(FALSE !== $beianconfig){
				$this->success("编辑成功",U('Admin/Beian/plist'));
			}else{
				$this->error("编辑失败");
			}	
			exit();
		}
		$data=D('beian_product')->where(array('product_id'=>1))->find();
		$this->assign('data',$data);
		$this->display();
	}
	//单独设置用户价格
	public function puser(){
		$ary_get=$_GET;
		$ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', C('PAGE_COUNT'));
		$where=" 1=1 ";
		if(!empty($ary_get['username']))$where=$where." and username ='".$ary_get['username']."'";
		$count = M("beian_product_user")->where($where)->count();
		$obj_page =$this->AdminPage($count,$ary_get['pageall']);
        $pageinfo = $obj_page->show();
        $data = M("beian_product_user")
			    ->where($where)
        		->limit($obj_page->firstRow, $obj_page->listRows)
				->order("addtime desc")
				->select();
		
		$this->assign("page",$pageinfo);
		$this->assign('data',$data);
		$this->assign('username',$ary_get['username']);
		$this->display();
	}
	public function puseradd(){
		if (IS_POST){
			$ary_post=$_POST;
			$user=D('user')->where(array('username'=>$ary_post['username']))->find();
			if (!$user)$this->error('用户不存在');
			$beian_product_user=D('beian_product_user')->where(array('username'=>$ary_post['username']))->find();
			if ($beian_product_user)$this->error('用户已配置单独价格');
			$data=array(
				'user_id'=>$user['user_id'],
				'username'=>$user['username'],
				'product_price'=>$ary_post['product_price'],
				'addtime'=>time(),
			);
			$result=D('beian_product_user')->add($data);
			if ($result==false)$this->error('添加失败');
			$this->success('配置成功');
			exit();
		}
		$this->display();
	}
	public function puseredit(){
		if (IS_POST){			
			$arr_post = $this->_post();
			$add_rs = D('beian_product_user')->where('id='.$arr_post['id'])->save($arr_post);
			if(FALSE !== $add_rs){
				$this->success("编辑成功",U('Admin/Beian/puser'));
			}else{
				$this->error("编辑失败");
			}	
			exit();
		}
		$id = I('id','','htmlspecialchars');
		if ($id){
			$data=D('beian_product_user')->where(array('id'=>$id))->find();
			$this->assign('data',$data);
			$this->display();
		}else{
			$this->error('编辑项不能为空!');
		}
	}
	public function puserdel(){
		$id = I('id','','htmlspecialchars');
		if ($id){
			$mod=D('beian_product_user');
			$ary_result = $mod->where(array('id'=>$id))->delete();
			if ($ary_result){
				$this->success('操作成功');
			}else{
				$this->error('操作失败');
			}	
		}else{
			$this->error('删除项不能为空!');
		}
	}
	public function config(){
		if (IS_POST){
			$data['BEIAN']=array('API_USERNAME'=>$_POST['API_USERNAME'],'API_PASSWORD'=>$_POST['API_PASSWORD'],'API_IP'=>$_POST['API_IP']);
			$data['AODUN']=array('beiurl'=>$_POST['beiurl'],'beiaccount'=>$_POST['beiaccount'],'beipwd'=>$_POST['beipwd'],'beinum'=>$_POST['beinum']);
			F('beian',$data,CONF_PATH);//写入配置文件
			$beianconfig=D('beian_config')->where(array('id'=>0))->save($_POST);
			if(FALSE !== $beianconfig){
				$this->success("编辑成功",U('Admin/Beian/index'));
			}else{
				$this->error("编辑失败");
			}	
			exit();
		}
		$beianconfig=D('beian_config')->where(array('id'=>0))->find();
		$this->assign('data',$beianconfig);
		$this->display();
	}
	public function edit(){
		if (IS_POST){
			$arr_post = $this->_post();
			$add_rs = D('beian_code')->where('id='.$arr_post['id'])->save($arr_post);
			if(FALSE !== $add_rs){
				$this->success("编辑成功",U('Admin/Beian/index'));
			}else{
				$this->error("编辑失败");
			}	
			exit();
		}
		$ids = I('id','');
		if($ids){
			$beian_code = M("beian_code");
			$data = $beian_code->where("id =".$ids)->find();
			if ($data[type]==1){
				$cloud=D('Cloud')->where(array('id'=>$data['pid']))->find();
				$data['name']=$cloud['cloudname'];
				$endip="";
				$ipqosinfo=json_decode($cloud['ipqosinfo']);
				if ($ipqosinfo->status=="success"){
					foreach ($ipqosinfo->value as $kk=>$vv){
						if (!$vv->shared){
							foreach ($vv->ip_infos as $kkk=>$vvv){
								$endip.=$vvv->ip."<br>";
							}
						}
					}
				}
				$data['ip']=$endip;
			}
			
			$this->assign("data",$data);
			$this->display();
		}
	}
	
	public function del(){
		$ids = I('id','');
		if($ids){
			$mod = M("beian_code");
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
	public function state(){
		$act=I('act','status','htmlspecialchars');
		$code=I('code','0','htmlspecialchars');
		if (empty($code)){
			echo 0;
			exit;
		}
		$beiancode=D('beian_code')->where(array('code'=>$code))->find();
		if (empty($beiancode)){
			echo 0;
			exit;
		}
		$codeinfo=$this->selectcode($code);
		$jsondata=json_decode($codeinfo,true);
		$jsondata['code']=$beiancode['code'];
		echo json_encode($jsondata);
		exit();
	}
	protected function selectcode($Code){
		$beianconfig=C('AODUN');
		$posturl=$beianconfig['beiurl']."/icpv3.0/api/private/QueryStatus";
		$account=$beianconfig['beiaccount'];
		$pwd=$beianconfig['beipwd'];
		$serverCode=$Code;
		$randomString=randstr(20);
		$sign=md5($serverCode.$pwd.$randomString);
		$postdata=array(
			'serverCode'=>$serverCode,
			'randomString'=>$randomString,
			'sign'=>$sign,
			'account'=>$account
		);
		return  $this->postcurl($posturl,$postdata);
	}
	protected  function postcurl($posturl,$postdata){
		foreach($postdata as $key=>$value){
			$post.=$key.'='.urlencode($value)."&";
		}
		$post = trim($post, '&');
	    $ch = curl_init($posturl);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$resp = curl_exec($ch);
		$error = curl_error($ch);
		if($error){
			echo $error;exit;
		}
		curl_close($ch);
		return $resp;
	}
	
	
}
?>