<ul class="nav nav-pills">
	<li id="post"><a href="/admin/user/capability/post">文章</a></li>
	<li id="contribute"><a href="/admin/user/capability/contribute">投稿</a></li>
	<li id="user"><a href="/admin/user/capability/user">用户</a></li>
	<li id="setting"><a href="/admin/user/capability/setting">网站设置</a></li>
</ul>
<script type="text/javascript">
	(function($){
		$("#<?=$group?>").addClass("active");
	})(jQuery);
</script>
<table class="table table-bordered">
	<thead><tr>
		<td><?=Yii::t("admin", "Capability")?></td><?
		foreach($capObjList as $capObj) {
			// count the number of capabilities
			if ($capObj["role"] == "administrator") {
				$allCaps = $capObj["list"];
				$num = count($capObj["list"]);
			}
		?>
		<td><?=Yii::t("admin", ucfirst($capObj["role"]))?></td><?
		} ?>
	</tr></thead>

	<tbody><?
		foreach ($allCaps as $singleCap) { ?>
		<tr>
			<? $name = Yii::t("admin", ucfirst($singleCap)); ?>
				<td class="cap-name"><?=$name?></td>
				<? foreach ($capObjList as $capObj) { ?>
				<td class="cap-value">

					<? if( in_array($singleCap, $capObj["list"] )) { ?>
					<label class="checkbox">
					<input type="checkbox" checked="checked" />
					<span class="label label-info"><?=$name?></span></label>
					<? } else { ?>
					<label class="checkbox">
					<input type="checkbox"/>
					<span class="label label-lowkey"><?=$name?></span></label>
					<? } ?>
					
				</td>
				<? } ?>
			</tr>	
		<? } ?>
	</tbody>

	<tfoot>
		<td><?=Yii::t("admin", "Capability")?></td><?
		foreach($capObjList as $capObj) { ?>
		<td><?=Yii::t("admin", ucfirst($capObj["role"]))?></td><?
		} ?>
	</tfoot>
</table>
