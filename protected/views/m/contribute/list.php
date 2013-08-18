<link rel="stylesheet" href="/m/css/contribute-list.css">
<div data-role="content">

<h1>投稿列表
	<a href="/contribute/submit" data-role="button" data-mini="true" data-inline="true" data-theme="e" data-icon="plus" data-iconpos="left" style="margin-top:0;">新稿件</a>
</h1>

<ul data-role="controlgroup" data-type="horizontal" class="localnav" data-theme="c"><?
	foreach( $scopeList as $scope) { ?>

	<li><a id="<?=$scope?>" href="/contribute/list/<?=$scope?>" data-role="button" <?=($scope==$type? "class='ui-btn-active'": "")?>>
		<span class="ui-btn-text"><?=Yii::t("contribute", ucfirst($scope))?></span></a>
	</li><?
	} ?>

</ul>

<ul data-role="controlgroup" data-type="horizontal" class="localnav" data-theme="c"><?
	foreach( $statusList as $status) { ?>

	<li><a id="<?=$status?>" href="/contribute/list/<?=$status?>" data-role="button" <?=($status==$type? "class='ui-btn-active'": "")?>>
		<span class="ui-btn-text"><?=Yii::t("contribute", ucfirst($status))?></span></a>
	</li><?
	} ?>

</ul>

<p>&nbsp;</p>
<ul data-role="listview">
	<? foreach ($dataProvider->data as $contrib) { ?>
	<li><a href="/contribute/id/<?=$contrib->id?>">
		<?=$contrib->title?>
		<span class="ui-li-count">
			<?=Yii::t("contribute", ucfirst($contrib->status))?>
		</span>
		</a>
	</li>
	<? } ?>
</ul>

</div>