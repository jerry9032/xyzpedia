<div id="share-button" class="pull-right">
	<script type="text/javascript">
		post_title = "<?=addslashes($post->title)?>";
		post_thumbnail = "http://xyzpedia.org/images/<?=$post->thumbnail?>";
		post_excerpt = "<?
			$excerpt = str_replace("\r\n", "", $post->excerpt);
			$excerpt = addslashes($excerpt);
			echo $excerpt; ?>";
		post_permalink = "http://xyzpedia.org/<?=$permalink?>";
	</script>
	<script type="text/javascript" src="/js/share-buttons.js"></script>
</div>
<div class="clearfix"></div>
