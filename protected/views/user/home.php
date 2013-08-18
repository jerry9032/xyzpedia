<link rel="stylesheet" type="text/css" href="/css/widget.css" media="screen, projection">
<link rel="stylesheet" type="text/css" href="/css/author.css" media="screen, projection">

<div class="container">


<div class="row">
	
	<header class="subhead">
	<h1>
		关于 <?=$user->nick_name?>　
		<img src="/images/light_bulb_on.png" title="词条被点亮 <?=$stat->light?> 次">
	</h1>
	</header>

	<div id="author-info">
		<div id="avatar-wrap">
			<? $avatar = empty($user->avatar)? "default.jpg": $user->avatar; ?>
			<img id="avatar"  class="shadow-small radius-small" src="/images/avatar/<?=$avatar?>">
		</div>
		<div id="author-meta">
			<p><b>稿件数量：</b><?=$stat->all_count?> 篇（其中 <?=$stat->all_sent_count?> 篇已发送）</p>
			<p><b>词条被点亮：</b><?=$stat->light?> 次</p>
			<p><b>个人说明：</b><?=$user_info->descript?></p>
			<p><b>个人主页：</b><a href="<?=$user_info->url?>"><?=$user_info->url?></a></p>

			<? if ($setting->show_home) { ?>
			<p><b>家　　乡：</b><?=$user_info->hometown?></p>
			<? }

			if ($setting->show_resident) { ?>
			<p><b>常驻地区：</b><?=$user_info->resident?></p>
			<? }

			if ($setting->show_tags) { ?>
			<p><b>用户标签：</b><?
				$tag_i = 0;
				foreach ($tags as $tag) {
					if ($tag_i >= 1) {
						echo " ";
					} ?>
					<span class="label label-info"><?=$tag->tag?></span><?
					$tag_i++;
			}?></p>
			<? } ?>
		</div>
	</div>
	<div style="clear:both;"></div>

	<hr>

	<div id="posts" class="posts">
		<h4 class="section-title"><?=$user->nick_name?> 编写的所有稿件 (<?=count($posts)?>)</h4>

		<? $post_num = $setting->contrib_num;
		if ( ($post_num > 0) && ( count($posts) > $post_num ) ) { ?>
			<h6>只显示最新的 <?=$post_num?> 篇</h6>

		<? } else {
			$post_num = count($posts);
		} ?>
		<!--  post  list   -->
		<ul id="post-list" class="post-list">

		<? $post_count = 0;

		foreach ($posts as $post) {
			if (++$post_count > $post_num)
				break;
			$this->renderPartial('/user/post-template', array(
				'post' => $post,
				'user' => $user,
			));
		} ?>
		</ul>

		<div><a id='load-more' href='#' class="btn btn-primary" style="margin-left: 2em;">显示全部稿件</a></div>
		<ul id="post-list-more" class="post-list" style="display:none;">
		<? if ( count($posts) > $post_num ) {
			for (; $post_count <= count($posts) ; $post_count++) { 
				$this->renderPartial('/user/post-template', array(
					'post' => $posts[$post_count-1],
					'user' => $user,
				));
			}
		} ?>
		</ul>
	</div>

	<div id="submits" class="posts">
		<h4 class="section-title"><?=$user->nick_name?> 配图的所有稿件 (<?=count($submits)?>)</h4>

		<? $submit_num = $setting->illustrate_num;
		if ( ($submit_num > 0) && ( count($submits) > $submit_num ) ) { ?>
			<h6>只显示最新的 <?=$submit_num?> 篇</h6>

		<? } else {
			$submit_num = count($submits);
		} ?>
		
		<ul id="submit-list" class="post-list">
		<? $submit_count = 0;
		foreach ($submits as $submit) {
			if (++$submit_count > $submit_num)
				break; ?>
			<li>
				<a href="/<?=timeFormat($submit->created, 'path')."/".$submit->slug?>">
					【小宇宙百科 <?=$submit->serial?>】
					<?=$submit->title?>
				</a>
			</li>		
		<? } ?>
		</ul>
	</div>

</div>

</div>
<script type="text/javascript" src="/js/user/home.js"></script>