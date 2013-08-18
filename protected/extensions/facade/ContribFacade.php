<?

/**
* 
*/
class ContribFacade {
	function __construct() {
	}

	static function convert($contrib) {
		return (object) array(
			"id" => $contrib->id,
			"title" => $contrib->title,
			"author" => (object)array(
				"id" => $contrib->author_id,
				"name" => $contrib->author->nick_name),
			"category" => (object)array(
				"id" => $contrib->category_id,
				"name" => $contrib->category->short),
			"created" => $contrib->created,
			"appoint" => $contrib->appoint,
			"color" => "",
			"reason" => ""
		);
	}
}


?>