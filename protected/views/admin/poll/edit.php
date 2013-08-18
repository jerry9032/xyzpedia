<? $this->renderPartial('/common/fixed-info'); ?>

<? $edit_page = isset($poll); ?>
<link rel="stylesheet" href="/css/admin/poll/edit.css">

<? $actionUrl = $edit_page? "/admin/poll/updatepoll": "/admin/poll/insertpoll"; ?>
<form class="form-horizontal" method="post" action="<?=$actionUrl?>" style="border:0;">
	<fieldset>
		<input type="text" id="title" name="title"
			value="<?=($edit_page? $poll->title: '')?>" class="span6">

		<input type="hidden" name="qid" value="<?=($edit_page? $poll->id: 0)?>">
		<input type="hidden" id="num-multiple" value="<?=($edit_page? count($poll->answers): 0)?>">

		<? if ($edit_page) { ?>
		<table class="table table-striped table-bordered answer-list">
			<thead>
				<tr>
					<td>选项文字</td>
					<td>选项链接</td>
					<td>人工编号</td>
					<!--<td>得票</td>-->
					<td></td>
				</tr>
			</thead>
			<tbody>
				<? if($edit_page)
				foreach ($poll->answers as $answer) {
					$this->renderPartial('/admin/poll/answer-template', array(
						'poll' => $poll,
						'answer' => $answer
					));
				} ?>
			</tbody>
			<tfoot>
				<tr>
					<td class="td-input">
						<input type="text" class="answer-text span3" id="answer-text"></td>
					<td class="td-input">
						<input type="text" class="answer-text span3" id="answer-link"></td>
					<td class="td-input">
						<input type="text" class="answer-text input-mini" id="answer-order"></td>
					</td>
					<!--<td></td>-->
					<td class="td-btn">
						<button class="btn btn-success btn-small" id="btn-add-answer">
						<i class="icon-plus icon-white"></i> 添加</button>
					</td>
				</tr>
			</tfoot>
		</table>

		<div class="control-group">
			<div class="control">
			<div class="input-prepend input-append">
				<span class="add-on">选项搜索</span>
				<input type="text" class="add-on span2" id="answer-key" placeholder="关键词">
				<button class="btn" id="btn-answer-search">
					<i class="icon-search"></i> 搜一个
				</button>
			</div>
			<span class="help-inline">支持搜索用户、文章。</span>
			</div>
		</div>
		<? } else { ?>
		<div class="control-group">
			<div class="control">
			<div class="input-prepend input-append">
				<span class="add-on">投票选项</span>
				<span class="add-on red">请先添加“投票”后再添加“投票选项”。</span>
			</div>
			</div>
		</div>
		<? } ?>

		<div class="control-group" id="search-result-list" style="display:none;">
			<div class="control">
			<div class="input-prepend input-append">
				<span class="add-on">搜索结果</span>
				<div class="add-on" style="height:auto;text-align:left;max-width:650px;">
					<strong>用户</strong>
					<div id="user-result-list"></div>
					<div class="clearfix"></div>
					<strong>文章</strong>
					<label class="checkbox">
						<input type="checkbox" id="post-include-author"/> 添加选项时包含作者
					</label>
					<div id="post-result-list"></div>
				</div>
			</div>
			</div>
		</div>

		<? $expire = ($edit_page? $poll->expire: gmtTime()); ?>
		<div class="control-group">
			<div class="control">
			<div class="input-prepend input-append">
				<span class="add-on">截止时间</span>
				<input type="text" class="span2" id="expire-date" name="date"
					value="<?=timeFormat($expire, 'date')?>" readonly>
				<span class="add-on"><i class="icon-calendar"></i></span>
			</div>
			&nbsp;&nbsp;
			<input type="text" class="input-micro" name="hour"
				value="<?=timeFormat($expire, 'hour')?>"> 时
			<input type="text" class="input-micro" name="minute"
				value="<?=timeFormat($expire, 'minute')?>"> 分
			</div>
		</div>

		<? if ($edit_page) { ?>
		<div class="control-group">
			<div class="control">
			<div class="input-prepend">
				<span class="add-on">可选个数</span>
				<select class="span3" id="multiple" name="multiple">
					<? for ($i = 1; $i <= count($poll->answers); $i++) { ?>
					<option value="<?=$i?>" <?=$poll->multiple==$i? "selected": ""?>>
						<?=$i?> 个
					</option>
					<? } ?>
				</select>
			</div></div>
		</div>

		<div class="control-group">
			<div class="control">
			<div class="input-prepend input-append">
				<span class="add-on">投票提示</span><input class="span4" type="text"
				name="prompt" value="<?=$poll->succ_prompt?>">
			</div></div>
		</div>
		<? } ?>

		<? $display_order = array(
			0 => "按添加日期 - 顺序",
			1 => "按添加日期 - 逆序",
			2 => "按投票数 - 顺序",
			3 => "按投票数 - 逆序",
			4 => "按人工编号 - 顺序",
			5 => "按人工编号 - 逆序"
		); ?>
		<div class="control-group">
			<div class="control">
			<div class="input-prepend">
				<span class="add-on">前台排序</span>
				<select class="span3" id="order" name="order">
					<? foreach($display_order as $k => $v) { ?>
					<option value="<?=$k?>" <?=($edit_page && $poll->order==$k)? "selected": ""?>><?=$v?></option>
					<? } ?>
				</select>
			</div></div>
		</div>

		<? $hiddens = array(
			0 => "任何人都可见",
			1 => "投票后可见",
			2 => "隐藏"
		); ?>
		<div class="control-group">
			<div class="control">
			<div class="input-prepend">
				<span class="add-on">投票结果</span>
				<select class="span3" name="hidden">
					<? foreach($hiddens as $k => $v) { ?>
					<option value="<?=$k?>" <?=($edit_page && $poll->hidden_type==$k)? "selected": ""?>>
						<?=$v?>
					</option>
					<? } ?>
				</select>
			</div></div>
		</div>

		<? $force_login = ($edit_page? $poll->force_login: 1); ?>
		<div class="control-group">
			<div class="input-prepend">
			<span class="add-on">登录投票</span>
			<label class="radio">
				<input value="0" type="radio" name="login" <?=(!$force_login? "checked": "")?>>
				<span rel="popover" data-content="游客可以投票，将以记录IP的方式验证用户唯一性。" data-original-title="不需登录">不需登录</span>
			</label>
			<label class="radio">
				<input value="1" type="radio" name="login" <?=($force_login? "checked": "")?>>
				<span rel="popover" data-content="每个网站的注册用户只能投一次票，将以记录用户ID的方式验证用户唯一性。" data-original-title="要求登录">要求登录</span>
			</label>
			</div>
		</div>

		<button class="btn btn-primary">提交投票</button>
		<? if ($edit_page) { ?>
		<button class="btn" href="/admin/poll/result/<?=$poll->id?>">投票结果</button>
		<button class="btn" href="/admin/poll/detail/<?=$poll->id?>">投票详情</button>
		<? } ?>
	</fieldset>
</form>

<link rel="stylesheet" type="text/css" href="/css/bootstrap-datepicker.css">
<script type="text/javascript" src="/js/bootstrap-datepicker.js"></script>
<script type="text/javascript">
qid = <?=$edit_page? $poll->id: 0?>;
</script>
<script type="text/javascript" src="/js/admin/poll/edit.js"></script>