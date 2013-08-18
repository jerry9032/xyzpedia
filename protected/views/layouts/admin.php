<?
if ( isset(user()->id) ) {
	$now = gmtTime();
	$interval = 600;
	$last_login = isset($_COOKIE['last_login'])? $_COOKIE['last_login']: ($now - $interval);

	if ( $now - $last_login >= $interval ) {  /// every 10 min
		UserInfo::model()->updateByPk(user()->id, array(
			"last_login" => $now,
		));
		setcookie("last_login", $now, $now + 86400);
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<!-- bootstrap CSS framework -->
	<link rel="stylesheet" type="text/css" href="/css/bootstrap.css" media="screen, projection" />
	<!--[if lt IE 9]>
	<link rel="stylesheet" type="text/css" href="/css/ie.css" media="screen, projection" />
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<script type="text/javascript" src="/js/json2.js"></script>
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="/css/jquery-ui-bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="/css/common.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="/css/admin.css" />
	<script type="text/javascript" src="/js/jquery.js"></script>
	<script type="text/javascript" src="/js/jquery-ui.js"></script>
	<script type="text/javascript" src="/js/common-before.js"></script>
	<!--[if IE 6]> 
	<script type="text/javascript"> 
		alert("<?=Yii::t("general", "Unsupported Browser.")?>") 
	</script> 
	<![endif]--> 
	<title><?=CHtml::encode($this->pageTitle)?></title>
</head>

<body>

<? $this->renderPartial('/common/common-navbar'); ?>

<div class="container admin-menu">
	<header class="subhead"><h1><?=Yii::t('admin/sidebar', 'Admin panel')?></h1></header>

	<div class="row">

		<!-- left panel -->
		<div class="span3">
			<? $this->renderPartial("/common/admin-left", array()); ?>
		</div>

		<div class="span9">
			<?=$content?>
		</div>
	</div>
</div>

<? $this->renderPartial('/common/common-footer'); ?>

<script type="text/javascript" src="/js/bootstrap.js"></script>
<script type="text/javascript" src="/js/bootstrap-dropdown.js"></script>
<script type="text/javascript" src="/js/bootstrap-popover.js"></script>
<script type="text/javascript" src="/js/common-after.js"></script>
</body>
</html>
