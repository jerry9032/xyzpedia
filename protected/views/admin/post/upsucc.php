<script type="text/javascript" src='/js/jquery.js'></script>
<script type="text/javascript">
item = "http://xyzpedia.org<?=$imgurl?>";
item = "<li><span>" + item + "</span> " +
		"<a href='#' class='insert-pic'>插入</a></li>"
$(window.parent.document).find("#uppic-list").append(item);
window.location = '/admin/post/upload';
</script>
