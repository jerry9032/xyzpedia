<? $this->renderPartial("/common/fixed-info"); ?>

<form class="form-horizontal form-nodesc" method="post" style="border:0;" action="/admin/weixin/update/<?=$post->id?>">

	<!-- title -->
	<div class="control-group">
	<div class="controls">
		<input type="text" class="span9" name="post-wxtitle" id="post-title"
			value="<?=(empty($post->wxtitle)? $post->title :$post->wxtitle )?>">
	</div></div>

	<!-- weixin url -->
	<div class="control-group">
	<div class="controls" id="wxurl">
		<div class="input-prepend">
			<span class="add-on"><?=Yii::t('admin/post', "WxUrl"); ?></span><input type="text" name="post-wxurl" autocomplete="off" class="span4" value="<?=$post->wxurl?>"/>
		</div>
	</div></div>

	<!-- weixin pic url -->
	<div class="control-group">
	<div class="controls" id="post-wxpic">
		<div class="input-prepend">
			<span class="add-on"><?=Yii::t('admin/post', "WxPic"); ?></span><input type="text" name="post-wxpic" autocomplete="off" class="span4" value="<?=$post->wxpic?>"/>
		</div>
	</div></div>

	<!-- excerpt -->
	<div class="control-group">
	<div class="controls">
		<textarea class="span9" id="post-wxexcerpt" name="post-wxexcerpt" rows="8" placeholder="<?=Yii::t('admin/post', "Type excerpt here"); ?>"><?=(empty($post->wxexcerpt)? $post->excerpt: $post->wxexcerpt)?></textarea>
	</div></div>

	<div class="controls btn-toolbar">
		<button type="submit" class="btn btn-primary"><i class="icon-edit icon-white"></i> 提交</button>
	</div>

	</div><!-- end of left span5 -->
</form>

