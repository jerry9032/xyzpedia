<? $enqueue_this_week = ($arrange->status == "not yet")
	&& ($arrange->start_date > $now) && ($arrange->start_date < $now + 604800); ?>
<tr>
	<td><?=$arrange->ID?></td>
	<td><?=timeFormat($arrange->start_date - 2 * 86400, 'date')?></td>
	<td>
		<?=timeFormat($arrange->start_date, 'date')?> ~
		<?=timeFormat($arrange->start_date + 6 * 86400, 'date')?></td>
	<td>
		<span id="arrange<?=$arrange->ID?>">
			<a href='/author/<?=$arrange->user->login?>'><?=$arrange->user->nick_name?></a>
		</span>
		<? if ($enqueue_this_week) { ?>
		<a href="javascript:;" id="alter-enqueue-link" rel="<?=$arrange->ID?>">
			<i class="icon-pencil icon-blue pull-right" id="alter-enqueue"></i>
		</a>
		<? } ?>
	</td>
	<td>
	<? if ( $enqueue_this_week ) { ?>
		<button id="prompt-commettee" class="btn btn-small btn-warning" rel="<?=$arrange->user->id?>" aid="<?=$arrange->ID?>" hits="<?=$arrange->hits?>">
			<i class="icon-exclamation-sign icon-white"></i> 发送提醒
		</button>
	<? } ?>
	</td>
	<td><?=$arrange->status?></td>
</tr>