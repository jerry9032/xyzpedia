<?
/**
* 
*/
class UserController extends Controller
{
	
	function init() {
		if ( isGuest() )
			$this->redirect("/login");
		
		$this->layout = "/layouts/admin";
		$this->pageTitle = Yii::t("general", "XYZPedia"). " - ";
	}

	public function actionList() {
		// check cap
		if ( !can("user", "list") ) {
			throwException(404, Yii::t("admin", "No access."));
		}

		$rawData = User::model()->findAll();

		$dataProvider = new CArrayDataProvider($rawData, array(
			'id' => 'user_list',
			'sort' => array(
				'attributes' => array(
					'id', 'login', 'nick_name',
				),
			),
			'pagination' => array(
				'pageSize' => 20
			),
		));

		$this->pageTitle.= "用户列表";
		$this->render("/admin/user/list", array(
			"dataProvider" => $dataProvider,
		));
	}

	public function actionSearch($key = '') {
		// check cap
		if ( !can("user", "list") ) {
			throwException(404, Yii::t("admin", "No access."));
		}
		if (empty($key))
			throwException(404, '搜索数据不能为空。');

		$criteria = new CDbCriteria;
		$criteria->addSearchCondition('login',		$key, true, 'or');
		$criteria->addSearchCondition('nick_name',	$key, true, 'or');
		$criteria->addSearchCondition('mobile', 	$key, true, 'or');
		$rawData = User::model()->with('info')->findAll($criteria);

		$dataProvider = new CArrayDataProvider($rawData, array(
			'id' => 'user_list',
			'sort' => array(
				'attributes' => array(
					'id', 'login', 'nick_name',
				),
			),
			'pagination' => array(
				'pageSize' => 20
			),
		));
		$this->pageTitle.= "搜索结果";
		$this->render("/admin/user/list", array(
			"type" => "search",
			"prompt" => "包含关键词 “".$key."”的搜索结果。",
			"dataProvider" => $dataProvider,
		));
	}


	public function actionInfoList()
	{
		// check cap
		if ( !can("user", "list") ) {
			throwException(404, Yii::t("admin", "No access."));
		}

		$rawData = UserInfo::model()->with('user')->findAll();

		$dataProvider = new CArrayDataProvider($rawData, array(
			'id' => 'userinfo_list',
			'sort' => array(
				'attributes' => array(
					'id', 'email', 'upline', 'downline'
				),
			),
			'pagination' => array(
				'pageSize' => 20
			),
		));

		$this->pageTitle.= "用户信息列表";
		$this->render("/admin/user/infolist", array(
			"dataProvider" => $dataProvider,
		));
	}


	public function actionCapability($type = "post") {

		// check cap
		if ( !can("user", "promote") ) {
			throwException(404, Yii::t("admin", "No access."));
		}

		$this->pageTitle.= Yii::t("admin", "Edit Capability");

		$criteria = new CDbCriteria();
		$criteria->addCondition("`group`='{$type}'");
		$caps = Capability::model()->findAll($criteria);
		
		// dump($caps);die();

		$capObjList = array();
		foreach ($caps as $cap) {
			$c = $cap->attributes;
			$capObjList[] = array(
				"role" => $c["role"],
				"list" => explode(",", $c["list"])
			);
		}
		//dump($capability); die();
		$this->render("/admin/user/capability", array(
			"group" => $type,
			"capObjList" => $capObjList,
		));
	}


	public function actionApply() {
		// check cap
		if ( !can("user", "permote") ) {
			throwException(404, Yii::t("admin", "No access."));
		}
		$this->pageTitle.= "处理申请";

		$rawData = Apply::model()->with("req_user")->findAllByAttributes(array(
			"status" => 1,
		));

		$dataProvider = new CArrayDataProvider($rawData, array(
			'id' => 'apply-list',
			'sort' => array(
				'attributes' => array(
					'id', 'type', 'req_user_id'
				),
				'defaultOrder'=>'id',
			),
			'pagination' => array(
				'pageSize' => 20,
			),
		));

		$this->render("/admin/user/apply", array(
			"dataProvider" => $dataProvider,
		));
	}

	public function actionApprove() {
		
		if ( !empty($_POST) ) {
			$p = $_POST;
			// approve it
			Apply::model()->updateByPk($p['aid'], array(
				"status" => 2,
				"pass_user_id" => user()->id,
			));
			// if type=fwd, update role, update downline
			if ( $p['type'] = 'fwd' ) {
				$apply = Apply::model()->findByPk($p['aid']);
				$uid = $apply->req_user_id;
				$num = json_decode($apply->info)->num;
				UserInfo::model()->updateByPk($uid, array('downline' => $num));
				User::model()->updateByPk($uid, array('role' => 'foward'));
			}
			echo "1";
		}
	}
}
?>
