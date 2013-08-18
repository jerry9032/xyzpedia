<? $this->renderPartial("/common/fixed-info"); ?>

<form class="form-horizontal" method="post" action="/user/apply/forward">
<legend><h4>申请转发</h4></legend>
<fieldset>

	<? if ( isset($apply) || can("contribute", "download") ) { ?>
	<div class="explain">
		<div class="alert alert-warning">
			<strong>提示：</strong><?

			if ( isset($apply) ) {
				$info = (object)CJSON::decode($apply->info);
				switch ( $apply->status ) {
					case 1: echo "您的申请待审中，下线人数为 {$info->num} 人。"; break;
					case 2: echo "您的申请已经通过，下线人数为 {$user_info->downline} 人。"; break;
					case 3: echo "您的申请被驳回，原因为 {$info->reason}。"; break;
					default: break;
				}
			} else {
				echo "您已有下载权限，下线人数为 {$user_info->downline} 人。";
			} ?>

		</div>
	</div>
	<? } ?>

	<div class="explain">
		<p><strong>什么是转发？</strong></p>
		<p>转发是指利用“飞信”的方式，向您的朋友发送每天的小百科内容。发送时利用了飞信的“定时”功能。非常方便，您不需早起，只需要简单的几步就可以完成一条小百科的定时工作。</p>
		<p>如果您是新手，我们写了一个定时短信的 <a href="http://ishare.iask.sina.com.cn/f/24411507.html" target="_blank">教程</a>，供您参考，感谢您的支持。</p>
		<p><strong>稿件来源是哪儿？</strong></p>
		<p>在您申请成功后，可以到这个页面 → <a href="/download">http://xyzpedia.org/download</a> 下载下周的小百科内容。一般我们的定稿人员会在<b>每周日傍晚</b>上传，您在<b>周日晚上</b>访问此页面下载即可。</p>
		<p><strong>怎样申请？</strong></p>
		<p>见下。</p>
	</div>

	<hr>

	<div class="control-group">
		<label class="control-label">下线人数</label>
		<div class="controls">
			<div id="downline-slider" class="slider" value="20"></div>
			<label id="downline-num" class="slider-num">20</label>
			<input type="hidden" name="downline-num" value="20">
			<br><br>
			<div class="help-block">此项是指，您（即将）向几位朋友发送小百科。</div>
		</div>		
	</div>

	<div class="control-group">
		<label class="control-label">大约发送时间</label>
		<div class="controls">
			<div class="btn-group" data-toggle="buttons-radio" id="time-picker">
				<button type="button" rel="morning" class="btn btn-primary active">早晨 08:30</button>
				<button type="button" rel="noon" class="btn">中午 12:30</button>
				<button type="button" rel="evening" class="btn">晚上 22:30</button>
			</div>
			<input type="hidden" name="send-time" value="morning">
		</div>
	</div>


	<div class="control-group">
		<label class="control-label">发送承诺</label>
		<div class="controls">
			<label class="checkbox">
				<input type="checkbox">
				我能做到每天按时为朋友们发送小百科
			</label>
		</div>
	</div>

	<div class="form-actions">
		<button type="submit" class="btn btn-primary"><i class="icon-ok icon-white"></i> 提交申请</button>
	</div>

</fieldset>
</form>

<link rel="stylesheet" href="/css/jquery-ui-bootstrap.css">
<script type="text/javascript" src="/js/jquery-ui.js"></script>
<script type="text/javascript" src="/js/user/apply/forward.js"></script>