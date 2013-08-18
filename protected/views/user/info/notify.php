<form class="form-horizontal">
<legend><h4><?=Yii::t("user", "Notify")?></h4></legend>
<fieldset>
	<div class="notify control-group no-input">
		<label class="control-label">稿件评论
			<span id="comment-notify-count">0</span>
		</label>
		<div class="controls">
			<ul id="comment-notify"></ul>
		</div>
	</div>
	<div class="notify control-group no-input">
		<label class="control-label">文章评论
			<span id="postreply-notify-count">0</span>
		</label>
		<div class="controls">
			<ul id="postreply-notify"></ul>
		</div>
	</div>
	<div class="notify control-group no-input">
		<label class="control-label">@我的
			<span id="at-notify-count">0</span>
		</label>
		<div class="controls">
			<ul id="at-notify"></ul>
		</div>
	</div>

	<div class="notify control-group no-input">
		<label class="control-label">待审稿件
			<span id="edit-notify-count">0</span>
		</label>
		<div class="controls">
			<ul id="edit-notify"></ul>
		</div>
	</div>
	<div class="notify control-group no-input">
		<label class="control-label">待修改稿件
			<span id="modify-notify-count">0</span>
		</label>
		<div class="controls">
			<ul id="modify-notify"></ul>
		</div>
	</div>
	<? if ( can('contribute', 'assign') ) { ?>
	<div class="notify control-group no-input">
		<label class="control-label">
			<span rel="popover" data-content="您是<?=Yii::t("admin", ucfirst(user()->role))?>，有分配审稿权。" data-original-title="分配审稿">
				待分配稿件
				<span id="assign-notify-count">0</span>
			</span>
		</label>
		<div class="controls">
			<ul id="assign-notify"></ul>
		</div>
	</div>
	<? } ?>
	<? if ( can('contribute', 'final') ) { ?>
	<div class="notify control-group no-input">
		<label class="control-label">
			<span rel="popover" data-content="您是<?=Yii::t("admin", ucfirst(user()->role))?>，有稿件终审权。" data-original-title="稿件终审">
				待终审稿件
				<span id="final-notify-count">0</span>
			</span>
		</label>
		<div class="controls">
			<ul id="final-notify"></ul>
		</div>
	</div>
	<? } ?>
	<div class="notify control-group no-input" id="empty-notify">
		<label class="control-label">没有通知。</label>
	</div>
</fieldset>
</form>


<script type="text/javascript" src="/js/user/info/notify.js"></script>
<script type="text/javascript">
loadNotify('postreply', 'postreply');

loadNotify('comment', 'comment');
loadNotify('comment', 'at');

loadNotify('contrib', 'edit');
loadNotify('contrib', 'modify');

<? if ( can('contribute', 'assign') ) { ?>
loadNotify('contrib', 'assign');
<? } ?>

<? if ( can('contribute', 'final') ) { ?>
loadNotify('contrib', 'final');
<? } ?>
</script>
