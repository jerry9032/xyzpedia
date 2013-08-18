(function($){
	$(document).ready(function(){

		$('.dropdown-toggle').dropdown();

		$("button").click(function(){
			if ($(this).attr("href")) {
				window.location = $(this).attr("href");
				return false;
			}
		});

		setTimeout(function(){
			$('.alert-fixed').fadeOut(500);
		}, 3000);

		$("span.left[rel=popover]").popover({
			trigger: 'hover',
			placement: 'left'
		});
		$("span[rel=popover]").popover({
			trigger: 'hover'
		});

		$('a[action=logout]').click(function(){
			$.ajax({
				url: "/login/logout",
				success: function() {
					window.location = "/login";
				}
			});
			return false;
		});
	});
})(jQuery);
