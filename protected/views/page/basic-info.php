<link rel="stylesheet" type="text/css" href="<?=Yii::app()->request->baseUrl?>/css/post.css" media="screen, projection">
<link rel="stylesheet" type="text/css" href="<?=Yii::app()->request->baseUrl?>/css/widget.css" media="screen, projection">


<div class="container">


<h1 id="site-title">
	<span><a href="/" title="小宇宙百科" rel="home">
	<img src="/images/logo.png" alt="小宇宙百科">
	</a></span>
</h1>
<div class="row">
	<div class="span8">


<article class="post detail hentry radius-medium shadow-large">
	<h1 class="entry-title">“小宇宙百科”基本信息</h1>

	<p>&nbsp;</p>

	<div class="entry-content">

<p>有人说我是梦想家，但我坚信我不是，无论如何，人应该努力成为他命中注定要成为的那个人。</p>

<p>小宇宙百科始于2008年12月27日，是一个以手机飞信为工具，以友谊为纽带，一个完全免费、共享的知识驿站。小百科工作室每周提供一组百科知识，由志愿者通过飞信的定时短信功能将百科知识分享给自己的朋友。所有百科条目由志愿者投稿，工作室录用编辑以后发行。</p>

<p>共享知识，分享生活</p>
<p>Stay Hungry, Stay Foolish</p>

<p>联系：<a href="mailto:edit.xyzpedia@gmail.com">edit.xyzpedia@gmail.com</a></p>

<hr>

<h4>平台</h4>

<p>小宇宙百科官方网站：<a href="http://xyzpedia.org/">http://xyzpedia.org/</a></p>
<p>小宇宙百科人人公共主页：<a href="http://page.renren.com/601006672">http://page.renren.com/601006672</a></p>
<p>小宇宙百科人人小站：<a href="http://zhan.renren.com/xyzpedia">http://zhan.renren.com/xyzpedia</a></p>
<p>小宇宙百科轻微博：<a href="http://qing.sina.com.cn/xyzbaike">http://qing.sina.com.cn/xyzbaike</a></p>
<p>小宇宙百科微博：<a href="http://weibo.com/xyzbaike">http://weibo.com/xyzbaike</a></p>
<p>小宇宙百科豆瓣小站：<a href="http://site.douban.com/130996/">http://site.douban.com/130996/</a></p>



<hr>

<h4>QQ群</h4>
<ul>
<li><p>-杭州地区：165405934</p></li>
<li><p>-北京地区：168734692</p></li>
<li><p>-南京地区：209952309</p></li>
<li><p>-上海地区：167566007</p></li>
<li><p>-其他地区：125561399</p></li>
</ul>

	</div>
</article>



	</div><!-- end of span 8 -->


	<!-- side bar widgets -->
	<div class="span4">
		<?
		$widgets = explode(",", $widgets);
		foreach ($widgets as $widget) {
			$this->renderPartial('/widget/'.$widget);
		}
		?>
	</div>
</div>
</div>