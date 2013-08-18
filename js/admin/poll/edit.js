(function($){

$('#expire-date').datepicker({
	format: 'yyyy-mm-dd'
});
$('#btn-add-answer').click(function(){
	answer = $("#answer-text").val();
	link = $("#answer-link").val();
	order = $("#answer-order").val();

	if ( answer == "" || link == "") {
		alert("请填写完整。");
	}

	$.post('/admin/poll/addanswer', {
		'qid': qid,
		'answer': answer,
		'link': link,
		'order': order
	}, function(data){
		$('table tbody').append(data);

		$num_multiple = $('#num-multiple');
		num_multiple = parseInt($num_multiple.val());
		$num_multiple.val(num_multiple++);
		option = '<option value="' + num_multiple + '"">' + num_multiple + ' 个</option>';
		$('#multiple').append(option);

		$("#answer-text").val("");
		$("#answer-link").val("");
		$("#answer-order").val("");
	});

	return false;
});

$(".btn-modify-answer").click(function(){
	aid = $(this).attr('rel');
	$td_link = $('#link' + aid);
	$td_text = $('#text' + aid);
	$td_order = $('#order' + aid);
	old_link = $td_link.text();
	old_text = $td_text.text();
	old_order = $td_order.text();
	edit_link = '<input type="text" class="span3" id="edit-link' + aid + '" value="' + old_link + '">'
	edit_text = '<input type="text" class="span3" id="edit-text' + aid + '" value="' + old_text + '">'
	edit_order = '<input type="text" class="input-mini" id="edit-order' + aid + '" value="' + old_order + '">'

	$(this).parent().hide().siblings().show();
	$td_link.html(edit_link);
	$td_text.html(edit_text);
	$td_order.html(edit_order);
	return false;
});

$(".btn-delete-answer").click(function(){
	if ( confirm('确认删除这个选项？') ) {
		aid = $(this).attr('rel');
		$.post('/admin/poll/deleteanswer', {
			'aid': aid
		}, function(data){
			d = JSON.parse(data);
			if ( d.error ) {
				alert(d.msg);
			} else {
				$("#answer" + aid).hide('fast');
			}
		});
	}
	return false;
});

$(".btn-confirm").click(function(){
	aid = $(this).attr('rel');
	$td_link = $('#link' + aid);
	$td_text = $('#text' + aid);
	$td_order = $('#order' + aid);
	$edit_link = $('#edit-link' + aid);
	$edit_text = $('#edit-text' + aid);
	$edit_order = $('#edit-order' + aid);
	new_link = $edit_link.val();
	new_text = $edit_text.val();
	new_order = $edit_order.val();

	$btn_group = $(this).parent();
	$.post('/admin/poll/updateanswer', {
		'aid': aid,
		'link': new_link,
		'order': new_order,
		'answer': new_text
	}, function(data){
		d = JSON.parse(data);
		if ( d.error ) {
			alert(d.msg);
		} else {
			$btn_group.hide().siblings().show();
			$td_link.html(new_link);
			$td_text.html(new_text);
			$td_order.html(new_order);
		}
	});
	return false;
});

$(".btn-cancel").click(function(){
	$td_link = $('#link' + aid);
	$td_text = $('#text' + aid);
	$td_order = $('#order' + aid);
	$edit_link = $('#edit-link' + aid);
	$edit_text = $('#edit-text' + aid);
	$edit_order = $('#edit-order' + aid);

	$btn_group = $(this).parent();
	$btn_group.hide().siblings().show();

	$td_link.html($edit_link.val());
	$td_text.html($edit_text.val());
	$td_order.html($edit_order.val());
	return false;
});


$("#btn-answer-search").click(function(){
	key = $("#answer-key").val();
	if ( key == "") {
		alert("请填写关键词。"); return false;
	}
	$("#search-result-list").show("fast");

	

	$.post('/admin/poll/search', {
		'key': key
	}, function(data){
		d = JSON.parse(data);
		if ( d.error ) {
			alert(d.msg); return false;
		}

		user_not_found = "<span id='user-not-found'>" +
						"没有找到“登录名”或者“投稿署名”中包含此关键字的用户。</span>";
		post_not_found = "<span id='post-not-found'>" +
						"没有找到“标题”或者“小百科编号”中包含此关键字的文章。</span>";
		$("#user-result-list").html(user_not_found);
		$("#post-result-list").html(post_not_found);
		for (var key in d.users) {
			u = d.users[key];
			user = '<a class="user-search-result" href="#" rel="'+u.answer_link+'">';
			user+= u.answer;
			user+= '</a><span class="devider">&nbsp;|&nbsp;</span>';
			$("#user-not-found").hide();
			$("#user-result-list").append(user);
		}
		for (var key in d.posts) {
			p = d.posts[key];
			post = '<a class="post-search-result" href="#" rel="' + p.answer_link
				+ '" author="' + p.author + '" serial="' + p.serial + '">';
			post+= p.answer;
			post+= '</a><span class="devider">&nbsp;|&nbsp;</span>';
			$("#post-not-found").hide();
			$("#post-result-list").append(post);
		}
	});
	return false;
});

$(".user-search-result").live("click", function(){
	text = $(this).text();
	link = $(this).attr("rel");
	$("#answer-text").val(text);
	$("#answer-link").val(link);
	$("#answer-order").val("0");
	return false;
});

$(".post-search-result").live("click", function(){
	post_include_author = $("#post-include-author").is(":checked");
	author = $(this).attr("author");

	if ( post_include_author && (author != "")) {
		text = $(this).text() + "（作者：" + author + "）";
	} else {
		text = $(this).text();
	}
	link = $(this).attr("rel");
	order = $(this).attr("serial");
	$("#answer-text").val(text);
	$("#answer-link").val(link);
	$("#answer-order").val(order);

	return false;
});

})(jQuery);