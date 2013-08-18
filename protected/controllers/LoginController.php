<?

class LoginController extends Controller
{

	public function init()
	{
		Yii::app()->language = 'zh_cn';
		$this->pageTitle = Yii::t("general", "XYZPedia"). " - ";
		$this->layout = v().'/layouts/login';
	}

	// display login form
	public function actionIndex() {

		$this->pageTitle.= Yii::t("actions", "Login");
		$this->render( v()."/login/login", array());
	}


	// handle login and logout
	public function actionLogin() {

		$username = $_POST["username"];
		$password = $_POST["password"];
		$remember = $_POST["remember"];

		//echo($username); die();

		$userIdentity = new UserIdentity($username, $password);
		$userIdentity->authenticate();
		// wrong user name
		if ($userIdentity->errorCode === userIdentity::ERROR_USERNAME_INVALID) {
			$data = array(
				"error" => 1,
				"message" => Yii::t("actions", "User does not exists.")
			);
		// login success
		} elseif ($userIdentity->errorCode === UserIdentity::ERROR_NONE) {
			$user = $userIdentity->user;
			$duration = $remember ? 3600 * 24 * 30 : 0; // 30 days
			Yii::app()->user->login($userIdentity, $duration);
			$data = array(
				"error" => 0,
				"nick_name" => $user->nick_name,
				"avatar" => $user->avatar,
				"panel" => can("panel", "panel"),
			);
			//UserInfo::model()->updateByPk($user->id, array(
			UserInfo::model()->updateAll(
				array("last_login" => gmtTime()),
				'id='.$user->id
			);
		// wrong password
		} else {
			$data = array(
				"error" => 1,
				"message" => Yii::t("actions", "Wrong password."),
			);
		}
		die(CJSON::encode($data));
	}

	// display logout page
	public function actionLogout() {

		$cookies = app()->request->getCookies();
		
		foreach($cookies as $c) {
			unset($cookies[$c->name]);
		}
	
		Yii::app()->session->clear();
		Yii::app()->session->destroy();
		Yii::app()->user->logout();	

		//$this->redirect(app()->user->loginUrl);
		$message = "You are already logged out.";
	}


	public function actionRegister() {
		$this->pageTitle.= "注册";
		$this->layout = "/layouts/login";
		$this->render("/login/register", array());
	}

	public function actionRegUnique() {
		
		$field = $_POST["field"];
		$value = $_POST['value'];

		if ( in_array($field, array("login", "nick_name", "mobile")) ) {
			$user = User::model()->find($field.'="'.$value.'"');
			if ( empty($user) ) {
				$data = array("exist" => 0);
			} else {
				$data = array("exist" => 1);
			}
			die(CJSON::encode($data));
		}
		if ( in_array($field, array("email")) ) {
			$user_info = UserInfo::model()->find($field.'="'.$value.'"');
			if ( empty($user_info) ) {
				$data = array("exist" => 0);
			} else {
				$data = array("exist" => 1);
			}
			die(CJSON::encode($data));
		}
	}

	public function actionRegSuccess() {
		
		if ( empty($_POST) )
			throwException(404, "没有POST数据。");

		$this->pageTitle.= "注册信息";
		$p = $_POST;
		if ( $p["pass1"] != $p['pass2']) {
			throwException(404, "两次密码不相同。");
		}
		$user = User::model()->findByAttributes( array("login" => $p['login']) );
		if ( !empty($user) ) {
			throwException(404, "用户名 ".$p['login']." 已存在，请勿重复注册。");
		}

		Yii::import('ext.PasswordHash');
		$pass = new PasswordHash(8, true);
		$encrypt = $pass->HashPassword($p['pass1']);
		$activation = $this->srand( $p['login'] );

		{
			// 原子性操作
			$msg = array();
			sql('start transaction')->query();

			// 创建用户
			$user = new User();
			$user->attributes = array(
				"login" => $p['login'],
				"pass" => $encrypt,
				"nick_name" => $p['nick_name'],
				"role" => "contributor",
				"real_name" => $p['login'],
				"status" => 0,
				"mobile" => $_POST["mobile"],
				"avatar" => ''
			);
			if ( !($succ1 = $user->save()) ) {
				$msg = array_merge($msg, $user->getErrors());
				sql('rollback')->query();
				break;
			}
			$uid = $user->id;

			// 用户信息
			$user_info = new UserInfo;
			$user_info->attributes = array(
				"id" => $uid,
				"email" => $p["mail"],
				"hometown" => $p["ht-province"].",".$p["ht-city"].",".$p["ht-area"],
				"resident" => $p["rd-province"].",".$p["rd-city"].",".$p["rd-area"],
				"regdate" => gmtTime(),
				"upline" => $p["upline"],
				"career" => $p["job"],
				"school" => $p['school'],
				"ip" => getIp(),
				"activation" => $activation,
			);
			if ( !($succ2 = $user_info->save()) ) {
				$msg = array_merge($msg, $user_info->getErrors());
				sql('rollback')->query();
				break;
			}

			// 用户设置
			$user_setting = new UserSetting();
			$user_setting->attributes = array(
				"id" => $uid
			);
			if ( !($succ3 = $user_setting->save()) ) {
				$msg = array_merge($msg, $user_setting->getErrors());
				sql('rollback')->query();
				break;
			}
			sql('commit')->query();
		}

		if ( $succ1 && $succ2 && $succ3 ) {
			$this->render("/login/loginfo", array(
				"msg" => "注册成功，请返回登录。"
			));
		} else {
			$this->render("/login/loginfo", array(
				"msg" => "注册失败。".var_dump($msg)
			));
		}

		/*
		$link = "http://xyzpedia.org/login/envoke?code=".$activation;
		$addr = "<a href='".$link."'>".$link."</a>";
		$recipient = (object)array(
			"addr" => $p['mail'],
			"name" => $p['nick_name'],
		);
		$subject = "【小宇宙百科】账号激活";
		$body = "<p>亲爱的小百科用户 <strong>".$user->nick_name."</strong>：</p>";
		$body.= "<p>您于".timeFormat(gmtTime(), "full")."注册了一个账号“".$p['login']."”，如果不是您自己所为，请勿点击下面的链接。</p>";
		$body.= "<p>您的激活地址是：".$addr."</p>";
		$body.= "<p>系统邮件，请勿回复。</p>";
		$body.= "<p>&nbsp;</p>";
		$body.= "<p style='text-align:right;'>小宇宙百科网站 敬上</p>";
		$body.= "<p style='text-align:right;'>".timeFormat(gmtTime(), "chinese")."</p>";
		Yii::import("ext.PHPMailSender");
		$res = PHPMailSender::sendMail($recipient, $subject, $body);

		$this->layout = "/layouts/login";
		$this->pageTitle = "提示信息";

		if ($res->status == 1) {
			$this->render("/login/loginfo", array(
				"msg" => "注册成功，请去邮箱查收激活邮件。"
			));
		} */
	}

	public function actionEnvoke() {
		
		if ( empty($_GET['code']) )
			throwException(404, '未指明激活码。');
		
		$activation = $_GET["code"];

		$this->layout = "/layouts/login";
		$this->pageTitle = "信息提示";
		$user_info = UserInfo::model()->find("activation='".$activation."'");
		if ( empty($user_info) ) {
			$this->render("/login/loginfo", array(
				"msg" => "激活地址不正确，有可能已经激活，请到<a href='/login'>这里</a>登录试试。"
			));
			exit;
		}
		UserInfo::model()->updateByPk($user_info->id, array(
			"activation" => ""
		));
		User::model()->updateByPk($user_info->id, array(
			"status" => 0
		));
		$this->render("/login/loginfo", array(
			"msg" => "激活成功，请到<a href='/login'>这里</a>登录。"
		));
	}

	public function actionRetrieve() {

		$this->pageTitle.= "重设密码";
		$this->layout = "/layouts/login";

		if ( !empty($_GET['code']) ) {
			$activation_key = $_GET['code'];

			$user_info = UserInfo::model()->find('activation="'.$activation_key.'"');
			if ( empty($user_info) ) {
				$this->render("/login/loginfo", array(
					"msg" => "重置链接不正确，有可能已经重置完毕，请到<a href='/login'>这里</a>登录试试。"
				));
				return;
			}
			$user = User::model()->findByPk($user_info->id);
			//Yii::import('ext.PasswordHash');
			$this->render("/login/resetpass", array(
				"user" => $user,
				"user_info" => $user_info,
				"activation_key" => $activation_key,
			));
		} else {
			$this->render("/login/retrieve", array());
		}
	}
	public function actionSendMail() {
		if ( empty($_POST))
			throwException(404, "没有POST数据。");

		$username = $_POST["username"];
		$nickname = $_POST["nickname"];
		$email = $_POST["email"];

		$user = User::model()->find("login='".$username."'");
		if ( empty($user) )
			throwException(404, "不存在名为“".$username."”的用户。");

		$user_info = UserInfo::model()->findByPk($user->id);
		if ( empty($user_info) )
			throwException(404, "用户信息不存在。");

		Yii::import("ext.PHPMailSender");

		$encrypt = $this->srand($username);
		$addr =  'http://xyzpedia.org/login/retrieve?code='.$encrypt;
		$link = '<a href="'.$addr.'">'.$addr.'</a>';

		$recipient = (object)array(
			"addr" => $user_info->email,
			"name" => $user->nick_name,
		);
		$subject = "【小宇宙百科】密码重置";
		$body = "<p>亲爱的小百科用户 <strong>".$user->nick_name."</strong>：</p>";
		$body.= "<p>您于".timeFormat(gmtTime(), "full")."请求了重置密码，如果不是您自己所为，请勿点击下面的链接。</p>";
		$body.= "<p>您的重置地址是：".$addr."</p>";
		$body.= "<p>密码重置后失效。系统邮件，请勿回复。</p>";
		$body.= "<p>&nbsp;</p>";
		$body.= "<p style='text-align:right;'>小宇宙百科网站 敬上</p>";
		$body.= "<p style='text-align:right;'>".timeFormat(gmtTime(), "chinese")."</p>";


		if ( !empty($email) ) {
			if ( $email == $user_info->email ) {
				// send email
				$res = PHPMailSender::sendMail($recipient, $subject, $body);
			} else {
				throwException(404, "用户名为“".$username."”的Email地址不正确。");
			}
		} elseif ( !empty($nickname) ) {
			if ( $nickname == $user->nick_name ) {
				// send email
				$res = PHPMailSender::sendMail($recipient, $subject, $body);
			} else {
				throwException(404, "用户名为“".$username."”的投稿署名不正确。");
			}
		} else {
			throwException(404, "邮箱和投稿署名必须填一个。");
		}
		if ($res->status == 1) {
			UserInfo::model()->updateByPk($user->id, array(
				"activation" => $encrypt,
			));
			$this->layout = "/layouts/login";
			$this->pageTitle = "信息提示";
			$this->render("/login/loginfo", array(
				"msg" => "发送成功，请至". $user_info->email ."查收邮件。"
			));
		} else {
			throwException(404, $res->info);
		}
	}

	public function actionResetPass() {
		
		$p = $_POST;
		if ( empty($p)) {
			throwException(404, "没有POST数据。");
		}
		if ( $p["password1"] != $p['password2']) {
			throwException(404, "两次密码不相同。");
		}
		$activation_key = $p['activation_key'];

		$user_info = UserInfo::model()->find('activation="'.$activation_key.'"');
		if ( empty($user_info) ) {
			throwException(404, "重置链接不正确。");
		}
		Yii::import('ext.PasswordHash');
		$pass = new PasswordHash(8, true);
		$encrypt = $pass->HashPassword($p['password1']);

		User::model()->updateByPk($user_info->id, array(
			"pass" => $encrypt,
		));
		UserInfo::model()->updateByPk($user_info->id, array(
			"activation" => "",
		));
		echo "密码重置成功。<a href='/login'>登录</a>";

	}


	public function srand($str) {
		$encrypt = sha1( $str.gmtTime() );
		$encrypt = md5($encrypt);
		$encrypt = substr($encrypt, 0, 18);
		return $encrypt;
	}
	public function actionPassTest() 
	{
		Yii::import('application.extensions.*');
		require_once("PasswordHash.php");
		$criteria = new CDbCriteria();
		$criteria->condition = "login = '4'";
		$user = User::model()->findAll($criteria);

		if ( !isset($user[0]->attributes) ) {
			die("user not exists");
		} else {

			//die(print_r($user[0]->attributes));

			$pass = new PasswordHash(8, true);
			$correct = $pass->CheckPassword("wuyou", $user[0]->pass);

			die($correct);
		}
	}

}
?>