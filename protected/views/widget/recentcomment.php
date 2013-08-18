<?
$criteria = new CDbCriteria;
$criteria->limit = (int) get_option("index_recentcomment_num");
$criteria->condition = "approved=1";
$criteria->order = "ID desc";
$comments = Comment::model()->findAll($criteria);
?>
<aside id="recent-comments" class="widget well">
	<h1 class="widget-title">近期评论</h1>
	<ul id="recentcomments">
	<?foreach ($comments as $comment) { ?>
		<li style="font-size: 13px;">
			<? if (isset($comment->author)) { ?>
				<a href="/author/<?=$comment->author->login?>"><?=$comment->author->nick_name?></a>
			<? } else {
				echo $comment->author_name;
			} ?> - 

			<? switch ($comment->post->type) {
				case 'post': ?>
					<a href="/<?=timeFormat($comment->post->created, "path")?>/<?=$comment->post->slug?>">
					<? break;

				case 'event': ?>
					<a href="/event/<?=$comment->post->slug?>">
					<? break;

				default:
					break;
			} ?>
			<?=$comment->post->title?></a>
		</li>
	<? } ?>
	</ul>
</aside>