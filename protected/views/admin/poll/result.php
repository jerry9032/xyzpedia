<legend><h4>投票结果 - <?=$poll->title?></h4></legend>

<div>
	<p><strong>创建/过期：</strong> <?=timeFormat($poll->created)?> / <?=timeFormat($poll->expire)?></p>
	<p><strong>参与人数/票数：</strong> <?=$poll->voters?> / <?=$poll->votes?></p>
	<p><strong>最多可选：</strong> <?=$poll->multiple?> 个</p>
	<p><strong>选项列表：</strong></p>
</div>

<?
$progress_style = array("", "-info", "-success", "-warning", "-danger"); $i = rand();

// $max = 0;
// foreach($answers as $answer) {
// 	$max = ($max < $answer->votes) ? $answer->votes: $max;
// }
?>
<style type="text/css">
.vote_progress { width: 150px; display: inline-block; }
.vote_progress .progress { margin: 0; }
</style>
<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<td>选项</td>
			<td>得票</td>
		</tr>
	</thead>
	<tbody>
		<? foreach($answers as $answer) { ?>
		<tr>
			<td>
				<a href="<?=$answer->link?>">
				<?=$answer->answer?></a>
			</td>
			<td>
				<? $presentage = ($poll->voters > 0)? round($answer->votes / $poll->voters * 100): 0; ?>
				<div class="vote_progress">
					<div class="progress progress<?=$progress_style[$i++ % 5]?>">
						<div class="bar" style="width: <?=$presentage?>%;"></div>
					</div>
				</div>&nbsp;&nbsp;<?
				if ( $poll->multiple == 1 ) {
					$presentage = ($poll->votes == 0) ? 0 :
							round($answer->votes / $poll->votes * 100);
					echo $answer->votes. " 票";
					echo " (" .$presentage. "%)";
				} else {
					echo $answer->votes. " 票";
				} ?>
			</td>
		</tr>
		<? } ?>
	</tbody>
</table>

<button class="btn" href="/admin/poll/edit/<?=$poll->id?>">返回编辑</button>