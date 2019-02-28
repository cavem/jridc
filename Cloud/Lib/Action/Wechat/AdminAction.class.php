<?php
class AdminAction extends MainadminAction{
	public function doquit(){
		unset($_SESSION['Wechatadmin']);
		$this->redirect('Admin/Main/index');
		
	}
	public function index(){
		header("location:".U("wechat/admin/userlist"));
//		$this->display();
	}
	//设置接口
	public function setapi(){
		$data=D('weixin_config')->where(array('id'=>1))->find();
		if (IS_POST){
			$ary_poat=$_POST;
			if($data){
				D('weixin_config')->where(array('id'=>1))->save($ary_poat);
			}else{
				D('weixin_config')->add($ary_poat);
			}
			$this->success("设置成功");
			exit();
		}
		$this->assign("data",$data);
		$this->display();
	}

	//模版消息 模版列表
	public function templatelist(){
		//查询条件
		$where = " 1 ";
		$prm_name = $_GET["prm_name"];
		if($prm_name){
			$where .=" and name like '%".$prm_name."%'";
			$this->assign("prm_name",$prm_name);
		}
		$prm_t_id = $_GET["prm_t_id"];
		if($prm_t_id){
			$where .=" and t_id like '%".$prm_t_id."%'";
			$this->assign("prm_t_id",$prm_t_id);
		}
		$data = M("weixin_tmsg_config")->where($where)->order("sort")->select();
		$this->assign("data",$data);
		$this->display();
	}
	//模版消息 添加模版
	public function templateadd(){
		if($_POST){
			$save_date = array(
				"name"=>$_POST['name'],
				"t_id"=>$_POST['t_id'],
				"sort"=>$_POST['sort']
			);
			unset($_POST['name']);
			unset($_POST['t_id']);
			unset($_POST['sort']);
			$fieldCount = count($_POST);
			for($i=0;$i<$fieldCount;$i++){
				$farr["field".$i] = $_POST['field'.$i];
			}
			$save_date['content'] = serialize($farr);
			$rs = M("weixin_tmsg_config")->add($save_date);
			if($rs!==FALSE){
				$this->success("添加成功",U("wechat/admin/templatelist"));
			}else{
				$this->error("添加失败");
			}
		}
		$this->display();
	}
	public function templateedit(){
		$id = I("id");
		if($id){
			if($_POST){
				$save_date = array(
					"id"=>$id,
					"name"=>$_POST['name'],
					"t_id"=>$_POST['t_id'],
					"sort"=>$_POST['sort']
				);
				unset($_POST['id']);
				unset($_POST['name']);
				unset($_POST['t_id']);
				unset($_POST['sort']);
				$fieldCount = count($_POST);
				for($i=0;$i<$fieldCount;$i++){
					$farr["field".$i] = $_POST['field'.$i];
				}
				$save_date['content'] = serialize($farr);
				M("weixin_tmsg_config")->save($save_date);
				header("location:".U("wechat/admin/templatelist"));
			}
			$data = M("weixin_tmsg_config")->where(array("id"=>$id))->find();
			if($data){
				$content = unserialize($data['content']);
				$fieldCount = count($content);
				for($i=0;$i<$fieldCount;$i++){
					$farr[$i] = $content['field'.$i];
				}
				$data['content'] = $farr;
				$this->assign("data",$data);
				$this->display();
			}else{
				$this->error("该数据不存在。");
			}
		}else{
			$this->error("参数错误",U("wechat/admin/templatelist"));
		}
	}
	public function templatedel(){
		$id = I("id");
		if($id){
			$rs = M("weixin_tmsg_config")->where(array("id"=>$id))->delete();
			if($rs!==FALSE){
				$this->success("删除成功");
			}else{
				$this->error("删除失败");
			}
		}else{
			$this->error("参数错误");
		}
	}
	//模版消息群发
	public function templatesend(){
		
		if($_POST){//执行发送
			//查询消息模版
			$id = $_POST['t_id'];
			$tmsg_data = M("weixin_tmsg_config")->where(array("id"=>$id))->find();
			if(!empty($tmsg_data)){
				//获取模版ID 模版格式
				$t_id = $tmsg_data['t_id'];
				$fields = unserialize($tmsg_data['content']);
				foreach ($fields as $k=>$v){
					$tm[$v] = array("value"=>$_POST[$v]);
					unset($_POST[$v]);
				}
				//获取用户列表
				if($_POST['fanstype']==1){//查询所有仍关注的粉丝
					$fans = M("weixin_user")->where(array("subscribe"=>1))->select();
				}else{//获取选定粉丝
					unset($_POST['t_id']);
					unset($_POST['fanstype']);
					unset($_POST['checkboxall']);
					foreach ($_POST as $k=>$v){
						$rs = M("weixin_user")->where(array("id"=>$v,"subscribe"=>1))->find();
						if(!empty($rs))
						$fans[] = $rs;
					}
				}
				$count = count($fans);
				//向用户发送模版消息
				$success_count = 0;
				foreach($fans as $k=>$v){
					$wechat = new Wechat();
					$sendrs = $wechat->sendTempMsg($v['wxid'], $t_id, $tm);
					if($sendrs=="ok"){
						$success_count++;
					}
				}
				$this->success("共向【".$count."】个用户发送模版消息，【".$success_count."】个成功！");
			}else{
				$this->error("模版不存在");
			}
		}
		//获取粉丝列表
		//查询条件
		$where = " 1 ";
		$prm_wx_id = $_GET["wx_id"];
		if($prm_wx_id){
			$where .=" and wxid like '%".$prm_wx_id."%'";
			$this->assign("prm_wx_id",$prm_wx_id);
		}
		$prm_uname = $_GET["uname"];
		if($prm_uname){
			$where .=" and uname like '%".$prm_uname."%'";
			$this->assign("prm_uname",$prm_uname);
		}
		$Mod=M('weixin_user');
		$data = $Mod->where($where)->order('add_time desc')->select();
		$this->assign("datawu",$data);
		
		//获取模版列表
		//查询条件
		$where_tmgs = " 1 ";
		$prm_name = $_GET["prm_name"];
		if($prm_name){
			$where_tmgs .=" and name like '%".$prm_name."%'";
			$this->assign("prm_name",$prm_name);
		}
		$prm_t_id = $_GET["prm_t_id"];
		if($prm_t_id){
			$where_tmgs .=" and t_id like '%".$prm_t_id."%'";
			$this->assign("prm_t_id",$prm_t_id);
		}
		$data_tmgs = M("weixin_tmsg_config")->where($where_tmgs)->order("sort")->select();
		$this->assign("datat",$data_tmgs);
		$this->display();
	}
	
	//微信用户管理-用户列表
	public function userlist(){
		//查询条件
		$where = " 1 ";
		$prm_wx_id = $_GET["wx_id"];
		if($prm_wx_id){
			$where .=" and wxid like '%".$prm_wx_id."%'";
			$this->assign("prm_wx_id",$prm_wx_id);
		}
		$prm_uname = $_GET["uname"];
		if($prm_uname){
			$where .=" and uname like '%".$prm_uname."%'";
			$this->assign("prm_uname",$prm_uname);
		}
		$Mod=M('weixin_user');
		$data = $Mod->where($where)->order('add_time desc')->select();
		$this->assign("data",$data);
		$this->display();
	}
	//编辑用户信息
	public function useredit(){
		$Mod=M('weixin_user');
		if(IS_POST){
			$post_arr = $_POST;
			$id = $post_arr['id'];
			$uname = $post_arr['uname'];
			$rsmsg = '操作成功';
			if($uname){
				$u_info = M("user")->where("username = '$uname'")->find();
				if($u_info){
					$post_arr['uid'] = $u_info['user_id'];
				}else{
					$this->error('绑定帐号 不存在');
				}
			}else{//不填用户名 则认为是取消用户绑定
				$rsmsg = '取消用户绑定成功';
				$post_arr['uid'] = 0;
			}
			$rs = $Mod->where(" id = ".$id)->save($post_arr);
			if(FALSE !== $rs){
				$this->success($rsmsg);
			}else{
				$this->error('操作失败');
			}
			exit();
		}
		$id = I('id','','htmlspecialchars');
		if ($id){
			$data = $Mod->where(array('id'=>$id))->find();
			$this->assign('data',$data);
			$this->display();
		}else{
			$this->error('编号不能为空！');
		}
	}
	//删除用户
	public function userdel(){
		$mod=M('weixin_user');
		if (IS_POST){
			$ary_post=$_POST;
			unset($ary_post['_URL_']);
			unset($ary_post['all']);
			$ids="";
			foreach ($ary_post as $k=>$v){
				if ($ids){
					$ids=$ids.",".$v;
				}else{
					$ids=$v;
				}
			}
			$id = $ids;
		}else{
			$id = I('id','');
		}
		if ($id){
			$info = $mod->where("id in (".$id.")")->delete();
			if($info){
				$this->success("删除成功");
			}else{
				$this->error("删除失败");
			}
		}else{
			$this->error('编号不能为空！');
		}
	}
	//多选操作用户
	public function optuser(){
		$opttype = I('opttype');
		unset($_POST['opttype']);
		unset($_POST['checkboxall']);
		switch ($opttype){
			case 'del':
				$this->userdel();
				break;
			case 'tb':
				$this->syninfo();
				break;
		}
	}
	//同步用户信息
	public function syninfo(){
		$mod=M('weixin_user');
		if (IS_POST){
			$ary_post=$_POST;
			unset($ary_post['_URL_']);
			unset($ary_post['all']);
			$ids;
			foreach ($ary_post as $k=>$v){
				$ids[]= $v;
			}
		}else{
			$ids = I('id','');//暂时没有单个同步的操作，保留
		}
		if ($ids){
			$ids = is_array($ids)?$ids:array($ids);
			$iall = 0;
			$icount = 0;
			$weixin_config = M("weixin_config")->where('id = 1')->find();
			$access_token = $this->wechat->get_access_token($weixin_config['appid'],$weixin_config['appsecret']);
			foreach ($ids as $k=>$v){
				$iall++;
				$weixin_user = $mod->where('id = '.$v)->find();
				if($weixin_user['wxid']){
					$wechat_info = $this->wechat->getWechatUserInfo($weixin_user['wxid'],$access_token);
					if(!$wechat_info[errcode]){//如果获取信息成功
						$updata = array(
								"nickname"=>$wechat_info['nickname'],
								"subscribe"=>$wechat_info['subscribe']
							);
						$info = $mod->where("id = ".$v)->save($updata);
						if($info){
							$icount++;
						}
					}
				}
			}
			$this->success("对【".$iall."】个用户同步，其中【".$icount."】个产生变更");
		}else{
			$this->error('编号不能为空！');
		}
	}
	//微信发放优惠券
	public function couponlist(){
		//查询条件
		$where = " 1 ";
		//批号
		$prm_act_id = $_GET["act_id"];
		if($prm_act_id){
			$where .=" and wc.act_id like '%".$prm_act_id."%'";
			$this->assign("prm_act_id",$prm_act_id);
		}
		//openid
		$prm_open_id = $_GET["open_id"];
		if($prm_open_id){
			$where .=" and wc.open_id like '%".$prm_open_id."%'";
			$this->assign("prm_open_id",$prm_open_id);
		}
		//昵称
		$prm_nickname = $_GET["nickname"];
		if($prm_nickname){
			$where .=" and wu.nickname like '%".$prm_nickname."%'";
			$this->assign("prm_nickname",$prm_nickname);
		}
		//绑定用户名
		$prm_uname = $_GET["uname"];
		if($prm_uname){
			$where .=" and wc.username like '%".$prm_uname."%'";
			$this->assign("prm_uname",$prm_uname);
		}
		//状态
		$prm_status = $_GET["status"];
		if($prm_status){
			$where .=" and wc.status = ".$prm_status;
			$this->assign("prm_status",$prm_status);
		}
		$Mod=M('weixin_coupon');
		$data = $Mod->table(C('DB_PREFIX') . "weixin_coupon as wc ")
				->join(C('DB_PREFIX') . "weixin_user as wu on wu.wxid = wc.open_id")
				->field('wc.*,wu.nickname')
				->where($where)->order('wc.add_time desc')->select();
		$this->assign("data",$data);
		$this->display();
	}
	//优惠券详情
	public function coupondetail(){
		$mod=M('weixin_coupon');
		$id = I('id','');
		if ($id){
			$data = $mod->where(" id = $id")->find();
			if($data){
				$this->assign("data",$data);
				$this->display();
			}else{
				$this->error("该数据不存在");
			}
		}else{
			$this->error('编号不能为空！');
		}
		
	}
	//删除优惠券
	public function coupondel(){
		$mod=M('weixin_coupon');
		if (IS_POST){
			$ary_post=$_POST;
			unset($ary_post['_URL_']);
			unset($ary_post['all']);
			$ids="";
			foreach ($ary_post as $k=>$v){
				if ($ids){
					$ids=$ids.",".$v;
				}else{
					$ids=$v;
				}
			}
			$id = $ids;
		}else{
			$id = I('id','');
		}
		if ($id){
			$info = $mod->where("id in (".$id.")")->delete();
			if($info){
				$this->success("删除成功");
			}else{
				$this->error("删除失败");
			}
		}else{
			$this->error('编号不能为空！');
		}
	}
	//发放优惠券
	public function couponsend(){
		if(IS_POST){
			$utype = $_POST['utype'];
			if($utype=='some'){
				$ids = $_POST['ids'];
				$users = M("weixin_user")->where(array('id'=>array('in',$ids)))->select();
			}else{//获取所有已绑定帐号的用户
				$users = M("weixin_user")->where(' uid !=0 ')->select();
			}
			
			//生成批号
			$act_id = "c".date('YmdHis',time());
			$savedata = $_POST;
			unset($savedata['utype']);
			unset($savedata['ids']);
			unset($savedata['chosUser']);
			$exptime = $savedata['expire_time'];
			$savedata['act_id'] = $act_id;
			$savedata['expire_time'] = strtotime($exptime);
			$savedata['add_time'] = time();
			$i = 0;
			foreach($users as $k=>$v){
				$savedata['open_id'] = $v['wxid'];
				$savedata['user_id'] = $v['uid'];
				$savedata['username'] = $v['uname'];
				//保存数据
				$rs = M("weixin_coupon")->add($savedata);
				//发送模版消息
				$template_id = 'Ztc4s4vkuZrSl-RjDCqF04_NiegAM7D1epr7R71xTuE';
				$datatemp = C($template_id);
				$datatemp['data1']['value'] = '优惠活动 赠送您【'.$savedata['cmoney'].'】元抵用券，请于：'.$exptime." 之前领取使用。";
				$url = "http://".$_SERVER['HTTP_HOST']."".U('/Wechat/Web/OauthAndTo/to/reccoupon?id='.$rs);
				$rs_send = $this->wechat->sendTempMsg($v['wxid'],$template_id,$datatemp,$topclolor='#FF0000',$url);
				$i++;
			}
			$this->success("共".$i."个用户发送成功");
		}
		//默认查询所有用户
		$where = ' uid != 0 ';
		$users = M("weixin_user")->where($where)->select();
		$this->assign('users',$users);
		//获取客服
		$mod_kefu=M('kefu');
		$mod_kr=M('kefu_rank');
		$krs = $mod_kr->select();
		foreach ($krs as $k=>$v){
			$kfs = $mod_kefu->where('rank_id = '.$v['rank_id'])->select();
			$krs[$k]['kefus'] = $kfs;
		}
		$this->assign('kefus',$krs);
		$this->display();
	}
	//ajax查询用户列表
	public function couponusers(){
		$where = ' 1 ';
		$open_id = $_GET['open_id'];
		if($open_id){
			$where .= " and wxid like '%$open_id%' ";
		}
		$nickname = $_GET['nickname'];
		if($nickname){
			$where .= " and nickname like '%$nickname%' ";
		}
		$uname = $_GET['uname'];
		if($uname){
			$where .= " and uname like '%$uname%' ";
		}
		$where .= ' and uid != 0 ';//只获取绑定帐号的用户
		$data = M("weixin_user")->where($where)->select();
		$this->success($data);
	}
	//粉丝管理
	//微信公众号自定义菜单
	public function menu(){
		$Mod=M('weixin_menu');
		$data = $Mod->order('sort_order asc')->select();
		$tree = new Tree();
		$tree->icon = array('│ ','├─ ','└─ ');
		$tree->nbsp = '&nbsp;&nbsp;&nbsp;';
		$array = array();
		if(!empty($data) && is_array($data)){
			foreach($data as $vl){
				$vl['parentid_node'] = ($vl['pid'])? ' class="child-of-node-'.$vl['pid'].'"' : '';
				if($vl['weixin_type']){
					$vl['weixin_type_name']="查看";
					$vl['weixin_type_value']=$vl['links'];
				}else{
					$vl['weixin_type_name']="点击";
					$vl['weixin_type_value']=$vl['weixin_key'];
				}
				$vl['edit_url'] =	U('/Wechat/Admin/menuedit',array('id'=>$vl['id'])); 
				$vl['del_url']	=	U('/Wechat/Admin/menudel',array('id'=>$vl['id'])); 
				$array[] = $vl;
			}
			$str = "<tr id='list_\$id' \$parentid_node>
						<td>\$spacer\$cat_name</td>
                        <td>\$weixin_type_name</td>
                        <td >\$weixin_type_value</td>
                        <td class='align-center'>\$sort_order</td>
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
		$this->display();
	}
	//更新菜单
	public function menuupdate(){
		$weixin_config=D('weixin_config')->where(array('id'=>1))->find();
		$menu=D('weixin_menu')->where(array('pid'=>0))->select();
		$keyword=array();
		foreach($menu as $k=>$v){
			$nextmenu=D('weixin_menu')->where(array('pid'=>$v['id']))->select();
			 if(count($nextmenu) != 0){//没有下级栏目
				foreach ($nextmenu as $key2) {
					if($key2['weixin_type']>0){
					   $kk[] = array('type' => 'view', 'name' => $key2['cat_name'], 'url' => $key2['links']);
					}else{
					   $kk[] = array('type' => 'click', 'name' => $key2['cat_name'], 'key' => $key2['weixin_key']);
					}
				}
				$keyword['button'][] = array('name' => $v['cat_name'], 'sub_button' => $kk);
				$kk = '';
			}else{
				if($v['weixin_type']>0){
					$keyword['button'][] = array('type' => 'view', 'name' => $v['cat_name'], 'url' => $v['links']);
				}else{
					$keyword['button'][] = array('type' => 'click', 'name' => $v['cat_name'], 'key' => $v['weixin_key']);
				}
			}
		}
		$data=json_encode($keyword);
		$get_access_token=$this->wechat->get_access_token($weixin_config['appid'],$weixin_config['appsecret']);
		$msg=$this->wechat->createMenu($get_access_token,$data);
		if ($msg['errmsg'] == 'ok') {
			$this->success("创建自定义菜单成功");
			exit;
		} else {
			$this->error($msg['errmsg']);
			exit;
		}
	}
	public function menuadd(){
		if (IS_POST){
			$ary_poat=$_POST;
			if($ary_poat['weixin_type']){
				$ary_poat['links']=$ary_poat['weixin_key_links'];
			}else{
				$ary_poat['weixin_key']=$ary_poat['weixin_key_links'];
			}
			$rs = D('weixin_menu')->add($ary_poat);
			if($rs!==FALSE){
				$this->success("添加成功");
			}else{
				$this->error("添加失败");
			}
			exit();
		}
		$menu=D('weixin_menu')->where(array('pid'=>0))->select();
		$this->assign("menu",$menu);
		$this->display();
	}
	public function menuedit(){
		$Mod=M('weixin_menu');
		if (IS_POST){
				$ary_poat = $this->_post();
				$data = $Mod->where(array('id'=>$ary_poat['id']))->find();
				if(!$data)$this->error("菜单不存在");
				if($data['pid']==0 && $ary_poat['pid']!=0){
					$datas = $Mod->where(array('pid'=>$ary_poat['id']))->select();
					if($datas)$this->error("先删除二级菜单");
				}
				if($ary_poat['weixin_type']){
				$ary_poat['links']=$ary_poat['weixin_key_links'];
				}else{
				$ary_poat['weixin_key']=$ary_poat['weixin_key_links'];
				}
				$add_rs = $Mod->where(array('id'=>$ary_poat['id']))->save($ary_poat);
				if(FALSE !== $add_rs){
					$this->success("编辑成功",U('Wechat/Admin/menu'));
				}else{
					$this->error("编辑失败");
				}
				exit();
		}
		$id = I('id','','htmlspecialchars');
		if ($id){
			$data = $Mod->where(array('id'=>$id))->find();
			
			if($data['weixin_type']){
					$data['weixin_type_value']=$data['links'];
					
			}else{
					$data['weixin_type_value']=$data['weixin_key'];
			}
			$this->assign('data',$data);
			$menu=D('weixin_menu')->where(array('pid'=>0))->select();
			$this->assign("menu",$menu);
			$this->display();
		}else{
			$this->error('编号不能为空!');
		}
		
	}
	public function menudel(){
		$id = I('id','','htmlspecialchars');
		if ($id){
			$mod=D('weixin_menu');
			$arr_childs = $mod->where(array("pid"=>$id))->select();
			if($arr_childs)$this->error('请先删除子分类');
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
	//关键词回复 
	public function reply(){
		$mod = M("reply_rule");
		$where = " 1 ";
		$title = $_GET["name"];
		if($title){
			$where .= " and name like '%".$title."%'";
			$this->assign('prm_name',$title);
		}
		$data = $mod->where($where)->order('sort asc')->select();
		$this->assign("data",$data);
		$this->display();
	}
	//添加回复规则
	public function replyadd(){
		$mod=D('reply_rule');
		$mod_n=D('reply_news');
		if(IS_POST){
			$post_arr = $_POST;
			$rule_data =array(
					'name' => $post_arr['name'],
					'keyword' => $post_arr['keyword'],
					'sort' => $post_arr['sort'],
					'type' => $post_arr['type']
				);
			$rs = $mod->data($rule_data)->add();
			if(FALSE !== $rs){
				if($post_arr['type'] == 1){
					$news_data = array(
						'rid' => $rs,
						'content' => $post_arr['text_content'],
						'add_time' => time()
					);
				}else{
					$news_data = array(
						'rid' => $rs,
						'title' => $post_arr['title'],
						'img' => $post_arr['img'],
						'sort' => $post_arr['rsort'],
						'disc' => $post_arr['disc'],
						'content' => $post_arr['content'],
						'add_time' => time(),
						'upd_time' => time()
					);
				}
				$nrs = $mod_n->data($news_data)->add();
				if(FALSE !== $nrs){ $this->success('操作成功');
				}else { $this->error('消息保存失败');}
			}else{
				$this->error('规则保存失败');
			}
			exit();
		}
		$this->display();
	}
	//编辑回复规则
	public function replyedit(){
		$mod=D('reply_rule');
		$mod_n=D('reply_news');
		if(IS_POST){
			$post_arr = $_POST;
			$rule_data =array(
					'name' => $post_arr['name'],
					'keyword' => $post_arr['keyword'],
					'sort' => $post_arr['sort'],
					'type' => $post_arr['type']
				);
			$id = $post_arr['id'];
			$rs = $mod->where(" id = ".$id)->save($post_arr);
			if(FALSE !== $rs){
				$news_data = array(
					'title' => $post_arr['title'],
					'img' => $post_arr['img'],
					'sort' => $post_arr['rsort'],
					'disc' => $post_arr['disc'],
					'content' => $post_arr['content'],
					'upd_time' => time()
				);
				if($post_arr['nid']){
					$nrs = $mod_n->where("id = ".$post_arr['nid'])->save($news_data);
				}else{
					$nrs = $mod_n->data($news_data)->add();
				}
				$this->success('操作成功');
			}else{
				$this->error('操作失败');
			}
			exit();
		}
		$id = I('id','','htmlspecialchars');
		if ($id){
			$data = $mod->where(array('id'=>$id))->find();
			$this->assign('data',$data);
			$datan = $mod_n->where(array('rid'=>$id))->find();
			$this->assign('data',$data);
			$this->assign('datan',$datan);
			$this->display();
		}else{
			$this->error('编号不能为空！');
		}
	}
	//删除回复规则
	public function replydel(){
		$mod=D('reply_rule');
		$mod_n=D('reply_news');
		if (IS_POST){
			$ary_post=$_POST;
			unset($ary_post['_URL_']);
			unset($ary_post['all']);
			$ids="";
			foreach ($ary_post as $k=>$v){
				if ($ids){
					$ids=$ids.",".$v;
				}else{
					$ids=$v;
				}
			}
			$id = $ids;
		}else{
			$id = I('id','');
		}
		if ($id){
			$info = $mod->where("id in (".$id.")")->delete();
			if($info){
				$mod_n->where("rid in (".$id.")")->delete();
				$this->success("删除成功");
			}else{
				$this->error("删除失败");
			}
		}else{
			$this->error('编号不能为空！');
		}
	}
	//图文信息详情
	public function newsdetail(){
		$id = I('id','');
		if ($id){
			$mod_n=D('reply_news');
			$data = $mod_n->where("id = ".$id)->find();
			$this->assign("data",$data);
			$this->display();
		}else{
			$this->error('编号不能为空！');
		}
	}
	//发送模版消息 此处仅为测试，实际发送时 请调用Common/Wechat/sendTempMsg接口
	public function sendTempMsg(){
		$wx_users = M("weixin_user")->select();
		foreach($wx_users as $k=>$v){
			p('开始向【'.$v[wxid].'】发送模版消息');
//			$template_id = "drJV2yQKWL6X90li6i0OzRxGDLjZrz1y21Gg02tt1h4";//葛
			$template_id = 'bZ6x24-NBQ7JTzsOz2ljCpEGell5sJxEn41uTYCIr1Y';
			$datatemp = C($template_id);
			$datatemp['first']['value'] = '标题';
			$datatemp['data1']['value'] = '内容1';
			$datatemp['data2']['value'] = '内容2';
			$datatemp['bak']['value'] = '这里是备注';
			$rs_send = $this->wechat->sendTempMsg($v['wxid'],$template_id,$datatemp,$topclolor='#FF0000');
			p("发送结果：".$rs_send);
		}
	}
	//测试获取用户基本信息
	public function testGetWechatUserInfo(){
		$weixin_config = M("weixin_config")->where('id = 1')->find();
		$access_token = $this->wechat->get_access_token($weixin_config['appid'],$weixin_config['appsecret']);
		$wx_users = M("weixin_user")->select();
		foreach($wx_users as $k=>$v){
			$rs_send = $this->wechat->getWechatUserInfo($v['wxid'],$access_token);
			p($rs_send);
		}
	}
}




?>




