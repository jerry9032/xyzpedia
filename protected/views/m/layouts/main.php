<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?=$this->pageTitle?></title>
<link rel="stylesheet" href="/m/css/jquery.mobile.css" />
<link rel="stylesheet" href="/m/css/bootstrap.mobile.css" />
<link rel="stylesheet" href="/m/css/my.css" />
<link rel="stylesheet" href="/m/css/common.css" />
<script src="/js/jquery.js"></script>
<script src="/m/js/jquery.mobile.js"></script>
<script src="/m/js/my.js"></script>
</head>
<body>

<!-- Home -->
<div data-role="page" id="page1">
	<div id="page-header" data-theme="a" data-role="header">
		<h3>小宇宙百科</h3>
		<div id="nav-bar" data-role="navbar" data-iconpos="bottom">
			<ul>
				<li><a href="/" data-theme="" data-icon="home">主页</a></li>
				<li><a href="/contribute/list" data-theme="" data-icon="plus">投稿</a></li>
				<li><a href="#page1" data-theme="" data-icon="info">问答</a></li>
				<li><a href="#page1" data-theme="" data-icon="alert">通知</a></li>
			</ul>
		</div>
	</div>
	
	<?=$content?>

	<div data-theme="a" data-role="footer">
		<h6 id="footer">
			XYZPedia.org &copy; 2008-2012<br>
			All rights reserved.
		</h6>
	</div>
</div>
</body>
</html>