<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<td>ID</td>
			<td>预计定稿时间</td>
			<td>发送时间范围</td>
			<td>定稿人员</td>
			<td>发送提醒</td>
			<td>状态</td>
		</tr>
	</thead>

	<tbody><?
		$now = gmtTime();
		foreach ($arranges as $arrange) {
			$this->renderPartial("/admin/publish/appoint-template", array(
				"arrange" => $arrange,
				"now" => $now,
			));
		} ?>
	</tbody>

	<tfoot>
		<tr class="input">
			<td>(*)</td>
			<td>(自动)</td>
			<td class="input">
				<span rel="popover" data-original-title="如何选择" data-content="只需选择一周的开始日期，请选择每一周的周一。">
				<input id="start-date" name="star-date" type="text" style="cursor: pointer;" readonly>
				</span>
			</td>
			<td class="input">
				<input type="text" id="add-enqueue" name="add-enqueue" data-provide="typeahead" data-items="4" data-source='<?=$user_list?>'>
			</td>
			<td><button class="btn btn-primary btn-small" id="add-entrust">
				<i class="icon-plus icon-white"></i> 添加记录</button>
			</td>
			<td>(自动)</td>
		</tr>
		<tr>
			<td>ID</td>
			<td>预计定稿时间</td>
			<td>发送时间范围</td>
			<td>定稿人员</td>
			<td>发送提醒</td>
			<td>状态</td>
		</tr>
	</tfoot>
</table>



<div class="modal hide fade" id="myModal">
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h5>修改定稿人员</h5>
	</div>
	<div class="modal-body">
		<input type="text" id="new_enqueue" name="new_enqueue" data-provide="typeahead" data-items="4" data-source='<?=$user_list?>'>
		<button class="btn btn-primary" id="alter-enqueue-btn" style="margin-left: 10px; margin-bottom: 9px;">提交</button>
	</div>
</div>

<script type="text/javascript" src="/js/bootstrap.js"></script>
<script type="text/javascript" src="/js/bootstrap-modal.js"></script>
<script type="text/javascript" src="/js/bootstrap-typeahead.js"></script>
<link rel="stylesheet" href="/css/jquery-ui-bootstrap.css" />
<script type="text/javascript" src="/js/jquery-ui.js"></script>
<script type="text/javascript" src="/js/admin/publish/appoint.js"></script>
