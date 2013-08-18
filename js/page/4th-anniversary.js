height = new Array(0, 0, 730, 435, 1415, 280);

$(document).ready(function(){

	$(".toggle").click(function(){
		poll_id = $(this).attr("rel");
		if ( poll_id != 4 ) {
			$ifm = $("#ifm-poll" + poll_id);
			$ifm.parent().toggle('fast');

			// $ifmdoc = $(window.frames["ifm-poll" + poll_id].document);
			// $ifmdoc.ready(function(){
			// 	height = $ifmdoc[0].body.scrollHeight;
			$ifm.height(height[poll_id]);
			// });
		}
	});

	$(".toggle").each(function(){
		$(this).click();
	});
});