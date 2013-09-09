<? $this->renderPartial("/common/fixed-info"); ?>

<legend>
	<h4>文章列表</h4>
</legend>

<?

$id_str = '((($data->created < gmtTime()) || (user()->id == $data->submit_id) || can("post", "preview")) ?
" <a href=\"/".timeFormat($data->created, "path")."/".$data->slug."\">".$data->id."</a>" :
"$data->id")';

$title_str = '(((user()->id == $data->submit_id) || can("post", "updateall")) ?
"<a href=\"/admin/post/edit/".$data->id."\">".$data->title."</a>" :
$data->title)';

$submit_str = '"<span id=\"submit".$data->id."\">
<a href=\"/author/".$data->submit->login."\">".$data->submit->nick_name."</a>
</span>".
(( (user()->id == $data->submit_id) || can("post", "alter")) ?
"<a href=\"javascript:;\"><i class=\"alter icon-pencil icon-blue pull-right\" rel=\"".$data->id."\"></i></a>":"")';

$category_str = '"<a href=\"/category/".$data->category->slug."\">".$data->category->name."</a>"';

$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider' => $dataProvider,
	'template' => '{summary}{pager}{items}{summary}{pager}',
	'summaryText' => '第 {start}-{end} 条，共 {count} 条',
	'columns'=>array(
		array(
			'name' => 'id',
			'header' => 'ID',
			'type' => 'raw',
			'value' => $id_str,
		),
		array(
			'name' => 'title',
			'header' => '文章标题',
			'type' => 'raw',
			'value' => $title_str,
		),
		array(
			'name' => 'submit_id',
			'header' => '配图',
			'type' => 'raw',
			'value' => $submit_str,
		),
		array(
			'name' => 'category_id',
			'header' => '分类目录',
			'type' => 'raw',
			'value' => $category_str,
		),
		array(
			'name' => 'comments',
			'header' => '评论',
			'value' => '$data->comments',
		),
		array( 
			'name'=>'created',
			'header' => '发表于',
			'value' => 'timeFormat($data->created)',
		),
	),
	'cssFile' => false,
	'itemsCssClass' => 'table table-bordered table-striped post-list',
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


<div class="modal hide fade" id="myModal">
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h5>修改配图人员 - 投稿署名</h5>
	</div>
	<div class="modal-body">
		<input type="text" id="new_submit" name="new_submit" data-provide="typeahead" data-items="4" data-source='<?=$user_list?>' style="margin-bottom: 0;">
		<input type="hidden" name="post_id" id="post_id" value="">
		<button class="btn btn-primary" id="alter_submit" style="margin-left: 10px;">提交</button>
	</div>
</div>
<script type="text/javascript" src="/js/bootstrap.js"></script>
<script type="text/javascript" src="/js/bootstrap-modal.js"></script>
<script type="text/javascript" src="/js/bootstrap-typeahead.js"></script>
<script type="text/javascript" src="/js/admin/post/list.js?v=201309091332"></script>
