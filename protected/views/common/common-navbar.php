<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<a class="brand" href="/">小宇宙百科</a>
			<div class="nav-collapse">
				<ul class="nav">
					<li><a href="/">首页</a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">投稿 <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="/contribute/submit">投稿</a></li>
							<li><a href="/contribute/list/my">投稿列表</a></li>
							<? if ( isAdmin()) { ?>
							<li class="red"><a href="/contribute/comment/list">审稿评论列表</a></li>
							<? } ?>
							<li class="divider"></li>
							<li><a href="/contribute/guide">如何投稿</a></li>
						</ul>
					</li>
					<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">转发 <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<? if ( !can("contribute", "download") ) { ?>
							<li><a href="/user/apply/forward">申请转发</a></li>
							<? } ?>
							<li><a href="/download">下周百科下载</a></li>
							<li class="divider"></li>
							<li><a href="http://ishare.iask.sina.com.cn/f/24411507.html" target="_blank">如何转发</a></li>
						</ul>
					</li>
					<!--<li><a href="#">问答</a></li>-->
					<li><a href="/voice">有声版</a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">关于 <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="/basic-info">基本信息</a></li>
							<li><a href="/about">关于</a></li>
							<li class="divider"></li>
							<li><a href="#">加入我们</a></li>
							<!-- <li class="nav-header">导航头</li> -->
						</ul>
					</li>
				</ul>
				<ul class="nav pull-right">
					<? if ( !isGuest() ) { ?>
					<li><a href="/user/info/notify">通知　
						<? $this->renderPartial('/common/common-notify-icon'); ?></a></li>
					<li class="divider-vertical"></li>
					<li><? // 头像
						if ( !empty(user()->avatar) )
							$avatar = '/images/avatar/'. user()->avatar;
						else
							$avatar = '/images/avatar/default.jpg'; ?>
						<a class="avatar" href="/author/<?=user()->login?>">
							<img src="<?=$avatar?>">
						</a>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<?=Yii::t("general", "Hello, ")?><?=user()->nick_name?>
							<?=((user()->status==1)? "(未验证邮箱)": "")?>
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
							<!--<li class="divider"></li> -->
							<!--<li class="nav-header">动作</li>-->
							<? if ( can("panel", "panel") ) { ?>
							<li><a href="/admin">
								<i class="icon-asterisk"></i> <?=Yii::t("admin", "Admin panel")?>
							</a></li>
							<li class="divider"></li>
							<? } ?>
							<li><a href="/user/info">
								<i class="icon-info-sign"></i>
								<?=Yii::t("user", "My Info")?>
							</a></li>
							<li><a href="/user/profile">
								<i class="icon-user"></i>
								<?=Yii::t("user", "My profile")?>
							</a></li>
							<li><a href="/logout" action="logout">
								<i class="icon-off"></i>
								<?=Yii::t("actions", "Logout")?>
							</a></li>
						</ul>
					</li>
					<? } else { ?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=Yii::t("actions", "Login")?><b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="/login"><i class="icon-user"></i><?=Yii::t("actions", "Login")?></a></li>
							<li class="divider"></li>
							<li><a href="/register"><i class="icon-file"></i><?=Yii::t("actions", "Register")?></a></li>
						</ul>
					</li>
					<? } ?>
				</ul>
			</div><!-- /.nav-collapse -->
		</div>
	</div><!-- /navbar-inner -->
</div><!-- /navbar -->

<!--[if lt IE 8]>
<div class="alert alert-warning">
<?=Yii::t("general", "Bad experiment browser.")?>
</div>
<![endif]-->