<?
/**
* 
*/
class InfoController extends Controller
{
	
	function init() {
		if ( isGuest() ) {
			$this->redirect("/login");
		}

		$this->pageTitle = Yii::t("general", "XYZPedia") . " - ";
		$this->layout = "/layouts/user";
	}

	public function actionIndex() {
		$this->redirect('/user/info/notify');
	}

	public function actionContribute() {
		$this->pageTitle.= "我的投稿情况";
		$this->render("/user/info/contribute");
	}

	public function actionNotify() {
		$this->pageTitle.= "通知";
		$this->render("/user/info/notify");
	}

	public function actionAtme($type = 'contribute') {
		$this->pageTitle.= "艾特我的";
		$criteria = new CDbCriteria;
		$criteria->limit = 10;
		$criteria->order = "ID desc";
		
		if ($type == "contribute") {
			$criteria->condition = 'comment like "%'.user()->nick_name.'%"';
			$comments = ContribComment::model()->findAll($criteria);
		} elseif($type == "post") {
			$criteria->condition = 'content like "%'.user()->nick_name.'%"';
			$comments = Comment::model()->findAll($criteria);
		} else {
			throwException(404, "没有找到对应的评论区域。");
		}
		$this->render("/user/info/atme", array(
			"scope"		=> $type,
			"comments"	=> $comments,
		));
	}
}
?>