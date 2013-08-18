<legend><h4>定时任务管理</h4></legend>


<?

function cron_time($time_int) {

	$hour = (int)($time_int / 3600);
	$minute = ($time_int % 3600) / 60;
	$time_str = "";

	$time_str.= ($hour <= 9)? '0'.$hour: $hour;
	$time_str.= ': ';
	$time_str.= ($minute <= 9)? '0'.$minute: $minute;

	return $time_str;
}

$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
	'template' => '{summary}{items}{summary}',
	'columns'=>array(
		array(
			'name' => 'id',
			'header' => 'ID',
			'value' => '$data->id',
		),
		array(
			'name' => 'day',
			'header' => '日期',
			'value' => 'Yii::t("admin/setting", $data->day)',
		),
		array(
			'name' => 'time',
			'header' => '预定时间',
			'value' => 'cron_time($data->time)',
		),
		array(
			'name' => 'type',
			'header' => '类型',
			'value' => 'Yii::t("admin/setting", $data->subtype).Yii::t("admin/setting", $data->type)',
		),
		array(
			'name' => 'action',
			'header' => '动作',
			'value' => 'Yii::t("admin/setting", $data->action . "cron")',
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
