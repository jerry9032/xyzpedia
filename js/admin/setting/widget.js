$(function() {

	// widget sort
	$( ".sortable" ).sortable({
		placeholder: "ui-state-highlight"
	});
	$( ".sortable" ).disableSelection();
	$("form").submit(function(){
		widget_arr = new Array;
		widget_all_arr = new Array;
		widget_i = 0;
		widget_j = 0;
		$("#index-widget .widget_order").each(function(){
			that = this;
			if ($(this).children(".widget-action").attr("show") == 1) {
				widget_arr[widget_i++] = $(that).attr("rel");
			}
			widget_all_arr[widget_j++] = $(that).attr("rel");
		});
		$("#index_widget_order").val(widget_arr.toString());
		$("#index_widget_all").val(widget_all_arr.toString());

		widget_arr = new Array;
		widget_all_arr = new Array;
		widget_i = 0;
		widget_j = 0;
		$("#post-widget .widget_order").each(function(){
			that = this;
			if ($(this).children(".widget-action").attr("show") == 1) {
				widget_arr[widget_i++] = $(that).attr("rel");
			}
			widget_all_arr[widget_j++] = $(that).attr("rel");
		});
		$("#post_widget_order").val(widget_arr.toString());
		$("#post_widget_all").val(widget_all_arr.toString());
	});
	$(".widget-action").click(function(){
		if ($(this).attr("show") == 1) {
			$(this).attr("show", 0);
			$(this).removeClass("btn-danger").addClass("btn-success");
			$(this).html("显示");
			$(this).parent().addClass("ui-state-disabled");
		} else {
			$(this).attr("show", 1);
			$(this).removeClass("btn-success").addClass("btn-danger");
			$(this).html("隐藏");
			$(this).parent().removeClass("ui-state-disabled");
		}
		return false;
	});

	// slider
	$("#post-slider").slider({
		range: "min",
		min: 5,
		max: 20,
		value: $("#post-slider").attr("value"),
		slide: function( event, ui ) {
			$("#post_num").html(ui.value);
			$("#index_recentpost_num").val(ui.value);
		}
		//change: refresh_post,
	});
	$("#comment-slider").slider({
		range: "min",
		min: 5,
		max: 20,
		value: $("#comment-slider").attr("value"),
		slide: function (event, ui) {
			$("#comment_num").html(ui.value);
			$("#index_recentcomment_num").val(ui.value);
		}
		//change: refresh_comment,
	});
});