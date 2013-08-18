<?

/**
* 
*/
class FeedController extends Controller
{
	
	function init()
	{
		
	}

	public function actionRSS()
	{

		$criteria = new CDbCriteria;
		$criteria->addCondition("`type`='post'");
		$criteria->addCondition("created<".gmtTime());
		$criteria->order = "created desc";
		$criteria->limit = 20; // read in database

		$posts = Post::model()->findAll($criteria);

		$this->render("/feed/rss", array(
			"posts" => $posts,
		));
	}
}
?>