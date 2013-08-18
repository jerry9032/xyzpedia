<?

class UserController extends Controller {
	
	public function init() {
		if ( isGuest() ) {
			$this->redirect("/login");
		}
		$this->pageTitle = Yii::t("general", "XYZPedia") . " - ";
		$this->layout = "/layouts/user";
	}


	public function actionProfile($action = "basic") {

		$this->pageTitle.= Yii::t("user", "User Profile");

		$p = $_POST;
		$msg = 0;

		
		if ( !empty($p) ) {
			switch ($action) {
				case 'basic':
					User::model()->updateByPk(user()->id, array(
						"real_name" => $p["real-name"],
						//"nick_name" => $p["nick-name"],
					));
					UserInfo::model()->updateByPk(user()->id, array(
						"hometown" => $p["ht-province"].','.$p["ht-city"].','.$p["ht-area"],
						"resident" => $p["rd-province"].','.$p["rd-city"].','.$p["rd-area"],
						"descript" => $p["descript"],
						"downline" => $p["downline"],
					));
					$msg = 1;
					break;

				case 'contact':
					User::model()->updateByPk(user()->id, array(
						"mobile" => $p["mobile"],
					));
					UserInfo::model()->updateByPk(user()->id, array(
						"email" => $p["email"],
						"url" => $p["url"],
						"qq" => $p["qq"],
						"upline" => $p["upline"],
					));
					$msg = 2;
					break;

				case 'tags':
					//dump($_POST);
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
					$msg = 3;
					break;

				case 'career':
					UserInfo::model()->updateByPk(user()->id, array(
						"career" => $p["career"],
						"school" => $p["school"],
					));
					$msg = 4;
					break;

				default:
					break;
			}
		}

		$user = User::model()->findByPk(user()->id);
		$user_info = UserInfo::model()->findByPk(user()->id);


		$tagObjs = Category::model()->findAll();
		$tagList = array();
		foreach ($tagObjs as $tagObj) {
			$tagList[] = $tagObj->tag;
		}

		$this->render("/user/profile", array(
			"action" => $action,
			"user" => $user,
			"user_info" => $user->info,
			"roles" => Capability::roleList(),
			"tag_list" => $tagList,
			"msg_id" => $msg,
		));
	}

	public function actionSetting($action = "setting")
	{

		$p = $_POST;
		$msg = 0;

		$this->pageTitle.= "账号设置";

		if ( !empty($p)) {
			switch ($action) {
				case 'setting':

					Yii::import("ext.PasswordHash");
					$pass_hasher = new PasswordHash(8, true);
					$org_pass = $p["org-password"];
					$pass_plain = $p["password1"];

					// check password
					$user = User::model()->findByPk(user()->id);
					$correct = $pass_hasher->CheckPassword($org_pass, $user->pass);
					if ( !$correct ) {
						throwException(500, "Original password not correct.");
					}

					// update password
					$pass_crypt = $pass_hasher->HashPassword($pass_plain);
					User::model()->updateByPk(user()->id, array(
						"pass" => $pass_crypt,
					));
					$msg = 5;
					break;
				
				case 'page':
					// dump($p);die();

					UserSetting::model()->updateByPk(user()->id, array(
						"show_home" => isset($p["show-hometown"]) ? 1: 0,
						"show_resident" => isset($p["show-resident"]) ? 1: 0,
						"show_tags"  => isset($p['show-tags']) ? 1: 0,
						"contrib_num" => $p['contrib-num'],
						"illustrate_num" => $p['illustrate-num'],
					));
					$msg = 6;

				default:
					break;
			}
		}

		$user = User::model()->findByPk(user()->id);

		$this->render("/user/profile", array(
			"action" => $action,
			"user" => $user,
			"roles" => Capability::roleList(),
			"user_setting" => $user->setting,
			"msg_id" => $msg,
		));
	}


	public function actionInfo($action = "notify", $scope = "contribute")
	{
		switch ($action) {
			case 'contribute':
				$this->pageTitle.= "我的投稿情况";
				$this->render("/user/info", array(
					"action" => $action,
				));
				break;
			case 'notify':
				$this->pageTitle.= "通知";
				$this->render("/user/info", array(
					"action" => $action,
				));
				break;
			case 'atme':
				$this->pageTitle.= "艾特我的";
				$criteria = new CDbCriteria;
				$criteria->limit = 10;
				$criteria->order = "ID desc";
				
				if ($scope == "contribute") {
					$criteria->condition = 'comment like "%'.user()->nick_name.'%"';
					$comments = ContribComment::model()->findAll($criteria);
				} elseif($scope == "post") {
					$criteria->condition = 'content like "%'.user()->nick_name.'%"';
					$comments = Comment::model()->findAll($criteria);
				} else {
					throwException(404, "没有找到对应的评论区域。");
				}
				$this->render("/user/info", array(
					"action"	=> $action,
					"scope"		=> $scope,
					"comments"	=> $comments,
				));
				break;
			default:
				die();
				break;
		}
	}

	public function actionHome($login) {
		
		// get user
		$user = User::model()->findByAttributes(array("login" => $login));

		// error handling
		if ( empty($user) ) {
			throwException(404, "Can not find user named ".$login.".");
			if ( empty($user->info) ) {
				throwException(404, "Can not find user_info named ".$login.".");	
			}
		}
		$this->pageTitle.= $user->nick_name;

		// get user's written post
		$c = new CDbCriteria;
		$c->condition = 'login="'.$login.'" and type="post" and created<'.gmtTime();
		$c->order = 'posts.ID desc';
		$userTemp = User::model()->with("posts")->find($c);
		$posts = empty($userTemp)? array(): $userTemp->posts;

		// get user's submit post
		$c->condition = 'submit_id='.$user->id.' and type="post" and created<'.gmtTime();
		$c->order = 'ID desc';
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


		$this->render( v()."/user/home", array(
			"user" => $user,
			"user_info" => $user->info,
			"stat" => (object)array(
				"all_count" => $all_count,
				"all_sent_count" => $all_sent_count,
			),
			"posts" => $posts,
			"submits" => $submits,
			"setting" => $user->setting,
			"tags" => $user->tags
		));
	}

	public function actionRequest()
	{
		# code...
	}
}


?>