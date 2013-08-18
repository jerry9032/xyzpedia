function loadNotify(type, id) {
	if (id == '') {
		return;
	} else {
		var url = '/notify/'+type+'/'+id;
		var box = $('#'+id+'-notify');
		var count = $('#'+id+'-notify-count');
		var refresh = $('#'+id+'-notify-refresh');
	}
	box.html('<img src="/images/spin-small.gif">');
	$.ajax({
		type: 'post',
		url: url,
		success: function(data) {
			box.html('');
			d = eval(data);
			for (var i = 0; i < d.length; i++) {
				li = '<li rel="'+d[i].id+'"">';
				li+= '<a href="'+d[i].href+'" target="_blank">'
				li+= d[i].title + '</a> ';
				li+= '<span>'+ d[i].author + ' ' + d[i].date + '</span>';
				li+= '</li>';
				box.append(li);
			};
			
			if (d.length == 0) {
				box.parent().parent().hide();
			} else if (d.length > 0 && d.length <= 9) {
				count.html('<img class="unread" src="/images/unread/'+d.length+'.png">');
				$('#empty-notify').hide();
			} else {
				count.html(d.length);
				count.addClass("label label-important");
				$('#empty-notify').hide();
			}
		}
	});
}
$("#notify-refresh").click(function(){
	window.location.reload();
});


$("#postreply-notify li a").live("click", function(){
	li = $(this).parent();
	ul = li.parent();
	contrib_id = li.attr("rel");
	count = $("#postreply-notify-count");
	$.post("/notify/delete", {contrib_id: contrib_id}, function(){
		//li.remove();
		$("#postreply-notify li[rel="+contrib_id+"]").remove();
		len = ul.children("li").length;
		if (len == 0) {
			ul.parent().parent().hide();
		} else if (len >= 1  && len <= 9) {
			count.removeClass("label label-important");
			count.html('<img class="unread" src="/images/unread/'+len+'.png">');
		} else {
			count.html(len);
			count.addClass("label label-important");
		}
	});
});

$("#comment-notify li a").live("click", function(){
	li = $(this).parent();
	ul = li.parent();
	contrib_id = li.attr("rel");
	count = $("#comment-notify-count");
	$.post("/notify/delete", {contrib_id: contrib_id}, function(){
		//li.remove();
		$("#comment-notify li[rel="+contrib_id+"]").remove();
		len = ul.children("li").length;
		if (len == 0) {
			ul.parent().parent().hide();
		} else if (len >= 1  && len <= 9) {
			count.removeClass("label label-important");
			count.html('<img class="unread" src="/images/unread/'+len+'.png">');
		} else {
			count.html(len);
			count.addClass("label label-important");
		}
	});
});

$("#at-notify li a").live("click", function(){
	li = $(this).parent();
	ul = li.parent();
	contrib_id = li.attr("rel");
	count = $("#at-notify-count");
	$.post("/notify/delete", {contrib_id: contrib_id}, function(){
		//li.remove();
		$("#at-notify li[rel="+contrib_id+"]").remove();
		len = ul.children("li").length;
		if (len == 0) {
			ul.parent().parent().hide();
		} else if (len >= 1  && len <= 9) {
			count.removeClass("label label-important");
			count.html('<img class="unread" src="/images/unread/'+len+'.png">');
		} else {
			count.html(len);
			count.addClass("label label-important");
		}
	});
});