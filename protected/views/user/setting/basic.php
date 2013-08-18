<? $this->renderPartial('/common/fixed-info'); ?>

<form class="form-horizontal" method="post" action="/user/setting/basic">
	<legend><h4><?=Yii::t("user", "Basic Settings")?></h4></legend>
	<fieldset>
	<div class="control-group">
		<label class="control-label" for="role">我的角色</label>
		<div class="controls">
			<select name="role" disabled><?
			foreach ($roles as $role) { ?>
				<option value="<?=$role?>" <?
					if ( user()->role == $role )
						echo "selected";
					?>><?=Yii::t("admin", ucfirst($role))?>
				</option>
			<? } ?>
			</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="password1">更改密码</label>
		<div class="controls">
			<div class="input-prepend">
				<span class="add-on">原密码</span><input class="input-medium" type="password" id="org-password" name="org-password">
			</div>
			<p></p>
			<div class="input-prepend">
				<span class="add-on">新的密码</span><input class="input-medium" type="password" id="password1" name="password1">
			</div>
			<p></p>
			<div class="input-prepend">
				<span class="add-on">再次输入</span><input class="input-medium" type="password" id="password2" name="password2">
			</div>
		</div>
	</div>
	<div class="form-actions">
		<button type="submit" class="btn btn-primary">保存更改</button>
	</div>

	</fieldset>
</form>