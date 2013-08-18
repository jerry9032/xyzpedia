<? $this->renderPartial('/common/fixed-info'); ?>

<form class="form-horizontal" method="post" action="/user/profile/career">
	<legend><h4><?=Yii::t("user", "Education & Career Info")?></h4></legend>
	<fieldset>
	<div class="control-group">
		<label class="control-label" for="career">您的职业</label>
		<div class="controls">
			<select id="career" name="career">
				<option>-- 请选择 --</option>
				<option value="student"<?
					if ($user_info->career=="student")
						echo " selected";
					?>>学生</option>
				<option value="work"<?
					if ($user_info->career=="work")
						echo " selected";
					?>>工作</option>
				<option value="freejob"<?
					if ($user_info->career=="freejob")
						echo " selected";
					?>>自由职业</option>
				<option value="retired"<?
					if ($user_info->career=="retired")
						echo " selected";
					?>>退休</option>
			</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="school">院校</label>
		<div class="controls">
			<input type="text" id="school" name="school" value="<?=$user_info->school?>">
		</div>
	</div>
	<div class="form-actions">
		<button type="submit" class="btn btn-primary">保存更改</button>
	</div>
	</fieldset>
</form>