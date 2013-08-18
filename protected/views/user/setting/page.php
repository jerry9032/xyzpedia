<? $this->renderPartial('/common/fixed-info'); ?>

<form class="form-horizontal" method="post" action="/user/setting/page">
	<legend><h4><?=Yii::t("user", "Personal Page Display")?></h4></legend>
	<fieldset>
	<div class="control-group">
		<label class="control-label" for="show-hometown">主页上是否显示</label>
		<div class="controls">
			<label class="checkbox">
				<input type="checkbox" id="show-hometown" name="show-hometown" value="1"<?
					if ($user_setting->show_home)
						echo " checked";
					?>>
				我的家乡
			</label>
			<label class="checkbox">
				<input type="checkbox" id="show-resident" name="show-resident" value="1"<?
					if ($user_setting->show_resident)
						echo " checked";
					?>>
				我的常驻地区
			</label>
			<label class="checkbox">
				<input type="checkbox" id="show-tags" name="show-tags" value="1"<?
					if ($user_setting->show_tags)
						echo " checked";
					?>>
				我的个人标签
			</label>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="contrib-num">
			<span rel="popover" data-original-title="投稿篇数" data-content="0代表无限制。">
				显示投稿篇数
			</span>
		</label>
		<div class="controls">
			<? $contrib_num = $user_setting->contrib_num; ?>
			<div id="contrib-slider" class="slider" value="<?=$contrib_num?>"></div>
			<label id="contrib-num" class="slider-num"><?=$contrib_num?></label>
			<input type="hidden" name="contrib-num" value="<?=$contrib_num?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="illustrate-num">
			<span rel="popover" data-original-title="配图篇数" data-content="同上。">
				显示配图篇数
			</span>
		</label>
		<div class="controls">
			<? $illustrate_num = $user_setting->illustrate_num; ?>
			<div id="illustrate-slider" class="slider" value="<?=$illustrate_num?>"></div>
			<label id="illustrate-num" class="slider-num"><?=$illustrate_num?></label>
			<input type="hidden" name="illustrate-num" value="<?=$illustrate_num?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="contrib-num">查看我的主页</label>
		<div class="controls">
			<button class="btn" href="/author/<?=$user->login?>"><?=$user->nick_name?> <i class="icon-arrow-right"></i></button>
		</div>
	</div>
	<div class="form-actions">
		<button type="submit" class="btn btn-primary">保存更改</button>
	</div>

	</fieldset>
</form>

<link rel="stylesheet" href="/css/jquery-ui-bootstrap.css">
<script type="text/javascript" src="/js/jquery-ui.js"></script>
<script type="text/javascript" src="/js/user/setting/page.js"></script>