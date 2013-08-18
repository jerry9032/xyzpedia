<legend><h4>用户信息列表</h4></legend>

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
			'name' => 'nick_name',
			'header' => '投稿署名',
			'value' => '$data->user->nick_name',
		),
		array(
			'name' => 'email',
			'header' => '电子邮件',
			'value' => '$data->email',
		),
		array(
			'name' => 'upline',
			'header' => '上线手机号',
			'value' => '$data->upline',
		),
		array(
			'name' => 'downline',
			'header' => '下线人数',
			'value' => '$data->downline',
		),
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
