$(".alter").click(function(){
	$("#post_id").val($(this).attr("rel"));
	$('#myModal').modal({
		backdrop: true,
		keyboard: true,
		show: true
	});
});
$("#alter_submit").click(function(){
	$.post("/admin/post/altersubmit", {
		post_id: $("#post_id").val(),
		submit_name: $("#new_submit").val(),
	}, function(data){
		d = JSON.parse(data);
		if (d.error == 1) {
			alert(d.msg);
			return false;
		} else {
			$("#myModal").modal("hide");
			$("#submit" + $("#post_id").val()).html(d.msg);
		}
	});
});