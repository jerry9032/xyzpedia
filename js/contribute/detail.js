loading = false;

$("#a-load-more").live('click', function(){

	if ( loading ) {
		alert("载入中…别这么着急嘛 *^-^*");
		return false;
	} else
		loading = true;

	start = $(this).attr('start');
	end = $(this).attr('end');
	$("#loading").show();

	$.post('/contribute/loadmorecomments', {
		'cid': cid,
		'start': start,
		'end': end
	}, function(data){
		loading = false;
		$("#li-load-more").remove();
		$(".comments-list").prepend(data);
	});

	return false;
});



$('textarea').jGrow();
$("#add-comment").click(function(){
	addComment();
});
$('textarea').ctrlSubmit(function(){
	addComment();
});

function addComment () {
	if ( "" == $("#textarea").val() ) {
		return false;
	}
	that = this;
	$(this).attr("disabled", "disabled");
	$("#textarea").attr("disabled", "disabled");
	$("#loading").show();
	$.ajax({
		type: 'post',
		url: '/contribute/comment/add',
		data: {
			contrib_id: $("input[name=contrib-id]").val(),
			comment: $("#textarea").val()
		},
		success: function(data) {
			$("#loading").hide();
			$("#no-comment").hide();
			$(".comments-list").append(data);
			$(that).removeAttr("disabled");
			$("#textarea").val("").removeAttr("disabled");
		}
	})
}
$("#btn-delete").live("click", function(){
	$('#delete').modal({
		keyboard:true,
		backdrop:true,
		show:true
	});
});
$("#btn-delete-sure").click(function(){
	$.post("/contribute/delete", {id:$("input[name=contrib-id]").val()}, function(data){
		d = JSON.parse(data);
		if (d.error) {
			alert("删除失败");
		} else {
			window.location = "/contribute/list/?msg=1";
		}
	});
	return false;
});

function reply_to( nick_name ){
	val = $("textarea").val();
	$("textarea").focus();
	$("textarea").val("回复@" + nick_name + ": " + val);
	$("body").animate({ scrollTop: $("html").height() }, 1000);
}

$("a[rel=reply]").live("click", function(){
	nick_name = $(this).attr("nick-name");
	reply_to(nick_name);
});

function delete_comment( id ) {
	$.post('/contribute/comment/delete', {
		id: id
	}, function(data){
		d = JSON.parse(data);
		if ( d.error == 1 ) {
			alert(d.msg);
		} else {
			$('li[comment-id='+id+']').hide('fast');
		}
	});
}

$("a[rel=delete]").live("click", function(){
	id = $(this).attr("comment-id");
	if( confirm("确认删除此条评论吗？") ) {
		delete_comment(id);
	}
});

