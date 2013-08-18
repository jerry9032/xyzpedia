<form class="form-horizontal">
	<legend><h4>网站概要</h4></legend>
	<div class="control-group no-input">
		<label class="control-label">网站用户数</label>
		<div class="controls">
			<?=$stat->num_of_user?>
		</div>
	</div>
	<div class="control-group no-input">
		<label class="control-label">转发人数统计</label>
		<div class="controls">
			<?=$stat->num_of_forward?>
		</div>
	</div>
	<div class="control-group no-input">
		<label class="control-label">网站发文篇数</label>
		<div class="controls">
			<?=$stat->num_of_post?>
		</div>
	</div>
	<div class="control-group no-input">
		<label class="control-label">网站投稿篇数</label>
		<div class="controls">
			<?=$stat->num_of_contrib?>
		</div>
	</div>

	<div id="placeholder" style="width:600px;height:300px;margin:30px auto;"></div>
</form>

<script type="text/javascript">
var d1 = [];
<? foreach($chart["users"] as $user) { ?>
d1.push([<?=strtotime($user["month"]."-01 UTC")*1000?>, <?=$user["users"]?>]);
<? } ?>

var d2 = [];
<? foreach($chart["contribs"] as $contrib) { ?>
d2.push([<?=strtotime($contrib["month"]."-01 UTC")*1000?>, <?=$contrib["contribs"]?>]);
<? } ?>
</script>
<script src="/js/flot/jquery.flot.js"></script>
<script src="/js/admin/info/summary.js"></script>
