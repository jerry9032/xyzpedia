<form class="form-horizontal">
<legend><h4><?=Yii::t("user", "At me")?></h4></legend>
<fieldset>
	<div class="info-wrap">
	<h6>只显示最新10条</h6>

	<? if ($scope == "post") { ?>
	<ul class="nav nav-tabs">
		<li class="active">
			<a href="/user/info/atme/post">在文章评论中艾特</a>
		</li>
		<li>
			<a href="/user/info/atme/contribute">在稿件评论中艾特</a>
		</li>
	</ul>
	<ul id="comment-list">
	<? foreach ($comments as $comment) { ?>

		<? if ( isset($comment->post) ) { ?>
		<li>
			<? $avatar = empty($comment->author->avatar)? "default.jpg": $comment->author->avatar;?>
			<div class="avatar radius-small shadow-small">
				<img class="radius-small shadow-medium" src="/images/avatar/<?=$avatar?>">
			</div>
			<div class="comment">
				<p class="comment-meta"><a href="/author/<?=$comment->author->login?>">
					<?=$comment->author->nick_name?></a> 回复于
					<a href="/<?=timeFormat($comment->post->created, "path")?>/<?=$comment->post->slug?>">
					<?=$comment->post->title?></a>
					<span class="comment-date"><?=timeFormat($comment->created)?></span>
				</p>
				<p class="comment-content"><?=commentFilter($comment->content)?></p>
			</div>
		</li>
		<? } ?>
	<? } ?>
	</ul>

	<? } elseif ($scope == "contribute") { ?>
	<ul class="nav nav-tabs">
		<li>
			<a href="/user/info/atme/post">在文章评论中艾特</a>
		</li>
		<li class="active">
			<a href="/user/info/atme/contribute">在稿件评论中艾特</a>
		</li>
	</ul>
	<ul id="comment-list">
	<? foreach ($comments as $comment) { ?>

		<? if ( isset($comment->contrib) ) { ?>
		<li>
			<? $avatar = empty($comment->author->avatar)? "default.jpg": $comment->author->avatar;?>
			<div class="avatar radius-small shadow-small">
				<img class="radius-small shadow-medium" src="/images/avatar/<?=$avatar?>">
			</div>
			<div class="comment">
				<p class="comment-meta"><a href="/author/<?=$comment->author->login?>">
					<?=$comment->author->nick_name?></a> 回复于
					<a href="/contribute/id/<?=$comment->contrib_id?>">
					<?=$comment->contrib->title?></a>
					<span class="comment-date"><?=timeFormat($comment->created)?></span>
				</p>
				<p class="comment-content"><?=commentFilter($comment->comment)?></p>
			</div>
		</li>
		<? } ?>
	<? } ?>
	</ul>
	<? } ?>

	</div>
</fieldset>
</form>