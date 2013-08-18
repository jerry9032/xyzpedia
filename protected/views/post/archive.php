<link rel="stylesheet" type="text/css" href="<?=Yii::app()->request->baseUrl?>/css/post.css" media="screen, projection">
<link rel="stylesheet" type="text/css" href="<?=Yii::app()->request->baseUrl?>/css/widget.css" media="screen, projection">
<div class="container">


<h1 id="site-title">
	<span><a href="http://xyzpedia.org/" title="小宇宙百科" rel="home">
	<img src="/images/logo.png" alt="小宇宙百科">
	</a></span>
</h1>


<div class="row">
	<div class="span8">

<article class="post detail hentry radius-medium shadow-large">
	<header class="entry-header">
		<h4>
			<?=$this->pageTitle?>
			<? if ($page_type == "year") { ?>
			(第 <?=$paging->page?> 页)
			<? } ?>
		</h4>
		<hr>
	</header>

	<div class="entry-content">

		<ul id="post-archive-list">
		<? foreach ($posts as $post) { ?>
			<li>
				<time class="entry-meta"><?=timeFormat($post->created, "chinese")?></time>
				<a href="/<?=timeFormat($post->created, "path")?>/<?=$post->slug?>"><?=$post->title?></a>
				<? if ( isset($post->category)) { ?>
				<div class="entry-meta">[<a href="/category/<?=$post->category->slug?>"><?=$post->category->name?></a>]</div>
				<? } ?>
			</li>
		<? } ?>
		</ul>


		<? if ($page_type == "year") { ?>
		<ul class="pager">
			<? if ($paging->page > 1) { ?>
			<li class="previous">
				<a href="/<?=$date->year?>/page/<?=($paging->page-1)?>">&larr; 上一页</a>
			</li>
			<? } ?>
			<? if ($paging->page < ceil($paging->count / $paging->limit)) { ?>
			<li class="next">
				<a href="/<?=$date->year?>/page/<?=($paging->page+1)?>">下一页 &rarr;</a>
			</li>
			<? } ?>
		</ul>
		<? } elseif ($page_type == "month") { ?>
		<ul class="pager">
			<? if ($date->year > 2008) { // display ?>
				<li class="previous">
				<? if ($date->month == 1) { ?>
					<a href="/<?=($date->year-1)?>/12">&larr; 上一个月</a>
				<? } else { ?>
					<a href="/<?=$date->year?>/<?=($date->month-1)?>">&larr; 上一个月</a>
				<? } ?>
				</li>
			<? } ?>
			<? if ($date->year <= (int)timeFormat(gmtTime(), "year") 
				&& $date->month <= (int)timeFormat(gmtTime(), "month-only")-1) { ?>
				<li class="next">
				<? if ($date->month == 12) { ?>
					<a href="/<?=($date->year+1)?>/01?>">下一个月 &rarr;</a>
				<? } else { ?>
					<a href="/<?=$date->year?>/<?=($date->month+1)?>">下一个月 &rarr;</a>
				<? } ?>
				</li>
			<? } ?>
		</ul>
		<? } ?>
	</div>
</article>



	</div><!-- end of span 8 -->

	<!-- sidebar widgets -->
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