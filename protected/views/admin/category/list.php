
<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<td><?=Yii::t("admin", "ID")?></td>
			<td><?=Yii::t("admin", "Name")?></td>
			<td><?=Yii::t("admin", "Short")?></td>
			<td><?=Yii::t("admin", "Tag")?></td>
			<td><?=Yii::t("admin", "Slug")?></td>
			<td><?=Yii::t("admin", "Tags")?></td>
			<td><?=Yii::t("admin", "Contributs")?></td>
			<td><?=Yii::t("admin", "Posts")?></td>
		</tr>
	</thead>

	<tbody>
	<? foreach ($categories as $cat) { ?>
		<tr>
			<td><?=$cat->ID?></td>
			<td><a href="/category/<?=$cat->slug?>"><?=$cat->name?></a></td>
			<td><a href="/contribute/list/category/<?=$cat->ID?>"><?=$cat->short?></a></td>
			<td><?=$cat->tag?></td>
			<td><a href="/category/<?=$cat->slug?>"><?=$cat->slug?></a></td>
			<td><?=count($cat->tags)?></td>
			<td><?=count($cat->contribs)?></td>
			<td><?=count($cat->posts)?></td>
		</tr>
	<? } ?>
	</tbody>

	<tfoot>
		<tr>
			<td><?=Yii::t("admin", "ID")?></td>
			<td><?=Yii::t("admin", "Name")?></td>
			<td><?=Yii::t("admin", "Short")?></td>
			<td><?=Yii::t("admin", "Tag")?></td>
			<td><?=Yii::t("admin", "Slug")?></td>
			<td><?=Yii::t("admin", "Tags")?></td>
			<td><?=Yii::t("admin", "Contributs")?></td>
			<td><?=Yii::t("admin", "Posts")?></td>
		</tr>
	</tfoot>
</table>