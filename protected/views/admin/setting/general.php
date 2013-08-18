<? $this->renderPartial("/common/fixed-info"); ?>

<form method="post" action="/admin/setting/generalupdate" class="form-horizontal" style="margin-bottom:0;">
<legend><h4><?=Yii::t("admin", ucfirst($action))?></h4></legend>
<fieldset>

	<? $color = get_option('index_notify_color'); ?>
	<div class="control-group no-input">
		<label class="control-label">主页通知颜色</label>
		<div class="controls">
			<select name="index_notify_color" class="span4">
				<option value="info" <?=($color=='info'?'selected':'')?>>蓝色（默认）</option>
				<option value="success" <?=($color=='success'?'selected':'')?>>绿色</option>
				<option value="warning" <?=($color=='warning'?'selected':'')?>>橙色</option>
				<option value="danger" <?=($color=='danger'?'selected':'')?>>红色</option>
			</select>
		</div>
	</div>

	<div class="control-group no-input">
		<label class="control-label">刷新用户得分</label>
		<div class="controls">
			<button id="btn-refresh-score" class="btn btn-small"><i class="icon-refresh"></i> 点击刷新</button>
			<img id="loading" src="/images/spin-small.gif" style="display:none;">
		</div>
	</div>
	<div class="form-actions">
		<button type="submit" class="btn btn-primary">保存更改</button>
	</div>
</fieldset>
</form>

<script type="text/javascript">
$("#btn-refresh-score").click(function(){
	that = $("#btn-refresh-score");
	loading = $("#loading");

	loading.show();
	that.attr("disabled", "disabled");
	$.post("/site/refreshscore", {}, function(){
		alert("刷新成功。");
		loading.hide();
		that.removeAttr("disabled");
	});
	return false;
});
</script>