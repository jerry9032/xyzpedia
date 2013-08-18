<div class="container">
<div class="loginwrap">
	<div class="retrieveform well">
		<form method="post" action="/login/sendmail">
		<legend><h3>　取回密码</h3></legend>
		<label for="username">用户名必填：</label>
		<input id="username" name="username" type="text" class="input-xlarge"
			placeholder="用户名">
		
		<label for="username">邮箱与投稿署名选填一个：</label>
		<input id="email" name="email" type="text" class="input-xlarge"
			placeholder="邮箱">
		<input id="nickname" name="nickname" type="text" class="input-xlarge"
			placeholder="投稿署名">

		<div class="modal-footer" style="margin-top: 25px;">
			<button class="btn btn-success btn-large" id="registerbtn">
				发送邮件
			</button>
		</div>
	</div>
</div>
</div>
<script type="text/javascript">
(function($){
$("form").submit(function(){
	username = $("#username").val();
	if ( username == "") {
		alert("用户名必填。");
		return false;
	};
	email = $("#email").val();
	nickname = $("#nickname").val();
	if ( email == ""  &&  nickname == "" ) {
		alert("邮箱与投稿署名必须要填一个。");
		return false;
	}
});
})(jQuery);
</script>