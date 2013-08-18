$("form").validation();

(function($){
	$("form").submit(function(){
		pass1 = $("#pass1").val();
		pass2 = $("#pass2").val();
		if ( pass1 != pass2 ) {
			alert("两次密码输入不一致喔~");return false;
		};
		if ( $("#job").val() == "") {
			alert("工作必选。");return false;
		};
		if ( $("#job").val() == "student" && $("#school").val() == "") {
			alert("如果您是学生，学校是必填的哟~");return false;
		}
		province = $("select[name=ht-province]").val();
		city = $("select[name=ht-city]").val();
		area = $("select[name=ht-area]").val();
		if ( province == "" || city=="" || area=="") {
			alert("家乡必选。");return false;
		};
		province = $("select[name=rd-province]").val();
		city = $("select[name=rd-city]").val();
		area = $("select[name=rd-area]").val();
		if ( province == "" || city=="" || area=="") {
			alert("所在地必选。");return false;
		};
	});

	$("span[rel=popover]").popover({
		trigger: 'hover'
	});

	$("#login").blur(function(){
		$.post("/login/regunique", {
			field: "login",
			value: $("#login").val()
		}, function(data){
			d = JSON.parse(data);
			if (d.exist == 1) {
				alert("用户名已存在，如果您已经注册过，请直接至 xyzpedia.org/login 登录。");
				return false;
			}
		})
	});
	$("#nick_name").blur(function(){
		$.post("/login/regunique", {
			field: "nick_name",
			value: $("#nick_name").val()
		}, function(data){
			d = JSON.parse(data);
			if (d.exist == 1) {
				alert("投稿署名已存在。");
				return false;
			}
		})
	});
	$("#mail").blur(function(){
		$.post("/login/regunique", {
			field: "mail",
			value: $("#mail").val()
		}, function(data){
			d = JSON.parse(data);
			if (d.exist == 1) {
				alert("该邮箱已被注册。");
				return false;
			}
		})
	});
	$("#mobile").blur(function(){
		$.post("/login/regunique", {
			field: "mobile",
			value: $("#mobile").val()
		}, function(data){
			d = JSON.parse(data);
			if (d.exist == 1) {
				alert("该手机号已被注册。");
				return false;
			}
		})
	});
	$("#job").click(function(){
		if ( $(this).val() == "student" ) {
			$("#school-wrap").show("fast");
		} else {
			$("#school-wrap").hide("fast");
		}
	});
})(jQuery);

new PCAS("ht-province","ht-city","ht-area","","","");
new PCAS("rd-province","rd-city","rd-area","","","");