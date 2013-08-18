$("#yw0 .btn-success").live("click", function(){
	that = this;
	if ($(this).attr("req-type") != "fwd") {
		alert("还不支持通过此类申请。");
		return false;
	}
	$.post('/admin/user/approve', {
		type: $(this).attr("req-type"),
		aid: $(this).attr("rel")
	}, function(){
		$(that).parent().parent().hide("fast");
	});
});

$("#yw0 .btn-danger").live("click", function(){
	alert('为什么要驳回他呢？通过吧。。\n\n（其实是因为没做我会告诉你么）');
});