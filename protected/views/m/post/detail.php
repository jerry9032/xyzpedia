<link rel="stylesheet" type="text/css" href="/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/css/post.css">
<style type="text/css">
.detail .entry-content img { width: 100%; }
div.aligncenter, center { font-size: 13px; text-align: center; }
blockquote#entry-excerpt { margin: 20px 0; line-height: 22px;}
#author-avatar { float: left; width: 78px; }
</style>
<div data-role="content" class="detail">
	<? $permalink = "/".timeFormat($post->created, 'path')."/".$post->slug; ?>
	<h3 id="entry-header"><?=$post->title?></h3>

	<div class="entry-content">

		<? if ( $post->type == "post" ) { ?>
		<div class="entry-meta">
			<a href="/<?=timeFormat($post->created, "path")?>/<?=$post->slug?>/">
				<time class="entry-date"><?=date("Y 年 n 月 j 日", $post->created);?></time>
			</a>
			<!-- registered author(s) -->
			<?
			$author_num = 0;
			$user_id = isGuest()? 0: user()->id;
			$can_edit = false;

			foreach ($post->author as $author) {
				if ($author_num >= 1) {
					echo " & ";
				}
				if ( $author->id == $user_id ) {
					$can_edit |= can("post", "update");
				}
				?><a href='/author/<?=urlencode($author->login)?>'><?=$author->nick_name?></a><? 
				$author_num++;
			} ?>

			<!-- unregister author(s) --><?
			foreach ($post->author_no_reg as $author) {
				// distinguish unregister
				if ($author->author_id == 0) {
					if ($author_num >= 1) {
						echo " & ";
					}
					echo $author->author_name;
				}
				$author_num++;
			} ?> / 编&nbsp;
			<a href="/author/<?=urlencode($post->submit->login)?>"><?=$post->submit->nick_name?></a> / 配图
		</div>
		<? } ?>

		<? if ( isset($post->category) ) { ?>
		<div class="entry-meta">分类：[<a href="/category/<?=$post->category->slug?>"><?=$post->category->name?></a>]</div>
		<? } ?>


		<? if ( !empty($post->excerpt)) { ?>
		<blockquote id="entry-excerpt" class="excerpt">
			<p><?=$post->excerpt?></p>
		</blockquote>
		<? } ?>

		<?=postFilter($post->content)?>
	</div>
	<script type="text/javascript">
	(function($){
		$(function(){
			var oFa = document.createElement('div');
			$(oFa).attr('class','aligncenter');
			$('.entry-content img').wrap(oFa);
		});
	})(jQuery);
	</script>

	<!-- if registered author has description, show his/her info -->
	<? for ($author_i = 0; $author_i < count($post->author); $author_i++) {
		if ( !empty($post->author_info[$author_i]->descript) ) { ?>
		<div id="author-info">
			<div id="author-avatar" class="shadow-medium radius-medium">
				<a href="/author/<?=urlencode($post->author[$author_i]->login)?>">
					<? $avatar = empty($post->author[$author_i]->avatar)? "default.jpg": $post->author[$author_i]->avatar; ?>
					<img src="/images/avatar/<?=$avatar?>"
						class="avatar radius-medium" alt="<?$post->author[$author_i]->nick_name?>">
				</a>
			</div>
			<div id="author-description">
				<h2>编者
					<a href="/author/<?=urlencode($post->author[$author_i]->login)?>">
						<?=$post->author[$author_i]->nick_name?>
					</a>
				</h2>
				<?=$post->author_info[$author_i]->descript?>
			</div>
		</div>
		<? }
	} ?>
</div>