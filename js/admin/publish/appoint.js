(function($){
	$("#prompt-commettee").click(function(){
		// 如果已经戳过了
		hits = $(this).attr("hits");
		if (hits > 0) {
			confirm_msg = "这周已经戳过该用户 " + hits + " 次了哟！"
						+"确定还要戳一次吗？";
			confirm_send = confirm(confirm_msg);
			if (confirm_send == false) {
				return false;
			}
		}

		$(this).parent().append(
			"<img id='loading' src='/images/spin-small.gif'/>"
		);
		$(this).attr("disabled", "disabled");
		that = this;
		$.post("/notify/sendsms", {
			uid: $(that).attr("rel"),
			cid: 0,
			mode: "enqueue"
		}, function(data){
			alert(data);
			if (data == "发送成功") {
				$.post("/admin/publish/addhits?id=" + $(that).attr("aid"));
			}
			$("#loading").remove();
		});
	});

	$(".alter-enqueue").click(function(){
		arrange_id = $(this).parent().attr("rel");
		$("#myModal").modal();
	});

	arrange_id = 0;

	$("#alter-enqueue-btn").click(function(){
		$.post("/admin/publish/alter", {
			arrange_name: $("#new_enqueue").val(),
			arrange_id: arrange_id
		}, function(data){
			d = JSON.parse(data);
			if (d.error == 1) {
				alert(d.msg);
			} else {
				$("#arrange"+arrange_id).html(d.msg);
				//alert("修改成功，要发送短信提醒请刷新页面~");
				window.location.reload();
			}
			$("#myModal").modal("hide");
		});
	});

	$("#add-entrust").click(function(){
		$.post("/admin/publish/addappoint", {
			start_date: $("#start-date").val(),
			user_name: $("#add-enqueue").val()
		}, function(data){
			try {
				d = JSON.parse(data);
				if ( d.error > 0 ) alert(d.msg);
			} catch (e) {
				$("table tbody").append(data);
				$("#add-enqueue").val("");
			}
		});
	});

	$("#start-date").datepicker({
		showWeek: true,
		firstDay: 1,
		minDate: new Date(),
		dateFormat: "yy-mm-dd",
		showOtherMonths: true,
		selectOtherMonths: true,
		monthNames: ['一月','二月','三月','四月','五月','六月',
					'七月','八月','九月','十月','十一月','十二月'],
		dayNames: ['星期天', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'],
		dayNamesMin: ['日','一','二','三','四','五','六'],
		prevText:"",
		nextText:"",
		weekHeader: "周"
	});
})(jQuery);
