$("#downline-slider").slider({
	range: "min",
	min: 0,
	step: 1,
	max: 100,
	value: $("#downline-slider").attr("value"),
	slide: function( event, ui ) {
		$("#downline-num").html(ui.value);
		$("input[name=downline-num]").val(ui.value);
	}
	//change: refresh_post,
});


$("#time-picker button").click(function(){
	$("#time-picker button").each(function(){
		$(this).removeClass("btn-primary");
	});
	$(this).addClass("btn-primary");
	$("input[name=send-time]").val($(this).attr("rel"));
});

$("form").submit(function(){
	if ( $("input[type=checkbox]").attr("checked") ) {

	} else {
		alert('您必须确保每天坚持发哟~~');
		return false;
	}
});