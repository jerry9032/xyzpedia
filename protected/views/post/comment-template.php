<li>
	<div class="info-wrap">
		<div class="avatar shadow-medium radius-small">
			<? $avatar = empty($c->author->avatar)? "default.jpg": $c->author->avatar; ?>
			<img class="radius-small" src='/images/avatar/<?=$avatar?>'/>
		</div>
		<div class="floor"><?//=($fid<0? "漂浮": vividFloor($fid))?></div>
	</div>

	<div class="content">
		<span class='comment-name'>
			<? if (isset($c->author) ) { ?>
			<a href='/author/<?=$c->author->login?>'><?=$c->author->nick_name?></a>
			<? } else { ?>
			<?=$c->author_name?>
			<? } ?>
		</span>
		<span class="comment-date"><?=timeFormat($c->created, "apart")?></span>
		<br>
		<span class='comment'>
			<?=commentFilter($c->content)?>
			<? if ( !isGuest() ) { // 如果不是访客，再判断是否显示功能按钮 ?>
			<? if ( isset($c->author) && ($c->user_id != user()->id)) { ?>
			<a class="reply" name='<?=$c->author->nick_name?>' rel="<?=$c->id?>">回复</a>
			<? } ?>
			<? if ( ($c->user_id == user()->id) || isAdmin() ) { ?>
			<a class="delete red" rel="<?=$c->id?>">删除</a>
			<? } ?>
			<? } ?>
		</span>
		<? if (!empty($c->parent)) {
			$parent = $c->parent;//Comment::model()->findByPk($c->parent); ?>

			<div class="parent">
				<p class="parent-meta">引用 <span class="parent-name">
					<? if (isset($parent->author)) { ?>
					<a href="/author/<?=$parent->author->login?>">@<?=$parent->author->nick_name?></a>
					<? } else { ?>
					@<?=$parent->author_name?>
					<? } ?>
					</span> 的评论：
				</p>
				<p class="parent-comment"><?=commentFilter($parent->content)?></p>
			</div>
		<? } ?>
	</div>
	<div class='clearfix'></div>
</li>