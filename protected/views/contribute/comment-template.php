<li comment-id='<?=$c->ID?>'>
	<div class="info-wrap">
		<div class="avatar shadow-medium">
			<? $avatar = empty($c->author->avatar)? "default.jpg": $c->author->avatar; ?>
			<img class="radius-small" src='/images/avatar/<?=$avatar?>'/>
		</div>
		<div class="floor"><?=($fid<0? "漂浮": vividFloor($fid))?></div>
	</div>

	<div class="content">
		<span class='comment-name'>
			<? if (isset($c->author) ) { ?>
				<a href='/author/<?=$c->author->login?>'><?=$c->author->nick_name?></a>
			<? } else { ?>
				dump($c);
			<? } ?>
		</span>

		<span class="comment-date">
			<?=timeFormat($c->created, "apart")?>
			<?=d($c->from)?>
		</span>
		
		<span class='comment'>
			<?=commentFilter($c['comment'])?>
			<? if ( $c->author->id != user()->id) { ?>
			<a href="javascript:;" rel="reply" nick-name="<?=$c->author->nick_name?>">回复</a>
			<? } ?>
			<? if ( $c->author->id == user()->id || isAdmin() ) { ?>
			<a class="red" href="javascript:;" rel="delete" comment-id="<?=$c->ID?>">删除</a>
			<? } ?>
		</span>


	</div>
	<div class='clearfix'></div>
</li>