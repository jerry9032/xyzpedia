<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<? if ($data["error"]) { ?>
	<link rel="stylesheet" type="text/css" href="/css/bootstrap.css">
	<div class="alert alert-danger">
		<strong><?=Yii::t("admin","Error")?></strong><?=$data["message"]?>
	</div>
	<div class="pull-right">
		<a href="javascript:history.back(-1);"><?=Yii::t("admin", "Back")?></a>
	</div>
<? } else { ?>


<link rel="stylesheet" href="/css/jquery.jcrop.css" type="text/css" />
<style type="text/css">
.imgedit-group {
	color: #3A87AD;
	text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
	border: 1px solid #BCE8F1;
	-moz-border-radius: 4px;
	-webkit-border-radius: 4px;
	border-radius: 4px;
	margin-bottom: 12px;
	padding: 5px 10px;
	font-size: 12px;
	background-color: #D9EDF7;
}
.imgedit-group label{
	display: block;
}
.alert-info {
	color: #3A87AD;
	background-color: #D9EDF7;
	padding: 8px 35px 8px 14px;
	text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
	border: 1px solid #BCE8F1;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
	font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
	font-size: 14px;
	line-height: 20px;
}
</style>
<script src="/js/jquery.js" type="text/javascript"></script>
<script src="/js/jquery-jcrop.js" type="text/javascript"></script>
<script type="text/javascript">
	jQuery(function($){
		$('#target').Jcrop({
		onChange: showCoords,
		onSelect: showCoords,
		onRelease: clearCoords
		});
	});

	// Simple event handler, called from onChange and onSelect
	// event handlers, as per the Jcrop invocation above
	function showCoords(c)
	{
		$('#x1').val(c.x);
		$('#y1').val(c.y);
		$('#x2').val(c.x2);
		$('#y2').val(c.y2);
		$('#w').val(c.w);
		$('#h').val(c.h);
	};

	function clearCoords()
	{
		//$('#coords input[type=text]').val('');
		$('#x1').val('');
		$('#y1').val('');
		$('#w').val('');
		$('#h').val('');
		$('#h').css({color:'red'});
		window.setTimeout(function(){
			$('#h').css({color:'inherit'});
		}, 500);
	};

	//等比例缩小图片,并且计算出比例，否在在服务器端截图时候会错位
	function DrawImage(ImgD, FitWidth, FitHeight){
		var image = new Image();
		image.src = ImgD.src;
		if(image.width > 0 && image.height > 0){
			if(image.width/image.height >= FitWidth/FitHeight){
				if(image.width > FitWidth){
					ImgD.width = FitWidth;
					ImgD.height = (image.height*FitWidth)/image.width;
				} else {
					ImgD.width = image.width; 
					ImgD.height = image.height;
				}
				$("#scale").val(image.width / ImgD.width);
			} else {
				if(image.height > FitHeight){
					ImgD.height = FitHeight;
					ImgD.width = (image.width*FitHeight)/image.height;
				} else {
					ImgD.width = image.width; 
					ImgD.height = image.height;
				}
				$("#scale").val(image.width/ImgD.width);
			}
		}
	}
	function check(){
		if($("#x1").val() == "" || $("#src").val() == ""){
			alert("请选择区域");
			return false;
		}
		return true;
	}
</script>
</head>

<body>

<div class="alert alert-info">
	<strong>提示：</strong>在这个页面可以进行图片的裁剪。为了网站显示和手机App都正常而又漂亮的显示，请把图片切成<strong>接近于 1:1</strong> 的大小喔~
</div>
<br style="clear: both" />

<div style="float:left">
	<img src="<?=$url ?>" alt="target" id="target" onload="javascript:DrawImage(this,300,250);"/>
</div>

<div style="float:right">
	<form id="coords"  class="coords" action="/admin/postpic/resize" method="get" onsubmit="return check();">
		<div class="imgedit-group">
			<label>X <input type="text" size="10" id="x1" name="x" readonly/></label>
			<label>Y <input type="text" size="10" id="y1" name="y" readonly/></label>
		</div>
		<div class="imgedit-group">
			<label>宽 <input type="text" size="10" id="w" name="w" readonly/></label>
			<label>高 <input type="text" size="10" id="h" name="h" readonly/></label>
		</div>
		<div class="imgedit-group">
			<input type="hidden" id="src" name="src" value="<?=$url?>" />
			<input type="hidden" id="id" name="id" value="<?=$id?>" />
			<label>缩放 <input type="text" size="10" id="scale" name="scale" readonly/></label>
		</div>
		<div class="imgedit-group">
			<label><input type="submit" value="保存"/></label>
		</div>
	</form>
</div>

</body>

</html>
<? } ?>