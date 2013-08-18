(function($){
	$("textarea").jGrow();

	$("#add-excerpt").click(function(){
		$("#excerpt").show("fast");
		$(this).hide();
		return false;
	});

	$('form').validation();

	today = new Date();
	days = 8 - today.getDay();
	$("#appoint").datepicker({
		minDate: days,
		firstDay: 1,
		//maxDate: "+2M +10D"
		dateFormat: "yy-mm-dd",
		monthNames: ['一月','二月','三月','四月','五月','六月',
					'七月','八月','九月','十月','十一月','十二月'],
		dayNames: ['星期天', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'],
		dayNamesMin: ['日','一','二','三','四','五','六'],
		prevText:"",
		nextText:""
	});
	$(".icon-calendar").parent().click(function(){
		$("#appoint").datepicker("show");
	});

	$('#appoint-clear').click(function(){
		$("#appoint").val("");
		return false;
	});

	$('#contrib-status').change(function() {
		if ( $(this).val() == "neglected" ) {
			alert("请注意，提请审核后无法切换为草稿，请完成后再提交。");
		}
	});

	function store_article() {
		var storage = window.localStorage;
		var title_key = "title" + post_id;
		var title_val = $('#contrib-title').val();
		var post_key = "post" + post_id;
		var post_val = $('#contrib-content').val();
		if (title_val)
			storage.setItem(title_key, title_val);
		if (post_val)
			storage.setItem(post_key, post_val);
	}

	function restore_article () {
		var storage = window.localStorage;
		var title_key = "title" + post_id;
		var title_val = storage.getItem(title_key);
		var post_key = "post" + post_id;
		var post_val = storage.getItem(post_key);
		if (title_val)
			$('#contrib-title').val(title_val);
		if (post_val)
			$('#contrib-content').val(post_val);
	}

	if (window.localStorage){
		restore_article();
		setInterval(store_article, 1000);	
	} else {
		alert('此浏览器不支持自动保存。');
	}

})(jQuery);