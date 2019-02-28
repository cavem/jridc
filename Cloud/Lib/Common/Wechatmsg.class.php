<?php
class Wechatmsg{
	//用户发送的文本消息，自动回复
	public function msgs($Eventkeyword,$fromUsername,$toUsername){
		$reply_rule = M("reply_rule")->where(" keyword like '%".$Eventkeyword."%'")->find();
		switch ($reply_rule['type']){
			case 1://回复文本信息
				$reply_news = M("reply_news")->where(" rid = ".$reply_rule['id'])->find();
				$contentStr = $reply_news['content'];
				$resultStr = sprintf($this->tpltext(), $toUsername, $fromUsername, time(), $contentStr, 0);
				break;
			case 2://回复图文信息信息
				$reply_news = M("reply_news")->where(" rid = ".$reply_rule['id'])->find();
				$title = $reply_news['title'];
				$disc = $reply_news['disc'];
				$content = $reply_news['content'];
				$imgpath = "http://".$_SERVER['HTTP_HOST'].$reply_news['img'];
				$url ;
				if($reply_news['url']){//如果图文消息设置了连接地址 跳转到指定地址
					$url = $reply_news['url'];
				}else{//如果没有设置连接地址 跳转到图文详情
					$url = "http://".$_SERVER['HTTP_HOST']."/index.php/wechat/web/newsdetail/id/".$reply_news['id'];
				}
				$resultStr = sprintf($this->tplnews(),$toUsername,$fromUsername,time(),1,$title,$disc,$imgpath,$url,0);
				break;
			case 3://回复音乐信息
				$resultStr = sprintf($this->tplmusic(), $toUsername, $fromUsername, time(), $msgType, $contentStr);
				break;
			case 4://回复图片信息
				$resultStr = sprintf($this->tplimages(), $toUsername, $fromUsername, time(), $msgType, $contentStr);
				break;
			default://没有检索到回复规则时
				$resultStr = sprintf($this->tpltext(), $toUsername, $fromUsername, time(), 'what are you 弄啥嘞?', 0);
				break;
		}
		return $resultStr;
	}
	
	//用户事件操作，自动回复
	public function handleEvent($object){
		$m_user = M("weixin_user");
		$m_fans = M("weixin_fans");
		$contentStr = "";
		switch ($object->Event){
			case "subscribe"://首次关注
				//推送首次关注信息
				$wconfig=D('weixin_config')->where(array('id'=>1))->find();
				$contentStr = $wconfig['replycontext'];
				$contentStr = sprintf($this->tpltext(), $object->FromUserName, $object->ToUserName, time(), $contentStr, 0);
				//修改粉丝状态为已关注 
				$data_fans = array(
					"subscribe_time"	=>	time(),
					"upd_time"	=>	time(),
					"subscribe"	=>	1
				);
				$m_user->where("wxid = '".$object->FromUserName."' and wechat_id = '".$object->ToUserName."'")->save($data_fans);
				$m_fans->where("open_id = '".$object->FromUserName."'")->save($data_fans);
				Log::write("m_user sql".$m_user->getLastSql());
				break;
			case "unsubscribe"://取消关注
				$arr_f = array(
					"subscribe"=>0,
					"upd_time"=>time(),
				);
				$m_user->where("wxid = '".$object->FromUserName."' and wechat_id = '".$object->ToUserName."'")->save($arr_f);
				$m_fans->where("open_id = '".$object->FromUserName."'")->save($arr_f);
				break;
			case "VIEW"://点击菜单 访问菜单绑定网址 [这种情况，系统是不会向用户回复信息的]
				$contentStr = "点击菜单跳转链接时的事件推送 连接地址为：".$object->EventKey;
				break;
			case "CLICK"://点击菜单 触发回复关键词
				$contentStr = $this->msgs($object->EventKey, $object->ToUserName, $object->FromUserName);
				break;
			default :
				$contentStr = "Unknow Event: ".$object->Event;
				break;
		}
		return $contentStr;
	}
	
	private function tpltext(){
		$textTpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA[%s]]></Content>
                <FuncFlag>%d</FuncFlag>
                </xml>";
		return $textTpl;
	}
	private function tplnews(){
		$newsTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[news]]></MsgType>
					<ArticleCount>%d</ArticleCount>
					<Articles>
					<item>
					<Title><![CDATA[%s]]></Title> 
					<Description><![CDATA[%s]]></Description>
					<PicUrl><![CDATA[%s]]></PicUrl>
					<Url><![CDATA[%s]]></Url>
					</item>
					</Articles>
					<FuncFlag>%d</FuncFlag>
					</xml>";
		return $newsTpl;
	}
	private function tplmusic(){//内容为复制images的 需修改
		$imageTpl = "<xml>
						 <ToUserName><![CDATA[%s]]></ToUserName>
						 <FromUserName><![CDATA[%s]]></FromUserName>
						 <CreateTime>%s</CreateTime>
						 <MsgType><![CDATA[%s]]></MsgType>
						 <ArticleCount>%s</ArticleCount>
						 <Articles>
						 %s
						 </Articles>
						 <FuncFlag>0</FuncFlag>
						 </xml>";
		return $imageTpl;
	}
	private function tplimages(){
		$imageTpl = "<xml>
						 <ToUserName><![CDATA[%s]]></ToUserName>
						 <FromUserName><![CDATA[%s]]></FromUserName>
						 <CreateTime>%s</CreateTime>
						 <MsgType><![CDATA[%s]]></MsgType>
						 <ArticleCount>%s</ArticleCount>
						 <Articles>
						 %s
						 </Articles>
						 <FuncFlag>0</FuncFlag>
						 </xml>";
		return $imageTpl;
	}
}