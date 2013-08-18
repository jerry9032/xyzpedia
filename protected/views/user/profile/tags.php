<? $this->renderPartial('/common/fixed-info'); ?>

<form class="form-horizontal" method="post" action="/user/profile/tags">
	<legend><h4><?=Yii::t("user", "Personal Tags")?></h4></legend>
	<fieldset>
	<div class="control-group">
		<label class="control-label" for="tags">
			<span rel="popover" data-original-title="标签说明" data-content="支持拖放和排序。">
				个人标签
			</span>
		</label>
		<div class="controls">
			<div class="tag-wrap" id="tag-wrap-cur" style="height:30px;width:500px;padding: 0;">
			<? foreach ($user->tags as $tag) { ?>
				<span class="label label-info">
					<span><?=$tag->tag?></span>
					<a href="#" class="remove-tag">&times;</a>
				</span>
			<? } ?>
			<input type="hidden" name="tags">
			</div>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="tags">
			<span rel="popover" data-original-title="标签说明" data-content="已经有的标签不能重复添加，能给自己贴的标签最多5个。">
				本站可用标签
			</span>
		</label>
		<div class="controls"><div class="tag-wrap" id="tag-wrap-lib">
		<? foreach ($tags_all as $tag) { ?>
			<span class="label"><?=$tag->tag?></span>
		<? } ?>
		</div></div>
	</div>
	<script type="text/javascript" src="/js/jquery-ui.js"></script>
	<script type="text/javascript" src="/js/user/profile/tags.js"></script>
	<div class="form-actions">
		<button type="submit" class="btn btn-primary">保存更改</button>
	</div>

	</fieldset>
</form>