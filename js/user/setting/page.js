$("#contrib-slider").slider({
	range: "min",
	min: 0,
	step: 10,
	max: 50,
	value: $("#contrib-slider").attr("value"),
	slide: function( event, ui ) {
		$("#contrib-num").html(ui.value);
		$("input[name=contrib-num]").val(ui.value);
	}
	//change: refresh_post,
});

$("#illustrate-slider").slider({
	range: "min",
	min: 0,
	step: 10,
	max: 50,
	value: $("#illustrate-slider").attr("value"),
	slide: function( event, ui ) {
		$("#illustrate-num").html(ui.value);
		$("input[name=illustrate-num]").val(ui.value);
	}
	//change: refresh_post,
});