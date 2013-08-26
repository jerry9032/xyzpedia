<? $this->renderPartial("/common/fixed-info"); ?>
<link rel="stylesheet" href="/css/jquery-ui-bootstrap.css"/>

<form class="form-horizontal" method="post" action="/admin/file/saveupload">
	<fieldset>
		<legend><h4>文件上传</h4></legend>
		<div class="control-group">
			<label class="control-label">选择文件
				<span rel="popover" data-original-title="上传文件" data-content="选择文件后自动上传，重复上传会覆盖之前的同名文件。上传者以第一次的为准。">
					<i class="icon-question-sign icon-blue"></i>
				</span>
			</label>
			<div class="controls">


<? $this->widget('ext.EAjaxUpload.EAjaxUpload',
	array(
		'id'=>'uploadFile',
		'config'=>array(
			'action'=>'/site/FileAjaxUpload',
			'allowedExtensions'=>array("txt", "mp3"),
			'sizeLimit'=>50*1024*1024,// maximum file size in bytes
			'minSizeLimit'=>1*1024,// minimum file size in bytes
			'onComplete'=>"js:function(id, fileName, responseJSON){ fillForm(responseJSON); }",
			'messages'=>array(
				'typeError'=>"{file} has invalid extension. Only {extensions} are allowed.",
				'sizeError'=>"{file} is too large, maximum file size is {sizeLimit}.",
				'minSizeError'=>"{file} is too small, minimum file size is {minSizeLimit}.",
				'emptyError'=>"{file} is empty, please select files again without it.",
				'onLeave'=>"The files are being uploaded, if you leave now the upload will be cancelled."
			),
			'showMessage'=>"js:function(message){ alert(message); }"
		)
	)
); ?>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label">
				<span rel="popover" data-original-title="文件名" data-content="上传成功后自动填写。">文件名</span>
			</label>
			<div class="controls">
				<input type="text" name="filename" id="filename" check-type="required" readonly>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label">
				<span rel="popover" data-original-title="标题" data-content="上传成功后自动填写，如需修改请手动。">标题</span></label>
			<div class="controls">
				<input type="text" name="title" id="title" check-type="required">
			</div>
		</div>

		<div class="control-group">
			<label class="control-label">分类</label>
			<div class="controls">
				<div id="categories" class="btn-group" data-toggle="buttons-radio">
					<a class="btn" rel="text">下周小百科</a>
					<a class="btn" rel="voice">有声版</a>
					<a class="btn" rel="music">文章配乐</a>
				</div>
				<input type="hidden" name="category" value="" check-type="required">
			</div>
		</div>


		<div class="control-group" id="appoint-time" style="display:none;">
			<label class="control-label">显示时间</label>
			<div class="controls">
				<div class="input-append">
					<input type="text" class="span2" name="appoint-date" id="appoint-date" readonly>
					<span class="add-on"><i class="icon-calendar"></i></span>
				</div> 日
				<input type="text" class="input-micro" name="appoint-hour" value="18" check-type="required"> 时
				<input type="text" class="input-micro" name="appoint-minute" value="0" check-type="required"> 分
				<span class="help-inline">请选择此文件从何时开始显示在“下载”页面中。</span>
			</div>
		</div>

		<div class="form-actions">
			<button type="submit" class="btn btn-primary">保存更改</button>
		</div>

	</fieldset>
</form>


<script type="text/javascript" src="/js/bootstrap-button.js"></script>
<script type="text/javascript" src="/js/jquery-ui.js"></script>
<script type="text/javascript" src="/js/dateformat.js"></script>
<script type="text/javascript" src="/js/bootstrap-validation.js"></script>
<script type="text/javascript">
(function($){
$("#appoint-date").datepicker({
	minDate: new Date(),
	dateFormat: "yy-mm-dd"
});
$(".icon-calendar").click(function(){
	$(this).parent().siblings("input").datepicker("show");
});
$("#appoint-date").val( (new Date()).format("yyyy-MM-dd") );
$("#categories a").click(function(){
	cat = $(this).attr("rel");
	if (cat == "text") {
		$("#appoint-time").show("fast");
	} else {
		$("#appoint-time").hide("fast");
	}
	$("input[name=category]").val( cat );
});
$("form").validation();
})(jQuery);

function fillForm(d) {
	$("#filename").val(d.filename);
	p = d.filename.lastIndexOf('.');
	$("#title").val(d.filename.substr(0,p));
}
</script>
