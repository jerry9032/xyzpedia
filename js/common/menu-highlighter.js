(function($) {
	var url = document.location.href;
	var uri = document.location.href.substring(url.indexOf('/', 8));

	if ( uri == '/admin')
		$("#summary").addClass('active');

	$('ul.nav.nav-list').find('a').each(function(i, v) {
		href = $(v).attr('href');
		if(uri.indexOf(href) != -1) {
			$(v).parent().addClass('active');
		}
	});
})(jQuery);