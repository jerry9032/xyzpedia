<div data-role="content" id="content">

	<legend><h3>登录</h3></legend>

	<div data-role="fieldcontain" class="ui-field-contain">
		<label for="username" class="ui-input-text">用户名</label>
		<input type="text" name="username" id="username" value="" placeholder="用户名">
	</div>

	<div data-role="fieldcontain" class="ui-field-contain">
		<label for="username" class="ui-input-text">密码 </label>
		<input type="password" name="password" id="password" value="" placeholder="密码">
	</div>

	<div data-role="fieldcontain" class="ui-hide-label">
		<fieldset data-role="controlgroup">
			<legend>记住我</legend>
			<input type="checkbox" name="remember" id="remember" checked="true" />
			<label for="remember">记住我</label>
		</fieldset>
	</div>

	<button data-theme="b" id="loginbtn">登录</button>

</div>

<div id="afterlogin" style="display:none;">

	<h4><?=Yii::t("actions", "Welcome, ")?><span id="nick"></span></h4>
	<p>&nbsp;</p>

	<ul data-role="listview">
		<li><a href="/">首页</a></li>
		<li><a href="/contribute/list">投稿</a></li>
		<li><a href="/user/notify">消息</a></li>
		<li><a href="#">后台</a></li>
	</ul>

	<p>&nbsp;</p>
</div>

<script type="text/javascript">
$("#loginbtn").click(function(){
	$.post("/login/login", {
			username: $("#username").val(),
			password: $("#password").val(),
			remember: ($("#remember").attr("checked") == true)
		}, function(data){
			successLogin(data);
		}
	);
});
function successLogin(data) {
	d = JSON.parse(data);

	if (d.error == 1) {
		alert(d.message);
	} else {
		if ( d.panel == 0 ) {
			$("#panel").remove();
		}
		$("#nick").text(d.nick_name);
		$("#content").html( $("#afterlogin").html() );
	}

}
</script>