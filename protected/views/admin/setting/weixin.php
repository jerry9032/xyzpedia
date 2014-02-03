<? $this->renderPartial("/common/fixed-info"); ?>

<form method="post" action="/admin/setting/weixinupdate" class="form-horizontal" style="margin-bottom:0;">
<legend><h4><?=Yii::t("admin", ucfirst($action))?></h4></legend>
<fieldset>

	<div class="control-group no-input">
		<label class="control-label">线下活动</label>
		<div class="controls">
			<label><input type="radio" name="party_echo_on" value="1" <?=($party_echo_on=="1"?"checked":"")?>> 近期有线下活动</label>
			<label><input type="radio" name="party_echo_on" value="0" <?=($party_echo_on=="0"?"checked":"")?>> 没有</label>
		</div>
	</div>

	<div class="control-group no-input" <?=($party_echo_on=="0"?"style=\"display:none\"":"")?>>
		<label class="control-label">线下活动内容</label>
		<div class="controls">
			<textarea class="span6" name="party_echo" rows="6"><?=$party_echo?></textarea>
		</div>
	</div>

	<hr>

	<div class="control-group no-input">
		<label class="control-label">线下活动报名回复</label>
		<div class="controls">
			<label><input type="radio" name="party_signup_echo_on" value="1" <?=($party_signup_echo_on=="1"?"checked":"")?>> 打开微信报名回复</label>
			<label><input type="radio" name="party_signup_echo_on" value="0" <?=($party_signup_echo_on=="0"?"checked":"")?>> 关闭</label>
		</div>
	</div>

	<div class="control-group no-input" <?=($party_signup_echo_on=="0"?"style=\"display:none\"":"")?>>
		<label class="control-label">报名回复提示语</label>
		<div class="controls">
			<textarea class="span6" name="party_signup_echo" rows="4"><?=$party_signup_echo?></textarea>
		</div>
	</div>

	<hr>

	<div class="control-group no-input">
		<label class="control-label">小百科搜索提示语</label>
		<div class="controls">
			<textarea class="span6" name="keyword_search_echo" rows="4"><?=$keyword_search_echo?></textarea>
		</div>
	</div>

	<div class="control-group no-input">
		<label class="control-label">如何投稿提示语</label>
		<div class="controls">
			<textarea class="span6" name="howto_contribute_echo" rows="8"><?=$howto_contribute_echo?></textarea>
		</div>
	</div>

	<div class="control-group no-input">
		<label class="control-label">关于小百科提示语</label>
		<div class="controls">
			<textarea class="span6" name="about_xyzpedia_echo" rows="12"><?=$about_xyzpedia_echo?></textarea>
		</div>
	</div>
	<div class="form-actions">
		<button type="submit" class="btn btn-primary">保存更改</button>
	</div>
</fieldset>
</form>

<script language="javascript">
$("input[name=party_echo_on]").click(function(){
	if ($(this).val() == 0) {
		$("textarea[name=party_echo]").parent().parent().hide("fast");
	} else {
		$("textarea[name=party_echo]").parent().parent().show("fast");
	}
});
$("input[name=party_signup_echo_on]").click(function(){
	if ($(this).val() == 0) {
		$("textarea[name=party_signup_echo]").parent().parent().hide("fast");
	} else {
		$("textarea[name=party_signup_echo]").parent().parent().show("fast");
	}
});
</script>
