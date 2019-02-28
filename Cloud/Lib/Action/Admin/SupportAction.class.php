<?php
/**
 * 客服支持管理
 * 工单系统
 * @author minran
 */
class SupportAction extends AdminAction{
	//工单列表
	public function index(){
		$Mod=D('support');
		$mod_cate=M('support_types');
		$ary_get = $this->_get();
		$where = '1';
		$type = $ary_get['type'];//类型
		if (!empty($type)){
		 	$typedata=$mod_cate->table(C("DB_PREFIX").'support_types as s')->select();
			$cids = GetSonIds($typedata,$type,true);
			$where .= " and s.type in(".$cids.")";
        	$this->assign("prm_cid",$type);
		}
		$prm_status = $ary_get['status'];//状态
		if($prm_status){
			$where .= " and s.status = ".$prm_status."";
			$this->assign('prm_status',$prm_status);
		}
		$username = $ary_get['username'];//用户名
		if (!empty($username)){
			$uinfo = M("user")->where("username = '$username'")->find();
			$uid = $uinfo['user_id'];
			$where .= " and s.user_id = ".$uid;
        	$this->assign("prm_username",$username);
		}
		//分页
		$ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', C('PAGE_COUNT'));
		$count = $Mod->table(C("DB_PREFIX")."support AS s")
						 ->where($where)
						 ->count();
        $obj_page =$this->AdminPage($count, $ary_get['pageall']);
        $page = $obj_page->show();
        $this->assign("page", $page);
        //获取列表
		$data = $Mod->table(C("DB_PREFIX")."support s")
				->join(C("DB_PREFIX").'support_types as c on c.id = s.type')
				->join(C("DB_PREFIX").'user as u on u.user_id = s.user_id')
				->field('s.*,c.name as cate,u.username')
				->order('last_update desc')
        		->where($where)
        		->limit($obj_page->firstRow, $obj_page->listRows)
				->select();
//				p($Mod->getLastSql());
		//获取分类
		$cates = $mod_cate->where('pid = 0')->order('sort asc')->select();
		foreach ($cates as $k=>$v){
			$cates[$k]['childs'] = $mod_cate->where('pid = '.$v["id"])->order('sort asc')->select();
		}
		$this->assign('cates',$cates);
		//工单状态
		$this->assign('status',$this->getStatus());
		$this->assign('data',$data);
		$this->display();
	}
	
	//编辑工单
	public function edit(){
		$mod = M("support");
		$mod_apd = M("support_append");
		$mod_img = M("support_images");
		if (IS_POST){
			$arr_post = $this->_post();
			$arr_post['user_id'] = session(C('USER_AUTH_KEY'));
			$arr_post['type'] = 2;//1客户追问，2回复
			$arr_post['add_time'] = time();
			$data_id = $mod_apd->add($arr_post);
			if(FALSE !== $data_id){
				//修改状态为回复
				$sp['status'] = 3;
				$sp['last_update'] = time();
				$rs1 = $mod->where(' id = '.$arr_post['wo_id'])->save($sp);
				if(FALSE === $rs1){
					$this->error('保存回复失败！');
				}
				//上传图片
				import('ORG.Net.UploadFile');
				$upload = new UploadFile();// 实例化上传类
				$i = 0;
				foreach ($_FILES as $key=>$file){
					$i++;
					if(!empty($file['name'])) {
				        $upload->autoSub = true;
				        $upload->subType =  'date';
						$upload->maxSize = 3145728 ;// 设置附件上传大小
						$upload->saveRule  = time().$i ;
						$upload->uploadReplace  = true ;// 设置附件上传大小
						$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
						$uploadsaveurl='Upload/Support/'.$this->username.'/';//存放数据库地址
						$uploadsavepath=$uploadsaveurl;
						make_dir($uploadsavepath);//创建目录
						$upload->savePath = $uploadsavepath ;// 设置附件上传目录
						$info =  $upload->uploadOne($file);
						if(!$info){//上传错误
						}else{//保存附件信息
							$uploadsaveurl = str_replace("./","/",$uploadsaveurl);
							$file=$uploadsaveurl.$info[0]['savename'];
							$date_img = array(
								'wo_id'=>$arr_post['wo_id'],
								'apd_id'=>$data_id,
								'path'=>$file,
							);
							$res_addimg = M('support_images')->add($date_img);
						}
					}
				}
				//查询工单基本信息
				$sinfo = M("support")->where(array("id"=>$arr_post['wo_id']))->find();
				//发送提醒客户
				$this->stc(2,$sinfo['s_no'],$sinfo['user_id'],$_SESSION['admin_name']);
				$this->success("添加成功");
			}else{
				$this->error("添加失败");
			}
			exit();
		}
		//查询工单详情
		$id=I('id','','htmlspecialchars');
		if (empty($id))$this->error("参数错误");
		$data = $mod ->where(array("id"=>$id))->find();
		if($data){//如果有该工单
			//修改状态为处理中
			if($data['status']==1){//如果是新提交的工单，查看时修改状态为处理中
				//发送提醒 客户
				$this->stc(1,$data['s_no'],$data['user_id'],$_SESSION['admin_name']);
				//查看时修改状态为处理中
				$upd = array(
						"status"=>2,
						"last_update"=>time()
					);
				$rs = $mod ->where(array("id"=>$id))->save($upd);
			}
			//如果客户选择具体产品问题 查询产品信息
			if($data[type]==9999){
				switch ($data['product_type']){
					case 1://云主机
						$cloud_info = M('cloud')->where(array("id"=>$data['product_id']))->find();
						if($cloud_info)
						$data['product'] = $cloud_info["cloudname"]."(".$cloud_info["Cloudtype"].")";
						break;
					case 2://域名
						$data['product'] = false;
						break;
					case 3://托管租用
						$data['product'] = false;
						break;
				}
			}
			//查询工单对应的图片
			$img_data = $mod_img->where("wo_id = ".$id." and apd_id is null")->order('id asc')->select();
			$data['imgs']=$img_data;
			$this->assign('data',$data);
			//查询追加信息
			$apd_data = $mod_apd->table(C("DB_PREFIX").'support_append sa')
								->join(C("DB_PREFIX")."admin as adm on adm.u_id = sa.user_id")
								->field('sa.*,adm.u_name')
								->where(' sa.wo_id = '.$id)
								->order('sa.add_time asc')
								->select();
			//查询工单追加信息对应的图片
			foreach($apd_data as $k=>$v){
				$img_date_apd = $mod_img -> where(' apd_id = '.$v['id'])->order('id asc')->select();
				$apd_data[$k]['imgs']=$img_date_apd;
			}
			$this->assign('apd_data',$apd_data);
			$this->display();
		}else{
			$this->error("您所查询的数据 不存在");
		}
	}
	//删除工单
	public function del(){
		$id = I('id','','htmlspecialchars');
		if ($id){
			$mod=M('support');
			$mod_apd=M('support_append');
			$mod_img=M('support_images');
			$ary_result = $mod->where(array('id'=>$id))->delete();
			if($ary_result){
				//删除相关追问
				$mod_apd->where(array('wo_id'=>$id))->delete();
				//删除相关图片
				$imgs = $mod_img->where(array('wo_id'=>$id))->select();
				foreach($imgs as $k=>$v){
					//删除图片文件
					$img_path = ROOT_PATH.$v['path'];
					unlink($img_path);
				}
				$mod_img->where(array('wo_id'=>$id))->delete();
			}
			if ($ary_result){
				$this->success('操作成功');
			}else{
				$this->error('操作失败');
			}	
		}else{
			$this->error('删除项不能为空!');
		}
	}

	//工单分类
	public function cate(){
		$Mod=M('support_types');
		$data = $Mod->order("sort")->select();
		//树状图显示结果
		$tree = new Tree();
		$tree->icon = array('│ ','├─ ','└─ ');
		$tree->nbsp = '&nbsp;&nbsp;&nbsp;';
		$array = array();
		if(!empty($data) && is_array($data)){
			foreach($data as $vl){
				$vl['parentid_node'] = ($vl['pid'])? ' class="child-of-node-'.$vl['pid'].'"' : '';
				$vl['edit_url'] =	U('/Admin/support/cateedit',array('id'=>$vl['id'])); 
				$vl['del_url']	=	U('/Admin/support/catedel',array('id'=>$vl['id'])); 
				$array[] = $vl;
			}
			$str = "<tr id='list_\$id' \$parentid_node>
						<td>\$id</td>
                        <td>\$spacer\$name</td>
                        <td>\$sort</td>
                        <td class='align-center'>
                        <a href='\$edit_url' class='edit'>编辑</a>
						&nbsp;
						<a href='\$del_url' class='confirmbutton'>删除</a>
                        </td>
                    </tr>";
			$tree->init($array);
			$list = $tree->get_tree(0, $str);
			$this->assign('list', $list);
		}
		$this->assign('cates',$data);
		$this->display();
	}
	//添加分类
	public function cateadd(){
		$Mod=M('support_types');
		if (IS_POST){
			$arr_post = $this->_post();
			$add_rs = $Mod->add($arr_post);
			if(FALSE !== $add_rs){
				$this->success("添加成功",U('Admin/support/cate'));
			}else{
				$this->error("添加失败");
			}
			exit();
		}
		$cates = $Mod->where(' pid = 0 ')->select();
		$this->assign('parents',$cates);
		$this->display();
	}
	//编辑分类
	public function cateedit(){
		$Mod=M('support_types');
		if (IS_POST){
			$arr_post = $this->_post();
//			if($arr_post['status']==0){
//				$arr_childs = $Mod->where(array("pid"=>$id,"status"=>'1'))->select();
//				if($arr_childs)$this->error('仍有可用的子分类，请先处理子分类');
//			}
			$add_rs = $Mod->save($arr_post);
			if(FALSE !== $add_rs){
				$this->success("编辑成功",U('Admin/support/cate'));
			}else{
				$this->error("编辑失败");
			}
			exit();
		}
		$id = I('id','','htmlspecialchars');
		if ($id){
			$data = $Mod->where(array('id'=>$id))->find();
			$this->assign('data',$data);
			$parents = $Mod->where('pid = 0')->select();
			$this->assign('parents',$parents);
			$this->display();
		}else{
			$this->error('编号不能为空!');
		}
	}
	//删除分类
	public function catedel(){
		$id = I('id','','htmlspecialchars');
		if ($id){
			$mod=D('support_types');
			//判断是否包含子类
			$arr_childs = $mod->where(array("pid"=>$id))->select();
			if($arr_childs)$this->error('请先删除子分类');
			//执行删除
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
	function stc($jd,$s_no,$cid,$aname){
		//通知客户工单进度start
		//获取客户信息
		$uinfo = M("user")->where(array("user_id"=>$cid))->find();
//		$wuinfo = M("weixin_user")->where(array("uid"=>$cid))->select();
//		$uinfo['wxids'] = $wuinfo;
		//发送提醒
		$send_rs = D('Support')->stc($s_no,$jd,$uinfo,$aname);
		//通知客户工单进度end
		return $send_rs;
	}
	//工单状态
	function getStatus(){
		return array(
			'1'=>'已提交',
			'2'=>'处理中',
			'3'=>'已回复',
			'4'=>'追问',
			'5'=>'已完成',
			'9'=>'已取消'
		);
	}
}
?>