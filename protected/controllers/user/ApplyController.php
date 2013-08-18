<?
/**
* 
*/
class ApplyController extends Controller
{
	
	function init() {
		if ( isGuest() ) {
			$this->redirect("/login");
		}

		$this->pageTitle = Yii::t("general", "XYZPedia") . " - ";
		$this->layout = "/layouts/user";
	}

	public function actionIndex() {
		$this->redirect("/user/apply/forward");
	}
 

	public function actionForward() {
		$this->pageTitle.= "申请转发";

		if ( !empty($_POST) ) {
			$p = $_POST;
			// already can download
			if ( can("contribute", "download") ) {
				$this->redirect("/user/apply/forward?msg=1222");
			}
			$is_applied = Apply::model()->countByAttributes( array(
				"req_user_id" => user()->id,
				"type" => "fwd",
			));
			// have applied
			if ( $is_applied ) {
				$this->redirect("/user/apply/forward?msg=1223");
			}
			$apply = new Apply;
			$info = array(
				"num" => $p['downline-num'],
				"time" => $p['send-time'],
			);
			$apply->attributes = array(
				"req_user_id" => user()->id,
				"type" => "fwd",
				"status" => 1,
				"pass_user_id" => 0,
				"info" => CJSON::encode($info),
			);
			$apply->save();
			$this->redirect("/user/apply/forward?msg=221");
		}

		$apply = Apply::model()->findByAttributes( array(
			"req_user_id" => user()->id,
			"type" => "fwd",
		));
		$user_info = UserInfo::model()->findByPk(user()->id);
		$this->render("/user/apply/forward", array(
			"apply" => $apply,
			"user_info" => $user_info,
		));
	}


	public function actionManagement() {
		if ( !empty($_POST)) {
			$apply = new Apply;
			$apply->attributes = array(
				"req_user_id" => user()->id,
				"type" => $_POST['type'],
				"status" => 1,
				"pass_user_id" => 0,
				"info" => "{\"msg\":\"-\"}",
			);
			if ( !$apply->save() ) {
				dump( $apply->getErrors() );
				die();
			}
			$this->redirect("/user/apply/management?msg=231");
		}
		$this->pageTitle.= "申请参与管理";
		$this->render("/user/apply/management");
	}

}
?>