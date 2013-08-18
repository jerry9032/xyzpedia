slug_edit_status = false;
current_slug = "";

(function($){
	// slug edit
	$("#slug-placeholder").live("click", function(){
		current_slug = $("#slug-placeholder").html();
		if ( !slug_edit_status ) {
			$("#slug-edit-wrap").html('<input type="text" class="span3" id="post-slug" autocomplete="off" value="' + current_slug + '" />');
			$("#slug-btn-group").css("display", "inline-block");
			$("#slug-help").html("");
			slug_edit_status = true;
		}
	});
	$("#slug-confirm").click(function(){
		$.post("/admin/post/validateslug", {
			post_id: $("#post-id").val(),
			slug: $("#post-slug").val()
		}, function(data){
			d = JSON.parse(data);
			if (d.error == 1) {
				$("#slug-help").html(d.msg);
				$("#slug-confirm").parent().parent().parent().addClass("error");
			} else {
				$("#slug-help").html(d.msg);
				$("#slug-edit-wrap").html('<span id="slug-placeholder" class="add-on">'
					+ d.slug + '</span>');
				$("input[name=post-slug]").val(d.slug);
				$("#slug-confirm").parent().parent().parent().removeClass("error");
				$("#slug-btn-group").hide();
				slug_edit_status = false;
			}
		});
		return false;
	});
	$("#slug-cancel").click(function(){
		$("#slug-edit-wrap").html('<span id="slug-placeholder" class="add-on">'
			+ current_slug + '</span>');
		$("#slug-cancel").parent().parent().parent().removeClass("error");
		$("#slug-btn-group").hide();
		slug_edit_status = false;
		return false;
	});

	$('#post-date').datepicker({
		format: 'yyyy-mm-dd'
	});
	$('#post-content').markItUp(mySettings);

	$('form').submit(function() {
		if ( slug_edit_status || $("#post-slug").val() == "" ) {
			alert("请填写链接或将链接提交检查。");
			return false;
		};
		if ( $("#post-status").val() == "draft" ) {
			$("input[name=post-slug]").removeAttr("check-type");
			$("input[name=post-thumbnail]").removeAttr("check-type");
		} else {
			$("input[name=post-slug]").attr("check-type", "required");
			$("input[name=post-thumbnail]").attr("check-type", "required");
		}
	});
	$('form').validation();
})(jQuery);