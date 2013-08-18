<?

if ( isGuest() ) {
	login();
} else {
	alreadyLogin();
}
?>





<? function login() { ?>
<div class="container">
<div class="loginwrap">
	<div class="loginform well">
		<div class="avatar">
			<img src="<?=Yii::app()->request->baseUrl?>/images/avatar/default.jpg" />
		</div>
		<div class="spin" style="display:none;">
			<img src="<?=Yii::app()->request->baseUrl?>/images/spin-big.gif" />
		</div>
		<input id="username" type="text" class="input-xlarge"
			placeholder="<?=Yii::t("actions", "User Name")?>">
		<input id="password" type="password" class="input-xlarge"
			placeholder="<?=Yii::t("actions", "Password")?>">
		<label>
			<button class="btn checkbox"><i class="icon-ok"></i></button><div><?=Yii::t("actions", "Remember Me")?></div>
			<span style="margin-top: 7px; font-size: 12px;" class="pull-right"> <a href="/login/retrieve">忘记密码？</a></span>
			<span style="margin-top: 7px; font-size: 12px;" class="pull-right"> <a href="/register">注册？</a></span>
		</label>

		<div class="modal-footer" style="width:270px;">
			<button class="btn btn-success btn-large" id="loginbtn"><?=Yii::t("actions", "Login")?></button>
		</div>
	</div>
</div>
</div>

<div style="display:none"><div class="afterlogin">
	<div class="avatar">
		<img src="<?=Yii::app()->request->baseUrl?>/images/avatar/default.jpg" />
	</div>
	<?=Yii::t("actions", "Welcome, ")?><span id="nick"></span><br><br>

	<div class="btn-group">
	<button class="btn link" href="/"><i class="icon-arrow-left"></i> 首页</button>
	<button class="btn link" href="/user/info/notify"><i class="icon-info-sign"></i> 消息</button>
	<button class="btn btn-success link" id="panel" href="/admin"><i class="icon-arrow-right icon-white"></i> 后台</button>
	</div>
	<br><br>
</div></div>

<div class="modal hide" id="error-prompt">
	<div class="modal-header">
		<a class="close" data-dismiss="modal">×</a>
		<h4><?=Yii::t("actions", "Error prompt")?></h4>
	</div>
	<div class="modal-body">
		<p id="msg"></p>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-primary btn-small" data-dismiss="modal">关闭</a>
	</div>
</div>

<script type="text/javascript" src="/js/jquery-css-transform.js"></script>
<script type="text/javascript" src="/js/jquery-rotate.js"></script>
<script type="text/javascript" src="/js/bootstrap-modal.js"></script>
<script type="text/javascript">
(function($) {
	$("#loginbtn").click(function(){

		$(".spin").show();
		$(".alert-info").hide("fast");
		$.post("/login/login", {
				username: $("#username").val(),
				password: $("#password").val(),
				remember: $(".checkbox").find("i").hasClass("icon-ok")
			}, function(data){
				successLogin(data);
			}
		);
	});
	$(".checkbox").click(function(){
		icon = $(this).children("i");
		if (icon.hasClass("icon-ok")) {
			icon.removeClass("icon-ok");
		} else {
			icon.addClass("icon-ok");
		}
	});
	$(".link").live("click", function(){
		window.location = $(this).attr("href");
	});
	$("#password").keypress(function(e){
		var key = e.which;
		if (key == 13) {
			$("#loginbtn").click();
		};
	});
})(jQuery);

var successLogin = function(data) {

	$(".spin").hide();

	d = JSON.parse(data);
	if ( d.error == 1 ) {
		$("#msg").html( d.message );
		$('#error-prompt').modal({
			backdrop: true,
			keyboard: true,
			show: true
		});
	} else {
		if ( d.panel == 0 ) {
			$("#panel").remove();
		}
		$('.loginwrap').rotate3Di("toggle", 600, {
			option: "anticlockwise",
			sideChange: function() {
				if ( "" != d.avatar ) {
					$('.afterlogin').find("img").attr("src", 
						"/images/avatar/"+d.avatar);
				}
				$('.loginform').html($('.afterlogin').parent().html());
				$('#nick').html( d.nick_name );
			},
			easing: "swing"
		});
	}
}
</script>
<? } ?>








<? function alreadyLogin() { ?>
<div class="container">

<div class="alert alert-info" style="display:none;">
	<strong><?=Yii::t("actions", "Prompt: ")?></strong>
	<?=Yii::t("actions", "You are already logged out.")?>
</div>

<div class="loginwrap">
	<div class="loginform well">
		<div class="afterlogin">
			<div class="avatar">
				<img src="<?=(user()->avatar ? '/images/avatar/'. user()->avatar : '/images/avatar/default.jpg"')?>" />
			</div>
			<span id="nick"><?=user()->nick_name?> <?=Yii::t("actions", " is already logged in.")?></span>
			<br><br>
			<div class="modal-footer">
				<button class="btn btn-danger btn-large" id="loginbtn"><?=Yii::t("actions", "Logout")?></button>
			</div>
		</div>
	</div>
</div>
</div>
<script type="text/javascript">
(function($){
	$("#loginbtn").click(function(){
		$.ajax({
			url: "/login/logout",
			success: function() {
				$(".alert-info").fadeIn("fast");
				setTimeout(function() {
					location.reload();
				}, 1500);
			}
		});
	});
})(jQuery);
</script>
<? } ?>