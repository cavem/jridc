<?php
class MainAction extends AdminAction{
function update(){
		$action=I('action',1,'intval');
		$url=C('UPDATEURL');
		$username=C('UPDATEUSERNAME');
		$apikey=C('UPDATEPASSWORD');
		$method="Update/index";//云主机名检测
		$post['version']=C('VERSION');//post参数
		$post['type']='ylagent';
		$result=$this->send($url,$method,$post,$username,$apikey);
		if ($result['status']=='failed'){
			$this->error($result['info']);
		}
		$updatezip=$result['value']['downzip'];
		$updatefile=$result['value']['path'];
		if ($action==1){
			$this->success('版本:'.$result['value']['version'].'内容:'.$result['value']['content']);
			exit();
		}
    	$up=new Webupdate();
    	make_dir(ROOT_PATH."Upload/update/");//创建目录
    	$downfile=$up->down($updatezip,ROOT_PATH."Upload/update/".$updatefile);
    	if (empty($downfile))$this->error('获取更新文件失败');
    	$zipinfo = new Pclzip(ROOT_PATH."Upload/update/".$updatefile);
    	if ($zipinfo->extract(PCLZIP_OPT_PATH, ROOT_PATH."Upload/update",PCLZIP_OPT_REMOVE_PATH, '') == 0) {
			$this->error($archive->errorInfo(true));
        }
        //获取更新文件列表
        $fileinfo=$up->get_filenamesbydir(ROOT_PATH."Upload/update/".str_replace('.zip','',$updatefile));
    	$up->update(ROOT_PATH."Upload/update/".str_replace('.zip','',$updatefile),ROOT_PATH);
    	$up->delfile(ROOT_PATH."Upload/update/".str_replace('.zip','',$updatefile));
    	$this->success('更新完成');
	}
	protected function getsign($data,$apikey){
		unset($data['username']);
	    unset($data['sign']);
	    $param_keys = array_keys($data);
	    sort($param_keys);
		foreach($param_keys as $k=>$v){
			$signpost.=$v.'='.urlencode($data[$v])."&";
		}
		$signpost .= 'apikey=' . $apikey;
		$sign = md5($signpost);
		return $sign;
	}
	protected function send($url,$method,$post,$username,$apikey){
		$post['random']=randstr(20);
		foreach($post as $key=>$value){
			$posturl.=$key.'='.urlencode($value)."&";
		}
		$sign=$this->getsign($post,$apikey);
		$posturl .= 'sign=' .$sign;
		$posturl=$posturl."&username=".$username;
		$host=$url.'/'.$method;
		$ch = curl_init($host);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_TIMEOUT,60);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $posturl);
		$resp = curl_exec($ch);
		$error = curl_error($ch);
		if($error){
	        return json_decode('{"status": "failed", "info": '.$error.'}', true);
		}
		curl_close($ch);
		return json_decode($resp, true);
	}
    public function index(){
    	//登录信息
    	//IP
//    	$login_info = M("admin_log")->where("u_id = ".session(C('USER_AUTH_KEY')))->max('log_create');
    	$login_info = M("admin_log")->where("u_id = ".session(C('USER_AUTH_KEY')))->order("id desc")->find();
    	$this->assign('last_ip',$login_info['log_ip']);
    	$this->assign('last_time',$login_info['log_create']);
    	
    	//总注册用户数量
    	$alluser = M("user")->count();
    	$this->assign('alluser',$alluser);
    	
    	//今天新用户注册
    	$today = strtotime(Date("Y-m-d",time()));
    	$tduser = M("user")->where("regtime >=".$today)->count();
    	$this->assign('tduser',$tduser);
    	
    	//今日充值入账
    	$tdolRecharge = M("money_log")->where(" addtime >=".$today." and isadd = 1 and type = 1 ")->sum("usermoney");
    	$tdolRecharge = empty($tdolRecharge)?0:$tdolRecharge;
    	$this->assign('tdolRecharge',$tdolRecharge);
    	
    	//后台入账
    	$tdbsRecharge = M("money_log")->where(" addtime >=".$today." and isadd = 1 and type = 2 ")->sum("usermoney");
    	$tdbsRecharge = empty($tdbsRecharge)?0:$tdbsRecharge;
    	$this->assign('tdbsRecharge',$tdbsRecharge);
    	
    	//今日开通云数据数量
    	$tdcloud = M("cloud")->where("starttime >=".$today)->count();
    	$this->assign('tdcloud',$tdcloud);
    	
    	//今日到期云主机
    	$todayend = $today+3600*24;
    	$tdendcloud = M("cloud")->where("endtime >=".$today." and endtime<".$todayend)->count();
    	$this->assign('tdendcloud',$tdendcloud);
    	
    	//今天工单数量
    	$tdsupport = M("support")->where("add_time >=".$today)->count();
    	$this->assign('tdsupport',$tdsupport);
    	
    	//未处理工单数量 3：已回复 5：已完成 9：已取消
    	$udsupport = M("support")->where(" status not in (3,5,9)")->count();
    	$this->assign('udsupport',$udsupport);
    	
		$this->display();
	}
}
?>