<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/jquery.jplayer.js"></script>

<script type="text/javascript">

$(document).ready(function(){

	// Local copy of jQuery selectors, for performance.
	var my_jPlayer = $("#jquery_jplayer"),
		my_trackName = $("#jp_container .track-name"),
		my_playState = $("#jp_container .play-state"),
		my_extraPlayInfo = $("#jp_container .extra-play-info");

	// Some options
	var opt_play_first = false, // onload play
		opt_auto_play = true, // when selected, it will auto-play.
		opt_text_playing = "Now playing", // Text when playing
		opt_text_selected = "Track selected"; // Text when not playing

	// A flag to capture the first track
	var first_track = true;

	// Change the time format
	$.jPlayer.timeFormat.padMin = false;
	$.jPlayer.timeFormat.padSec = false;
	$.jPlayer.timeFormat.sepMin = " min ";
	$.jPlayer.timeFormat.sepSec = " sec";


	// Initialize the play state text
	my_playState.text(opt_text_selected);

	// Instance jPlayer
	my_jPlayer.jPlayer({
		ready: function () {
			$("#jp_container .track-default").click();
		},
		timeupdate: function(event) {
			my_extraPlayInfo.text(parseInt(event.jPlayer.status.currentPercentAbsolute, 10) + "%");
		},
		play: function(event) {
			my_playState.text(opt_text_playing);
		},
		pause: function(event) {
			my_playState.text(opt_text_selected);
		},
		ended: function(event) {
			my_playState.text(opt_text_selected);
		},
		swfPath: "/js",
		cssSelectorAncestor: "#jp_container",
		supplied: "mp3",
		wmode: "window"
	});

	// Create click handlers for the different tracks
	$("#jp_container .track").click(function(e) {
		my_trackName.text($(this).text());
		my_jPlayer.jPlayer("setMedia", {
			mp3: $(this).attr("href")
		});
		if((opt_play_first && first_track) || (opt_auto_play && !first_track)) {
			my_jPlayer.jPlayer("play");
		}
		first_track = false;
		$(this).blur();
		return false;
	});


	$("#jplayer_inspector").jPlayerInspector({jPlayer:$("#jquery_jplayer")});

});
</script>


<div id="jquery_jplayer"></div>

<div id="jp_container" class="demo-container">
<ul>
	<li><span>Select a track : </span></li>
	<li><a href="http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3" class="track track-default">Cro Magnon Man</a></li>
	<li> | </li>
	<li><a href="http://www.jplayer.org/audio/mp3/Miaow-05-The-separation.mp3" class="track">The Separation</a></li>
	<li> | </li>
	<li><a href="http://www.jplayer.org/audio/mp3/Miaow-04-Lismore.mp3" class="track">Lismore</a></li>
	<li> | </li>
	<li><a href="http://www.jplayer.org/audio/mp3/Miaow-10-Thin-ice.mp3" class="track">Thin Ice</a></li>
</ul>

<p>
	<span class="play-state"></span> :
	<span class="track-name">nothing</span>
	at <span class="extra-play-info"></span>
	of <span class="jp-duration"></span>, which is
	<span class="jp-current-time"></span>
</p>

<ul>
	<li><a class="jp-play" href="#">Play</a></li>
	<li><a class="jp-pause" href="#">Pause</a></li>
	<li><a class="jp-stop" href="#">Stop</a></li>
</ul>
<ul>
	<li>volume :</li>
	<li><a class="jp-mute" href="#">Mute</a></li>
	<li><a class="jp-unmute" href="#">Unmute</a></li>
	<li> <a class="jp-volume-bar" href="#">|&lt;----------&gt;|</a></li>
	<li><a class="jp-volume-max" href="#">Max</a></li>
</ul>
