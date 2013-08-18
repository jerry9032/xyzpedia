<?
	$actionName = array(
		"contribute"=> "Contribute Info",
		"notify"	=> "Notify",
		"atme"		=> "At me",
		
		"basic"		=> "Basic Info",
		"contact"	=> "Contact Info",
		"tags"		=> "Personal Tags",
		"career"	=> "Education & Career Info",

		"setting"	=> "Basic Settings",
		"page"		=> "Personal Page Display",

		"forward"	=> "Apply for Forward",
		"manage"	=> "Apply for Management",
	);
?>

<div class="well" style="padding: 8px 0;">
	<ul class="nav nav-list">
		<li class="nav-header"><?=Yii::t("user", "User Info")?></a></li>
		<!--<li><a href="/user/info/contribute">
		<?=Yii::t("user", $actionName["contribute"])?></a></li>-->
		<li><a href="/user/info/notify"><?=Yii::t("user", $actionName["notify"])?></a></li>
		<li><a href="/user/info/atme"><?=Yii::t("user", $actionName["atme"])?></a></li>

		<li class="nav-header"><?=Yii::t("user", "Personal Info")?></li>
		<li><a href="/user/profile/basic"><?=Yii::t("user", $actionName["basic"])?></a></li>
		<li><a href="/user/profile/contact"><?=Yii::t("user", $actionName["contact"])?></a></li>
		<li><a href="/user/profile/tags"><?=Yii::t("user", $actionName["tags"])?></a></li>
		<li><a href="/user/profile/career"><?=Yii::t("user", $actionName["career"])?></a></li>
		
		<li class="nav-header"><?=Yii::t("user", "Account Setting")?></li>
		<li><a href="/user/setting/basic"><?=Yii::t("user", $actionName["setting"])?></a></li>
		<li><a href="/user/setting/page"><?=Yii::t("user", $actionName["page"])?></a></li>

		<li class="nav-header"><?=Yii::t("user", "Apply")?></li>
		<li><a href="/user/apply/forward"><?=Yii::t("user", $actionName["forward"])?></a></li>
		<li><a href="/user/apply/management"><?=Yii::t("user", $actionName["manage"])?></a></li>

		<li class="divider"></li>
		<li><a href="#">帮助</a></li>
	</ul>
</div>
<script type="text/javascript" src='/js/common/menu-highlighter.js'></script>