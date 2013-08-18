<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript">

$ = window.parent.$;

$("img.thumbnail").attr("src", "/images/<?=$name?>");
$("input.thumbnail").val("<?=$name?>");
// hide the box
$("#close-modal").click();
$("#upload-thumb-btn").val("修改");

window.location = "/admin/postpic/upload/"+<?=$id?>;

</script>