(function($){
	$.fn.autoTextarea = function(options) {
		var defaults={
			maxHeight:null,
			minHeight:$(this).height() 
		};
		var opts = $.extend({},defaults,options);
		return $(this).each(function() {
			$(this).bind("paste cut keydown keyup focus blur",function(){
				var height;
				var style = this.style;
				//this.style.height = opts.minHeight + 'px';
				style.height = parseInt(this.style.height) - 7 + "px";
				if (this.scrollHeight > opts.minHeight) {
					if (opts.maxHeight && this.scrollHeight > opts.maxHeight) {
						height = opts.maxHeight;
						style.overflowY = 'scroll';
					} else {
						height = this.scrollHeight;
						style.overflowY = 'hidden';
					}
					style.height = height + 'px';
				} else {
					style.height = opts.minHeight;
				}
			});
		});
	};

})(jQuery);