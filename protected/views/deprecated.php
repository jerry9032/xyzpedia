<script type="text/javascript">
(function($){
$(".pagination button").click(function(){
	$.ajax({
		type: "post",
		url: "/contribute/listajax",
		data: {
			type: "all",
			page: $(this).attr("page")
		},
		success: function(data){
			// data is json
			data = eval(data);
			//alert(data[1].title);
			tr = $("#the-list").find("tr");
			i = 0;
			for (var r in data) {
				tr.eq(i+1).find("td").eq(0).html(
					'<a href="/contribute/'+ data[r].ID + '">' +
						data[r].title+
					'</a>'
				);
				tr.eq(i+1).find("td").eq(1).html(data[r].status);
				tr.eq(i+1).find("td").eq(2).html(
					'<a href="/contribute/author/'+data[r].author.login+'">'+
					data[r].author.nick_name+
					'</a>'
				);
				tr.eq(i+1).find("td").eq(3).html(
					'<a href="/contribute/editor/'+data[r].editor.login+'">'+
					data[r].editor.nick_name+
					'</a>'
				);
				tr.eq(i+1).find("td").eq(4).html(
					'<a href="/contribute/category/'+data[r].category.slug+'">'+
					data[r].category.short+
					'</a>'
				);
				tr.eq(i+1).find("td").eq(5).html(data[r].wordcount);
				tr.eq(i+1).find("td").eq(6).html(
					'<a href="/contribute/'+data[r].ID+'">'+
					data[r].comments+
					'</a>'
				);
				tr.eq(i+1).find("td").eq(7).html(
					"<abbr title="+data[r].created+">"+
					data[r].created_short+
					"</abbr>");
				i++;
			}
		}
	});
});
})(jQuery);
</script>



	public function actionListAjax() {
		$type = $_POST["type"];
		$page = $_POST["page"];

		$param = new StdClass;
		$param->page = $page;

		$obj = $this->buildObj($type, $param);

		$contribList = Contrib::model()->findAll($obj["criteria"]);

		//die(CJSON::encode($contribList));
		date_default_timezone_set("PRC");
		$i = 0;
		foreach($contribList as $c) {
			$data[$i++] = array(
				"ID" => $c->ID,
				"title" => $c->title,
				"status" => Yii::t('contribute', ucfirst($c->status)),
				"author" => $c->author,
				"editor" => $c->editor,
				"category" => $c->category,
				"wordcount" => str_word_count($c->content),
				"comments" => 14,
				"created" => date('Y-m-d H:i:s', $c->created),
				"created_short" => substr(date('Y-m-d H:i:s', $c->created), 0, 10),
			);
		}
		die(CJSON::encode($data));
	}
