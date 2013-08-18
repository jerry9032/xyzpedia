<aside id="" class="widget well form-search">
	<input type="text" class="input-medium search-query" id="key" placeholder="关键词" />
	<input type="button" class="btn" id="searchsubmit" value="搜索" />
</aside>
<script type="text/javascript">
(function($) {
	$("#key").keypress(function(e){
		var key = e.which;
		if (key == 13) {
			$("#searchsubmit").click();
		};
	});
	$("#searchsubmit").click(function(){
		if ( $("key").val ) {
			window.location = "/search/"+$("#key").val();
		} else {
			return false;
		}
	});
})(jQuery);
</script>