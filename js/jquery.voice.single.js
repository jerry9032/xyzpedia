
$(document).ready(function(){


	voice_player = '<div id="jquery_jplayer"></div>'+
	'<div style="margin-left:2em;">'+
		'<div class="btn-group" style="float:left;">'+
			'<button class="btn btn-small btn-primary jp-play" id="play">'+
				'<i class="icon-play icon-white"></i>'+
			'</button>'+
			'<button class="btn btn-small btn-primary jp-pause" id="pause">'+
				'<i class="icon-pause icon-white"></i>'+
			'</button>'+
			'<button class="btn btn-small btn-danger jp-stop" id="stop">'+
				'<i class="icon-stop icon-white"></i>'+
			'</button>'+
		'</div>'+
		'<div class="progress progress-striped active" style="width:0;float:left;margin:0 10px;height:25px;">'+
			'<div class="bar" style="width: 0%;"></div>'+
		'</div>'+
		'<div style="float:none;clear:both"></div>'+
	'</div>';

	$("#player_wrap").html(voice_player);

	var my_jPlayer = $("#jquery_jplayer");
	var started = false;

	my_jPlayer.jPlayer({
		ready: function () {
			$("#jp_container .track-default").click();
		},
		timeupdate: function(event) {
			//my_extraPlayInfo.text(parseInt(event.jPlayer.status.currentPercentAbsolute, 10) + "%");
			presentage = parseInt(event.jPlayer.status.currentPercentAbsolute, 10) + "%";
			$(".progress .bar").css("width", presentage);
		},
		play: function(event) {
			//my_playState.text(opt_text_playing);
		},
		pause: function(event) {
			//my_playState.text(opt_text_selected);
		},
		ended: function(event) {
			started = false;			
			$(".progress").animate({
				width: 0,
			});
		},
		swfPath: "/js",
		//cssSelectorAncestor: "#jp_container",
		supplied: "mp3",
		wmode: "window"
	});
	$("#play").click(function(){
		if ( !started ) {
			my_jPlayer.jPlayer("setMedia", {
				mp3: url,
			});
		}
		my_jPlayer.jPlayer("play");
		started = true;
		$(".progress").animate({
			width: 200,
		});
	});
	$("#pause").click(function(){
		my_jPlayer.jPlayer("pause");
	});
	$("#stop").click(function(){
		my_jPlayer.jPlayer("stop");
		started = false;
		$(".progress").animate({
			width: 0,
		});
	})

	$("#play").click();
});
