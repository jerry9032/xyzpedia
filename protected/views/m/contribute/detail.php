<link rel="stylesheet" href="/m/css/contribute-detail.css">
<div data-role="content">

	<h1>
		稿件详情
		<a href="/contribute/id/<?=$r->id?>/edit" data-role="button" data-mini="true" data-inline="true" data-theme="b" data-icon="gear" data-iconpos="left" style="margin-top:0;">编辑</a>
	</h1>

	<div id="contrib-title">
		<h3><?=$r->title?></h3>
	</div>

	<div class="contrib-meta">
		<?=$r->author->nick_name?>
		<?=Yii::t('contribute', "Submitted in")?>
		<?=timeFormat($r->created, "full")?>
	</div>
	<div class="contrib-meta">
		<?=Yii::t('contribute', ucfirst($r->status))?>，
		<?=$r->category->short?>，
		<?=(isset($r->editor))? $r->editor->nick_name: "(还没有)"?>初审
	</div>


	<div id="contrib-content">
		<p><?=str_replace("\n", "<p>", $r->content)?></p>
	</div>


	<div id="contrib-comments">
		<h5><i class="icon-comment"></i>
			<?=Yii::t('contribute', "Comments of Contribution"); ?>
			(<?=count($cs)?>)
		</h5>
		<ul data-role="listview" id="comments-list" style="margin-bottom:20px;"><?
			$fid = 0;
			if ( isset($cs) ) {
				foreach($cs as $c) {
					$fid++;
					$this->renderPartial("/m/contribute/comment-template", array(
						"c" => $c,
						"fid" => $fid
					));
				}
			}
			if ($fid == 0) {
				echo "<li id='no-comment'>暂无评论。</li>";
			}
			?>
		</ul>

		<h5><i class="icon-comment"></i> <?=Yii::t('contribute', "Leave your comment"); ?></h5>
		<div class="add-comment">
			<textarea id="comment" name="comment" rows="2" style="height:50px;"></textarea>
			<button id="add-comment" data-role="button" data-inline="true" data-mini="true" data-theme="b">
				<?=Yii::t('contribute', "Submit"); ?>
			</button>
			<img src="/images/spin-small.gif" id="loading" style="display:none;">
		</div>
	</div>
</div>
<script type="text/javascript">
$("#add-comment").click(function(){
	if ( "" == $("#comment").val() ) {
		return false;
	}
	that = this;
	$(this).attr("disabled", "disabled");
	$("#comment").attr("disabled", "disabled");
	$("#loading").show();
	$.ajax({
		type: 'post',
		url: '/contribute/comment/add',
		data: {
			contrib_id: <?=$r->id?>,
			comment: $("#comment").val()
		},
		success: function(data) {
			$("#loading").hide();
			$("#no-comment").hide();
			$("#comments-list").append(data);
			$(that).removeAttr("disabled");
			$("#comment").val("");
			$("#comment").removeAttr("disabled");
		}
	})
});
</script>