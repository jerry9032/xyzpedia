<?
/**
* 
*/
class ProfileController extends Controller {
	
	function init() {
		if ( isGuest() ) {
			$this->redirect('/login');
		}

		$this->pageTitle = Yii::t("general", "XYZPedia") . ' - ';
		$this->pageTitle.= Yii::t("user", "User Profile");

		$this->layout = "/layouts/user";
	}

	public function actionIndex() {
		$this->redirect('/user/profile/basic');
	}

	public function actionBasic() {

		if ( !empty($_POST) ) {
			$p = $_POST;
			User::model()->updateByPk(user()->id, array(
				"real_name" => $p["real-name"],
				'avatar' => $p["avatar-current"],
				//"nick_name" => $p["nick-name"],
			));
			UserInfo::model()->updateByPk(user()->id, array(
				"hometown" => $p["ht-province"].','.$p["ht-city"].','.$p["ht-area"],
				"resident" => $p["rd-province"].','.$p["rd-city"].','.$p["rd-area"],
				"descript" => $p["descript"],
				"downline" => $p["downline"],
			));
			$this->redirect('/user/profile/basic?msg=201');
		}
		$user = User::model()->findByPk(user()->id);

		$this->render("/user/profile/basic", array(
			"user" => $user,
			"user_info" => $user->info,
		));
	}

	public function trimDomain($url) {
		$domainPos = strpos($url, "http://");
		if ($domainPos !== false) {
			$url = substr($url, 7);
		}
		$domainPos = strpos($url, "/");
		if ($domainPos !== false) {
			$url = substr($url, $domainPos+1);
		}
		return $url;
	}

	public function actionContact() {
		if ( !empty($_POST) ) {
			$p = $_POST;
			User::model()->updateByPk(user()->id, array(
				"mobile" => $p["mobile"],
			));
			UserInfo::model()->updateByPk(user()->id, array(
				"email" => $p["email"],
				"url" => $p["url"],
				"qq" => $p["qq"],
				"weibo" => $p["weibo"],
				"weibo_url" => $this->trimDomain($p["weibo_url"]),
				"upline" => $p["upline"],
			));
			$this->redirect('/user/profile/contact?msg=202');
		}
		$user = User::model()->findByPk(user()->id);

		$this->render("/user/profile/contact", array(
			"user" => $user,
			"user_info" => $user->info,
		));
	}


	public function actionTags() {
		if ( !empty($_POST) ) {
			$tags = explode(',', $_POST['tags']);
			$order = 1;
			sql("delete from {{user2tag}} where user_id='".user()->id."'")->query();
			foreach ($tags as $tag) {
				$tid = sql("select ID from {{category}} where tag='".$tag."'")->queryScalar();
				if ( $tid ) {
					$u2t = new User2Tag;
					$u2t->attributes = array(
						"user_id" => user()->id,
						"tag_id" => $tid,
						"tag_order" => $order++,
					);
					$u2t->save();
				}
			}
			$this->redirect('/user/profile/tags?msg=203');
		}
		$user = User::model()->findByPk(user()->id);
		$tags_all = Category::model()->findAll();

		$this->render("/user/profile/tags", array(
			"user" => $user,
			"user_info" => $user->info,
			"tags_all" => $tags_all,
		));
	}


	public function actionCareer() {
		if ( !empty($_POST) ) {
			$p = $_POST;
			UserInfo::model()->updateByPk(user()->id, array(
				"career" => $p["career"],
				"school" => $p["school"],
			));
			$this->redirect('/user/profile/career?msg=204');
		}
		$user_info = UserInfo::model()->findByPk(user()->id);

		$this->render("/user/profile/career", array(
			"user_info" => $user_info,
		));
	}


	public function actionAvatarUpload()
	{
		Yii::import("ext.AvatarUpload.qqFileUploader");
		$folder = './images/avatar/';

		$allowedExtensions = array('jpg', 'jpeg', 'png', 'bmp', 'gif'); 
		$sizeLimit = 100 * 1024;
		$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
		$result = $uploader->handleUpload($folder, true);
		if($result['success'])
		{
			$result['error'] = 0;
		}
		else
		{
			$result['error'] = 1;
		}
		
		die(json_encode($result));
	}

	public function actionAvatarCrop()
	{
		$src = $_POST['source'];
		$widthBase = $_POST['widthBase'];

		$original = json_decode($_POST['original']);
		$range = json_decode($_POST['range']);
		
		$scale = $widthBase / $original->width;

		if ( empty($range) || empty($original)) {
			die(json_encode(array('error' => 1, 'msg' => '参数错误。')));
		}
		$x = $range->x1 / $scale;
		$y = $range->y1 / $scale;
		$w = ($range->x2 - $range->x1) / $scale;
		$h = ($range->y2 - $range->y1) / $scale;

		$dir = Yii::getPathOfAlias('webroot') . $src;

		$target = @imagecreatetruecolor($w, $h)
			or die("Cannot Initialize new GD image stream");
		$source = imagecreatefromjpeg($dir);

		imagecopy( $target, $source, 0, 0, $x, $y, $w, $h);

		$namePos = strripos($dir, ".");
		$name = substr($dir, 0, $namePos);
		$cropName = $name."-crop.jpg";

		imagejpeg($target, $cropName, 100);

		$returnNamePos = strripos($cropName, "/");
		$returnName = substr($cropName, $returnNamePos + 1);

		die(json_encode(array('error' => 0, 'filename' => $returnName)));
	}
}

?>
