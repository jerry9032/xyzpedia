<aside id="categories" class="widget well">
	<h1 class="widget-title">分类目录</h1>
	<ul><?

	$cat_show_empty = get_option("index_category_show_empty");
	// $criteria = new CDbCriteria;
	// $criteria->condition = 'posts > 0';
	// $criteria->order = 'posts desc';
	$catList = Category::model()->findAll();

	//dump($catList);die();
	foreach ($catList as $cat) {
		if ( $cat_show_empty || count($cat->posts) > 0 ) { ?>
		<li><a href="/category/<?=$cat->slug?>"><?=('-' == $cat->name? $cat->short: $cat->name)?></a></li>
		<? } ?>
	<? } ?>
	</ul>
</aside>