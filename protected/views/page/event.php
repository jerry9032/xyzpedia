<link rel="stylesheet" type="text/css" href="/css/post.css" media="screen, projection">
<link rel="stylesheet" type="text/css" href="/css/widget.css" media="screen, projection">
<div class="container">


<h1 id="site-title">
	<span><a href="http://xyzpedia.org/">
	<img src="/images/logo.png">
	</a></span>
</h1>


<div class="row">
	<div class="span8">

<article class="post detail hentry radius-medium shadow-large">
	<header class="entry-header">
		<h1 class="entry-title"><?=$event->title?></h1>
		<? $permalink = "event/" . $event->slug; ?>
		<div class="entry-meta">
			<a href="/<?=$permalink?>"><time class="entry-date"><?=timeFormat($event->created, "chinese");?></time></a>
			<a href="/author/<?=$event->submit->login?>"><?=$event->submit->nick_name?></a> / 提交
		</div>
		<? $this->renderPartial("/post/share-button", array(
			"post" => $event,
			"permalink" => $permalink,
		)); ?>
	</header>

	<div class="entry-content">
		<? if (!empty($event->excerpt)){ ?>
		<blockquote class="excerpt">
			<p><?=$event->excerpt?></p>
		</blockquote>
		<? } ?>

		<div class="hidden" style="display:none"><img src="/images/<?=$event->thumbnail?>" /></div>
		<?=postFilter($event->content)?>
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

	<?
	// extra field
	if ( !empty($event->extra) ) {
		$objs = unserialize($event->extra);
		foreach ($objs as $obj) {
			$obj = (object)$obj;
			switch ($obj->type) {
				// handle vote, or called, poll
				case 'poll':
					$poll = PollQuestion::model()->findByPk($obj->id);
					if ( empty($poll) ) break;
					$this->renderPartial('poll', array(
						'poll' => $poll
					));
					break;
				default: break;
			}
		}
	}


	// edit button
	if ( isAdmin() ) { ?>
	<div style="margin-top: 20px;">
		<a href="/admin/page/edit/<?=$event->id?>" class="btn">编辑此通告</a>
	</div>
	<? } ?>


	<p style="clear:both;"></p>

</article>

<? $comments_num = count($comments); ?>
<a name="comment"></a>
<link rel="stylesheet" href="/css/post/detail.css">
<article class="post detail hentry radius-medium shadow-large comments">
	<legend><h4>通告评论 (<?=$comments_num?>)</h4></legend>
	<? $start = ($comments_num > 10)?  $comments_num-10: 0;
	$end = $comments_num-1; ?>

	<ul class="comments-list"><?

	$this->renderPartial('/post/load-more-template', array(
		'start' => $start,
		'end' => $end
	));
	if ( $comments_num > 0 ) {
		for($i = $start; $i <= $end; $i++ ) {
			$this->renderPartial("/post/comment-template", array(
				"c" => $comments[$i]
			));
		}
	} else { ?>
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
		<input type="hidden" id="post_id" name="post_id" value="<?=$event->id?>">
		<input type="hidden" id="parent" name="parent" value="0">
		<textarea id="comment-box" name="comment-box"></textarea>
		<button class="btn btn-success btn-small" id="add-comment">
			<i class="icon-edit icon-white"></i> 添加评论
		</button>
		<img src="/images/spin-small.gif" id="loading">
	</div>
	<script type="text/javascript" src="/js/jquery.jgrow.js"></script>
	<script type="text/javascript">$("#comment-box").jGrow();</script>
	<script type="text/javascript">pid = <?=$event->id?>;</script>
	<script type="text/javascript" src="/js/post/detail.js"></script>
	<? } ?>
</article>


	</div><!-- end of span 8 -->

	<!-- sidebar widgets -->
	<div class="span4">
		<? $this->renderPartial('/widget/events'); ?>
	</div>
</div>
</div>