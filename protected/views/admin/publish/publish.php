<? $this->renderPartial("/common/fixed-info"); ?>
<link rel="stylesheet" href="/css/jquery-ui-bootstrap.css" />
<link rel="stylesheet" href="/css/admin/publish.css" />

<!-- post list that are determined -->
<div class="span3" style="margin-left: 0;">
	<h4 style="margin-top:0;">候选稿件
		<span rel="popover" data-original-title="颜色代表什么？"
			data-content="<b>红色</b>：紧急，表示预约在本周发送，或稿件提交时间在2个月以前，或该作者稿数少于2，或处在通过状态大于4篇且第一篇大于一个月；<br><b>橙色</b>：较紧急，表示稿件提交时间在1个月以前，或该作者稿件数少于3，或处在通过状态大于4篇，最早一篇变橙色。" 
			><i class="icon-question-sign icon-blue"></i></span></h4>
	<hr>
	<ul id="enqueue-title-list"><?

	// bootstrap style
	$style = array(
		"" => "",
		"red" => "-danger",
		"orange" => "-warning",
		"yellow" => "-caution",
		"black" => "-inverse"
	);

	foreach ($contribs as $contrib) {
		$this->renderPartial('/admin/publish/publish-template', array(
			"contrib" => $contrib,
			"style" => $style,
			"nums" => $nums,
			"more" => false
		));
	} ?>
	</ul>
</div>

<div class="span6">
	<form id="" method="post" action="/admin/publish/generate">
	<h4 style="margin-top:0;">
		选定稿件 (<span id="contrib-selected">0</span>)
		<img class="loading" style="display:none;" src="/images/spin-small.gif">

		<div class="pull-right">
			<? if ( $undo ) { ?>
			<span rel="popover" data-original-title="撤销定稿结果" data-content="您对稿件的选择和分段将会全部丢失！此功能适用于选错稿件的同志们使用。">
			<a class="btn btn-danger" href="/admin/publish/revert">撤销</a>
			</span>
			<a class="btn btn-success" href="/admin/publish/confirm"><i class="icon-ok icon-white"></i> 确认刚才的定稿</a>
			<? } else { ?>
			<button type="submit" class="btn btn-primary" id="generate"><i class="icon-ok icon-white"></i> 提交</button>
			<? } ?>
		</div>
		<input type="hidden" name="id_list" id="id_list" value="">
		<input type="hidden" name="from" id="from" value="">
		<input type="hidden" name="to" id="to" value="">
		<input type="hidden" name="data" id="data" value="">
	</h4>
	</form>
	<hr>

	<div id="accordion"></div>
	<div id="test"></div>

	<input type="hidden" id="should_user_id" value="<?=$enqueue_user->user_id?>">
	<input type="hidden" id="should_user_name" value="<?=$enqueue_user->user->nick_name?>">
	<input type="hidden" id="current_user_id" value="<?=user()->id?>">
</div>


<div id="myModal" class="modal hide fade">
	<div class="modal-header">
		<a class="close" data-dismiss="modal" >&times;</a>
		<h4>Title</h4>
	</div>
	<div class="modal-body">
		<div class="meta alert alert-info">字数统计： <span id="words-count">0</span> 字</div>
		<div class="btn-group" style="margin-bottom: 15px;">
			<button class="btn btn-info">加广告</button>
			<button class="btn btn-info dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
			<ul class="dropdown-menu">
				<li><a class="insert-ad" aid="0" href="javascript:void(0);">鼓励投稿</a></li>
				<li><a class="insert-ad" aid="1" href="javascript:void(0);">鼓励转发</a></li>
				<li><a class="insert-ad" aid="2" href="javascript:void(0);">提问/Q&amp;A</a></li>
				<li><a class="insert-ad" aid="3" href="javascript:void(0);">微信/二维码</a></li>
				<li><a class="insert-ad" aid="4" href="javascript:void(0);">QQ群</a></li>
				<li><a class="insert-ad" aid="5" href="javascript:void(0);">专栏邀请</a></li>
				<li class="divider"></li>
				<li><a href="javascript:void(0);">取消</a></li>
			</ul>
		</div>
		<textarea style="width:515px;height:215px;font-size:12px;"></textarea>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-primary" id="save" contrib-id="" part="">保存</a>
		<a href="#" class="btn" data-dismiss="modal">关闭</a>
	</div>
</div>


<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/jquery-ui.js"></script>
<script type="text/javascript" src="/js/dateformat.js"></script>
<script type="text/javascript" src="/js/admin/publish/publish.js?ver=201304051604"></script>
