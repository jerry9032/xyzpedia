<link rel="stylesheet" type="text/css" href="/css/bootstrap.css">
<script type="text/javascript">

	function check(obj) {
		//alert(obj.file.value);
		if (obj.file.value == '') {
			alert("请选择文件。");
			return false;
		} else
			return true;
	}
</script>
<div class="alert alert-info">
	<strong>提示：</strong>在这个页面可以上传此稿件的特色图片~<br>
	我们只支持 <b>JPG</b> 和 <b>GIF</b> 格式的图片，其它格式是不支持的哟~~<br>
	文件大小请控制在 <b>200KB</b> 以内，不然就太大了呢。
</div>
<form action="/admin/postpic/upload/<?=$id?>" method="post" enctype="multipart/form-data" onsubmit="return check(this);">
	<input type="file" name="file" /><br><br>
	<input type="submit" class="btn btn-primary" value="上传" />
</form>