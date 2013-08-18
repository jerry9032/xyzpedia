<li <?=($more? 'class="more more'.$contrib->author->id.'"':'')?>>
	<button class="btn btn-small btn<?=(isset($style)? $style[$contrib->color]: "")?> load-content" contrib-id="<?=$contrib->id?>" id="contrib-<?=$contrib->id?>"><?=$contrib->title?></button>
	<div class="contrib-meta">
		[<a href="/contribute/list/category/<?=$contrib->category->id?>" target="_blank"><?=$contrib->category->name?></a>]
		[<a href="/author/id/<?=$contrib->author->id?>" target="_blank"><?=$contrib->author->name?></a>]
		<? if (!empty($contrib->reason)) { ?>
		<span rel="popover" data-original-title="高亮原因" data-content="<?=$contrib->reason?>"><i class="icon-question-sign icon-blue" style="margin-top:2px;"></i></span>
		<? } ?>
		<div class="pull-right"><?
			if (isset($nums)) {
				$num = $nums[$contrib->author->id];
				if ($num >= 2) { ?>
				<a href="#" class="load-more" uid="<?=$contrib->author->id?>" except="<?=$contrib->id?>" num="<?=($num-1)?>">(+<?=($num-1)?>)</a><?
				}
			} ?>
			(<a href="/contribute/id/<?=$contrib->id?>/edit" target="_blank">修改</a>)
		</div>
	</div>
</li>