<? $this->renderPartial('/common/fixed-info'); ?>

<form class="form-horizontal" method="post" action="/user/profile/basic">

	<legend><h4><?=Yii::t("user", "Basic Info")?></h4></legend>
	<fieldset>

	<div class="control-group">
		<label class="control-label" for="user-name">用户名</label>
		<div class="controls">
			<input type="text" id="user-name" value="<?=$user->login?>" disabled>
			<span class="help-inline">用户名改不了呢~</span>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="real-name">真实姓名</label>
		<div class="controls">
			<input type="text" id="real-name" name="real-name" value="<?=$user->real_name?>"
				title="推荐填写真实姓名喔~ ^^">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="nick-name">投稿署名</label>
		<div class="controls">
			<input type="text" id="nick-name" name="nick-name" value="<?=$user->nick_name?>" disabled>
			<span class="help-inline">投稿署名也改不了呢~</span>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="ht-province">家乡</label>
		<div class="controls">
			<select name="ht-province" class="input-medium"></select>
			<select name="ht-city" class="input-medium"></select>
			<select name="ht-area" class="input-medium"></select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="rd-province">常驻地区</label>
		<div class="controls">
			<select name="rd-province" class="input-medium"></select>
			<select name="rd-city" class="input-medium"></select>
			<select name="rd-area" class="input-medium"></select>
		</div>
	</div>
	<script type="text/javascript" src="/js/pcas.js"></script>
	<script type="text/javascript">
	<?
	$ht = explode(",", $user_info->hometown);
	$rd = explode(",", $user_info->resident);
	for ($i = 0; $i <= 2; $i++) {
		if ( empty($ht[$i]) )  $ht[$i] = '';
		if ( empty($rd[$i]) )  $rd[$i] = '';
	} ?>
	new PCAS("ht-province","ht-city","ht-area","<?=$ht[0]?>","<?=$ht[1]?>","<?=$ht[2]?>");
	new PCAS("rd-province","rd-city","rd-area","<?=$rd[0]?>","<?=$rd[1]?>","<?=$rd[2]?>");
	</script>

	<div class="control-group">
		<label class="control-label" for="textarea">个人说明</label>
		<div class="controls">
			<textarea class="span6" id="textarea" name="descript"><?=$user_info->descript?></textarea>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="downline">下线人数</label>
		<div class="controls">
			<input type="text" id="downline" name="downline" value="<?=$user_info->downline?>"
				title="您给几位朋友发小百科呢？">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="avatar-upload">当前头像</label>
		<div class="controls">
			<? if ( !empty($user->avatar) ) {
				$avatar = $user->avatar;
				$avatar_url = '/images/avatar/'. $avatar;
			} else {
				$avatar = '';
				$avatar_url = '/images/avatar/default.jpg';
			}
			?>
			<input class="input input-medium" type="text" name="avatar-current" readonly value="<?=$avatar?>">
			<img class="avatar radius-small" id="avatar-current" src="<?=$avatar_url?>">
		</div>
	</div>

	<div class="control-group">
		<label class="control-label"></label>
		<div class="controls">
<? $this->widget('ext.AvatarUpload.EAjaxUpload',
	array(
		'id'=>'uploadAvatar',
		'config'=>array(
			'action'=>'/user/profile/avatarupload',
			'allowedExtensions'=>array("jpg", "gif", "bmp", "png"),
			'sizeLimit'=>200*1024,// maximum file size in bytes
			'minSizeLimit'=>1*1024,// minimum file size in bytes
			'onComplete'=>"js:function(id, fileName, responseJSON){ resize(responseJSON); }",
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

	<link rel="stylesheet" type="text/css" href="/css/imgareaselect-default.css" />
	<script type="text/javascript" src="/js/jquery.imgareaselect.js"></script>
	<div class="control-group" id="avatar-selection" style="display:none;">
		<label for="" class="control-label"></label>
		<div class="controls">
			<img id="avatar-img" src="/images/avatar/default.jpg">
		</div>
	</div>

	<div class="control-group" id="avatar-confirm" style="display:none;">
		<label class="control-label"></label>
		<div class="controls">
			<button class="btn btn-success" id="btn-avatar-confirm">
				<i class="icon-ok icon-white"></i> 确认裁剪</button>
		</div>
	</div>

	<div class="form-actions">
		<button type="submit" class="btn btn-primary">保存更改</button>
	</div>

	</fieldset>
</form>
<script type="text/javascript" src="/js/user/profile/basic.js"></script>