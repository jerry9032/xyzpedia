<?
/**
* 
*/
class PageController extends Controller
{
	
	function init() {

		$this->pageTitle = Yii::t("general", "XYZPedia"). " - ";

	}


	public function actionDownload() {

		$this->pageTitle.= "下载";

		if ( !isset($_GET["id"]) ) {

			// normal page
			$criteria = new CDbCriteria;
			$criteria->condition = '`group`="text" and `appoint`<'.gmtTime();
			$criteria->order = "ID desc";
			$criteria->limit = 6; // read in database
			$files = Download::model()->findAll($criteria);

			$this->render("/page/download", array(
				"files" => $files,
				"widgets" => get_option("post_widget_order"),
			));
		} else {
			// download file
			$id = (int)$_GET["id"];

			// check permission
			if ( isGuest() ) {
				$this->redirect("/login");
			}
			if ( !can('contribute', 'download') ) {
				throwException(500, "You can not have the permission the download this file.");
			}

			// get file
			$file = Download::model()->findByPk($id);

			if ( empty($file) ) {
				throwException(404, "Can not find specified file.");
			}

			header("Content-type: text/plain");
			header("Accept-Ranges: bytes");
			header("Content-Disposition: attachment; filename=".$file['filename']);
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0" );
			header("Pragma: no-cache" );
			header("Expires: 0" ); 

			$url = "downloads/".$file['group']."/".$file['filename'];			
			$data = file_get_contents($url);
			echo $data;

			// add a download log
			// check exist
			$criteria = new CDbCriteria;
			$criteria->condition = "download_id=".$id." and user_id=".user()->id;
			$log = DownloadLog::model()->findAll($criteria);

			if ( empty($log) ) {
				// not exist
				// increase counter
				sql('update {{download}} set `hits`=`hits`+1 where ID='.$id)->query();

				$log = new DownloadLog;
				$log->attributes = array(
					"download_id" => $id,
					"user_id" => user()->id,
					"created" => gmtTime(),
					"ip" =>  getIP(),
				);

				if ( !$log->save() ) {
					dump($log->getErrors());
				}
			}
			

		}
	}


	public function actionVoice() {

		$this->pageTitle.= "有声版小百科";

		$criteria = new CDbCriteria;
		$criteria->condition = "`group`='voice'";
		$criteria->order = "ID desc";
		$files = Download::model()->findAll($criteria);

		$this->render("/page/voice", array(
			"files" => $files,
			"widgets" => get_option("post_widget_order"),
		));
		
	}

	public function actionAbout() {
		
		$this->pageTitle.= "关于“小宇宙百科”的一切和我的初衷";

		$this->render("/page/about", array(
			"widgets" => get_option("post_widget_order"),
		));
	}

	public function actionBasicInfo() {
		
		$this->pageTitle.= "基本信息";

		$this->render("/page/basic-info", array(
			"widgets" => get_option("post_widget_order"),
		));
	}


	public function actionEvent( $slug = "" ) {
		
		$this->pageTitle.= "活动通告";
		if ( empty($slug)) {
			throwException(404, "找不到页面。");
		}
		$event = Post::model()
			->with('comment')
			->findByAttributes( array(
				"slug" => $slug
			)
		);
		if ( empty($event) ) {
			throwException(404, "找不到页面。");
		}

		// comments
		$criteria = new CDbCriteria();
		$criteria->condition = "t.post_id=".$event->id;
		$criteria->order = "t.created";

		$this->render("/page/event", array(
			"event" => $event,
			"widgets" => get_option("post_widget_order"),
			"comments" => Comment::model()->with('parent')->findAll($criteria),
		));
	}
}
?>