<? $edit_page = isset($r); ?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/contribute.css" />
<div class="container">


<header class="subhead">
	<h1><?=Yii::t('contribute', "Contribute to XYZPedia"); ?></h1>
</header>


<? if ( !empty($_GET['msg']) ) { ?>
<!-- display the message -->
<div class="alert alert-info alert-fixed">
	<strong><?=Yii::t("general", "Prompt: ")?></strong>
	<?
	$msg_id = $_GET['msg'];
	$msg = array(
		1 => "Modify contribution success.",
		2 => "Submit contribution success.",
	);
	echo Yii::t("contribute", $msg[$msg_id]);
	?>
</div>
<? } ?>


<div class="row">
	<div class="span3">
		<? include "left.php" ?>
	</div>

	<div class="span9">

	<? if ( isset($r->locker) && (gmtTime() - $r->edit_lock < 60) && ($r->locker->id != user()->id)) { ?>
	<!-- edit lock -->
	<div class="alert alert-warning">
		<strong><?=Yii::t("general", "Warning: ")?></strong>
		<?=$r->locker->nick_name?>
		<?=Yii::t("contribute", "is editing this contribution.")?>
	</div>
	<? } else { ?>
		<? if ( $edit_page ) { ?>
		<script type="text/javascript">
		function sendEditLock() {
			pid = <?=$r->id?>;
			uid = <?=user()->id?>;
			jQuery.ajax({
				type: 'post',
				url: "/contribute/editlock", 
				data: {
					pid: pid,
					uid: uid
				},
				success: function() {
					setTimeout(sendEditLock, 60000);
				}
			});
		}
		sendEditLock();
		</script>
		<? } ?>
	<? } ?>

	<div class="alert alert-success" id="contrib-restore" style="display:none;">
	</div>

	<form class="form-horizontal form-nodesc" method="post" action="/contribute/<?=($edit_page? "update/".$r->id :"insert")?>" style="border:0;">

		<div class="control-group">
			<div class="controls">
			<input type="text" class="span9" id="contrib-title" name="contrib-title" placeholder="<?=Yii::t('contribute', "Type title here"); ?>" value="<?=(isset($r)? $r->title:"")?>" check-type="required" required-message="标题必填">
			</div>
		</div>

		<div class="control-group">
			<div class="controls">
			<textarea class="span9" id="contrib-content" name="contrib-content" rows="10" placeholder="<?=Yii::t('contribute', "Type content here"); ?>" check-type="required" required-message="正文必填"><?=(isset($r) ? $r->content: "")?></textarea>
				
			</div>
			<div class="contrib-info">
				<span id="words">0</span>字, 
				<span id="parts">0</span>条, 
				请勿手动添加编号。
				<? if ( empty($r->excerpt) ) { ?>
				<a href="#" id="add-excerpt">点击添加摘要</a>
				<span rel="popover" data-original-title="为什么要添加摘要？" data-content="发布小百科时会将摘要分享到微博，如果作者不自己添加，则默认由小百科的编辑从文中提取。"><i class="icon-question-sign icon-blue"></i></span>
				<span rel="popover" data-original-title="如何添加其它内容？" data-content="如果需要自己配图或配乐 请发送多媒体材料至 edit.xyzpedia@gmail.com"><i class="icon-plus icon-blue"></i></span>
				<? } ?>
			</div>
		</div>
		<script type="text/javascript">
		(function($){
			$("#contrib-content").live("keyup", function(){
				word_count("contrib-content", "words", "parts");
			});
		})(jQuery);
		word_count("contrib-content", "words", "parts");
		</script>

		<div id="excerpt" class="control-group" <?=(empty($r->excerpt)?"style='display:none;'":"")?>>
			<div class="controls">
			<textarea class="span9" id="contrib-excerpt" name="contrib-excerpt" rows="2" placeholder="<?=Yii::t('contribute', "Type excerpt here"); ?>"><?=(isset($r) ? $r->excerpt: "")?></textarea>
			</div>
		</div>

		<div class="control-group">
			<div class="controls">
			<div class="input-prepend">
			<span class="add-on"><?=Yii::t('contribute', "Category"); ?></span>
			<select id="contrib-category" name="contrib-category">
				<option value="0">-- 请选择 --</option>
				<? foreach ($cats as $cat) { ?>
				<? if (isset($r) && ($r->category_id == $cat["ID"])) { ?>
				<option selected value="<?=$cat["ID"]?>"><?=$cat["short"]?></option>
				<? } else { ?>
				<option value="<?=$cat["ID"]?>"><?=$cat["short"]?></option>
				<? }} ?>
			</select>
			</div>
			</div>
		</div>

		<? function select($r, $status) { 
				if ($r->status == $status)
					echo "selected ";
				echo 'value="'.$status.'"';
			}
		?>
		<div class="control-group">
			<div class="input-prepend">
			<span class="add-on"><?=Yii::t('contribute', "Status"); ?></span>
			<select id="contrib-status" name="contrib-status">
			
			<? if ( !$edit_page ) { ?>
				<!-- 如果是新稿件，显示“提请审核”和“存为草稿”，选中未分配 -->
				<option value="neglected" selected><?=Yii::t("contribute","Neglected.action")?></option>
				<option value="draft"><?=Yii::t("contribute","Draft.action")?></option>
			<? } else if ($r->status == "draft") { ?>
				<!-- 如果是草稿，显示“提请审核”和“存为草稿”，选中草稿 -->
				<option value="neglected" id="draft-to-official"><?=Yii::t("contribute","Neglected.action")?></option>
				<option value="draft" selected><?=Yii::t("contribute","Draft.action")?></option>
			<? } else { ?>

				<? if ( $r->status == "neglected" ) { ?>
				<option <?select($r, "neglected");?>><?=Yii::t("contribute","Neglected.action")?></option>
				<? } ?>
			
				<? if ( in_array($r->status, array("pending", "modify", "pass") ) || isAdmin()) { ?>
				<option <?select($r, "pending");?>><?=Yii::t("contribute","Pending.action")?></option>
					<? if ( can("contribute", "first") && ($r->editor_id == user()->id) || isAdmin() ) { ?>
					<!-- 如果稿件在初审状态，来的是有初审权限的初审人 -->
					<option <?select($r, "modify");?>><?=Yii::t("contribute","Modify.action")?></option>
					<option <?select($r, "pass");?>><?=Yii::t("contribute","Pass.action")?></option>
					<? } ?>
				<? } ?>
			
				<? if ( (in_array($r->status, array("pass", "back", "determined"))
					&& can("contribute", "final")) || isAdmin() ) {?>
				<!-- 如果稿件在终审状态，来的是有终审权限的编辑 -->
				<option <?select($r, "back");?>><?=Yii::t("contribute","Back.action")?></option>
				<option <?select($r, "determined");?>><?=Yii::t("contribute","Determined.action")?></option>
				<? } ?>
			
				<? if ( ($r->status == "sent") || isAdmin() ) {?>
				<!-- 已发送的稿子不需要更改状态 -->
				<option <?select($r, "sent");?>><?=Yii::t("contribute", "Sent.action")?></option>
				<? } ?>
				<? if ( isAdmin() ) { ?>
				<!-- 管理员能改成“未决” -->
				<option <?select($r, "hanging");?>><?=Yii::t("contribute", "Hanging.action")?></option>
				<? } ?>
			<? } ?>
			</select>
			</div>
		</div>

		<div class="control-group">
			<span rel="popover" data-original-title="高级选项" data-content="如果是新人投稿，不建议修改“高级选项”。">
			<a class="btn advance-btn btn-small" href="javascript:void(0);"><i class="icon-plus"></i> <?=Yii::t('contribute', "Advance Options"); ?></a>
			</span>
		</div>

		<div class="control-group advance-option-group">
			<? $time = $edit_page? (int)$r->appoint: 0;?>
			<div class="input-prepend input-append date inline" id="appoint-date" data-date="<?=( empty($time) ? timeFormat(gmtTime(), 'date'): timeFormat($time, 'date'))?>" data-date-format="yyyy-mm-dd">
				<span class="add-on">预约发送</span><span rel="popover" data-content="如果此稿件紧急，要在特定日期发送，请选择日期，否则请留空或删除。只能预约下周一开始的日期。" data-original-title="预约发送日期"><input class="span2" size="16" type="text" name="appoint" id="appoint" value="<?=( empty($time)? "": timeFormat($time, 'date'))?>" readonly></span><span class="add-on"><i class="icon-calendar"></i></span>
			</div>
			<button class="btn btn-mini" id="appoint-clear">清空日期</button>
		</div>

		<div class="control-group advance-option-group">
			<div class="input-prepend">
			<span class="add-on">授权模式</span>
			<label class="radio">
				<input value="0" type="radio" name="contrib-mode" <?=($edit_page && $r->mode?"":"checked")?>/>
				<span rel="popover" data-content="小百科编辑可以根据稿件状态，对您的稿件进行内容上的修改，您只能在评论栏中对编辑修改提出建议或删除稿件。" data-original-title="授权模式">授权模式</span>
			</label>
			<label class="radio">
				<input value="1" type="radio" name="contrib-mode" <?=($edit_page && $r->mode?"checked":"")?>/>
				<span rel="popover" data-content="初审阶段您是稿件唯一修改人，小百科编辑只能提出修改建议，不能修改。需要您经常登录网站修改您的稿件，跟踪稿件进度直到“终审通过”。" data-original-title="半授权模式">半授权模式</span>
			</label>
			</div>
		</div>


		<div class="control-group advance-option-group">
			<? if ( !empty($r->organization) ) $org=true; else $org=false; ?>
			<div class="input-prepend">
			<span class="add-on">投稿代表</span>
			<label class="radio"><input type="radio" name="represent" <?=($org?"":"checked")?>/> 个人投稿</label>
			<label class="radio"><input type="radio" name="represent" value="org" <?=($org?"checked":"")?>/> 社团投稿</label>
			</div>
		</div>

		<div class="control-group advance-option-group">
			<div class="input-prepend">
			<span class="add-on">代表社团</span>
			<span rel="popover" data-content="如果您是代表社团投稿，请填写社团名称，如果没有请留空。" data-original-title="代表社团投稿"><input value="<?=($edit_page? $r->organization: "")?>" type="text" name="organization"/></span>
			</div>
		</div>

		<div class="controls btn-toolbar">
			<button type="submit" class="btn btn-primary" id="contrib-submit"><?=($edit_page? "提交修改": "提请审核")?></button>
		</div>

	</form>
</div>
<link rel="stylesheet" type="text/css" href="/css/jquery-ui-bootstrap.css">
<script type="text/javascript" src="/js/jquery.jgrow.js"></script>
<script type="text/javascript" src="/js/jquery-advance-option.js"></script>
<script type="text/javascript" src="/js/jquery-ui.js"></script>
<script type="text/javascript" src='/js/bootstrap-validation.js'></script>
<script>
post_id = <?=($edit_page?$r->id:'0')?>;
edit_page = <?=($edit_page?'1':'0')?>;
</script>
<script type="text/javascript" src="/js/contribute/edit.js?ver=201308182252"></script>

</div></div>
