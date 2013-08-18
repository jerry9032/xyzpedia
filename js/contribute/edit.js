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

	$('#contrib-submit').click(function() {
		if (!storage) return;

		// 提交时，清空浏览器保存的草稿
		if (storage) {
			storage.setItem(title_key, "");
			storage.setItem(post_key, "");
		}
	});


	function isset(str) {
		return (str != "" && str != null);
	}

	function display_restore_prompt() {
		if (!storage) return;

		title_val = storage.getItem(title_key);
		post_val = storage.getItem(post_key);

		// 如果浏览器有存草稿，提示恢复
		if ( (isset(title_val) && title_val != $("#contrib-title").val()) || 
		     (isset(post_val)  && post_val  != $("#contrib-content").val()) ) {
			// contrib restore box
			$crb.append(restore_desc);
			$crb.show();
		}
	}

	function contrib_store() {
		if (!storage) return;

		title_val = $('#contrib-title').val();
		post_val = $('#contrib-content').val();

		if (title_val)
			storage.setItem(title_key, title_val);
		if (post_val)
			storage.setItem(post_key, post_val);
	}

	function contrib_restore () {
		if (!storage) return;

		title_val = storage.getItem(title_key);
		post_val = storage.getItem(post_key);

		if (title_val) {
			title_temp = $('#contrib-title').val();
			$('#contrib-title').val(title_val);
		}
		if (post_val) {
			post_temp = $('#contrib-content').val();
			$('#contrib-content').val(post_val);
		}
	}

	function contrib_restore_undo() {
		if (title_temp) {
			$('#contrib-title').val(title_temp);
		}
		if (post_temp) {
			$('#contrib-content').val(post_temp);
		}
	}

	var storage = window.localStorage;
	var title_key = "title" + post_id;
	var title_val = "";
	var post_key = "post" + post_id;
	var post_val = "";
	var title_temp = "";
	var post_temp = "";
	$crb = $("#contrib-restore");
	var restore_desc = "检测到草稿，如之前误操作关闭了浏览器，则需要 " +
			"<button class='btn btn-mini btn-success' id='restore'>恢复</button> " +
			"草稿，否则请忽略此提醒。";
	var restore_undo_desc = "如果发现草稿内容与预期不同，请 " + 
			"<button class='btn btn-mini btn-warning' id='restore-undo'>撤销</button> " +
			"恢复，否则请忽略此提醒。";
	var auto_save_started = 0;

	display_restore_prompt();

	$("button#restore").live("click", function() {
		contrib_restore();
		$crb.html("");
		$crb.removeClass("alert-success").addClass("alert-warning");
		$crb.append(restore_undo_desc);
	});

	$("button#restore-undo").live("click", function() {
		contrib_restore_undo();
		$crb.html("");
		$crb.removeClass("alert-warning").addClass("alert-success");
		display_restore_prompt();
	});

	// 投稿内容发生更改，启用自动保存
	$("#contrib-content").keyup(function(){
		if ( !auto_save_started ) {
			auto_save_started = 1;
			setInterval(contrib_store, 1000);
			$crb.hide();
		}
	});

})(jQuery);
