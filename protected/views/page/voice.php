<link rel="stylesheet" type="text/css" href="/css/post.css" media="screen, projection">
<link rel="stylesheet" type="text/css" href="/css/widget.css" media="screen, projection">
<link href="/css/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
<style type="text/css">
div.jp-audio {
	width: 560px;
}
ol li {
	margin-left: 2em;
	padding-left: 0.5em;
}
</style>
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/jquery.jplayer.js"></script>
<script type="text/javascript" src="/js/jplayer.playlist.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	new jPlayerPlaylist({
		jPlayer: "#jquery_jplayer_1",
		cssSelectorAncestor: "#jp_container_1"
	}, [
	<? foreach ($files as $file) { ?>
		{
			title:"<?=$file->title?>",
			mp3:"/downloads/<?=$file->group?>/<?=$file->filename?>",
		},
	<? } ?>
	], {
		swfPath: "js",
		supplied: "oga, mp3",
		wmode: "window"
	});
});
</script>


<div class="container">

<h1 id="site-title">
	<span><a href="/" title="小宇宙百科" rel="home">
	<img src="/images/logo.png" alt="小宇宙百科">
	</a></span>
</h1>
<div class="row">
	<div class="span8">


<article class="post detail hentry radius-medium shadow-large">
	<h1 class="entry-title">有声版小百科</h1>

	<p>&nbsp;</p>

	<div class="entry-content">

<p>期待已久了吗？戳 <a href="/voice/">有声版页面</a> 关注更新~</p>
<p>有声版不会放送所有词条喔~我们会精选优雅的词条进行播出~~</p>

<hr />


		<!-- jquery  player -->
		<div id="jquery_jplayer_1" class="jp-jplayer"></div>
		<div id="jp_container_1" class="jp-audio">
			<div class="jp-type-playlist">
				<div class="jp-gui jp-interface">
					<ul class="jp-controls">
						<li><a href="javascript:;" class="jp-previous" tabindex="1">previous</a></li>
						<li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
						<li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
						<li><a href="javascript:;" class="jp-next" tabindex="1">next</a></li>
						<li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>
						<li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li>
						<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li>
						<li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume">max volume</a></li>
					</ul>
					<div class="jp-progress">
						<div class="jp-seek-bar">
							<div class="jp-play-bar"></div>
						</div>
					</div>
					<div class="jp-volume-bar">
						<div class="jp-volume-bar-value"></div>
					</div>
					<div class="jp-time-holder">
						<div class="jp-current-time"></div>
						<div class="jp-duration"></div>
					</div>
					<ul class="jp-toggles">
						<li><a href="javascript:;" class="jp-shuffle" tabindex="1" title="shuffle">shuffle</a></li>
						<li><a href="javascript:;" class="jp-shuffle-off" tabindex="1" title="shuffle off">shuffle off</a></li>
						<li><a href="javascript:;" class="jp-repeat" tabindex="1" title="repeat">repeat</a></li>
						<li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off">repeat off</a></li>
					</ul>
				</div>
				<div class="jp-playlist">
					<ul>
						<li></li>
					</ul>
				</div>
				<div class="jp-no-solution">
					<span>Update Required</span>
					To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
				</div>
			</div>
		</div>


<p>&nbsp;</p>

<p><strong>【有声版小百科人员招募】</strong></p>
<p>为了给大家放送最动听的有声版小百科，声音的录制和后期合成都是很辛苦的哟~如果：</p>

<ol>
	<li>你是普通话标准的萌妹纸，或者</li>
	<li>普通话标准且声音有磁性的汉纸，或者</li>
	<li>对痴迷音乐且对音乐有独到的理解，或者</li>
	<li>拥有后期合成音频的经验</li>
</ol>

<p>请不要犹豫！！</p>
<p>立刻在新浪微博 <a href="http://weibo.com/xyzbaike" target="_blank">@小宇宙百科网</a> 联系主页妹，加入我们的队伍，洽谈相关事宜喔~！</p>



	</div>
</article>



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

