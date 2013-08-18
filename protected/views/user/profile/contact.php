<? $this->renderPartial('/common/fixed-info'); ?>

<form class="form-horizontal" method="post" action="/user/profile/contact">

	<legend><h4><?=Yii::t("user", "Contact Info")?></h4></legend>
	<fieldset>

	<div class="control-group">
		<label class="control-label" for="email">电子邮件</label>
		<div class="controls">
			<input type="text" id="email" name="email" value="<?=$user_info->email?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="url">个人主页</label>
		<div class="controls">
			<input type="text" id="url" name="url" class="span5" value="<?=$user_info->url?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="qq">QQ</label>
		<div class="controls">
			<input type="text" id="qq" name="qq" value="<?=$user_info->qq?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="mobile">您的手机号</label>
		<div class="controls">
			<input type="text" id="mobile" name="mobile" value="<?=$user->mobile?>" title="只用于统计，请放心填写。">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="upline" title="test">上线手机号</label>
		<div class="controls">
			<input type="text" id="upline" name="upline" value="<?=$user_info->upline?>" title="是谁给您发小百科呢？">
		</div>
	</div>
	<div class="form-actions">
		<button type="submit" class="btn btn-primary">保存更改</button>
	</div>

	</fieldset>
</form>
