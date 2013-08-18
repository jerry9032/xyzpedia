(function($){

	$(document).ready(function(){
		$(".light-btn").click(function(){

			lock = $(this).attr('lock');
			if ( lock == 'lock') {
				return false;
			} else {
				$(this).attr('lock', 'lock');
			}

			$article = $(this).parent().parent().parent().parent();
			pid = $article.attr("pid");
			num = parseInt($(this).attr('num'));
			that = this;
			//alert(pid);
			$.post("/post/lightup", {'pid': pid}, function(data){
				d = JSON.parse(data);
				if (d.error == 1) {
					alert(d.msg);
				} else {
					alert(d.msg);
					$(that).parent().html("点亮("+(num+1)+")");
					$article.addClass("lighted");
				}
				return false;
			});
			return false;
		});

	});

})(jQuery);