<?php
/**
 * 工单  客户支持
 * @author 3401146
 */
class SupportAction extends MainuserAction{
	public function index(){
		$mod=M('support');
		$mod_cate=M('support_types');
		$ary_get=$_GET;
		//查询条件
		$where=" s.user_id=".$this->uid;
		$prm_type = $ary_get['type'];//工单类型
		$prm_status = $ary_get['status'];//状态
		Load('extend');
		$ary_get['starttime'] = I('get.starttime','');
		$ary_get['starttime']=remove_xss($ary_get['starttime']);
		$ary_get['endtime'] = I('get.endtime','');
		$ary_get['endtime']=remove_xss($ary_get['endtime']);
		if (!empty($prm_type)){
			$prm_type=I('get.type','0','intval');//预防sql注入3-25
		 	$typedata=$mod_cate->select();
			$cids = GetSonIds($typedata,$prm_type,true);
			$where .= " and s.type in(".$cids.")";
			$this->assign("prm_type",$prm_type);
		}
		
		if($prm_status){
			$prm_status=I('get.status','0','intval');//预防sql注入3-25
			$where .= " and s.status = ".$prm_status."";
			$this->assign('prm_status',$prm_status);
		}
		
		if($ary_get['starttime']){
			$starttime=str_replace("+"," ",$ary_get['starttime']);	
			$starttime=convert_datefm($starttime);
			$starttime=intval($starttime);//预防sql注入3-25
			$where .= " and s.add_time > '".$starttime."'";
			$this->assign('prm_starttime',date('Y-m-d H:i:s', $starttime));
		}
		
		if($ary_get['endtime']){
			$endtime=str_replace("+"," ",$ary_get['endtime']);	
			$endtime=convert_datefm($endtime);
			$endtime=intval($endtime);//预防sql注入3-25
			$where .= " and s.add_time < '".$endtime."'";
			$this->assign('prm_endtime',date('Y-m-d H:i:s', $endtime));
		}
		//分页
	//	$ary_get['pageall'] = $this->_get('pageall', 'htmlspecialchars', C('PAGE_COUNT'));
		$count = $mod->table(C("DB_PREFIX").'support as s')->where($where)->count();
		$obj_page =$this->UserPage($count,10);
		$pageinfo = $obj_page->show();
		$this->assign("pageinfo",$pageinfo);
		//获取数据
		$data = $mod->table(C("DB_PREFIX").'support as s')
				->join(C("DB_PREFIX").'support_types as t on t.id=s.type')
				->field('s.*,t.name as type_name')
				->where($where)
				->limit($obj_page->firstRow, $obj_page->listRows)
				->order("last_update desc")
				->select();
//				p($mod->getLastSql());
		$this->assign('data',$data);
		//工单状态
		$this->getStatus();
		$this->assign('status',$this->getStatus());
		//获取分类
		$cates = $mod_cate->where('pid = 0')->order('sort asc')->select();
		foreach ($cates as $k=>$v){
			$cates[$k]['childs'] = $mod_cate->where('pid = '.$v["id"])->order('sort asc')->select();
		}
		$this->assign('cates',$cates);
		$this->display();
	}
	//提交工单
	public function add(){
		$Mod=M('support');
		if (IS_POST){
			$arr_post = $this->_post();
			foreach ($arr_post as $pk=>$pv){
				$arr_post[$pk]=remove_xss($pv);			
			}
			$arr_post['s_no'] = $this->getcode();
			$arr_post['user_id'] = $this->uid;
			$arr_post['status'] = 1;//默认状态为1，已提交
			$arr_post['add_time'] = time();
			$arr_post['last_update'] = time();
			$arr_post['type']=I('type','0','intval');
			$arr_post['product_type']=I('product_type','0','intval');
			$arr_post['product_id']=I('product_id','0','intval');
			$arr_post['title']=I('title','','');
			$arr_post['content']=I('content','0','');
			if (empty($arr_post['title']))$this->error("标题不能为空或格式错误");
			if (empty($arr_post['content']))$this->error("内容不能为空或格式错误");
			$data_id = $Mod->add($arr_post);
			if(FALSE !== $data_id){//工单成功提交
				//发送邮件通知管理员  新工单消息
				D('Support')->sta($data_id,1);
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
						if(!$info){// 上传错误
							$this->error($upload->getErrorMsg());
						}else{ // 保存附件信息
							$uploadsaveurl = str_replace("./","/",$uploadsaveurl);
							$file=$uploadsaveurl.$info[0]['savename'];
							import('ORG.Util.Image');
							$Image = new Image();
							$info=$Image->water(ROOT_PATH.$file,ROOT_PATH.'Upload/water.png');
							$date_img = array(
								'wo_id'=>$data_id,
								'path'=>$file,
							);
							$res_addimg = M('support_images')->add($date_img);
						}
					}
				}
				$this->success("添加成功",U('User/support/index'));
			}else{
				$this->error("添加失败");
			}
			exit();
		}
		//获取工单分类
		$mod_t = M('support_types');
		$types = $mod_t->where('pid=0')->order('sort')->select();
		foreach ($types as $k=>$v){
			$types[$k]['childs'] = $mod_t->where('pid='.$v['id'])->order("sort")->select();
		}
		$this->assign('types',$types);
		//获取产品：云主机列表
		$clouds = M("cloud")->where(array("user_id"=>$this->uid))->select();
		$this->assign('clouds',$clouds);
		$this->assign('uid',$this->uid);
		$this->display();
	}
	
	//工单详情-追问
	public function edit(){
		$mod = M("support");
		$mod_apd = M("support_append");
		$mod_img = M("support_images");
		if (IS_POST){
			$arr_post = $this->_post();
			foreach ($arr_post as $pk=>$pv){
				$arr_post[$pk]=remove_xss($pv);			
			}
			$supportinfo=$mod->where(array('id'=>$arr_post['wo_id'],'user_id'=>$this->uid))->find();
			if (empty($supportinfo))$this->error("工单不存在");
			$arr_post['user_id'] = $this->uid;
			$arr_post['type'] = 1;//1客户追问，2回复
			$arr_post['add_time'] = time();
			$arr_post['content']=I('content','','strip_tags');
			if (empty($arr_post['content']))$this->error("内容不能为空或格式错误");			
			$data_id = $mod_apd->add($arr_post);
			if(FALSE !== $data_id){
				//发送邮件通知管理员  追问消息
				D('Support')->sta($arr_post['wo_id'],2);
				//修改状态为追问
				$sp['status'] = 4;
				$sp['last_update'] = time();
				$rs1 = $mod->where(' id = '.$arr_post['wo_id'])->save($sp);
				if(FALSE === $rs1){//保存失败时
					$this->error('保存失败！');
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
							import('ORG.Util.Image');
							$Image = new Image();
							$info=$Image->water(ROOT_PATH.$file,ROOT_PATH.'Upload/water.png');
							$date_img = array(
								'wo_id'=>$arr_post['wo_id'],
								'apd_id'=>$data_id,
								'path'=>$file,
							);
							$res_addimg = M('support_images')->add($date_img);
						}
					}
				}
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
				$img_date_apd = $mod_img
								->where(' apd_id = '.$v['id'])
								->order('id asc')
								->select();
				$apd_data[$k]['imgs']=$img_date_apd;
			}
			$this->assign('apd_data',$apd_data);
			$this->display();
		}else{
			$this->error("您所查询的数据 不存在");
		}
	}
	//评价工单
	public function comment(){
		$mod = M("support");
		//查询工单详情
		$id=I('id','','htmlspecialchars');
		if (empty($id))$this->error("参数错误");
		$data = $mod ->where(array("id"=>$id))->find();
		if($data){//如果有该工单,修改状态为评价
			$sp['status'] = 5;//修改状态为完成
			$sp['comment_rank'] = $_POST['comment_rank'];
			$sp['comment'] = $_POST['comment'];
			$sp['last_update'] = time();
			$rs = $mod->where('id='.$id)->data($sp)->save();
			if($rs !== FALSE){
				$this->success('操作成功！');
			}else{
				$this->error("操作失败！");
			}
		}
	}
	//完成工单
	public function finish(){
		$mod = M("support");
		//查询工单详情
		$id=I('get.id',0,'intval');
		if (empty($id))$this->error("参数错误");
		$data = $mod ->where(array("id"=>$id))->find();
		if($data){//如果有该工单,修改状态为完成
			$sp['status'] = 5;
			$sp['last_update'] = time();
			$rs = $mod->where('id='.$id)->data($sp)->save();
			if($rs !== FALSE){
				$this->success('操作成功！');
			}else{
				$this->error("操作失败！");
			}
		}
	}
	//取消工单
	public function cancel(){
		$mod = M("support");
		//查询工单详情
		$id=I('get.id',0,'intval');
		if (empty($id))$this->error("参数错误");
		$data = $mod ->where(array("id"=>$id))->find();
		if($data){//如果有该工单,修改状态为取消
			$sp['status'] = 9;
			$sp['last_update'] = time();
			$rs = $mod->where('id='.$id)->data($sp)->save();
			if($rs !== FALSE){
				$this->success('操作成功！');
			}else{
				$this->error("操作失败！");
			}
		}
	}
	//生成工单号
	function getcode(){
	 	mt_srand((double) microtime() * 1000000);
	 	$code = "sn_".date('YmdHis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
	 	//验证号码是否存在
		$m=M('support');
		$oldcode=$m->where("s_no='".$code."'")->getField('s_no');
		if($oldcode){
			$this->getordcode();
		}else{
			return $code;
		}
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