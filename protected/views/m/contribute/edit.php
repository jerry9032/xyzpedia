<? $edit_page = isset($r); ?>
<div data-role="content">
	<form action="/contribute/update/<?=$r->id?>" method="post">

		<div data-role="fieldcontain" class="ui-hide-label">
			<label for="contrib-title">标题</label>
			<input type="text" name="contrib-title" id="contrib-title" value="<?=(isset($r)? $r->title:"")?>" placeholder="标题"/>
		</div>

		<div data-role="fieldcontain" class="ui-hide-label">
			<label for="contrib-content">正文</label>
			<textarea name="contrib-content" id="contrib-content" placeholder="正文" rows="15" style="height:250px;"><?=(isset($r) ? $r->content: "")?></textarea>
		</div>

		<div data-role="fieldcontain" class="ui-hide-label">
			<label for="contrib-category" class="select">分类</label>
			<select name="contrib-category" id="contrib-category">
				<? foreach ($cats as $cat) { ?>
				<? if (isset($r) && ($r->category_id == $cat["ID"])) { ?>
				<option selected value="<?=$cat["ID"]?>"><?=$cat["short"]?></option>
				<? } else { ?>
				<option value="<?=$cat["ID"]?>"><?=$cat["short"]?></option>
				<? }} ?>
			</select>
		</div>

		<input type="hidden" name="mobile" value="true">
		<button type="submit" data-theme="b">提交</button>
	</form>
</div>