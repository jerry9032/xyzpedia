<?
/**
* 
*/
class ContributeController extends Controller
{
	
	function init() {
		if ( isGuest() )
			$this->redirect("/login");
		
		$this->layout = "/layouts/admin";
		$this->pageTitle = Yii::t("general", "XYZPedia"). " - ";
	}

	public function actionIndex() {
		$this->redirect("/admin/contribute/assign");
	}

	public function actionAssign($id = 0) {

		// check cap
		if ( !can("contribute", "assign") ) {
			throwException(404, Yii::t("admin", "No access."));
		}

		if ($id == 0) {
			$this->redirect("/contribute/list/neglected");
		}

		$criteria = new CDbCriteria;
		$criteria->order = "score desc";
		$criteria->limit = 15;
		$usersByScore = UserInfo::model()->findAll($criteria);

		$criteria->order = "last_login desc";
		$usersByLogin = UserInfo::model()->findAll($criteria);

		$contrib = Contrib::model()->findByPk($id);
		$criteria = new CDbCriteria;
		$criteria->condition = "tag_id=".$contrib->category_id;
		$usersByTag = User2Tag::model()->with("user")->findAll($criteria);

		$this->pageTitle.= "分配审稿人";
		$this->render("/admin/contribute/assign", array(
			"id" => $id,
			"action" => "assign",
			"currentTag" => $contrib->category->short,
			"usersByTag" => $usersByTag,
			"usersByScore" => $usersByScore,
			"usersByName" => $this->userList(),
			"usersByLogin" => $usersByLogin,
		));
	}


	public function actionDoAssign( $id = 0 ) {
		
		if ( !empty( $_POST ) ) {
			$p = $_POST;
		} else {
			throwException(404, "没有Post数据。");
		}

		// by tag
		if (isset($p['by-tag'])) {
			Contrib::model()->updateByPk($id, array(
				"status" => 'pending',
				"editor_id" => $p['by-tag'],
			));


		// by score
		} elseif (isset($p['by-score'])) {
			Contrib::model()->updateByPk($id, array(
				"status" => 'pending',
				"editor_id" => $p['by-score'],
			));

		// by nick name
		} elseif (isset($p['by-nick-name'])) {
			$uid = sql("select id from {{user}} where nick_name='".$p['by-nick-name']."'")
				->queryScalar();
			if ( $uid == false) {
				throwException(404, "Can not find specified user.");
			} else {
				Contrib::model()->updateByPk($id, array(
					"status" => 'pending',
					"editor_id" => $uid,
				));
			}

		// by last login
		} elseif (isset($p['by-last-login'])) {
			Contrib::model()->updateByPk($id, array(
				"status" => 'pending',
				"editor_id" => $p['by-last-login'],
			));
		}
		$this->redirect("/contribute/list/pending");
	}


	public function userList() {

		$users = User::getUserList();

		$str = '[';
		$first_user = true;
		foreach ($users as $user) {
			if ( !$first_user ) {
				$str.= ',';
			}
			$str.= '"'.$user->nick_name.'"';
			$first_user = false;
		}
		$str.= ']';
		return $str;
	}
}
?>