<? if ( $start > 0 ) { ?>
<li id="li-load-more"><a id="a-load-more" href="#"
	start="<?=($start>10? $start-10: 0)?>" end="<?=$start-1?>">载入更多评论…</a>
	<img src="/images/spin-small.gif" id='loading'/>
</li>
<? } ?>