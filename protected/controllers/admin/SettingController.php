<?
/**
* 
*/
class SettingController extends Controller {

	function init() {
		if ( isGuest() )
			$this->redirect("/login");
		
		$this->layout = "/layouts/admin";
		$this->pageTitle = Yii::t("general", "XYZPedia"). " - ";
	}

	public function actionIndex() {
		//$this->redirect("/admin/post/list");
	}


	public function actionGeneral() {
		if ( !can("setting", "general") ) {
			throwException(404, Yii::t("admin", "您没有修改网站设置的权限。"));
		}
		
		$this->render("/admin/setting/general", array(
			"action" => "general"
		));
	}

	public function actionGeneralUpdate() {
		if ( empty($_POST) ) {
			throwException(404, "没有Post数据。");
		}

		set_option("index_notify_color", $_POST['index_notify_color']);

		$this->redirect("/admin/setting/general?msg=131");
	}

	public function actionReading() {
		if ( !can("setting", "reading") ) {
			throwException(404, Yii::t("admin", "您没有修改网站设置的权限。"));
		}
		$this->render("/admin/setting/reading", array(
			"action" => "reading"
		));
	}

	public function actionWidget() {
		if ( !can("setting", "widget") ) {
			throwException(404, Yii::t("admin", "您没有修改网站设置的权限。"));
		}

		$this->pageTitle.= "侧边栏";

		$this->render("/admin/setting/widget", array(
			"action" => "widget",
			"widgets" => explode(",", get_option("index_widget_order")),
			"widgets_post" => explode(",", get_option("post_widget_order")),
			"widgets_all" => explode(",", get_option("index_widget_all")),
			"widgets_post_all" => explode(",", get_option("post_widget_all")),
		));
	}

	public function actionWidgetUpdate()
	{
		if ( empty($_POST) ) {
			throwException(404, "没有Post数据。");
		}
		set_option("index_widget_order", $_POST['index_widget_order']);
		set_option("post_widget_order", $_POST['post_widget_order']);
		set_option("index_widget_all", $_POST['index_widget_all']);
		set_option("post_widget_all", $_POST['post_widget_all']);
		set_option("index_recentpost_num", $_POST['index_recentpost_num']);
		set_option("index_recentcomment_num", $_POST['index_recentcomment_num']);
		if ( isset($_POST['index_category_show_empty']) ) {
			set_option("index_category_show_empty", 1);
		} else {
			set_option("index_category_show_empty", 0);
		}
		
		$this->redirect("/admin/setting/widget?msg=133");
	}


	public function actionCron()
	{
		$rawData = Cron::model()->findAll();
		$dataProvider = new CArrayDataProvider($rawData, array(
			'id' => 'cron',
			'sort' => array(
				'attributes' => array(
					'id', 'day', 'action',
					'time', 'type'
				),
				'defaultOrder' => 'id',
			),
			'pagination' => array(
				'pageSize' => 20
			),
		));
		$this->pageTitle.= "定时管理";
		$this->render("/admin/setting/cron", array(
			"dataProvider" => $dataProvider,
		));
	}

	public function actionWeixin()
	{
		$this->pageTitle .= "微信管理";
		$this->render("/admin/setting/weixin", array(
			"action" => "Weixinsetting",
			"party_echo_on"        => get_option("weixin_party_echo_on"),
			"party_echo"           => get_option("weixin_party_echo"),
			"party_signup_echo_on" => get_option("weixin_party_signup_echo_on"),
			"party_signup_echo"    => get_option("weixin_party_signup_echo"),
			"keyword_search_echo"  => get_option("weixin_keyword_search_echo"),
			"howto_contribute_echo"=> get_option("weixin_howto_contribute_echo"),
			"about_xyzpedia_echo"  => get_option("weixin_about_xyzpedia_echo"),
		));
	}

	public function actionWeixinUpdate()
	{
		if (empty($_POST)){
			throwException(500, "没有Post数据");
		}
		set_option("weixin_party_echo_on",        $_POST["party_echo_on"]);
		set_option("weixin_party_echo",           $_POST["party_echo"]);
		set_option("weixin_party_signup_echo_on", $_POST["party_signup_echo_on"]);
		set_option("weixin_party_signup_echo",    $_POST["party_signup_echo"]);
		set_option("weixin_keyword_search_echo",  $_POST["keyword_search_echo"]);
		set_option("weixin_howto_contribute_echo",$_POST["howto_contribute_echo"]);
		set_option("weixin_about_xyzpedia_echo",  $_POST["about_xyzpedia_echo"]);
		$this->redirect("/admin/setting/weixin?msg=131");
	}

}

?>
