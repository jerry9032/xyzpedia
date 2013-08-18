<? $iter = 0;
$post = $posts[$iter]; ?>
<div data-role="content"><?

if ($page == 1) {
	$start = 1; $end = 9; ?>

	<? $permalink = "/".timeFormat($post->created, 'path')."/".$post->slug; ?>
	<h3 id="today-header"><?=$post->title?></h3>
	<a href="<?=$permalink?>" data-ajax="false" rel="external">
		<div style="text-align:center">
			<img class="radius-large shadow-large" id="today-thumbnail" style="width: 100%;" src="/images/<?=$post->thumbnail?>" />
		</div>
	</a>
	<p id="today-excerpt" style="font-size: 16px; margin-bottom: 40px;"><?=$post->excerpt?></p><?


} else { 
	$start = 0; $end = 9; 
} ?>

	<h3 id="history-header">历史词条</h3>
	<ul id="history-item" data-role="listview" data-divider-theme="a" data-inset="true">
	<? for($iter = $start; $iter <= $end; $iter++) {
		$post = $posts[$iter];
		$permalink = "/".timeFormat($post->created, 'path')."/".$post->slug; ?>
		<li data-theme="c">
			<a href="<?=$permalink?>" data-transition="slide">
				<?=$post->title?>
			</a>
		</li>
	<? } ?>
	</ul>
</div>
