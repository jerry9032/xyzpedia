$("#tag-wrap-lib span").draggable({
	appendTo: "body",
	helper: "clone",
	revert: true
});
$("#tag-wrap-cur").droppable({
	activeClass: "ui-state-default",
	hoverClass: "ui-state-hover",
	accept: ":not(.ui-sortable-helper)",
	drop: function( event, ui ) {
		//$( this ).find( ".placeholder" ).remove();
		exist = false;
		div = this;
		spans = $(this).children("span");
		exceed = (spans.length >= 5);
		spans.each(function(){
			if ( $(this).children("span").text() == ui.draggable.text() ) {
				exist = true;
			}
		});
		if ( !exist && !exceed ) {
			$( '<span class="label label-info"></span>' )
				.html( '<span>'+ui.draggable.text()+'</span>' )
				.append('<a href="#" class="remove-tag">&times</a>')
				.appendTo( div );
			//span.draggable({revert: false});
			$("body>span.label").remove();
		}
	}
}).sortable();
$(".remove-tag").live("click", function(){
	$(this).parent().remove();
	return false;
});
$("form").submit(function(){
	tags = new Array;
	tag_i = 0;
	$("#tag-wrap-cur > span").each(function(){
		tags[tag_i++] = $(this).children("span").text();
	});
	$("input[name=tags]").val(tags.toString());
});