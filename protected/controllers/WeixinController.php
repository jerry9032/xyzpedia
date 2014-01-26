<?php

define("TOKEN", "weixin");

class WeixinController extends Controller
{
	public function actionIndex()
	{
	}

	public function actionEchoTest()
	{
		$this->layout = false;

		$fromUsername = "shenjiale";
		$toUsername = "xyzpedia";
		$keyword = "20140125";
		$wx = new WeixinCallbackAPI();

		$keyword = date("Ymd", time());
		$ret = $wx->historyPost($fromUsername, $toUsername, $keyword, time());
		if ($ret != 0) {
			$wx->responseMsg($fromUsername, $toUsername, $keyword, time(),
				"今天($keyword)还没有词条哦~");
		}
	}
	public function actionEcho()
	{
		$this->layout = false;

		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		file_put_contents("/var/www/html/xyzpedia/assets/post.data", $postStr);
		if ( empty($postStr) )
			return;

		$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
		$fromUsername = $postObj->FromUserName;
		$toUsername = $postObj->ToUserName;
		$msgType = $postObj->MsgType;

		if ($msgType == "text") {
			$keyword = trim($postObj->Content);
			$this->textQuery($fromUsername, $toUsername, $keyword);
		}
		if ($msgType == "event") {
			$event = $postObj->EventKey;
			$this->eventQuery($fromUsername, $toUsername, $event);
		}
	}
	function textQuery($fromUsername, $toUsername, $keyword) {
		if (empty($fromUsername) || empty($toUsername) || empty($keyword))
			return;

		$wx = new WeixinCallbackAPI();
		$historyNum = (int) $keyword;
		if ( $historyNum >= 20081227 &&  $historyNum < 20991231 ) {
			$ret = $wx->historyPost($fromUsername, $toUsername, $keyword, time());
			if ($ret != 0) {
				$wx->responseMsg($fromUsername, $toUsername, $keyword, time(),
				"对不起，没有找到编号为 $keyword 的词条噢~");
			}
		} else {
			$ret = $wx->historyPostByQuery($fromUsername, $toUsername, $keyword, time());
			if ($ret != 0) {
				$wx->responseMsg($fromUsername, $toUsername, $keyword, time(),
				"对不起，没有找到关键词含有 $keyword 的词条噢~");
			}
		}
	}
	function eventQuery($fromUsername, $toUsername, $event) {
		if (empty($fromUsername) || empty($toUsername) || empty($event))
			return;
		$wx = new WeixinCallbackAPI();

		if ($event == "V1_TODAY_ENTRY") {
			$keyword = date("Ymd", time());
			$ret = $wx->historyPost($fromUsername, $toUsername, $keyword, time());
			if ($ret != 0) {
				$wx->responseMsg($fromUsername, $toUsername, $keyword, time(),
					"今天($keyword)还没有词条哦~");
			}
		}
		if ($event == "V1_KEYWORD_SEARCH") {
			$wx->responseMsg($fromUsername, $toUsername, NULL, time(), "小宇宙百科官方号即是一个移动百科小词典，输入关键词（如摄影、台湾）系统就会自动检索以往的小百科，帮助您随时随地掌握百科小知识。");			
		}
		if ($event == "V1_HOWTO_CONTRIBUTE") {
			$wx->responseMsg($fromUsername, $toUsername, NULL, time(), "小宇宙百科每一条词条都是由志愿者编辑整理而成，如果您也想与更多朋友分享您的所见、所识，贡献不一般的百科知识，欢迎登录：\n\n   http://xyzpedia.org/\n\n通过小宇宙百科，您可以将自己的智识传播给更多的人，也能认识更多志同道合的朋友。");
		}
		if ($event == "V1_ABOUT_US") {
			$wx->responseMsg($fromUsername, $toUsername, NULL, time(), "有人说我是梦想家，但我坚信我不是，无论如何，人应该努力成为他命中注定要成为的那个人。\n\n小宇宙百科始于2008年12月27日，是一个以新媒体为平台，以友谊为纽带，一个完全免费、共享的知识驿站。小百科工作室每天提供一条百科知识，通过微信、微博、网站等平台对外发布。所有百科条目由志愿者投稿，工作室录用编辑以后发行。\n\n共享知识，分享生活\n\nStay Hungry, Stay Foolish\n投稿,参与或建议，请登录http://xyzpedia.org\n\nQQ群\n- 杭州地区：165405934\n- 北京地区：168734692\n- 南京地区：209952309\n- 上海地区：167566007\n- 其他地区：125561399");
		}
	}
}


class WeixinCallbackAPI
{
	public function valid()
	{
		$echoStr = $_GET["echostr"];

		//valid signature , option
		if($this->checkSignature()){
			echo $echoStr;
			exit;
		}
	}

	public function historyPost($fromUsername, $toUsername, $keyword, $time)
	{
		$historyNum = (int)$keyword;
		if ($historyNum < 20081227)
			return -1;

		$gmtTime = strtotime($historyNum);
		$criteria = new CDbCriteria();
		$criteria->addCondition("created<" . ($gmtTime+43200));
		$criteria->addCondition("created>=" . ($gmtTime-43200));
		$post = Post::model()->find($criteria);
		if ( !empty($post)) {
			$this->buildNewsTpl($fromUsername, $toUsername, array(1 => $post), $time);
			return 0;
		}

		$criteria = new CDbCriteria();
		$criteria->addCondition("created<" . ($gmtTime-43200));
		$criteria->addCondition("created>=" . ($gmtTime-43200-86400));
		$post = Post::model()->find($criteria);
		if ( !empty($post)) {
			$this->buildNewsTpl($fromUsername, $toUsername, array(1 => $post), $time);
			return 0;
		}
		return -1;
	}


	public function historyPostByQuery($fromUsername, $toUsername, $keyword, $time)
	{
		$criteria = new CDbCriteria();
		$criteria->addCondition("author.nick_name='$keyword'");
		$criteria->addCondition("type='post'");
		$criteria->addCondition("t.status='publish'");
		$criteria->order = "t.light desc";
		$posts = Post::model()->with("author")->findAll($criteria);
		if ( !empty($posts) ) {
			$this->buildNewsTpl($fromUsername, $toUsername, $posts, $time);
			return 0;
		}

		$criteria = new CDbCriteria();
		$criteria->addCondition("content like '%$keyword%' or title like '%$keyword%'");
		$criteria->addCondition("type='post'");
		$criteria->addCondition("status='publish'");
		$criteria->order = "t.created desc";
		$posts = Post::model()->findAll($criteria);
		if ( !empty($posts) ) {
			$this->buildNewsTpl($fromUsername, $toUsername, $posts, $time);
			return 0;
		}

		return -1;
	}

	function buildNewsTpl($fromUsername, $toUsername, $posts, $time) {
		$textTpl = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[%s]]></MsgType>
			<ArticleCount>%d</ArticleCount>
			<Articles>%s</Articles>
			</xml>";

		$itemTpl = "<item>
			<Title><![CDATA[%s]]></Title>
			<Description><![CDATA[%s]]></Description>
			<PicUrl><![CDATA[%s]]></PicUrl>
			<Url><![CDATA[%s]]></Url>
			</item>";
		$items = "";
		$counter = 0;
		foreach($posts as $post) {
			$baseurl = "http://xyzpedia.org";
			$title = empty($post->wxtitle) ? $post->title : $post->wxtitle;
			$excerpt = empty($post->wxexcerpt) ? $post->excerpt : $post->wxexcerpt;
			$postpic = empty($post->wxpic) ? $baseurl."/images/".$post->thumbnail : $post->wxpic;
			$permalink = empty($post->wxurl) ? $baseurl."/".timeFormat($post->created, "path")."/".$post->slug."?from=weixin" : $post->wxurl;
			$item = sprintf($itemTpl, $title, $excerpt, $postpic, $permalink);
			$items .= $item;
			if (++$counter >= 5) break;
		}
		$response = sprintf($textTpl, $fromUsername, $toUsername, $time, "news", $counter, $items);
		echo $response;
	}

	public function responseMsg($fromUsername, $toUsername, $keyword, $time, $message)
	{
		$textTpl = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[%s]]></MsgType>
			<Content><![CDATA[%s]]></Content>
			<FuncFlag>0</FuncFlag>
			</xml>";
		if(!empty( $message ))
		{
			$msgType = "text";
			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $message);
			echo $resultStr;
		}
	}
		
	private function checkSignature()
	{
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];
				
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}

