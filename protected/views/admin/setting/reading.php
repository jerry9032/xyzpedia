<? $this->renderPartial("/common/fixed-info"); ?>

<form method="post" action="/admin/setting/readingupdate" class="form-horizontal" style="margin-bottom:0;">
<legend><h4><?=Yii::t("admin", ucfirst($action))?></h4></legend>
<fieldset>
	<div class="control-group no-input">
		<label class="control-label">这个更不着急</label>
		<div class="controls">
		</div>
	</div>
	<div class="form-actions">
		<button type="submit" class="btn btn-primary">保存更改</button>
	</div>
</fieldset>
</form>
