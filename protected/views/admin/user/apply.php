<legend>
	<h4>所有要处理的申请</h4>
</legend>
<?
$user_str = '"<a href=\"/author/".$data->req_user->login."\">"
.$data->req_user->nick_name."</a>"';

$type_str = 'Yii::t("admin", ucfirst($data->type))';

$status_str = '"待审"';

$info_str = '($data->type=="fwd")?("下线人数".json_decode($data->info)->num.
"，发送时间".json_decode($data->info)->time):(json_decode($data->info)->msg)';

$handle_str = '"<button class=\"btn btn-success btn-mini\" req-type=\"".$data->type."\" rel=\"".$data->id."\">
<i class=\"icon-ok icon-white\"></i> 通过</button>
<button class=\"btn btn-danger btn-mini\">
<i class=\"icon-remove icon-white\"></i> 驳回</button>"';

$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider' => $dataProvider,
	'template' => '{summary}{pager}{items}{summary}{pager}',
	'summaryText' => '第 {start}-{end} 条，共 {count} 条',
	'columns'=>array(
		array(	'name' => 'id',			'header' => '申请ID',
				'value' => '$data->id',
		),
		array(	'name' => 'req_user_id','header' => '申请用户',
				'type' => 'raw',		'value' => $user_str,
		),
		array(	'name' => 'type',		'header' => '类型',
				'value' => $type_str,
		),
		array(	'name' => 'status',		'header' => '状态',
				'value' => $status_str,
		),
		array(	'name' => 'info',		'header' => '信息',
				'value' => $info_str,
		),
		array(	'name' => 'handle',		'header' => '操作',
				'type' => 'raw',		'value' => $handle_str,
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

<script type="text/javascript" src="/js/admin/user/apply.js"></script>