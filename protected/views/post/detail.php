<link rel="stylesheet" type="text/css" href="/css/post.css" media="screen, projection">
<link rel="stylesheet" type="text/css" href="/css/widget.css" media="screen, projection">
<div class="container">


<h1 id="site-title">
	<span><a href="http://xyzpedia.org/" title="小宇宙百科" rel="home">
	<img src="/images/logo.png" alt="小宇宙百科">
	</a></span>
</h1>


<div class="row">
	<div class="span8">

<article class="post detail hentry radius-medium shadow-large" pid="<?=$post->id?>">
	<header class="entry-header">
		<h1 class="entry-title">
			<?=$post->title?>
			<? $cookie = Yii::app()->request->getCookies();
			$ck = $cookie['post'.$post->id];
			if ( isset($ck) && $ck->value == "lighted") { ?>
				<div class="light-bulb bulb-on" num="<?=$post->light?>"></div>
			<? } else { ?>
				<div class="light-bulb bulb-off" num="<?=$post->light?>"></div>
			<? } ?>
			<script src="/js/post/detail-light.js"></script>
		</h1>

		<? // compatible to wordpress's page
		$permalink = timeFormat($post->created, "path"). "/" . $post->slug;
		if ($post->type == "post") { ?>
		<div class="entry-meta">
			<a href="/<?=$permalink?>"><time class="entry-date"><?=timeFormat($post->created, "chinese");?></time></a> <?

			// registered author(s)
			$author_num = 0;
			foreach ($post->author as $author) {
				if ($author_num >= 1) {
					echo " & ";
				}
				?><a href='/author/<?=$author->login?>'><?=$author->nick_name?></a><? 
				$author_num++;
			}


			// unregister author(s)
			foreach ($post->author_no_reg as $author) {
				// distinguish unregister
				if ($author->author_id == 0) {
					if ($author_num >= 1) {
						echo " & ";
					}
					echo $author->author_name;
				}
				$author_num++;
			} ?> / 编

			<a href="/author/<?=$post->submit->login?>"><?=$post->submit->nick_name?></a> / 配图
		</div>
		<? } ?>


		<? if ( isset($post->category) ) { ?>
		<div class="entry-meta">分类：[<a href="/category/<?=$post->category->slug?>"><?=$post->category->name?></a>]</div>
		<? } ?>


		<? $this->renderPartial("share-button", array(
			"post" => $post,
			"permalink" => $permalink,
		));?>
	</header>

	<div class="entry-content">
		<? if (!empty($post->excerpt)){ ?>
		<blockquote class="excerpt">
			<p><?=$post->excerpt?></p>
		</blockquote>
		<? } ?>
		
		<?=postFilter($post->content)?>
	</div>
	<script type="text/javascript">
	(function($){
		$(function(){
			var oFa = document.createElement('div');
			$(oFa).attr('class','aligncenter');
			$('.entry-content img').wrap(oFa);
		})
	})(jQuery);
	</script>

	<? // edit button
	if ( !isGuest() && isset($post->submit_id) ) {
		$can_edit = (user()->id == $post->submit_id);
		$can_edit |= can("post", "updateall");
	} else {
		$can_edit = false;
	}

	if ($can_edit) { ?>
	<div style="margin-top: 20px;">
		<a href="/admin/post/edit/<?=$post->id?>" class="btn">编辑此文</a>
	</div>
	<? } ?>


	<? $this->renderPartial("share-button", array(
		"post" => $post,
		"permalink" => $permalink,
	));?>


	<!-- if registered author has description, show his/her info -->
	<? //foreach($post->author_info as $author_info) {
	for ($author_i = 0; $author_i < count($post->author); $author_i++) {
		if ( !empty($post->author_info[$author_i]->descript) ) { ?>
		<div id="author-info">
			<div class="author-avatar shadow-medium radius-medium">
				<a class="avatar" href="/author/<?=$post->author[$author_i]->login?>">
					<? $avatar = empty($post->author[$author_i]->avatar)? "default.jpg": $post->author[$author_i]->avatar; ?>
					<img src="/images/avatar/<?=$avatar?>"
						class="radius-medium" alt="<?$post->author[$author_i]->nick_name?>">
				</a>
			</div>
			<div id="author-description">
				<h2>编者
					<a href="/author/<?=$post->author[$author_i]->login?>">
						<?=$post->author[$author_i]->nick_name?>
					</a>
				</h2>
				<?=$post->author_info[$author_i]->descript?>
			</div>
		</div>
		<? }
	} ?>
	<p style="clear:both;"></p>


	<!--
	<ul class="pager">
		<li class="previous">
			<a href="#">&larr; “中国的银行体系”之商业银行</a>
		</li>
		<li class="next">
			<a href="#">已经是最新 &rarr;</a>
		</li>
	</ul>
	-->
</article>

<a name="comment"></a>
<link rel="stylesheet" href="/css/post/detail.css">
<article class="post detail hentry radius-medium shadow-large comments">
	<legend><h4>文章评论 (<?=count($comments)?>)</h4></legend>
	<ul class="comments-list">
	<? if ( !empty($comments) ) { ?>
		<? foreach ($comments as $comment) {
			$this->renderPartial("/post/comment-template", array(
				"c" => $comment
			));
		}?>
	<? } else { ?>
		<li id="no-comments">暂无评论。</li>
	<? } ?>
	</ul>

	<h5><i class="icon-comment"></i> 留下您的评论</h5>
	<? if ( isGuest() ) { ?>
		<p>还没有登录？请先 <a class="btn btn-primary btn-small" href="/login">登录</a> 。</p>
	<? } else { ?>
	<p><small>正在以 <a href="/author/<?=user()->login?>"><?=user()->nick_name?></a> 登录，
		<a href="/login">换个用户</a>？</small></p>
	<div class="add-comment">
		<input type="hidden" id="post_id" name="post_id" value="<?=$post->id?>">
		<input type="hidden" id="parent" name="parent" value="0">
		<textarea id="comment-box" name="comment-box"></textarea>
		<button class="btn btn-success btn-small" id="add-comment">
			<i class="icon-edit icon-white"></i> 添加评论
		</button>
		<img src="/images/spin-small.gif" id="loading">
	</div>
	<script type="text/javascript" src="/js/jquery.jgrow.js"></script>
	<script type="text/javascript">$("#comment-box").jGrow();</script>
	<script type="text/javascript" src="/js/post/detail.js"></script>
	<? } ?>
</article>


	</div><!-- end of span 8 -->

	<!-- sidebar widgets -->
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