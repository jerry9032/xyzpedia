// obj_id, initial, speed
var scroll_list = {
	"page2_title": {"bel": "2", "init": "50", "speed": "0.7"},
	"page2_01": {"bel": "2", "init": "80", "speed": "0.6"},
	"page2_02": {"bel": "2", "init": "220", "speed": "0.65"},
	"page2_03": {"bel": "2", "init": "300", "speed": "0.5"},
	"page2_04": {"bel": "2", "init": "450", "speed": "0.3"},

	"page3_title": {"bel": "3", "init": "10", "speed": "0.9"},
	"page3_01": {"bel": "3", "init": "20", "speed": "0.6"},
	"page3_02": {"bel": "3", "init": "180", "speed": "1.1"},
	"page3_03": {"bel": "3", "init": "420", "speed": "0.35"},

	"page4_title": {"bel": "4", "init": "50", "speed": "1.1"},
	"page4_01": {"bel": "4", "init": "220", "speed": "1.2"},
	"page4_02": {"bel": "4", "init": "10", "speed": "0.6"},
	"page4_03": {"bel": "4", "init": "120", "speed": "0.2"},
	"page4_04": {"bel": "4", "init": "420", "speed": "0.3"},

	"page5_title": {"bel": "5", "init": "325", "speed": "0.9"},

	"page6_title": {"bel": "6", "init": "205", "speed": "1.2"},
	"page6_01": {"bel": "6", "init": "220", "speed": "0.85"},
	"page6_02": {"bel": "6", "init": "185", "speed": "0.7"},
	"page6_03": {"bel": "6", "init": "160", "speed": "0.6"},
	"page6_04": {"bel": "6", "init": "120", "speed": "0.45"},
};

function scroll_objs() {
	for(var obj_id in scroll_list) {
		obj = $("#" + obj_id);
		para = scroll_list[obj_id];
		scrollTop = $(window).scrollTop() - (para.bel-1)*800;
		scrollNew = scrollTop * para.speed;
		obj.css("top", parseInt(para.init) + parseInt(scrollNew) + "px");
	}
}

$(document).ready(function(){
	$(window).scroll(function() {
		scroll_objs();
	});
});


$("#blessing").live("click", function(){
	xyzpedia = $("#xyzpedia").val();
	if ( xyzpedia != "小宇宙百科") {
		alert("请输入“小宇宙百科”，谢谢~ *^-^*");
		return false;
	}
	lucky_name = $("#lucky_name").val();
	message = $("#message").val();
	if (lucky_name.length <= 1 || lucky_name.length > 4) {
		alert("名字的字数好像不对嘛…麻烦亲认真填写，赠人玫瑰，手有余香~ *^-^*");
		return false;
	}
	$(this).attr("disabled", "disabled");

	$.post("/user/home/blessing", {
		'name': lucky_name,
		'message': message
	}, function(result){
		alert("短信"+result+"！再次感谢~");
		$("#myModal").modal("hide");
	});
	return false;
});
