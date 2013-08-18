<li class="ui-li ui-li-static ui-body-c">
	<h4 class="ui-li-heading"><?=$c->author->nick_name?></h4>
	<p class="ui-li-desc"><?=commentFilter($c->comment)?></p>
	<p class="ui-li-desc ui-li-meta"><?=timeFormat($c->created, "apart")?> <?=d($c->from)?></p>
</li>