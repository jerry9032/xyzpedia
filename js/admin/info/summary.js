$(function () {

	var plot = $.plot($("#placeholder"), [{
		color: "orange",
		data: d2,
		label: "投稿数",
		lines: { show: true, fill: true },
		points: { show: true, fill: true, steps: true },
	}, {
		color: "#9440ED",
		data: d1,
		label: "新用户数",
		lines: { show: true, fill: true },
		points: { show: true, fill: true, steps: true },
	}], {
		grid: { hoverable: true, clickable: true },
		xaxis: { mode: "time", timeformat: "%y.%m" }
	});


	function showTooltip(x, y, contents) {
		$('<div id="tooltip">' + contents + '</div>').css( {
			position: 'absolute',
			display: 'none',
			top: y + 5,
			left: x + 5,
			border: '1px solid #fdd',
			padding: '2px',
			'background-color': '#fee',
			opacity: 0.80
		}).appendTo("body").fadeIn(200);
	}

	var previousPoint = null;
	$("#placeholder").bind("plothover", function (event, pos, item) {
		if (item) {
			if (previousPoint != item.dataIndex) {
				previousPoint = item.dataIndex;
				
				$("#tooltip").remove();
				var x = item.datapoint[0].toFixed(2),
					y = item.datapoint[1].toFixed(2);
				
				showTooltip(item.pageX, item.pageY,
				//			item.series.label + " of " + x + " = " + y);
							item.series.label + " = " + parseInt(y));
			}
		}
		else {
			$("#tooltip").remove();
			previousPoint = null;            
		}
	});

	$("#placeholder").bind("plotclick", function (event, pos, item) {
		if (item) {
			plot.highlight(item.series, item.datapoint);
		}
	});
});