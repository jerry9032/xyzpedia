<legend><h4>
	<?=substr($this->pageTitle, 17)?>
	<div class="pull-right form-search">
		<input type="text" id="key" class="input-medium search-query"
			placeholder="关键词" title="仅支持搜索用户名/投稿署名与手机号。" />
		<button class="btn" id="search"
			onclick="javascript:window.location='/admin/user/search/'+$('#key').val();">
			<i class="icon-search"></i> 搜索
		</button>
	</div>
	<div class="clearfix"></div>
</h4></legend>

<? if (isset($prompt)) { ?>
<div class="alert alert-info"><?=$prompt?></div>
<? } ?>



<? $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
	'template' => '{summary}{pager}{items}{summary}{pager}',
	'columns'=>array(
		array(
			'name' => 'id',
			'header' => 'ID',
			'value' => '$data->id',
		),
		array(
			'name' => 'login',
			'header' => '用户名',
			'value' => '$data->login',
		),
		array(
			'name' => 'nick_name',
			'header' => '投稿署名',
			'value' => '$data->nick_name',
		),
		array(
			'name' => 'mobile',
			'header' => '联系电话',
			'value' => '$data->mobile',
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

