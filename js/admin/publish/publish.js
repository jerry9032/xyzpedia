// check if it's turn to you this week
should_user_id = $("#should_user_id").val();
should_user_name = $("#should_user_name").val();
current_user_id = $("#current_user_id").val();

if ( should_user_id != current_user_id )
	alert("本周是 "+should_user_name+" 定稿，不是你喔~");


var contribs_days = 0;

$(function() {

	$(".load-more").live("hover", function(){
		$(this).attr("title", "此作者其它词条");
	});
	$(".load-more").live("click", function(){
		uid = $(this).attr("uid");
		except = $(this).attr("except");
		num = $(this).attr("num");
		that = this;
		if ( $(this).attr("loaded") != "true" ) {
			$.post("/admin/publish/loadmore", {
				"uid": uid,
				"except": except
			}, function(data){
				$(that).attr("loaded", "true");
				$(that).parent().parent().append(data);
			});
		} else {
			$(".more" + $(this).attr("uid")).show("fast");
		}
		$(that).addClass("load-less").removeClass("load-more");
		$(that).html("(-"+num+")");
		return false;
	});

	$(".load-less").live("hover", function(){
		$(this).attr("title", "收起其它词条");
	});
	$(".load-less").live("click", function(){
		$(".more" + $(this).attr("uid")).hide("fast");
		$(this).addClass("load-more").removeClass("load-less");
		$(this).html("(+"+num+")");
		return false;
	});




	$("button.load-content").live("click", function(){
		
		// if too many contribs!!
		if (contribs_days > 7) {
			alert("稿件太多了啦！");
			return false;
		}

		// get ID
		that = this;
		id = $(this).attr("contrib-id");

		// show spin icon
		$(".loading").show();
		$(this).append('<img class="loading" src="/images/spin-small.gif">');

		// disable the envoke button
		$(that).attr("disabled", "disabled");

		// get content
		$.post("/contribute/enqueue", {id: id}, function(data){
			d = JSON.parse(data);

			parts = 0;
			for (var i = 0; i < d.length; i++) {
				// build new accordion
				node = '<div class="group content-'+id+'" contrib-id="'+id+'" parts="'+(d[i].length-1)+'">';
				node+= '<h3><span class="title">' + d[i][0] +'</span></h3><div>';
				for (var j = 1; j < d[i].length; j++) {
					parts++;
					node+= '<p data-toggle="modal" href="#myModal" id="c'+id+'-'+parts+'"'+
							'contrib-id="'+id+'" part="'+parts+'">';
					node+= d[i][j].replace(/[\r\n]|[\n]/, "<br>");
					node+= '</p>';
				}
				node+= '<button class="btn btn-danger btn-small remove pull-right" close-id="'+id+'"><i class="icon-trash icon-white"></i> 移除</button>';
				node+= '</div></div>';
				$("#accordion").append(node);
			};

			// statistic
			contribs_days += d.length;
			$("#contrib-selected").html(contribs_days);

			// rebuild accordion
			$("#accordion").accordion('destroy');
			initAccordion();

			// hide spin icon
			$(".loading").hide();
			$(that).children("img").remove();
			
		});
	});

	// remove the page of accordion
	$('.remove').live("click", function() {
		id = $(this).attr("close-id");

		// enable the envoke button
		$("#contrib-"+id).removeAttr("disabled");

		// statistic
		contribs_days -= $('.content-'+id).length;
		$("#contrib-selected").html(contribs_days);

		$('.content-'+id).remove();

	});


	// edit content
	$('div.group p').live("click", function() {

		// load title
		title = $(this).parent().parent().find(".title").html();
		$("#myModal .modal-header").children("h4").html(title);

		// load content
		content = $(this).html().replace(/<br>/gi,"\n");
		$("#myModal .modal-body").children("textarea").val(content);

		// set id the action button in case of saving
		id = $(this).attr("contrib-id");
		part = $(this).attr("part");
		$("#myModal .modal-footer").children("#save").attr("contrib-id", id)
		$("#myModal .modal-footer").children("#save").attr("part", part);

		// for words count
		$("textarea").blur();
	});

	$("#save").click(function(){

		// get modified content
		content = $(this).parent().parent().find("textarea").val()

		if (content.length > 350) {
			alert("超过350字，无法保存。");
			return false;
		}

		content = content.replace(/[\r\n]|[\n]/, "<br>");;

		// get id and part
		id = $(this).attr("contrib-id");
		part = $(this).attr("part");

		// set content
		$("#c"+id+"-"+part).html(content);

		// hide the dialog
		$("#myModal").modal("hide");
		return false;
	});


	// words count
	$("textarea").bind("click blur focus keyup change", function(){
		len = $(this).val().length;
		$("#words-count").html(len);

		if (len > 350) {
			$("div.meta").removeClass("alert-info");
			$("div.meta").addClass("alert-danger");
		} else {
			$("div.meta").removeClass("alert-danger");
			$("div.meta").addClass("alert-info");
		}
	});


	$("form").submit(function(){

		// check if it's turn to you this week
		if ( should_user_id != current_user_id ) {
			alert("本周是 "+should_user_name+" 定稿，不是你喔~");
			return false;
		}

		// to see if too many days or too few
		if ( contribs_days < 7 ) {
			alert("还不够发~");
			return false;
		}
		if ( contribs_days > 8) {
			alert("就算本周有英文版也发不完啦！");
			return false;
		}
		
		day_mill = 86400 * 1000;
		// file name 2012.10.29-2012.11.7
		today = new Date();
		// 周日getDay的值为0，直接是明天，其它日期拿8去减
		day_offset = ( (today.getDay() == 0)? 1: (8-today.getDate()) );
		// 设置下周一
		var next_monday = new Date();
		var next_sunday = new Date();
		next_monday.setTime(today.getTime() + day_offset * day_mill);
		next_sunday.setTime(today.getTime() + (day_offset + 6) * day_mill);

		var day_iter = 0; // integer
		var day = new Date(); // date

		// gen content and ID list
		str = "";
		id_list = new Array();

		$(".group").each(function(){
			// id list
			id_list[day_iter] = $(this).attr("contrib-id");

			// date
			day.setTime(next_monday.getTime() + day_iter * day_mill);
			str += day.format("yyyy.MM.dd") + ' ';
			day_iter++;

			// header
			parts = $(this).attr("parts");
			if (parts == 1) {
				str += "一条直发\n\n";
			} else {
				str += "分" + parts + "条发\n\n";
			}

			// content
			$(this).find("p").each(function(){
				str += $(this).html().replace(/<br>/gi,"\n");
				str += "\n\n";
			});
			str += "\n\n";
		});
		$("#id_list").val("("+id_list.join()+")");
		$("#from").val(next_monday.format("yyyy.MM.dd"));
		$("#to").val(next_sunday.format("yyyy.MM.dd"));
		$("#data").val(str);

		alert("定稿成功，请刷新页面。");
		//window.location.href =  "/admin/enqueue?msg=1";
	});

	// insert ad
	$(".insert-ad").click(function(){
		var ad_arr = new Array(
			"每一条小百科都是志愿者一字一字编出来的，如果您也愿意分享知识，腾出一点点时间，小宇宙百科诚邀您的加入。投稿请至http://xyzpedia.org 。",
			"每周只需10分钟，即可让Ta每天都收到来自你的分享和祝福！至小百科官网戳“我要转发”并申请，即可下载下周稿件。快与朋友们一起分享吧！",
			"【Q&A】小百科提问可至新浪微博@小宇宙百科网，或前往“小宇宙百科”官方网站或人人网公共主页留言。解答请至小百科官网投稿~",
			"戳小百科主页xyzpedia.org扫描QR码，或输入微信号【xyzpedia】就能加小宇宙百科为微信好友哦~希望大家能将微信版小百科推荐给更多的朋友，特别是联通、电信和在海外的用户，跨越平台，我们将走的更远。",
			"小百科转发和投稿功能请至官网xyzpedia.org，交流请至QQ群：杭州地区165405934、北京地区168734692 、南京地区209952309、上海地区167566007 、其他地区125561399。",
			"【专栏邀请】如果你熟知某一领域，有信心定期提供稿件，小百科诚邀你以个人的名义或与几位志同道合的好友一起开设这一领域的专栏。"
		);
		aid = $(this).attr("aid");

		// get content
		textarea = $(this).parent().parent().parent().siblings("textarea");
		content = textarea.val() + "//" + ad_arr[aid]

		// set content
		textarea.val(content);
		textarea.focus();
		//return false;
	});


	function initAccordion() {
		$("#accordion")
			.accordion({
				active: contribs_days - 1,
				header: "> div > h3",
				autoHeight: false
				// icons: {
				// 	'header': 'ui-icon-triangle-1-e', 
				// 	'headerSelected': 'ui-icon-triangle-1-s'
				// }
			})
			.sortable({
				axis: "y",
				handle: "h3",
				stop: function( event, ui ) {
					// IE doesn't register the blur when sorting
					// so trigger focusout handlers to remove .ui-state-focus
					ui.item.children( "h3" ).triggerHandler( "focusout" );
				}
			});
	}
	initAccordion();
});