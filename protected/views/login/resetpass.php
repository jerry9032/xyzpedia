<div class="container">
<div class="loginwrap">
	<div class="retrieveform well">
		<form method="post" action="/login/resetpass">
		<legend><h3>　重置<?=$user->nick_name?>的密码</h3></legend>
		<input id="password1" name="password1" type="password" class="input-xlarge"
			placeholder="密码">

		<input id="password2" name="password2" type="password" class="input-xlarge"
			placeholder="重复密码">
		<input type="hidden" name="activation_key" value="<?=$activation_key?>">

		<div class="modal-footer" style="margin-top: 25px;">
			<button class="btn btn-success btn-large" id="registerbtn">
				提交
			</button>
		</div>
		</form>
	</div>
</div>
</div>
<script type="text/javascript">
(function($){
$("form").submit(function(){
	pass1 = $("password1").val();
	pass2 = $("password2").val();
	if ( pass1 != pass2 ) {
		alert("两次密码不相同。");
		return false;
	}
});
})(jQuery);
</script>