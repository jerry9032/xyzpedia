(function($) {
	$(".advance-btn").click(function(){
		icon = $(this).children("i");
		if (icon.hasClass("icon-plus")) {
			icon.removeClass("icon-plus");
			icon.addClass("icon-minus");
		} else {
			icon.removeClass("icon-minus");
			icon.addClass("icon-plus");
		}
		$(this).parent().parent().siblings(".advance-option-group").toggle("fast");
	});
})(jQuery);