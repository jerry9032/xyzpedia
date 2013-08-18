
<? $actionName = array(
		"guide"		=> "Contribute guide",
		"submit"	=> "Contribute to XYZPedia",
		"detail"	=> "Contribution detail",
		"list"		=> "Contribution List",
		"comment"	=> "Comments List",
	);
?>
<div class="well left-bar">
	<ul class="nav nav-list">
		<li class="nav-header"><?=Yii::t("contribute", "Guide")?></a></li>
		<li id="guide"><a href="/contribute/guide"><?=Yii::t("contribute", $actionName["guide"])?></a></li>
		

		<li class="nav-header"><?=Yii::t("contribute", "Contribute")?></a></li>
		<li id="submit"><a href="/contribute/submit"><?=Yii::t("contribute", $actionName["submit"])?></a></li>
		<? if ($action == "detail") { ?>
		<li id="detail"><a href="javascript:void(0);"><?=Yii::t("contribute", $actionName["detail"])?></a></li>
		<? } elseif (!empty($r) && $action == "submit") { ?>
		<li id="detail"><a href="/contribute/id/<?=$r->id?>"><?=Yii::t("contribute", $actionName["detail"])?></a></li>
		<? } ?>
		<li id="list"><a href="/contribute/list"><?=Yii::t("contribute", $actionName["list"])?></a></li>

		<? if ( isAdmin() ) { ?>
		<li class="nav-header red">管理员可见</a></li>
		<li id="comment" class="red"><a href="/contribute/comment/list"><?=Yii::t("contribute", $actionName["comment"])?></a></li>
		<? } ?>
	</ul>
</div>
<script type="text/javascript">
(function($){
	$("#<?=$action?>").addClass("active");
})(jQuery);
</script>



<? if ($action == "detail" || (!empty($r) && $action == "submit")) { ?>
<!-- Contribution Info -->
<div class="well left-bar">
	<ul class="nav nav-list">
		<li class="nav-header"><?=Yii::t('contribute', "Contribution Info"); ?></a></li>
	</ul>
	<div class="left-bar-content">
	<p>
		<?=Yii::t('contribute', "Author: ")?><a href="/contribute/list/author/<?=$r->author->id?>"><?=$r->author->nick_name?></a>
	</p>
	<p><?=Yii::t('contribute', "Submit in: ")?><?=timeFormat($r->created, "full")?></p>
	<? if ( !empty($r->appoint)) { ?>
	<p><?=Yii::t('contribute', "Appoint at: ")?><?=timeFormat($r->appoint, "date")?></p>
	<? } ?>
	<p><?=Yii::t('contribute', "Status: ")?><?=Yii::t('contribute', ucfirst($r->status))?></p>
	<p><?=Yii::t('contribute', "Category: ")?><?=$r->category->short?></p>
	<p>
		<?=Yii::t('contribute', "Editor: ")?><a href="<?=(isset($r->editor)? "/contribute/list/editor/".$r->editor->id :"javascript:void(0);")?>"><?=(isset($r->editor))? $r->editor->nick_name: "(还没有)"?></a></p>
	<p><?=Yii::t('contribute', "Words count: ")?><?
		Yii::import("application.extensions.WordsCount");
		$len = WordsCount::length($r->content);
		printf( Yii::t('contribute', "%d Words"), $len);
		$part = WordsCount::parts($len);
		printf( Yii::t('contribute', "(%d Parts)"), $part); ?></p>
	<p><?=Yii::t('contribute', "Authority mode: ")?><?=($r->mode? "半授权模式": "授权模式")?></p>
	<? if (!empty($r->organization)) {
		echo "<p>";
		echo Yii::t('contribute', "Represent org: ");
		echo $r->organization;
		echo "</p>";
	} ?>
	</div>
</div>
<? } ?>

<div class="well left-bar">
	<ul class="nav nav-list">
		<li class="nav-header"><?=Yii::t("contribute", "Search library")?></a></li>
	</ul>
	<div class="form-search left-bar-content">
		<input type="text" class="input-small search-query" id="search-key" placeholder="关键词">
		<button class="btn btn-small" id="do-search"><?=Yii::t('contribute', "Search"); ?></button>
	</div>
</div>
<script type="text/javascript">
$("#do-search").click(function(){
	window.location = '/contribute/list/search/'+$("#search-key").val();
});
</script>