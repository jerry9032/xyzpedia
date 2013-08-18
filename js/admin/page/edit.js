slug_edit_status = false;
current_slug = "";

(function($){
	// slug edit
	$("#slug-placeholder").live("click", function(){
		current_slug = $("#slug-placeholder").html();
		if ( !slug_edit_status ) {
			$("#slug-edit-wrap").html('<input type="text" class="span3" id="page-slug" autocomplete="off" value="' + current_slug + '" />');
			$("#slug-btn-group").css("display", "inline-block");
			$("#slug-help").html("");
			slug_edit_status = true;
		}
	});
	$("#slug-confirm").click(function(){
		$.post("/admin/post/validateslug", {
			post_id: $("#page-id").val(),
			slug: $("#page-slug").val()
		}, function(data){
			d = JSON.parse(data);
			if (d.error == 1) {
				$("#slug-help").html(d.msg);
				$("#slug-confirm").parent().parent().parent().addClass("error");
			} else {
				$("#slug-help").html(d.msg);
				$("#slug-edit-wrap").html('<span id="slug-placeholder" class="add-on">'
					+ d.slug + '</span>');
				$("input[name=page-slug]").val(d.slug);
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

	$('#page-content').markItUp(mySettings);
	$('#page-date').datepicker({
		format: 'yyyy-mm-dd'
	});

	$('form').submit(function() {
		if ( slug_edit_status || $("#page-slug").val() == "" ) {
			alert("请填写链接或将链接提交检查。");
			return false;
		};
		if ( $("#page-status").val() == "draft" ) {
			$("input[name=page-slug]").removeAttr("check-type");
			$("input[name=page-thumbnail]").removeAttr("check-type");
		} else {
			$("input[name=page-slug]").attr("check-type", "required");
			$("input[name=page-thumbnail]").attr("check-type", "required");
		}
	});
	$('form').validation();
})(jQuery);