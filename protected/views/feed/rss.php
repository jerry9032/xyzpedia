<?

Yii::import("ext.RSS");

$site_title = "小宇宙百科";
$site_link = "http://xyzpedia.org/";
$site_description = "小宇宙百科是一个以手机飞信为媒介，以友谊为纽带，一个完全免费、共享的知识驿站。小百科工作室每周提供一组百科知识，由志愿者通过飞信的定时短信功能将百科知识分享给自己的朋友。所有百科条目由志愿者投稿，工作室采用编辑后发行。";
$site_img = "http://xyzpedia.org/images/logo.jpg";

$rss = new RSS($site_title, $site_link, $site_description, $site_img);

foreach ($posts as $post) {
	//AddItem($title, $author, $link, $description, $category, $pubDate)
	$post_title = $post->title;
	$post_author = '';
	foreach ($post->author as $author) {
		$post_author.= $author->nick_name . " ";
	}
	foreach ($post->author_no_reg as $author) {
		$post_author.= $author->author_name . " ";
	}
	$post_link = $site_link . timeFormat($post->created, "path").
				"/" . $post->slug;
	$post_description = postFilter($post->content);
	$post_category = $post->category->name;
	$post_pubdate = timeFormat($post->created, 'rfc');

	$rss->AddItem($post_title, $post_author, $post_link,
				$post_description, $post_category, $post_pubdate);
}

$rss->Display();

?>