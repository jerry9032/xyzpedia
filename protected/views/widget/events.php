<aside id="categories" class="widget well">
	<h1 class="widget-title">所有活动通告</h1>
	<ul><?

	$criteria = new CDbCriteria;
	$criteria->condition = 'type="event" and status="publish" and `show`>=2';
	$criteria->order = 'id desc';
	$events = Post::model()->findAll($criteria);

	foreach ($events as $event) { ?>
		<li><a href="/event/<?=$event->slug?>"><?=$event->title?></a></li>
	<? } ?>
	</ul>
</aside>