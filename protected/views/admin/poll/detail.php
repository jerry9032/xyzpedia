<legend>
	<h4>投票详情 - <?=$poll->title?></h4>
</legend>

<? $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider' => $dataProvider,
	'template' => '{summary}{pager}{items}{summary}{pager}',
	'summaryText' => '第 {start}-{end} 条，共 {count} 条',
	'columns'=>array(
		array(
			'name' => 'user_id',
			'header' => '用户',
			'type' => 'raw',
			'value' => '($data->user_id == 0) ? "<span class=\"ip\">".$data->ip."</span>": $data->user->nick_name',
		),
		array(
			'name' => 'created',
			'header' => '在时间',
			'value' => 'timeFormat($data->created)',
		),
		array(
			'name' => 'answer_id',
			'header' => '投给了',
			'value' => '$data->answer->answer',
		),
		array(
			'name' => 'anonymous',
			'header' => '匿名',
			'value' => '$data->anonymous',
		),
		array(
			'name' => 'answer.votes',
			'header' => '得票',
			'value' => '$data->answer->votes',
		),
	),
	'cssFile' => false,
	'itemsCssClass' => 'table table-bordered table-striped poll-detail',
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

<style type="text/css">
.ip {
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
	background-color: #FCF8E3;
	border: 1px solid #FAA732;
	cursor: pointer;
}
</style>
<script type="text/javascript">
ipurl = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=";
$("span.ip").live("click", function(){

	span = $(this);
	ip = $(this).text();

	$.getScript(ipurl + ip, function(json){
		info = remote_ip_info;
		str = "<br>";
		//str+= info.country;
		str+= ((info.province == null) ? "": info.province);
		str+= ((info.city == null || info.city == "北京") ?
			"": info.city + " ");
		str+= info.isp + " ";
		str+= info.desc;
		span.parent().append(str);
	});
});

</script>