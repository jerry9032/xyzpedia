<?
/**
* 
*/
class SettingController extends Controller {
	
	function init() {
		if ( isGuest() ) {
			$this->redirect("/login");
		}

		$this->pageTitle = Yii::t("general", "XYZPedia") . " - ";
		$this->layout = "/layouts/user";
	}

	public function actionIndex() {
		$this->redirect('/user/setting/basic');
	}

	public function actionBasic() {
		$this->pageTitle.= "账号基本设置";
		if ( !empty($_POST) ) {
			$p = $_POST;
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
			$this->redirect('/user/setting/basic?msg=205');
		}
		$user = User::model()->findByPk(user()->id);

		$this->render("/user/setting/basic", array(
			"user" => $user,
			"roles" => Capability::roleList(),
			"user_setting" => $user->setting,
		));
	}



	public function actionPage() {
		$this->pageTitle.= "作者主页设置";
		if ( !empty($_POST) ) {
			$p = $_POST;
			UserSetting::model()->updateByPk(user()->id, array(
				"show_home" => isset($p["show-hometown"]) ? 1: 0,
				"show_resident" => isset($p["show-resident"]) ? 1: 0,
				"show_tags"  => isset($p['show-tags']) ? 1: 0,
				"show_weibo"  => isset($p['show-weibo']) ? 1: 0,
				"contrib_num" => $p['contrib-num'],
				"illustrate_num" => $p['illustrate-num'],
			));
			$this->redirect('/user/setting/page?msg=206');
		}
		$user = User::model()->findByPk(user()->id);

		$this->render("/user/setting/page", array(
			"user" => $user,
			"user_setting" => $user->setting,
		));
	}
}
?>
