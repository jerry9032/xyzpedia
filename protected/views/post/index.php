<meta name="description" content="小宇宙百科是一个以手机飞信为媒介，以友谊为纽带，一个完全免费、共享的知识驿站。小百科工作室每周提供一组百科知识，由志愿者通过飞信的定时短信功能将百科知识分享给自己的朋友。所有百科条目由志愿者投稿，工作室录用编辑以后发行。" />
<meta name="keywords" content="小宇宙百科,知识分享,知识共享,生活,小百科" />
<link rel="stylesheet" type="text/css" href="<?=Yii::app()->request->baseUrl?>/css/post.css" media="screen, projection">
<link rel="stylesheet" type="text/css" href="<?=Yii::app()->request->baseUrl?>/css/widget.css" media="screen, projection">
<script src="/js/post/index.js"></script>

<div class="container">


<h1 id="site-title">
	<span><a href="/" rel="home">
	<img src="/images/logo.png" alt="小宇宙百科">
	</a></span>
</h1>
<div class="row">
	<!-- post lists -->
	<div class="span8">

	<? if ( !empty($events)) { ?>
	<? $color = get_option('index_notify_color'); ?>
	<!-- events list -->
	<div class="alert alert-<?=$color?>">
		<? foreach ($events as $event) { ?>
		<div class="event">
			<b>活动：</b>
			<a href="/event/<?=$event->slug?>"><?=$event->title?></a>
		</div>
		<? } ?>
	</div>
	<? } ?>

	<? if (isset($prompt) && !empty($prompt) ) {?>
	<div class="alert alert-info"><?=$prompt?></div>
	<? } ?>

	<? if (isset($order)) { ?>
	<!-- order -->
	<div class="alert alert-order">
		<? if ($order == "time") { ?>
			<span class="badge badge-warning">按时间顺序查看</span>
			<span class="separater">|</span>
			<span class="badge"><a href="/hot">按点亮次数查看</a></span>
		<? } else if ($order == "light") { ?>
			<span class="badge"><a href="/">按时间顺序查看</a></span>
			<span class="separater">|</span>
			<span class="badge badge-warning">按点亮次数查看</span>
		<? } ?>
	</div>
	<? } ?>
	
	<?
	$cookie = Yii::app()->request->getCookies();
	foreach ($posts as $post) {

		$permalink = "/".timeFormat($post->created, 'path')."/{$post->slug}";
		$thumbnail = "/images/{$post->thumbnail}";

		//dump($post->author[0]->login);//die();
		$ck = $cookie['post'.$post->id];
		$lighted = isset($ck) && ($ck->value == "lighted");
	?>
	
	<div class="content-wrap shadow-large radius-medium">
	<article class="post publish hentry radius-small <?=($lighted?'lighted':'')?>" pid="<?=$post->id?>">
       	<div class="post-thumbnail radius-medium">
    	<a href="<?=$permalink?>"><img class="radius-medium" src="<?=$thumbnail?>" /></a></div>

		<div class="post-wrap">
		<header class="entry-header">
			<h1 class="entry-title"><a href="<?=$permalink?>/"><?=$post->title?></a></h1>
			<div class="entry-meta">
				<a href="<?=$permalink?>/"><time class="entry-date"><?=timeFormat($post->created, "chinese")?></time></a>&nbsp;
				<!-- registered author(s) --><?
				$author_num = 0;
				foreach ($post->author as $author) {
					if ($author_num >= 1) {
						echo " & ";
					}
					?><a href='/author/<?=$author->login?>'><?=$author->nick_name?></a><? 
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
				<a href='/author/<?=$post->submit->login?>'><?=$post->submit->nick_name?></a> / 配图
			</div>
		</header>

		<div class="entry-summary">
			<p><?=$post->excerpt?> <a href="<?=$permalink?>/">继续阅读 &rsaquo;&rsaquo;</a></p>
		</div>

		<footer class="entry-meta">
			<? if ( !empty($post->category) ) {?>
			<span class="category">
				[<a href="/category/<?=$post->category->slug?>/" title="查看 <?=$post->category->name?> 中的全部文章"><?=$post->category->name?></a>]
			</span>
			<? } ?>
			<? if ( !isGuest() ) { ?>
			<span class="sep"> | </span>
			<span class="leave-reply">
				<a href="<?=$permalink?>#comment" title="回复此文章"><span class="reply">回复(<?=$post->comments?>)</span></a>
			</span>
			<? } ?>
			<? if ( !isGuest() && ((user()->id == $post->submit) || can("post", "updateall")) ) { ?>
			<span class="sep"> | </span>
			<span class="edit-link">
				<a class="post-edit-link" href="/admin/post/edit/<?=$post->id?>" title="编辑此文章">
					<span class="edit">编辑</span>
				</a>
			</span>
			<? } ?>
			<span class="sep"> | </span>
			<span class="light-up">
				<? 
				if ( $lighted ) { ?>
					点亮(<?=$post->light?>)
				<? } else { ?>
					<a href="#" class="light-btn" num="<?=$post->light?>">
					点亮(<?=$post->light?>)</a>
				<? } ?>
			</span>
		</footer>
		</div>
	</article>
	</div>
	<div style="float:none;clear:both;"></div>
	<? } ?>

	<? switch ($page_type) {
		case 'index':
			switch ($order) {
				case 'time':
					$url_perfix = ""; break;
				case 'light':
					$url_perfix = "/hot"; break;
				default:
					$url_perfix = ""; break;
			}
			break;
		case 'category':
			$url_perfix = "/category/".$cat_slug; break;
		case 'search':
			$url_perfix = "/search/".$search_key; break;
		default:
			$url_perfix = ""; break;
	} ?>
	<ul class="pager">
		<? if ($page < ceil($count / $limit)) { ?>
		<li class="previous">
			<a href="<?=$url_perfix?>/page/<?=($page+1)?>">&larr; 过去的</a>
		</li>
		<? } ?>
		<? if ($page > 1) { ?>
		<li class="next">
			<a href="<?=$url_perfix?>/page/<?=($page-1)?>">更新的 &rarr;</a>
		</li>
		<? } ?>
	</ul>


	</div><!-- end of span 8 -->



	<!-- side bar widgets -->
	<div class="span4">
		<?
		$widgets = explode(",", $widgets);
		foreach ($widgets as $widget) {
			$this->renderPartial('/widget/'.$widget);
		}
		?>
	</div>
</div>
</div>