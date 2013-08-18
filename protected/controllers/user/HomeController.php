<?
/**
* 
*/
class HomeController extends Controller
{
	
	function init() {
		$this->pageTitle = Yii::t("general", "XYZPedia") . " - ";
		$this->layout = v() . "/layouts/main";
	}

	public function actionIndex() {
		$this->redirect('/author/xyzpedia.org');
	}


	// display user's main page based on ID
	public function actionID( $id ) {

		$id = (int) $id;
		$user = User::model()->findByPk( $id );

		if ( !empty($user) ) {
			$this->redirect( '/author/'.$user->login );
		} else {
			throwException(404, "Can not find user by ID ".$id.".");
		}
	}


	// for yoyo
	// public function actionYoyo() {
	// 	$this->layout = false;
	// 	$this->render('/personal_timeline/personal');
	// }
	// public function actionYoyoOriginal() {
	// 	$this->actionPage("yoyo");
	// }
	// public function actionBlessing() {
	// 	$name = $_POST['name'];

	// 	$mobile = "18810519139";
	// 	//$mobile = "13666691729";
	// 	$msg = "【小宇宙百科生日祝福】".$name."祝你生日快乐！";
	// 	$msg.= $_POST['message'];
	// 	$msg.= "(来自IP:".getIP().")";

	// 	Yii::import("ext.PHPFetion");
	// 	$fetion = new PHPFetion('18267197908', '2008xyzbk1227');
	// 	$result = $fetion->send($mobile, $msg);

	// 	if (empty($result)) {
	// 		$success = 0;
	// 	} else {
	// 		$success = mb_strpos($result, "成功");
	// 	}

	// 	if ($success) {
	// 		echo "发送成功";
	// 	} else {
	// 		echo "发送失败";
	// 	}
	// }


	// user's main page
	public function actionPage( $login ) {
		// get user
		$user = User::model()->findByAttributes(array("login" => $login));

		if ( empty($user) ) {
			throwException(404, "Can not find user named ".$login.".");
			if ( empty($user->info) ) {
				throwException(404, "Can not find user_info named ".$login.".");	
			}
		}
		$this->pageTitle.= $user->nick_name;

		$id = $user->id;
		// get user's written post
		$c = new CDbCriteria;
		$c->condition = 't.id="'.$id.'" and type="post" and created<'.gmtTime();
		$c->order = 'posts.serial desc';
		$userTemp = User::model()->with("posts")->find($c);
		$posts = empty($userTemp)? array(): $userTemp->posts;

		// get user's submit post
		$c->condition = 'submit_id='.$id.' and type="post" and created<'.gmtTime();
		$c->order = 'id desc';
		$submits = Post::model()->findAll($c);


		// calculate
		$post_count = count($posts);
		$contrib_count = Contrib::model()
			->count("author_id=".$user->id);
		$contrib_sent_count = Contrib::model()
			->count("author_id=".$user->id." and status='sent'");
		// statistic
		$all_count = $post_count + $contrib_count - $contrib_sent_count;
		$all_sent_count = $post_count;


		// get light
		$sql = 'select sum(t.light) from {{post2author}} s '
			  .'left join {{post}} t '
			  .'on s.post_id=t.id '
			  .'where s.author_id='.$id;
		$light = sql($sql)->queryScalar();

		$this->render( v()."/user/home", array(
			"user" => $user,
			"user_info" => $user->info,
			"stat" => (object)array(
				"all_count" => $all_count,
				"all_sent_count" => $all_sent_count,
				"light" => $light
			),
			"posts" => $posts,
			"submits" => $submits,
			"setting" => $user->setting,
			"tags" => $user->tags
		));
	}
}



?>