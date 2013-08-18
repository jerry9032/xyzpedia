(function($){

	$(document).ready(function(){
		$(".light-bulb").click(function(){

			lock = $(this).attr('lock');
			if ( lock == 'lock') {
				return false;
			} else {
				$(this).attr('lock', 'lock');
			}

			pid = $(this).parent().parent().parent().attr('pid');
			num = parseInt($(this).attr("num"));
			that = this;
			$.post('/post/lightup', {'pid': pid}, function(data){
				d = JSON.parse(data);
				if (d.error == 1) {
					alert(d.msg);
				} else {
					alert(d.msg);
					$(that).removeClass('bulb-off').addClass('bulb-on');
					$(that).attr("num", num+1);
				}
			});
		});

		$(".light-bulb").hover(function(){
			num = $(this).attr('num');
			$(this).attr('title', "点亮(" + num + ")");
		});
	});
})(jQuery);