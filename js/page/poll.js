$("dl.onevote").click(function(){
	$checkbox = $(this).find(".checkbox");
	$vote_num = $("#vote_num");
	if ( $checkbox.attr('disabled') != 'disabled' ) {
		if ( $checkbox.is(':checked') )  {
			$checkbox.removeAttr("checked");
			$vote_num.val( parseInt($vote_num.val(), 10) - 1 );
		} else {
			$checkbox.attr("checked", "true");
			$vote_num.val( parseInt($vote_num.val(), 10) + 1 );
		}
	}

	if ( $vote_num.val() == multiple ) {
		$('.onevote').each(function(){
			$checkbox = $(this).find('.checkbox');
			if ( $checkbox.is(':checked') ) {
				
			} else {
				$checkbox.attr('disabled', 'true');
			}
		});
	} else {
		$('.onevote').each(function(){
			$checkbox = $(this).find('.checkbox');
			$checkbox.removeAttr('disabled');
		});
	}
});

// fix 
$(".checkbox").click(function(){
	$checkbox = $(this);
	if ( $checkbox.is(':checked') ) {
		$checkbox.removeAttr('checked');
	} else {
		$checkbox.attr('checked', 'true');
	}
});

$("#vote_submit").click(function(){
	$vote_num = $("#vote_num");
	multiple_min = Math.floor(multiple / 2) + 1;

	// 0没有投票
	if ( $vote_num.val() == 0 ) {
		alert('→_→，乃这是什么都没选嘛？');

	// 少于半数
	} else if ( $vote_num.val() < multiple_min ) {
		alert('哎呀能不能投'+ multiple_min +'个以上嘛~最好选'+ multiple +'个喔~');

	// 太多了
	} else if ( $vote_num.val() > multiple ) {
		alert('哎呀似乎多余'+ multiple +'票了喔~要等于'+ multiple +'票啦~');
	} else {
		answer = new Array; i = 0;
		$('.onevote').each(function(){
			$checkbox = $(this).find('.checkbox');
			if ( $checkbox.is(':checked') ) {
				answer[i++] = $checkbox.val();
			}
		});
		$.post('/poll/submit', {
			'qid': qid,
			'answer': answer.toString(),
			'anonymous': $("#anonymous").is(':checked')
		}, function(data){
			d = JSON.parse(data);
			if (d.error) {
				alert(d.msg);
			} else {
				alert(succ_prompt);
				window.location.reload();
			}
		});
	}
	return false;
});

$(document).ready(function(){
	$ifm = $(parent.document).find("#ifm-poll"+qid);
	height = $(document.body)[0].scrollHeight;
	$ifm.height(height);
});