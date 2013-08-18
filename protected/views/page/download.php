<link rel="stylesheet" type="text/css" href="<?=Yii::app()->request->baseUrl?>/css/post.css" media="screen, projection">
<link rel="stylesheet" type="text/css" href="<?=Yii::app()->request->baseUrl?>/css/widget.css" media="screen, projection">
<style type="text/css">
#download-list p {
	font-weight: bold;
}
#download-list .download-meta {
	font-weight: normal;
	font-size: 12px;
	color: #999;
}

</style>

<div class="container">


<h1 id="site-title">
	<span><a href="/" title="小宇宙百科" rel="home">
	<img src="/images/logo.png" alt="小宇宙百科">
	</a></span>
</h1>
<div class="row">
	<div class="span8">


<article class="post detail hentry radius-medium shadow-large">
	<h1 class="entry-title">下周小百科下载</h1>

	<p>&nbsp;</p>

	<div class="entry-content">

		<h4>如何成为转发者</h4>
		<? if ( isGuest() ) { ?>
			<p>请先在 <a href="/register" target="_blank">这个页面</a> 注册哈~</p>
		<? } elseif ( !can('contribute', 'download') ) { ?>
			<p>您现在还没有下载权限哟 ，请到 <a href="/user/apply/forward">申请页面</a> 申请，委员会会尽快通过~</p>
		<? } else { ?>
			<p>恭喜您！已经有下载权限啦~</p>
			<p>请点击下面的链接直接下载吧~</p>
		<? } ?>
		<hr />

		<h4>如何转发</h4>
		<p>我们写了一个教程，新浪ishare下载：</p>
		<p><a title="新浪ishare下载" href="http://ishare.iask.sina.com.cn/f/24411507.html" target="_blank">飞信定时短信_教程(v1.2).pdf</a></p>
		<!--本站下载：<br />
		<a href="/download/?id=5">飞信定时短信_教程(v1.2).pdf</a></p>-->
		<hr>

		<h4>下周小百科下载列表</h4>
		<ul id="download-list">
		<? foreach ($files as $file) { ?>
			<li><p>
				<a href="/download/?id=<?=$file->ID?>"><?=$file->title?></a><br>
				<span class="download-meta">
					<a href="/author/<?=$file->uploader->login?>"><?=$file->uploader->nick_name?></a>
					上传于 <?=timeFormat($file->created, "full")?>
					(已下载 <?=$file->hits?> 次)
					<? if ( can('contribute', 'enqueue') ) { ?>
					<a href="/admin/file/log/<?=$file->ID?>">下载记录</a>
					<? } ?>
				</span>
			</p></li>
		<? } ?>
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