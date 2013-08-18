<script type="text/javascript">
function upload_start(id, fileName)
{	

}

function uploading(id, fileName, loaded, total)
{
	uploadComplete = false;
	var percent = (loaded / total) * 100;
	console.log('正在上传中...' + percent.toPrecision(3) + '%');
}

function upload_complete(id, filename, data)
{
	console.log(filename);
	console.log(data);
}

</script>


<div class="content">
	<div id="upload-ajax-holder" style="width: 80px; height: 30px; float: left;">
	<?php
	$this->widget('ext.EAjaxUpload.EAjaxUpload', array(
		'id' => 'uploadFile',
		'config' => array(
		'action' => '/site/fileajaxupload',
		'allowedExtensions' => array('jpg', 'jpeg', 'png', 'bmp', 'gif'), 
		'sizeLimit' => 10 * 1024 * 1024, 
		'onSubmit' => 'js:function(id, fileName){ upload_start(id, fileName); }',
		'onProgress' => 'js:function(id, fileName, loaded, total){ uploading(id, fileName, loaded, total); }',
		'onComplete' => "js:function(id,filename,json){upload_complete(id,filename,json)}",
		)
	));
	?>
</div>
