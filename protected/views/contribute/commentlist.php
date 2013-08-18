<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/contribute.css" />
<div class="container">

	<header class="subhead">
		<h1><?=Yii::t('contribute', "Contribute to XYZPedia"); ?></h1>
	</header>


<div class="row">
	<div class="span3">
		<? include "left.php" ?>
	</div>

	<div class="span9">
		<form class="form-horizontal">
		<legend><h4><?=Yii::t("contribute", "Comments List")?></h4></legend>
		<fieldset>
		<div class="info-wrap">
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
							<a href="/contribute/id/<?=$comment->contrib->id?>">
							<?=$comment->contrib->title?></a>
							<span class="comment-date"><?=timeFormat($comment->created, "apart")?></span>
						</p>
						<p class="comment-content"><?=commentFilter($comment->comment)?></p>
					</div>
				</li>
				<? } ?>
			<? } ?>
		</ul>
		</div>
		</fieldset>
		</form>
	</div>

</div>
</div>
