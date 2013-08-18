<tr id="answer<?=$answer->id?>">
	<td id="text<?=$answer->id?>"><?=$answer->answer?></td>
	<td id="link<?=$answer->id?>"><?=$answer->link?></td>
	<td id="order<?=$answer->id?>"><?=$answer->order?></td>
	<!--<td id="votes<?=$answer->id?>">
		<? if ( $poll->multiple == 1 ) {
			$presentage = ($poll->votes == 0) ? 0 :round($answer->votes / $poll->votes * 100);
			echo $answer->votes;
			echo " (" .$presentage. "%)";
		} else {
			echo $answer->votes. " 票";
		} ?>
	</td>-->
	<td class="td-btn">
		<div class="btn-group">
			<button class="btn btn-small btn-modify-answer" rel="<?=$answer->id?>">
				<i class="icon-edit"></i> 修改
			</button>
			<? if ($answer->votes == 0) { ?>
			<button class="btn btn-danger btn-small btn-delete-answer" rel="<?=$answer->id?>">
				<i class="icon-trash icon-white"></i> 删除
			</button>
			<? } ?>
		</div>
		<div class="btn-group" style="display:none;">
			<button class="btn btn-primary btn-small btn-confirm" rel="<?=$answer->id?>">
				<i class="icon-ok icon-white"></i> 确定
			</button>
			<button class="btn btn-small btn-cancel" rel="<?=$answer->id?>">
				<i class="icon-remove"></i> 取消
			</button>
		</div>
	</td>
</tr>