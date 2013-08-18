<aside id="recent-posts" class="widget well">
	<h1 class="widget-title">近期文章</h1>
	<ul><?
		$criteria = new CDbCriteria;
		$criteria->condition = "type='post' and status='publish' and created<".gmtTime();
		$criteria->limit = get_option("index_recentpost_num");
		$criteria->order = "ID desc";
		$posts = Post::model()->findAll($criteria);
		foreach ($posts as $post) { ?>
			<li><a href="<?=date("/Y/m/",$post->created).$post->slug?>">
				<?=$post->title?></a>
			</li>
		<? } ?>
	</ul>
</aside>