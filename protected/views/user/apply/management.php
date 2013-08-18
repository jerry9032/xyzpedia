<style type="text/css">
.span9 {
	margin-left: 0;
}
#manage-list div {
	padding: 0;
	width: 211px;
	height: 100px;
	margin-bottom: 25px;
}
#manage-list dl {
height: 100%;
margin: 0;
padding: 0;
width: 100%;
}
#manage-list dt{
text-align: left;
margin: 10px;
font-size: 14px;
}
#manage-list dd {
text-align: left;
font-size: 13px;
margin: 0 10px 10px;
}
legend {
	margin-left: 20px;
}
</style>
<?
$manages = array(
	array(
		"slug" => "today",
		"name" => "网站今日词条配图",
		"duty" => "将今日词条文字配上图片后发至小宇宙百科网站。"
	),
	array(
		"slug" => "history",
		"name" => "网站历史词条配图",
		"duty" => "将历史词条文字配上图片后发至小宇宙百科网站。"
	),
	array(
		"slug" => "renren",
		"name" => "人人主页君",
		"duty" => "将网站的今日词条贴到人人。"
	),
	array(
		"slug" => "qing",
		"name" => "轻博客主页君",
		"duty" => "将网站的今日词条贴到新浪轻博客。"
	),
	array(
		"slug" => "wanxbk",
		"name" => "晚安小百科",
		"duty" => ""
	),
	array(
		"slug" => "weixin",
		"name" => "微信主页君",
		"duty" => ""
	),
	array(
		"slug" => "council",
		"name" => "小百科稿件委员会",
		"duty" => "参与稿件分配、提醒等工作。"
	),
);
?>

<legend>
	<h4>所有管理项目</h4>
</legend>


<div id="manage-list">
<? foreach ($manages as $manage) { ?>
<div class="btn span3" rel="<?=$manage['slug']?>">
	<dl>
		<dt><?=$manage['name']?></dt>
		<dd>职责：<?=$manage['duty']?></dd>
	</dl>
</div>
<? }?>
</div>

<form method="post" action="/user/apply/management">
<div class="modal hide fade" id="myModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4>确认</h4>
	</div>
	<div class="modal-body">
		<p>确认要申请？</p>
		<input name="type" type="hidden" />
	</div>
	<div class="modal-footer">
		<button type="submit" class="btn btn-primary" id="confirm"><i class="icon-ok icon-white"></i> 确认</button>
		<button class="btn" id="close"><i class="icon-remove"></i> 取消</button>
	</div>
</div>
</form>

<script type="text/javascript">
$(".btn.span3").click(function(){
	$("input[name=type]").val( $(this).attr("rel") );
	name = $(this).find('dt').text();
	$('.modal-body p').html( '确认要申请 <b>' + name + '</b> ？' );
	$('#myModal').modal({
		keyboard: true
	});
	$('#myModal').attr('rel', rel);
});
$("#close").click(function(){
	$("#myModal").modal("hide");
	return false;
});
$("#confirm").click(function(){
	alert("还不支持申请这个项目。");
	return false;
});
</script>