<link rel="stylesheet" type="text/css" href="/css/contribute.css?ver=201304100011" />
<div class="container">

	<header class="subhead">
		<h1><?=Yii::t('contribute', "Contribute to XYZPedia"); ?></h1>
	</header>


<div class="row">
	<div class="span3">
		<? include "left.php" ?>
	</div>

	<div class="span9">

		<header class="subhead"><h4><?=$r->title?></h4></header>
		<input type="hidden" id="contrib-id" name="contrib-id" value="<?=$r->id?>">

		<div class="contrib-meta">
		<span class="contrib-author"><?=$r->author->nick_name?></span>
		<span class="date">
			<?=Yii::t('contribute', "Submitted in")?>
			<?=timeFormat($r->created, "full")?>
			<?=d($r->from)?>
		</span>
		</div>
		<? if (!empty($r->excerpt)) { ?>
		<blockquote class="contrib-excerpt"><p><?=$r->excerpt?></p></blockquote>
		<? } ?>
		<p><?=str_replace("\n", "<p>", $r->content)?></p>


	<?
	$is_author = (user()->id == $r->author_id);
	$is_editor = (user()->id == $r->editor_id);
	$is_committee = can("contribute", "updateall");
	$is_admin = isAdmin();

	$can_edit = $is_author || $is_editor || $is_committee;
	$can_assign = can("contribute", "assign");
	$can_delete = $is_author || can('contribute', 'deleteall');
	$can_notify = can("contribute", 'notify');
	?>
	<? if ($can_edit || $can_notify || $can_delete || $can_assign) { ?>
		<div class="action-btn-bar">
			<? if ( $can_edit ) { ?>
			<div class="btn-group">
				<? if ($r->mode == 0 || $is_committee || $is_author) { ?>
				<button class="btn btn-primary" href="/contribute/id/<?=$r->id?>/edit">
					<i class="icon-edit icon-white"></i> <?=Yii::t('contribute', "I want to edit"); ?>
				</button>
				<? } else {?>
				<span rel="popover" data-original-title="为何不可编辑？" data-content="稿件是以“半授权模式”投稿的，您只能留下评论，不能直接编辑。">
				<button class="btn btn-primary">
					<i class="icon-edit icon-white"></i> <?=Yii::t('contribute', "I want to edit"); ?>
				</button>
				</span>
				<? } ?>
			</div>
			<? } ?>

			<? if ($can_notify || $can_delete || $can_assign) { ?>
			<div class="btn-group">
				<button class="btn"><i class="icon-asterisk"></i> 稿件管理</button>
				<button class="btn dropdown-toggle" data-toggle="dropdown">
				<span class="caret"></span>
				</button>
				<ul class="dropdown-menu">

				<? if ( isset($r->editor) && $can_notify && ($r->status == "pending") ) { ?>
				<li><a href="javascript:;" id="btn-prompt" data-toggle="modal"><i class="icon-comment"></i> 短信<?=Yii::t('contribute', "Prompt editor"); ?></a></li>
				<li><a href="javascript:;" id="btn-prompt-mail" data-toggle="modal"><i class="icon-envelope"></i> 邮件<?=Yii::t('contribute', "Prompt editor"); ?></a></li>
				<li class="divider"></li>
				<? } ?>

				<? if ( $can_assign ) { ?>
				<li><a href="/admin/contribute/assign/<?=$r->id?>"><i class="icon-repeat"></i> <?=Yii::t('contribute', "Redistribution"); ?></a></li>
				<? } ?>
				
				<? if ( $can_delete ) { ?>
				<li><a href="javascript:;" id="btn-delete" class="red" data-toggle="modal"><i class="icon-trash"></i> <?=Yii::t('contribute', "Delete"); ?></a></li>
				<? } ?>

				</ul>
			</div>
			<? } ?>
		</div>
	<? } ?>

		<div class="comments">
			<? $comments_num = count($cs); ?>
			<h5>
				<i class="icon-comment"></i>
				<?=Yii::t('contribute', "Comments of Contribution"); ?>
				(<?=$comments_num?>)
			</h5>
			<ul class='comments-list'>
			<?
			$start = ($comments_num > 10)? $comments_num-10: 0;
			$end = $comments_num-1;

			$this->renderPartial('/contribute/load-more-template', array(
				'start' => $start,
				'end' => $end
			));
			if ( $comments_num > 0 ) {
				for ( $fid = $start; $fid <= $end; $fid++ ) {
					$this->renderPartial("/contribute/comment-template", array(
						"c" => $cs[$fid],
						"fid" => $fid+1
					));
				}
			} else { ?>
				<li id='no-comment'>暂无评论。</li>
			<? } ?>
			</ul>

		<h5><i class="icon-comment"></i> <?=Yii::t('contribute', "Leave your comment"); ?></h5>
		<div class="add-comment">
			<textarea class="span9" id="textarea" name="comment" rows="2" style="height:50px;"></textarea>
			<button class="btn btn-success" id="add-comment">
				<i class="icon-pencil icon-white"></i> <?=Yii::t('contribute', "Submit"); ?>
			</button>
			<img src="/images/spin-small.gif" id="loading" style="display:none;">
		</div>
		</div><!-- end of comments -->

	</div>

</div>
</div>






<!-- modal -->
<? if ( isset($r->editor) ) { ?>
<div id="prompt-editor" class="modal hide">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4>确认发送提醒</h4>
	</div><!--Modal header-->
	<div class="modal-body">
		<h5>提醒是通过中国移动的“飞信”业务发送的</h5>
		<p>有几种情况可能会造成失败。</p>
		<p>大量频繁的发送可能导致用户屏蔽小百科飞信官号。</p>
	</div><!--Modal body-->
	<div class="modal-footer">
		<button class="btn btn-small btn-warning" id="confirm-prompt">
			<i class="icon-exclamation-sign icon-white"></i> <?=Yii::t('contribute', "I am sure to send"); ?>
		</button>
		<button class="btn btn-small" data-dismiss="modal" >
			<i class="icon-remove"></i> <?=Yii::t('contribute', "Do not send"); ?>
		</button>
	</div>
</div>
<div id="prompt-editor-mail" class="modal hide">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4>确认发送邮件</h4>
	</div><!--Modal header-->
	<div class="modal-body">
		<h5>邮件是通过服务器供应商的“邮件”服务发送的，发送身份为 admin@xyzpedia.org。</h5>
		<p>大量频繁的发送可能导致用户屏蔽小百科邮件地址。</p>
	</div><!--Modal body-->
	<div class="modal-footer">
		<button class="btn btn-small btn-warning" id="confirm-prompt-mail">
			<i class="icon-exclamation-sign icon-white"></i> <?=Yii::t('contribute', "I am sure to send"); ?>
		</button>
		<button class="btn btn-small" data-dismiss="modal" >
			<i class="icon-remove"></i> <?=Yii::t('contribute', "Do not send"); ?>
		</button>
	</div>
</div>
<script type="text/javascript">
$("#btn-prompt").click(function(){
	$('#prompt-editor').modal({
		keyboard:true,
		backdrop:true,
		show:true
	});
});
$("#btn-prompt-mail").click(function(){
	$('#prompt-editor-mail').modal({
		keyboard:true,
		backdrop:true,
		show:true
	});
});
$("#confirm-prompt").click(function(){
	$.post("/notify/sendsms", {
		uid: <?=$r->editor->id?>,
		cid: <?=$r->id?>,
		mode: "review"
	}, function(data){
		alert(data);
		$('#prompt-editor').modal("hide");
	});
	return false;
});
$("#confirm-prompt-mail").click(function(){
	$.post("/notify/sendmail", {
		uid: <?=$r->editor->id?>,
		cid: <?=$r->id?>,
		mode: "review"
	}, function(data){
		alert(data);
		//$(".modal-body").html(data);
		$('#prompt-editor-mail').modal("hide");
	});
	return false;
});
</script>
<? } ?>

<div id="delete" class="modal hide">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4>确认删除投稿</h4>
	</div><!--Modal header-->
	<div class="modal-body">
		<p>稿件将永远删除，无法恢复！</p>
	</div><!--Modal body-->
	<div class="modal-footer">
		<a href="#" id="btn-delete-sure" class="btn btn-small btn-danger">
			<i class="icon-trash icon-white"></i> <?=Yii::t('contribute', "I am sure to delete"); ?>
		</a>
		<a href="#" class="btn btn-small" data-dismiss="modal" >
			<i class="icon-off"></i> <?=Yii::t('contribute', "Do not delete"); ?></a>
	</div>
</div>


<!-- end of modal -->
<script type="text/javascript" src="/js/bootstrap-modal.js"></script>
<script type="text/javascript" src="/js/jquery.jgrow.js"></script>
<script type="text/javascript" src="/js/bootstrap-transition.js"></script>
<script type="text/javascript" src="/js/jquery-keyboard.js"></script>
<script type="text/javascript">cid = <?=$r->id?>;</script>
<script type="text/javascript" src="/js/contribute/detail.js"></script>