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

		$postStr = file_get_contents("/var/www/html/xyzpedia/assets/post.data");
		if ( empty($postStr) )
			return;

		$wx = new WeixinCallbackAPI();
		$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
		$fromUsername = $postObj->FromUserName;
		$toUsername = $postObj->ToUserName;
		$msgType = $postObj->MsgType;
		$msg = trim((string)$postObj->Content);

		if ($msgType == "text") {
			$is_party_signup_echo_on = (get_option("weixin_party_signup_echo_on") == "1");
			$party_signup_echo = get_option("weixin_party_signup_echo");
			if ($is_party_signup_echo_on && $this->_isSignUpMsg($msg)) {
				echo $wx->responseMsg($fromUsername, $toUsername, $msg, time(), $party_signup_echo);
			} else {
				$this->textQuery($fromUsername, $toUsername, $msg);
			}
		}
	}
	public function actionEcho()
	{
		$this->layout = false;

		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		file_put_contents("/var/www/html/xyzpedia/assets/post.data", $postStr);
		if ( empty($postStr) )
			return;

		$wx = new WeixinCallbackAPI();
		$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
		$fromUsername = $postObj->FromUserName;
		$toUsername = $postObj->ToUserName;
		$msgType = $postObj->MsgType;
		$msg = trim((string)$postObj->Content);

		if ($msgType == "text") {
			$is_party_signup_echo_on = (get_option("weixin_party_signup_echo_on") == "1");
			$party_signup_echo = get_option("weixin_party_signup_echo");
			if ($is_party_signup_echo_on && $this->_isSignUpMsg($msg)) {
				echo $wx->responseMsg($fromUsername, $toUsername, $msg, time(), $party_signup_echo);
			} else {
				$this->textQuery($fromUsername, $toUsername, $msg);
			}
		}
		if ($msgType == "event") {
			$this->eventQuery($fromUsername, $toUsername, (string)$postObj->EventKey);
		}
	}
	function textQuery($fromUsername, $toUsername, $keyword) {
		if (empty($fromUsername) || empty($toUsername) || empty($keyword))
			return;

		$wx = new WeixinCallbackAPI();
		$historyNum = (int) $keyword;
		if ( $historyNum >= 20081227 &&  $historyNum < 20991231 ) {
			$response = $wx->historyPost($fromUsername, $toUsername, $keyword, time());
			if (empty($response)) {
				echo $wx->responseMsg($fromUsername, $toUsername, $keyword, time(), "对不起，没有找到编号为 $keyword 的词条噢~");
			} else {
				echo $response;
			}
		} else {
			$response = $wx->historyPostByQuery($fromUsername, $toUsername, $keyword, time());
			if (empty($response)) {
				echo $wx->responseMsg($fromUsername, $toUsername, $keyword, time(), "对不起，没有找到关键词含有 $keyword 的词条噢~");
			} else {
				echo $response;
			}
		}
	}
	public function eventQuery($fromUsername, $toUsername, $event) {
		if (empty($fromUsername) || empty($toUsername) || empty($event)) {
			//echo $wx->responseMsg($fromUsername, $toUsername, $keyword, time(), "字段为空");
			return;
		}
		$wx = new WeixinCallbackAPI();

		if ($event == "V1_TODAY_ENTRY") {
			$keyword = time();
			$response = $wx->historyPost($fromUsername, $toUsername, $keyword, time());
			if (empty($response)) {
				echo $wx->responseMsg($fromUsername, $toUsername, $keyword, time(), "今天($keyword)还没有词条哦~");
			} else {
				echo $response;
			}
		} else if ($event == "V1_TODAY_AUTHOR") {
			$post = PostFinder::getHistoryPostByTimestamp(time());
			$authors = $post->author;
			$counter = 0;
			$items = "";
			$site = "http://xyzpedia.org";
			foreach($authors as $author) {
				$author_info = UserInfo::model()->findByPk($author->id);
				$title = "【今日作者】" . $author->nick_name;
				$excerpt = "作者简介：" . $author_info->descript;
				$postpic = $site. "/images/avatar/" . rawurlencode($author->avatar);
				$permalink = $site . "/author/id/" . $author->id;
				$item = $wx->buildNewsItemTpl($title, $excerpt, $postpic, $permalink);
				$items .= $item;
				if (++$counter > 10)
					break;
			}
			echo $wx->buildNewsTpl($fromUsername, $toUsername, $items, $counter, time());
		} else {
			switch($event) {
				case "V1_KEYWORD_SEARCH":
					$msg = get_option("weixin_keyword_search_echo"); break;
				case "V1_HOWTO_CONTRIBUTE":
					$msg = get_option("weixin_howto_contribute_echo"); break;
				case "V1_OFFLINE_PARTY":
					$party_on = (get_option("weixin_party_echo_on") == "1");
					if ($party_on) {
						$msg = get_option("weixin_party_echo");
					} else {
						$msg = "近期没有线下活动哟，请耐心等待~[愉快]";
					}
					break;
				case "V1_ABOUT_XYZPEDIA":
					$msg = get_option("weixin_about_xyzpedia_echo"); break;
			}
			echo $wx->responseMsg($fromUsername, $toUsername, NULL, time(), $msg);			
		}
	}

	private function _isSignUpMsg($msg) {
		$arr = preg_split("/[\s,.，+]+/", $msg);
		$regex = "/13[0-9]{9}|15[0|1|2|3|5|6|7|8|9]\d{8}|18[0|5|6|7|8|9]\d{8}/";
		$matches = array();
		foreach($arr as $key) {
			if (preg_match($regex, $key, $matches) && $matches[0] == $key) {
				return true;
			}
		}
		return false;
	}
}

class PostFinder
{
	public static function getPostsByNickName($nick_name) {
		$criteria = new CDbCriteria();
		$criteria->addCondition("author.nick_name='$nick_name'");
		$criteria->addCondition("type='post'");
		$criteria->addCondition("t.status='publish'");
		$criteria->order = "t.light desc";
		$posts = Post::model()->with("author")->findAll($criteria);
		return $posts;
	}
	public static function getPostsByKeyword($keyword) {
		$criteria = new CDbCriteria();
		$criteria->addCondition("content like '%$keyword%' or title like '%$keyword%'");
		$criteria->addCondition("type='post'");
		$criteria->addCondition("status='publish'");
		$criteria->order = "t.created desc";
		$posts = Post::model()->findAll($criteria);
		return $posts;
	}
	public static function getHistoryPostByDate($strTime) {
		$gmtTime = strtotime($strTime);
		return PostFinder::getHistoryPostByTimestamp($gmtTime);
	}
	public static function getHistoryPostByTimestamp($gmtTime) {
		$criteria = new CDbCriteria();
		$criteria->addCondition("created<" . ($gmtTime+43200));
		$criteria->addCondition("created>=" . ($gmtTime-43200));
		$criteria->order = "t.created";
		$post = Post::model()->find($criteria);
		if (!empty($post)) {
			return $post;
		}
		$criteria = new CDbCriteria();
		$criteria->addCondition("created<" . ($gmtTime-43200));
		$criteria->addCondition("created>=" . ($gmtTime-43200-86400));
		$post = Post::model()->find($criteria);
		return $post;
	}
}

class WeixinCallbackAPI
{
	function __construct() {
		$this->_newsTextTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <ArticleCount>%d</ArticleCount>
            <Articles>%s</Articles>
            </xml>";
		$this->_newsItemTpl = "<item>
            <Title><![CDATA[%s]]></Title>
            <Description><![CDATA[%s]]></Description>
            <PicUrl><![CDATA[%s]]></PicUrl>
            <Url><![CDATA[%s]]></Url>
            </item>";
		$this->_msgTextTpl = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[%s]]></MsgType>
			<Content><![CDATA[%s]]></Content>
			<FuncFlag>0</FuncFlag>
			</xml>";
	}

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
			return NULL;

		if ($historyNum > 1230307200)
			$post = PostFinder::getHistoryPostByTimestamp($keyword);
		else
			$post = PostFinder::getHistoryPostByDate($keyword);
		if (!empty($post)) {
			return $this->buildPosts($fromUsername, $toUsername, array($post), $time);
		}
		return NULL;
	}


	public function historyPostByQuery($fromUsername, $toUsername, $keyword, $time)
	{
		$posts = PostFinder::getPostsByNickName($keyword);
		if (!empty($posts)) {
			return $this->buildPosts($fromUsername, $toUsername, $posts, $time);
		}
		$posts = PostFinder::getPostsByKeyword($keyword);
		if (!empty($posts)) {
			return $this->buildPosts($fromUsername, $toUsername, $posts, $time);
		}
		return NULL;
	}

	public function buildPosts($fromUsername, $toUsername, $posts, $time) {
		$counter = 0;
		$items = "";
		foreach($posts as $post) {
			$baseurl = "http://xyzpedia.org";
			$title = empty($post->wxtitle) ? $post->title : $post->wxtitle;
			$excerpt = empty($post->wxexcerpt) ? $post->excerpt : $post->wxexcerpt;
			$postpic = empty($post->wxpic) ? $baseurl."/images/".$post->thumbnail : $post->wxpic;
			$permalink = empty($post->wxurl) ? $baseurl."/".timeFormat($post->created, "path")."/".$post->slug."?from=weixin" : $post->wxurl;
			$item = $this->buildNewsItemTpl($title, $excerpt, $postpic, $permalink);
			$items .= $item;
			if (++$counter >= 5) break;
		}
		return $this->buildNewsTpl($fromUsername, $toUsername, $items, $counter, $time);
	}

	public function buildNewsTpl($fromUsername, $toUsername, $items, $counter, $time) {
		return sprintf($this->_newsTextTpl, $fromUsername, $toUsername, $time, "news", $counter, $items);
	}

	public function buildNewsItemTpl($title, $excerpt, $postpic, $permalink) {
		return sprintf($this->_newsItemTpl, $title, $excerpt, $postpic, $permalink);
	}

	public function responseMsg($fromUsername, $toUsername, $keyword, $time, $message)
	{
		if(!empty( $message ))
		{
			$msgType = "text";
			return sprintf($this->_msgTextTpl, $fromUsername, $toUsername, $time, $msgType, $message);
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

	private $_msgTextTpl;
	private $_newsTextTpl;
	private $_newsItemTpl;
}
