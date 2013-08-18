<link rel="stylesheet" type="text/css" href="/css/contribute.css?ver=201304201247" />
<div class="container">

	<header class="subhead">
		<h1><?=Yii::t('contribute', "Contribute to XYZPedia"); ?></h1>
	</header>


<? if ( !empty($_GET['msg']) ) { ?>
<!-- display the message -->
<div class="alert alert-info alert-fixed">
	<strong><?=Yii::t("general", "Prompt: ")?></strong>
	<?
	$msg_id = $_GET['msg'];
	$msg = array(
		1 => "Delete contribution success.",
		//2 => "Submit contribution success.",
	);
	echo Yii::t("contribute", $msg[$msg_id]);
	?>
</div>
<? } ?>

<div class="row">
	<div class="span3">
		<? include "left.php" ?>
	</div>

	<div class="span9">

	
		<ul class="nav nav-pills">
		<? $allList = array_merge($scopeList, $statusList);

		foreach($allList as $k) {
		 	echo ($type == $k)? "<li class='active'>": "<li>"; ?>
		 	<a href='/contribute/list/<?=$k?>'><?=Yii::t('contribute', ucfirst($k))?></a>
		 	</li>
		<? } ?>
		</ul>

		<? if (isset($prompt)) { ?>
		<div class="alert alert-info"><?=$prompt?></div>
		<? } ?>

	<div id="the-list"><?


Yii::import("ext.WordsCount");
Yii::import("ext.XYZDate");

function title($item) {
	if ( !in_array($item->status, array("determined", "sent"))
		&& !empty($item->appoint) ) {
		$d = new XYZDate(gmtTime(), $item->created, $item->appoint);
		if ($d->appoint_this_week()) {
			return CHtml::link($item->title." - 紧急预约", "/contribute/id/" . $item->id, array("class" => "contrib-alert"));
		} else {
			return CHtml::link($item->title." - 预约", "/contribute/id/" . $item->id);
		}
	} else if ($item->status == "draft") {
		if ($item->author->id == user()->id) {
			return CHtml::link($item->title." - 未完", "/contribute/id/" . $item->id . "/edit", array("class" => "contrib-draft"));
		} else {
			return CHtml::link($item->title." - 未完", "/contribute/id/" . $item->id, array("class" => "contrib-draft"));
		}
	} else {
		return CHtml::link($item->title, "/contribute/id/".$item->id);
	}
}

$status_str =
'Yii::t("contribute", ucfirst($data->status))';

$author_str =
'(WordsCount::length($data->author->nick_name) > 6)?
	CHtml::link(
		WordsCount::substr($data->author->nick_name, 0, 6)."..",
		"/contribute/list/author/".$data->author_id
	): CHtml::link(
		$data->author->nick_name,
		"/contribute/list/author/".$data->author_id
	)';

$editor_str =
'isset($data->editor) ?
	CHtml::link(
		$data->editor->nick_name,
		"/contribute/list/editor/".$data->editor_id
	).(can("contribute", "assign") ?
		CHtml::link(
			"<i class=\"icon-blue icon-pencil\"></i>",
			"/admin/contribute/assign/".$data->id
	):""
	): (can("contribute", "assign") && ($data->status!="draft") ?
		CHtml::link(
			"(点击分配)",
			"/admin/contribute/assign/".$data->id
		): "(还没有)"
	)';

$category_str = '$data->category->short';
$length_str = '($length=WordsCount::length($data->content))."字(".WordsCount::parts($length)."条)"';
$comments_str = '$data->comments';
$created_str = 'timeFormat($data->created, "month")';

$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider' => $dataProvider,
	'template' => '{summary}{pager}{items}{summary}{pager}',
	'summaryText' => '第 {start}-{end} 条，共 {count} 条',
	'columns'=>array(
		array(	'name' => 'title',		'header' => '标题',
				'type' => 'raw',		'value' => 'title($data)'
		),
		array(	'name' => 'status',		'header' => '状态',
				'type' => 'raw',		'value' => $status_str
		),
		array(	'name' => 'author_id',	'header' => '作者',
				'type' => 'raw',		'value' => $author_str,
		),
		array(	'name' => 'editor_id',	'header' => '初审',
				'type' => 'raw',		'value' => $editor_str,
		),
		array(	'name' => 'category_id','header' => '分类',
				'type' => 'raw',		'value' => $category_str,
		),
		array(	'name' => 'length',		'header' => '长度',
				'type' => 'raw',		'value' => $length_str,
		),
		array(	'name' => 'comments',	'header' => '评论',
				'value' => $comments_str,
		),
		array(	'name'=>'created',		'header' => '时间',
				'value' => $created_str,
		),
	),
	'cssFile' => false,
	'itemsCssClass' => 'table table-bordered table-striped contrib-list',
	'pagerCssClass' => 'pagination pagination-right',
	'pager' => array(
		'cssFile' => false,
		'maxButtonCount' => 10,
		'prevPageLabel' => '上页',
		'nextPageLabel' => '下页',
		'firstPageLabel' => '«',
		'lastPageLabel' => '»',
		'header' => '',
		'selectedPageCssClass' => 'active',
	),
));
?>
	</div>



</div></div>
</div><!-- container -->
