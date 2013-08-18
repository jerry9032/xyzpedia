<? $this->renderPartial("/common/fixed-info"); ?>

<? $edit_page = isset($page); ?>


<form class="form-horizontal form-nodesc" method="post" action="<?=($edit_page? "/admin/page/update/{$page->id}": '/admin/page/insert')?>" style="border:0;">

	<!-- title -->
	<div class="control-group">
	<div class="controls">
		<input type="text" class="span9" name="page-title" id="post-title" placeholder="<?=Yii::t('admin', "Type title here"); ?>" check-type="required" required-message="标题必填哟" value="<?=($edit_page? $page->title: '')?>">
	</div></div>

	<!-- permalink -->
	<div class="control-group">
	<div class="controls" id="permalink">
		<div class="input-prepend">
			<span class="add-on">链接</span><span class="add-on">http://xyzpedia.org/event/</span><span id="slug-edit-wrap"><span id="slug-placeholder" class="add-on"><?=($edit_page? $page->slug: '')?></span></span>
			<input type="hidden" name="page-slug" value="<?=($edit_page? $page->slug: '')?>" check-type="required" required-message="链接必填的哟。"/>
		</div>
		<div class="btn-group" id="slug-btn-group" style="display:none;">
			<button class="btn btn-success btn-small" id="slug-confirm">检查</button><button class="btn btn-small" id="slug-cancel">取消</button>
		</div>
		<span class="help-inline" id="slug-help"></span>
	</div></div>


	<!-- page content -->
	<div class="control-group">
	<div class="controls">
		<textarea class="span9" id="page-content" name="page-content" rows="25" placeholder="<?=Yii::t('admin', "Type content here"); ?>" check-type="required" required-message="正文必填哟" ><?=($edit_page? $page->content: '')?></textarea>
	</div></div>


	<!-- excerpt -->
	<div class="control-group">
	<div class="controls">
		<textarea class="span9" id="page-excerpt" name="page-excerpt" rows="3" placeholder="<?=Yii::t('admin', "Type excerpt here"); ?>" check-type="required" required-message="摘要必填哟"><?=($edit_page? $page->excerpt: '')?></textarea>
	</div></div>


	<div class="row">
	<div class="span5">

	<!-- post date -->
	<? $time = $edit_page? $page->created: gmtTime(); ?>
	<div class="controls">
		<div class="input-prepend input-append date inline" id="page-date" data-date="<?=timeFormat($time, 'date')?>" data-date-format="yyyy-mm-dd">
			<span class="add-on">日期</span><input class="span2" size="16" type="text" name="page-date" value="<?=timeFormat($time, 'date')?>" readonly><span class="add-on"><i class="icon-calendar"></i></span>
		</div>
		&nbsp;&nbsp;
		<div class="inline">
			<input class="input-micro" type="text" name="page-hour" value="<?=timeFormat($time, 'hour')?>"> 时 
			<input class="input-micro" type="text" name="page-minute" value="<?=timeFormat($time, 'minute')?>"> 分
		</div>
	</div>

	<div class="controls">
		<div class="input-prepend">
		<span class="add-on">状态</span><select id="page-status" name="page-status">
			<? $draft = $edit_page && ($page->status=="draft"); ?>
			<option <?=($draft? "": "selected")?> value="publish">发布/定时</option>
			<option <?=($draft? "selected": "")?> value="draft">存为草稿</option>
		</select>
		</div>
	</div>

	<div class="controls">
		<div class="input-prepend">
		<span class="add-on">显示</span><select id="page-show" name="page-show">
			<? $show = $edit_page? $page->show: 0; ?>
			<option <?=($show==0?"selected":"")?> value="0">不显示</option>
			<option <?=($show==2?"selected":"")?> value="2">右侧</option>
			<option <?=($show==4?"selected":"")?> value="4">右侧与主页</option>
		</select>
		</div>
	</div>

	<? $displayUrl = $edit_page? $page->thumbnail : ""; ?>
	<div class="control-group">
	<div class="controls">
		<div class="input-prepend input-append">
			<span class="add-on">特色图片</span><input type="text" class="input thumbnail"
			value="<?=$displayUrl?>" name="page-thumbnail" readonly
			check-type="required" required-message="特色图片一定要上传哟"/><input type="button"
			class="btn" id="upload-thumb-btn"
			value="<?=((!$edit_page)||(empty($page->thumbnail)) ? '上传': '修改')?>"/>
		</div>

		<div class="modal fade hide" id="myModal">
			<div class="modal-header">
				<a class="close" id="close-modal" data-dismiss="modal">×</a>
				<h4>上传特色图片</h4>
			</div>
			<div class="modal-body" style="background-color: #FCFCFC;">
				<iframe id="upload-frame" src="/admin/postpic/upload/<?=($edit_page? $page->id: '0')?>"></iframe>
			</div>
			<div class="modal-footer">
			</div>
		</div>
		<script type="text/javascript" src="/js/bootstrap-modal.js"></script>
		<script type="text/javascript">
			$('#upload-thumb-btn').click(function(){
				$('#myModal').modal({ keyboard: false });
				return false;
			});
		</script>
	</div>
	</div>
	</div><!--end of span5-->

	<div class="span4">
		
		<? if ( !empty($page->thumbnail) ) { ?>
		<? $url = "/images/".$displayUrl; ?>
		<img class="thumbnail" title="特色图片" src="<?=$url?>"/>
		<? } else { ?>
		<img class="thumbnail" title="特色图片" src="/images/thumbnail.jpg"/>
		<? } ?>
	</div><!-- end of right span 4-->
	</div><!-- end of row -->

	<div class="controls btn-toolbar">
		<button type="submit" class="btn btn-primary"><i class="icon-edit icon-white"></i> 提交</button>
		<? if ($edit_page) { ?>
		<button class="btn" href="/event/<?=$page->slug?>"> 查看通告</button>
		<button class="btn btn-danger" id="delete" href="/admin/page/delete/<?=$page->id?>"><i class="icon-trash icon-white"></i> 删除</button>
		<? } ?>
	</div>

	<input type="hidden" id="page-id" name="page-id" value="<?=($edit_page? $page->id: 0)?>">
</form>


<link rel="stylesheet" type="text/css" href="/css/bootstrap-datepicker.css">
<script type="text/javascript" src="/js/bootstrap-datepicker.js"></script>

<link rel="stylesheet" type="text/css" href="/css/markitup.css">
<script type="text/javascript" src="/js/jquery.markitup.js"></script>
<script type="text/javascript" src="/js/jquery.markitup.set.js"></script>

<script type="text/javascript" src="/js/bootstrap-validation.js"></script>
<script type="text/javascript" src="/js/admin/page/edit.js"></script>
