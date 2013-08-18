<? $this->renderPartial("/common/fixed-info"); ?>

<? $edit_page = isset($post); ?>


<form class="form-horizontal form-nodesc" method="post" style="border:0;"
	action="<?=($edit_page? "/admin/post/update/{$post->id}": '/admin/post/insert')?>">

	<!-- title -->
	<div class="control-group">
	<div class="controls">
		<input type="text" class="span9" name="post-title" id="post-title"
			check-type="required" 
			required-message="<?=Yii::t('admin/post', "Title is required."); ?>"
			placeholder="<?=Yii::t('admin/post', "Type title here"); ?>"
			value="<?=($edit_page? $post->title: '')?>">
	</div></div>

	<!-- permalink -->
	<? $time = $edit_page? $post->created: gmtTime(); ?>
	<div class="control-group">
	<div class="controls" id="permalink">
		<div class="input-prepend">
			<span class="add-on"><?=Yii::t('admin/post', "Link"); ?></span><span class="add-on" title="发历史稿的姑娘们，请不要在意这个链接中的年月。">http://xyzpedia.org/<?=timeFormat($time, 'path')?>/</span><span id="slug-edit-wrap"><span id="slug-placeholder" class="add-on"><?=($edit_page? $post->slug: '')?></span></span>
			<input type="hidden" name="post-slug" value="<?=($edit_page? $post->slug: '')?>"
				check-type="required" required-message="链接必填的哟。"/>
		</div>
		<div class="btn-group" id="slug-btn-group" style="display:none;">
			<button class="btn btn-success btn-small" id="slug-confirm">检查</button><button class="btn btn-small" id="slug-cancel">取消</button>
		</div>
		<span class="help-inline" id="slug-help"></span>
	</div></div>

	<div class="control-group">
	<div class="controls">
		<div class="input-prepend">
			<span class="add-on">编号</span><input id="post-serial" name="post-serial" type="text" class="span2" placeholder="小百科编号" autocomplete="off" check-type="numberhyphen" numberhyphen-message="只能填数字和短横线哟，检查一下是不是有空格~" value="<?=($edit_page? $post->serial: '')?>" title="连续编号请用短横线分割，例如“12001-12002”。">
		</div>
	</div></div>


	<div class="control-group">
	<div class="controls">

	<div class="input-prepend">
		<span class="add-on">作者</span><input id="post-author" name="post-author"
			type="text" class="span2" placeholder="投稿署名" autocomplete="off"
			data-provide="typeahead" data-items="4" title="请填写投稿署名，多个作者间请用“&”分割。"
			check-type="required" required-message="作者必填哟"
			value="<?

			if ( isset($post) ) {
				$author_num = 0;
				// registered
				foreach ($post->author as $author) {
					if ($author_num >= 1) {
						echo "&";
					}
					echo $author->nick_name;
					$author_num++;
				}
				// unregistered
				foreach ($post->author_no_reg as $author) {
					if ($author->author_id == 0) {
						if ($author_num >= 1) {
							echo "&";
						}
						echo $author->author_name;
						$author_num++;
					}
				}
			} ?>"
			data-source='<?=$user_list?>'>
		</div>

	</div></div>


	<!-- upload picture -->
	<div class="control-group">
	<div class="controls" style="margin:0;">
		<iframe src="/admin/post/upload" style="border:0;width:500px;height:22px;"></iframe>
	</div></div>

	<div class="control-group">
	<div class="controls">
		<ul id="uppic-list" style="margin:0"></ul>
<script type="text/javascript">
$('.insert-pic').live('click', function(){
	url = $(this).siblings('span').text();
	text = $('#post-content').val();
	img = '<img src="' + url + '" />';
	$('#post-content').val( img + '\n' + text );
	return false;
});
</script>
	</div></div>

	<!-- post content -->
	<div class="control-group">
	<div class="controls">
		<textarea class="span9" id="post-content" name="post-content" rows="25" placeholder="<?=Yii::t('admin/post', "Type content here"); ?>" check-type="required" required-message="正文必填哟" ><?=($edit_page? $post->content: '')?></textarea>
	</div></div>


	<!-- excerpt -->
	<div class="control-group">
	<div class="controls">
		<textarea class="span9" id="post-excerpt" name="post-excerpt" rows="3" placeholder="<?=Yii::t('admin/post', "Type excerpt here"); ?>" check-type="required" required-message="摘要必填哟"><?=($edit_page? $post->excerpt: '')?></textarea>
	</div></div>


	<div class="row">
	<div class="span5">

	<!-- post date -->
	<div class="controls">
		<div class="input-prepend input-append date inline" id="post-date" data-date="<?=timeFormat($time, 'date')?>" data-date-format="yyyy-mm-dd">
			<span class="add-on">日期</span><input class="span2" size="16" type="text" name="post-date" value="<?=timeFormat($time, 'date')?>" readonly><span class="add-on"><i class="icon-calendar"></i></span>
		</div>
		&nbsp;&nbsp;
		<div class="inline">
			<input class="input-micro" type="text" name="post-hour" value="<?=timeFormat($time, 'hour')?>"> 时 
			<input class="input-micro" type="text" name="post-minute" value="<?=timeFormat($time, 'minute')?>"> 分
		</div>
	</div>

	<div class="controls">
		<div class="input-prepend">
		<span class="add-on">分类</span><select id="post-category" name="post-category">
			<option value="0">---- 请选择 ----</option>
			<? foreach ($cats as $cat) { ?>
			<? if (isset($post) && $post->category_id == $cat["ID"]) { ?>
			<option selected value="<?=$cat["ID"]?>">(<?=$cat["short"]?>) <?=$cat["name"]?></option>
			<? } else { ?>
			<option value="<?=$cat["ID"]?>">(<?=$cat["short"]?>) <?=$cat["name"]?></option>
			<? }} ?>
		</select>
		</div>
	</div>

	<div class="controls">
		<div class="input-prepend">
		<span class="add-on">状态</span><select id="post-status" name="post-status">
			<? $draft = $edit_page && ($post->status=="draft"); ?>
			<option <?=($draft? "": "selected")?> value="publish">发布/定时</option>
			<option <?=($draft? "selected": "")?> value="draft">存为草稿</option>
		</select>
		</div>
	</div>

	<? $displayUrl = $edit_page? $post->thumbnail : ""; ?>
	<div class="control-group">
	<div class="controls">
		<div class="input-prepend input-append">
			<span class="add-on">特色图片</span><input type="text" class="input thumbnail"
			value="<?=$displayUrl?>" name="post-thumbnail" readonly
			check-type="required" required-message="特色图片一定要上传哟"/><input type="button"
			class="btn" id="upload-thumb-btn"
			value="<?=((!$edit_page)||(empty($post->thumbnail)) ? '上传': '修改')?>"/>
		</div>

		<div class="modal fade hide" id="myModal">
			<div class="modal-header">
				<a class="close" id="close-modal" data-dismiss="modal">×</a>
				<h4>上传特色图片</h4>
			</div>
			<div class="modal-body" style="background-color: #FCFCFC;">
				<iframe id="upload-frame" src="/admin/postpic/upload/<?=($edit_page? $post->id: '0')?>"></iframe>
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

	</div><!-- end of left span5 -->

	<div class="span4">
		
		<? if ( !empty($post->thumbnail) ) { ?>
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
		<button class="btn" href="/<?=timeFormat($time, 'path')?>/<?=$post->slug?>"> 查看文章</button>
		<button class="btn btn-danger" id="delete" href="/admin/post/delete/<?=$post->id?>"><i class="icon-trash icon-white"></i> 删除</button>
		<? } ?>
	</div>

	<input type="hidden" id="post-id" name="post-id" value="<?=($edit_page? $post->id: 0)?>">
</form>


<link rel="stylesheet" type="text/css" href="/css/bootstrap-datepicker.css">
<script type="text/javascript" src="/js/bootstrap-datepicker.js"></script>

<link rel="stylesheet" type="text/css" href="/css/markitup.css">
<script type="text/javascript" src="/js/jquery.markitup.js"></script>
<script type="text/javascript" src="/js/jquery.markitup.set.js"></script>

<script type="text/javascript" src="/js/bootstrap-validation.js"></script>
<script type="text/javascript" src="/js/admin/post/edit.js"></script>
