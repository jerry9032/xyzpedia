<style type="text/css">
#author-info { font-size: 14px; }
#author-info tr { line-height: 22px; vertical-align: top; }
#author-info tr td:first-child{ width: 30%; font-weight: bold; }
</style>
<div data-role="content">
    <? $avatar = empty($user->avatar)? "default.jpg": $user->avatar; ?>

	<h1>作者 <?=$user->nick_name?></h1>
	<div class="avatar" style="margin-bottom: 20px;">
	<a href="/author/<?=urlencode($user->login)?>" >
		<img src="/images/avatar/<?=$avatar?>" class="radius-medium shadow-medium">
	</a>
	</div>
	
	<table id="author-info">
		<tr>
			<td>稿件数量：</td>
			<td>? 篇（其中 ? 篇已发送）</td>
		</tr>
		<tr>
			<td>个人说明：</td>
			<td><?=$user_info->descript?></td>
		</tr>
		<tr>
			<td>个人主页：</td>
			<td><a href="<?=$user_info->url?>"><?=$user_info->url?></a></td>
		</tr>

		<? if ($setting->show_home) { ?>
		<tr>
			<td>家　　乡：</td>
			<td><?=$user_info->hometown?></td>
		</tr>
		<? }

		if ($setting->show_resident) { ?>
		<tr>
			<td>常驻地区：</td>
			<td><?=$user_info->resident?></td>
		</tr>
			<? }

		if ($setting->show_tags) { ?>
		<tr>
			<td>用户标签：</td>
			<td><?
				$tag_i = 0;
				foreach ($tags as $tag) {
					if ($tag_i >= 1) {
						echo " ";
					} ?>
					<span class="label label-info"><?=$tag->tag?></span><?
					$tag_i++;
			}?></td>
		</tr>
		<? } ?>
	</table>

	<div style="clear:both;"></div>

	<style type="text/css">
	.post-list { font-size: 13px; }
	.post-list h6 { margin: 10px 0 ; font-size: 13px; }
	.post-list p { font-size: 13px; margin: 0; margin-top: 10px;}
	</style>
	<h2>稿件</h2>
	<div data-role="collapsible-set" data-theme="b" data-content-theme="b">
		<div data-role="collapsible" data-collapsed="false">
			<h3><?=$user->nick_name?> 编写的 (<?=count($posts)?>)</h3>
			<div class="post-list">
			<? $post_num = $setting->contrib_num;
			if ( ($post_num > 0) && ( count($posts) > $post_num ) ) { ?>
				<h6>只显示最新的 <?=$post_num?> 篇</h6>
			<? } else {
				$post_num = count($posts);
			} ?>
			<!--  post  list   -->
			<? $post_count = 0;
			foreach ($posts as $post) {
				if (++$post_count > $post_num)
					break; ?>
				<p><a href="/<?=timeFormat($post->created, 'path')."/".$post->slug?>">
						<?=$post->title?></a>
				<? // registered author(s)
				$other_author = "";
				foreach ($post->author as $author) {
					if ($author->id != $user->id) {
						$other_author.= "<a href='/author/".urlencode($author->login)."'>".$author->nick_name."</a> ";
					}
				}
				// unregister author(s)
				foreach ($post->author_no_reg as $author) {
					// distinguish unregister
					if ($author->author_id == 0) {
						$other_author.= $author->author_name." ";
					}
				}
				if ( !empty($other_author) ) {
					echo "(与 ".$other_author."合作)";
				} ?></p>
			<? } ?>
			</div>
		</div>
		<div data-role="collapsible" data-collapsed="true">
			<h3><?=$user->nick_name?> 配图的 (<?=count($submits)?>)</h3>

			<div class="post-list">
				<? $submit_num = $setting->illustrate_num;
				if ( ($submit_num > 0) && ( count($submits) > $submit_num ) ) { ?>
					<h6>只显示最新的 <?=$submit_num?> 篇</h6>
				<? } else {
					$submit_num = count($submits);
				}
				$submit_count = 0;
				foreach ($submits as $submit) {
					if (++$submit_count > $submit_num)
						break; ?>
				<p><a href="/<?=timeFormat($submit->created, 'path')."/".$submit->slug?>"><?=$submit->title?></a></p>		
				<? } ?>
			</div>
		</div>
	</div><!-- end of collapse set -->
</div>

