<legend><h4>文件下载记录</h4></legend>

<?

$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
	'template' => '{summary}{pager}{items}{summary}{pager}',
	'columns'=>array(
		array(
			'name' => 'id',
			'header' => 'ID',
			'value' => '$data->id',
		),
		array(
			//'name' => 'download_id',
			'header' => '文件标题',
			'value' => '$data->download->title',
		),
		array(
			'name' => 'user_id',
			'header' => '下载用户',
			'type' => 'raw',
			'value' => '"<a href=\"/author/".$data->user->login."\">".$data->user->nick_name."</a>"',
		),
		array( 
			'name'=>'created',
			'header' => '下载时间',
			'value'=>'timeFormat($data->created, "full")',
		),
		// array(
		// 	'class'=>'CButtonColumn',
		// ),
	),
	'cssFile' => false,
	'itemsCssClass' => 'table table-bordered table-striped',
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
