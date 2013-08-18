<li>
	<a href="/<?=timeFormat($post->created, "path")."/".$post->slug?>" title="点亮 <?=$post->light?> 次">
		【小宇宙百科 <?=$post->serial?>】 <?=$post->title?></a>
	<? // registered author(s)
	$other_author = "";
	foreach ($post->author as $author) {
		if ($author->id != $user->id) {
			$other_author.= "<a href='/author/".$author->login."'>".$author->nick_name."</a> ";
		}
	}
	// unregister author(s)
	foreach ($post->author_no_reg as $author) {
		// distinguish unregister
		if ($author->author_id == 0) {
			$other_author.= $author->author_name." ";
		}
	}
	if ( !empty($other_author) ) {
		echo "(与 ".$other_author."合作)";
	} ?>
</li>