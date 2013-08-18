<?
/**
* 
*/
class InfoController extends Controller
{
	
	function init() {

		user_setting_init();

		if ( isGuest() )
			$this->redirect("/login");
		
		$this->layout = "/layouts/admin";
		$this->pageTitle = Yii::t("general", "XYZPedia"). " - ";
	}


	public function actionIndex() {
		$this->forward("/admin/info/summary");
	}


	public function actionSummary() {
		$this->pageTitle.= "Admin Panel";

		$stat = array(
			"num_of_user"	=> sql("select count(*) from {{user}}")->queryScalar(),
			"num_of_forward"=> sql("select sum(downline) from {{user_info}} ")->queryScalar(),
			"num_of_post"	=> sql("select count(*) from {{post}} where type='post'")->queryScalar(),
			"num_of_contrib"=> sql("select count(*) from {{contrib}}")->queryScalar(),
		);


		$chart = array(
			"users" => sql("SELECT FROM_UNIXTIME(regdate, '%Y-%m') month, COUNT(id) users FROM xyz_user_info GROUP BY month;")->queryAll(),
			"contribs" => sql("SELECT FROM_UNIXTIME(created, '%Y-%m') month, COUNT(id) contribs FROM xyz_contrib GROUP BY month;")->queryAll()
		);

		$this->render("/admin/info/summary", array(
			"action" => "summary",
			"stat" => (object)$stat,
			"chart" => $chart
		));
	}
}
?>