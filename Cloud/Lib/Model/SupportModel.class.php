<?php
/**
 * 工单模板
 * Enter description here ...
 * @author Geyoulei
 */
class SupportModel extends Model{
	protected $tableName = 'support';

	/**
	 * 提醒管理员
	 * @param 工单编号 $sid
	 * @param 处理进度 $jd
	 */
	function sta($s_id,$jd=1){
		//获取工单的基本信息
		$rs = M("support")->where(array('id'=>$s_id))->find();
		if($rs){
			switch ($jd){
				case 1:
					$jdtxt = "新工单";
					break;
				case 2:
					$jdtxt = "追问";
					break;
			}
			//发送邮件提醒相关管理人员
			$email = C("support_email");//平台配置收件人
			$title = "214商务平台 ".$jdtxt.":".$rs['title']."";
			$content = $rs['content'];
			$result['mail']=D("Sendmail")->Send($email, $title, $content);
			//发送短信提醒
			$tel = C("support_tel");//平台配置收件人
			$result['tel'] = D("Sendsms")->Send($tel,$title);
			return $result;
		}
		return false;
	}
	/**
	 * 工单进度提醒
	 * @param $sid 工单编号
	 * @param $jd 进度：1正在处理 2已回复 3管理员关闭 4评价
	 * @param $jd 进度：1正在处理 2已回复 3管理员关闭 4评价
	 */
	function stc($sid,$jd=1,$uinfo,$admin_name){
		//获取用户选配
		$remind = M("support_remind")->where(array("user_id"=>$uinfo['user_id']))->find();
		if(empty($remind)){
			return false;
		}
		$w_rem = M("weixin_user")->where(array("uid"=>$uinfo['user_id'],"remind"=>1))->select();
		$remind['wechats'] = $w_rem;//绑定的微信帐号中 开启工单提醒的
		//获取工单的基本信息
		$sinfo = M("support")->where(array('s_no'=>$sid))->find();
		//如果勾选发送邮件 且 存在email
		if($remind['email'] && $uinfo['email']){
			$email = $uinfo['email'];//收件人
			switch ($jd){
				case 1://管理员已查看
					$title = "工单处理进度查看:".$admin_name."已查看您的工单:".$sinfo['title']."";
					$content = $admin_name."已查看您的工单，将会尽快给您处理并回复";
					break;
				case 2://已回复
					$title = "工单处理进度回复:".$admin_name."已回复您的工单:".$sinfo['title']."";
					$content = $admin_name."已回复您的工单，请及时查看。";
					break;
				case 3://管理员关闭工单
					$title = "工单处理进度关闭:您的工单[".$sinfo['title']."]已被".$admin_name." 关闭";
					$content = $admin_name."关闭了您的工单。如仍有疑问，请发起新的工单，或电话联系：0516-61888777。";
					break;
				case 4://评价
					$title = "工单处理进度评价:感谢您的评价:".$sinfo['title']."";
					$content = "非常感谢您的评价，您对我们的意见和建议，就是对我们最大的支持和帮助，感谢您留下宝贵的建议帮助我们改善服务品质！";
					break;
			}
			$result['email']=D("Sendmail")->Send($email, $title, $content);
		}
		//如果勾选发送短信 且 存在手机号
		if($remind['sms'] && $uinfo['mobi']){
			$tel = $uinfo['mobi'];//联系手机
			switch ($jd){
				case 1://管理员已查看
					$content = "工单处理进度查看:".$admin_name."已查看您的工单，将会尽快给您处理并回复";
					break;
				case 2://已回复
					$content = "工单处理进度回复:".$admin_name."已回复您的工单，请及时查看。";
					break;
				case 3://管理员关闭工单
					$content = "工单处理进度关闭".$admin_name."关闭了您的工单。如仍有疑问，请发起新的工单，或电话联系：0516-61888777。";
					break;
				case 4://评价
					$content = "工单处理进度评价 非常感谢您的评价，您对我们的意见和建议，就是对我们最大的支持和帮助，感谢您留下宝贵的建议帮助我们改善服务品质！";
					break;
			}
			$result['sms'] = D("Sendsms")->Send($tel,$content);
		}
		//如果勾选发送微信
		if($remind['wechats']){
			$template_id = "p6pedO7ih_-EZhItg9ALYi_XMqsqbULVvpXtKUkV3r4";//工单进度提醒模版消息编号
			switch ($jd){
				case 1://管理员已查看
//					$title = "查看:".$admin_name."已查看您的工单:".$sinfo['title'];
					$content = $admin_name."已查看您的工单，将会尽快给您处理并回复";
					$jdtxt = "管理员已查看";
					break;
				case 2://已回复
//					$title = "回复:".$admin_name."已回复您的工单:".$sinfo['title']."";
					$content = $admin_name."已回复您的工单，请及时查看。";
					$jdtxt = "已回复";
					break;
				case 3://管理员关闭工单
//					$title = "关闭:您的工单[".$sinfo['title']."]已被".$admin_name." 关闭";
					$content = $admin_name."关闭了您的工单。如仍有疑问，请发起新的工单，或电话联系：0516-61888777。";
					$jdtxt = "管理员关闭工单";
					break;
				case 4://完成评价
//					$title = "评价:感谢您的评价:".$sinfo['title']."";
					$content = "非常感谢您的评价，您对我们的意见和建议，就是对我们最大的支持和帮助，感谢您留下宝贵的建议帮助我们改善服务品质！";
					$jdtxt = "已完成评价";
					break;
			}
			$postdata = C($template_id);
			$postdata['first']['value'] = $sinfo['title'];//首行
			$postdata['keyword1']['value'] = $sinfo['s_no'];//工单号
			$postdata['keyword2']['value'] = $jdtxt;//工单进度
			$postdata['keyword3']['value'] = $admin_name;//工单处理人
			$postdata['remark']['value'] = $content;//备注内容
			$weixin_config=D('weixin_config')->where(array('id'=>1))->find();
			$wechat=new Wechat();
			$wechat->appid = $weixin_config['appid'];
			$wechat->appsecret = $weixin_config['appsecret'];
			//此处改为对多个用户发送微信模版消息
			foreach ($remind['wechats'] as $k=>$v){
				$result['wechat'][$k] = $wechat->sendTempMsg($v['wxid'], $template_id, $postdata,"#eee");
			}
		}
		return $result;
	}
}