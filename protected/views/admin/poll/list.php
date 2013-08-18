<? $this->renderPartial("/common/fixed-info"); ?>

<legend>
	<h4>投票列表</h4>
</legend>

<? $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider' => $dataProvider,
	'template' => '{summary}{pager}{items}{summary}{pager}',
	'summaryText' => '第 {start}-{end} 条，共 {count} 条',
	'columns'=>array(
		array(
			'name' => 'id',
			'header' => 'ID',
			'value' => '$data->id',
		),
		array(
			'name' => 'title',
			'header' => '投票标题',
			'type' => 'raw',
			'value' => '"<a href=\"/admin/poll/edit/".$data->id."\">".$data->title."</a>"',
		),
		array(
			'name' => 'created',
			'header' => '创建于',
			'value' => 'timeFormat($data->created)',
		),
		array(
			'name' => 'expire',
			'header' => '过期',
			'value' => 'timeFormat($data->expire)',
		),
		array(
			'name' => 'votes',
			'header' => '人数/票数',
			'value' => '$data->voters. "/" . $data->votes',
		),
		array(
			'name'=>'title',
			'header' => '结果',
			'type' => 'raw',
			'value' => '"<a href=\"/admin/poll/result/".$data->id."\">结果</a>
						<a href=\"/admin/poll/detail/".$data->id."\">详情</a>"',
		),
	),
	'cssFile' => false,
	'itemsCssClass' => 'table table-bordered table-striped poll-list',
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