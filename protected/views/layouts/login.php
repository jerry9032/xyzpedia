<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	
	<link href="/css/bootstrap.css" rel="stylesheet" />
	<link href="/css/login.css" rel="stylesheet" />

	<script type="text/javascript" src="/js/jquery.js"></script>
	<script type="text/javascript" src="/js/bootstrap.js"></script>

	<!--[if IE 6]> 
	<script type="text/javascript"> 
		alert("<?=Yii::t("general", "Unsupported Browser.")?>") 
	</script> 
	<![endif]--> 

	<!--[if lt IE 9]>
	<link rel="stylesheet" type="text/css" href="/css/ie.css" media="screen, projection" />
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<script type="text/javascript" src="/js/json2.js"></script>
	<![endif]-->
	<title><?=CHtml::encode($this->pageTitle)?></title>
</head>

<body class="login">


<?=$content; ?>


</body>
</html>
