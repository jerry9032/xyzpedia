<link rel="stylesheet" href="/css/bootstrap.css">
<link rel="stylesheet" href="/css/page/poll.css">
<meta charset="utf-8" />

<dl class="vote_ti" id="pl_vote_title">
	<dt><?=$poll->title?></dt>
	<dd class="join_nm">
		<div class="vote_joinum">
			<div class="txt">参与人数</div>
			<div class="all_nm " node-type="join_num"><?=$poll->voters?></div>
		</div>
	</dd>
	<dd class="end_time W_textb">
		<s></s><?

		$t = $poll->expire - gmtTime();
		$expired = ($t < 0);
		if ( $expired ) { ?>
			<span node-type="timeLeft">
			投票已于 <span node-type="time"><?=timeFormat($poll->expire, "full")?></span> 结束
			</span>
		<? } else { ?>
			<span node-type="timeLeft">
			距离投票结束还有
			<span node-type="time"><?
				$day = floor( $t / (24*60*60) );
				$hour = floor( ($t - $day *24*60*60) / (60*60));
				$min = floor( ($t - $day *24*60*60 - $hour*60*60) / 60);
				$sec = $t - $day *24*60*60 - $hour *60*60 - $min *60;
				echo $day.'天'.$hour.'小时'.$min.'分'.$sec.'秒';
			?></span>
			</span>
		<? } ?>
		<span class="vline">|</span> 请选择 <?=$poll->multiple?> 项
	</dd>
</dl>


<div class="vote_txtlist">

<?
// 先按登录ID查找是否投过票
if ( !isGuest() ) {
	$votes = PollLog::model()->findAllByAttributes(array(
		"user_id" => user()->id,
		"question_id" => $poll->id
	));
}
// 如果ID查不到，看看IP投票的记录
if ( empty($votes) ) {
	$votes = PollLog::model()->findAllByAttributes(array(
		"ip" => getIP(),
		"question_id" => $poll->id
	));
}
// 构建已投选项
$vs = array(); $i = 0;
foreach ($votes as $vote) {
	$vs[$i++] = $vote->answer_id;
}

// 计算投票情况
if ( empty($votes)) {
	// 如果没投过票，看看是否要求登录而没登陆
	if ( $poll->force_login && isGuest() )
		$voted = 2; // 要求登录后投票
	// 不要求登录 或 已经登录的话，正常显示
	else
		$voted = 0;
} else {
	// 如果已经投过票了
	$voted = 1;
}

$progress_style = array("", "-info", "-success", "-warning", "-danger");
$i = rand();


// 挑选出票数最多的那个选项
// $max = 0;
// foreach ($poll->answers as $answer) {
// 	$max = ($max < $answer->votes) ? $answer->votes : $max;
// }

$criteria = new CDbCriteria;
$criteria->addCondition("question_id=".$poll->id);
switch ($poll->order) {
	case '0': $criteria->order = "id"; break;
	case '1': $criteria->order = "id desc"; break;
	case '2': $criteria->order = "votes"; break;
	case '3': $criteria->order = "votes desc"; break;
	case '4': $criteria->order = "`order`"; break;
	case '5': $criteria->order = "`order` desc"; break;
	default: $criteria->order = "id"; break;
}
$answers = PollAnswer::model()->findAll($criteria);
$open_result = ($poll->hidden_type == 0 || ($poll->hidden_type == 1 && $voted == 1));

foreach($answers as $answer) { ?>
<dl class="onevote">
	<dt>
		<span class="op_box">
			<? if ( $expired ) { ?>
				<input type="checkbox" class="checkbox" disabled="true">
			<? } elseif ( !$voted ) { ?>
				<input type="checkbox" class="checkbox" value="<?=$answer->id?>">
			<? } else {
				if ( in_array($answer->id, $vs)) {
					$browser = getBrowserInfo();
					if ( $browser['browser'] == 'IE7' ) {
						// 丑一点没办法  ?>
						<i style="font-weight:bold;">&radic;</i>
					<? } else {
						// 美丽的勾 ?>
						<i class="icon-ok"></i>
					<? }
				}
			} ?>
		</span>
		<span class="text">
			<? if ( $poll->order == 4 || $poll->order == 5)
				$text = $answer->order . ' - ' . $answer->answer;
			else
				$text = $answer->answer; ?>

			<? if ( !empty($answer->link) ) { ?>
				<a href="<?=$answer->link?>" target="_blank"><?=$text?></a>
			<? } else { ?>
				<?=$text?>
			<? } ?>
		</span>
	</dt>
	

	<dd>
		<div class="vote_numline">
			<? $presentage = ($poll->voters == 0) ? 0 :
					round($answer->votes / $poll->voters * 100); ?>
			<div class="vote_progress" style="float:left;">
				<div class="progress progress<?=$progress_style[$i++ % 5]?>">
					<div class="bar" style="width: <?=($open_result? $presentage: 0)?>%;"></div>
				</div>
			</div>

			<? if ( !$open_result ) {  // 隐藏结果 ?>
				<span class="num_txt textb">隐藏</span>
			<? } elseif ( $poll->multiple == 1) {
				// 单选，只显示百分比； ?>
				<span class="num_txt textb"><?=$presentage?> %</span>
			<? } else {
				// 多选，显示票数相对比例。?>
				<span class="num_txt textb"><?=$answer->votes?> 票</span>
			<? } ?>
		</div>
	</dd>
</dl>
<? } ?>

</div>
<div class="clearfix"></div>

<div class="vote_optarea">
	<input type="hidden" id="vote_num" value="0">
	<? if ( $expired ) { ?>
		<button class="btn" id="voted" disabled="true">投票已结束</button>
	<? } elseif ( $voted == 0 ) { ?>
		<button class="btn btn-success" id="vote_submit">
			<i class="icon-align-left icon-white" style="-webkit-transform: rotate(-90deg);-moz-transform: rotate(-90deg);-o-transform: rotate(-90deg);"></i> 投票
		</button>
		<span class="detail W_textb" node-type="vote_info">
			<input id="anonymous" type="checkbox" class="checkbox" value="1" name="anonymous" node-type="anonymous"><label for="anonymous">匿名</label>
		</span>
	<? } elseif ($voted == 1) { ?>
		<button class="btn" id="voted" disabled="true">您已经投过票了</button>
	<? } elseif ($voted == 2) { ?>
		<a class="btn btn-success" href="/login">请登录后投票</a>
	<? } ?>

	<? if (isAdmin()) { ?>
		<a class="btn" href="/admin/poll/result/<?=$poll->id?>"
				target="parent">查看结果</a>
		<a class="btn" href="/admin/poll/detail/<?=$poll->id?>"
				target="parent">投票详情</a>
	<? } ?>
</div>

<script type="text/javascript">
multiple = <?=$poll->multiple?>;
qid = <?=$poll->id?>;
succ_prompt = "<?=$poll->succ_prompt?>";
</script>
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/page/poll.js"></script>