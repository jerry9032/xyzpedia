<? $this->renderPartial("/common/fixed-info"); ?>

<form method="post" action="/admin/setting/widgetupdate" class="form-horizontal" style="margin-bottom:0;">
<legend><h4><?=Yii::t("admin", ucfirst($action))?></h4></legend>
<fieldset>
	<link rel="stylesheet" type="text/css" href="/css/jquery-ui-bootstrap.css" />
	<link rel="stylesheet" href="/css/admin/setting/widget.css">

	<div class="tabbable tabs-left">
		<ul class="nav nav-tabs" id="widget-order-tab">
			<li class="active"><a href="#lA" data-toggle="tab">主页侧边栏</a></li>
			<li class=""><a href="#lB" data-toggle="tab">文章侧边栏</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="lA">
				<ul id="index-widget" class="sortable"><?
					foreach( $widgets_all as $widget) {
						if (in_array($widget, $widgets)) {
							$li_class = ""; 
							$btn_class = "btn-danger";
							$btn_attr = 1;
							$btn_text = "隐藏";
						} else {
							$li_class = "ui-state-disabled";
							$btn_class = "btn-success";
							$btn_attr = 0;
							$btn_text = "显示";
						} ?>

						<li class="widget_order ui-state-default <?=$li_class?> alert" rel="<?=$widget?>">
							<?=Yii::t("admin", $widget)?>
							<button class="btn btn-mini widget-action pull-right <?=$btn_class?>" show="<?=$btn_attr?>"><?=$btn_text?></button>
						</li>
					<? } ?>
				</ul>
			</div>
			<div class="tab-pane" id="lB">
				<ul id="post-widget" class="sortable"><?
					foreach( $widgets_post_all as $widget) {
						if (in_array($widget, $widgets_post)) {
							$li_class = ""; 
							$btn_class = "btn-danger";
							$btn_attr = 1;
							$btn_text = "隐藏";
						} else {
							$li_class = "ui-state-disabled";
							$btn_class = "btn-success";
							$btn_attr = 0;
							$btn_text = "显示";
						} ?>

						<li class="widget_order ui-state-default <?=$li_class?> alert" rel="<?=$widget?>">
							<?=Yii::t("admin", $widget)?>
							<button class="btn btn-mini widget-action pull-right <?=$btn_class?>" show="<?=$btn_attr?>"><?=$btn_text?></button>
						</li>
					<? } ?>
				</ul>
			</div>
		</div>
	</div>
	<input type="hidden" id="index_widget_order" name="index_widget_order"/>
	<input type="hidden" id="post_widget_order" name="post_widget_order"/>
	<input type="hidden" id="index_widget_all" name="index_widget_all"/>
	<input type="hidden" id="post_widget_all" name="post_widget_all"/>
	<div class="control-group">
		<label class="control-label">近期文章数</label>
		<div class="controls">
			<? $recent_post = get_option("index_recentpost_num"); ?>
			<div id="post-slider" class="slider" value="<?=$recent_post?>"></div>
			<label id="post_num" class="slider-num"><?=$recent_post?></label>
			<input type="hidden" id="index_recentpost_num" name="index_recentpost_num" value="<?=$recent_post?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">近期评论数</label>
		<div class="controls">
			<? $recent_comment = get_option("index_recentcomment_num"); ?>
			<div id="comment-slider" class="slider" value="<?=$recent_comment?>"></div>
			<label id="comment_num" class="slider-num"><?=$recent_comment?></label>
			<input type="hidden" id="index_recentcomment_num" name="index_recentcomment_num" value="<?=$recent_comment?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">分类目录</label>
		<div class="controls">
			<label class="checkbox">
			<input type="checkbox" name="index_category_show_empty" value="1" <?
				if (get_option("index_category_show_empty") == 1)
					echo "checked"; ?>>显示没有文章的分类
			</label>
		</div>
	</div>
	<script src="/js/jquery-ui.js"></script>
	<script src="/js/admin/setting/widget.js"></script>
	<div class="form-actions">
		<button type="submit" class="btn btn-primary">保存更改</button>
	</div>
</fieldset>
</form>

