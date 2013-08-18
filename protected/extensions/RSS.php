<?php
class RSS
{
	// RSS频道名
	protected $channel_title = '';
	// RSS频道链接
	protected $channel_link = '';
	// RSS频道描述
	protected $channel_description = '';
	// RSS频道使用的小图标的URL
	protected $channel_imgurl = '';
	// RSS频道所使用的语言
	protected $language = 'zh_CN';
	// RSS文档创建日期，默认为今天
	protected $pubDate = '';
	protected $lastBuildDate = '';

	protected $generator = 'XYZPedia RSS Generator';

	// RSS单条信息的数组
	protected $items = array();

	// 构造函数
	public function __construct($title, $link, $description, $imgurl = '')
	{
		$this->channel_title = $title;
		$this->channel_link = $link;
		$this->channel_description = $description;
		$this->channel_imgurl = $imgurl;
		$this->pubDate = Date('Y-m-d H:i:s', time());
		$this->lastBuildDate = Date('Y-m-d H:i:s', time());
	}
 
	public function Config($key,$value)
	{
		$this->{$key} = $value;
	}
 
	// 添加RSS项
	function AddItem($title, $author, $link, $description, $category, $pubDate)
	{
		$this->items[] = array(
			'title' => $title,
			'author' => $author,
			'link' => $link,
			'description' => $description,
			'category' => $category,
			'pubDate' => $pubDate
		);
	}
 
	 // 输出RSS的XML为字符串
	public function Fetch()
	{
		$rss = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\r\n";
		$rss = "<rss version=\"2.0\">\r\n";
		$rss .= "<channel>\r\n";
		$rss .= "<title><![CDATA[{$this->channel_title}]]></title>\r\n";
		$rss .= "<description><![CDATA[{$this->channel_description}]]></description>\r\n";
		$rss .= "<link>{$this->channel_link}</link>\r\n";
		$rss .= "<language>{$this->language}</language>\r\n";
 
		if (!empty($this->pubDate))
			$rss .= "<pubDate>{$this->pubDate}</pubDate>\r\n";
		if (!empty($this->lastBuildDate))
			$rss .= "<lastBuildDate>{$this->lastBuildDate}</lastBuildDate>\r\n";
		if (!empty($this->generator))
			$rss .= "<generator>{$this->generator}</generator>\r\n";
 
		$rss .= "<ttl>5</ttl>\r\n";
 
		if (!empty($this->channel_imgurl)) {
			$rss .= "<image>\r\n";
			$rss .= "<title><![CDATA[{$this->channel_title}]]></title>\r\n";
			$rss .= "<link>{$this->channel_link}</link>\r\n";
			$rss .= "<url>{$this->channel_imgurl}</url>\r\n";
			$rss .= "</image>\r\n";
		}
 
		for ($i = 0; $i < count($this->items); $i++) {
			$rss .= "<item>\r\n";
			$rss .= "<title><![CDATA[{$this->items[$i]['title']}]]></title>\r\n";
			$rss .= "<author><![CDATA[{$this->items[$i]['author']}]]></author>\r\n";
			$rss .= "<link>{$this->items[$i]['link']}</link>\r\n";
			$rss .= "<guid>{$this->items[$i]['link']}</guid>\r\n";
			$rss .= "<description><![CDATA[{$this->items[$i]['description']}]]></description>\r\n";
			$rss .= "<category><![CDATA[{$this->items[$i]['category']}]]></category>\r\n";
			$rss .= "<pubDate>{$this->items[$i]['pubDate']}</pubDate>\r\n";
			$rss .= "<comments>{$this->items[$i]['link']}#comment</comments>\r\n";
			$rss .= "</item>\r\n";
		}
 
		$rss .= "</channel>\r\n</rss>";
		return $rss;
	}
 
	// 输出RSS的XML到浏览器
	public function Display()
	{
		header("Content-Type: text/xml; charset=utf-8");
		echo $this->Fetch();
		exit;
	}
}
?>