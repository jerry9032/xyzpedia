comments_loading = false;

$("#a-load-more").live('click', function(){

	if ( comments_loading ) {
		alert("载入中…别这么着急嘛 *^-^*");
		return false;
	} else
		comments_loading = true;

	start = $(this).attr('start');
	end = $(this).attr('end');
	$("#loading").show();

	$.post('/post/loadmorecomments', {
		'pid': pid,
		'start': start,
		'end': end
	}, function(data){
		comments_loading = false;
		$("#li-load-more").remove();
		$(".comments-list").prepend(data);
	});

	return false;
});


$("#add-comment").click(function(){
	that = this;
	var post_id = $("#post_id").val();
	var comment = $("textarea[name=comment-box]").val();
	var post_parent = $("input[name=parent]").val();

	$("#loading").show();
	$(this).attr("disabled", "disabled");

	$.post("/post/addcomment", {
		post_id: post_id,
		comment: comment,
		parent: post_parent
	}, function(data){
		$("#no-comments").remove();
		$("ul.comments-list").append(data);
		$("#loading").hide();
		$(that).removeAttr("disabled");
		$("textarea[name=comment-box]").val("");
		$("input[name=parent]").val('0');
	});
});


$(".reply").live('click', function(){
	box = $("textarea[name=comment-box]");
	name = $(this).attr("name");
	rel = $(this).attr("rel");

	$("input[name=parent]").val(rel);
	content = "回复@" + name + ": " + box.val();
	box.focus();
	box.val(content);
});

$(".delete").live('click', function() {

	if ( confirm("确认删除这条评论？") == false )
		return;

	rel = $(this).attr("rel");
	$comment = $(this).parent().parent().parent();
	$.post("/post/deletecomment", {id: rel}, function(data) {
		d = JSON.parse(data);
		if (d.error == 1) {
			alert(d.msg);
		} else {
			$comment.hide("fast");
		}
	});
});