<?
/**
* 
*/
class FileController extends Controller
{
	
	function init() {
		if ( isGuest() )
			$this->redirect("/login");
		
		$this->layout = "/layouts/admin";
		$this->pageTitle = Yii::t("general", "XYZPedia"). " - ";
	}

	public function actionIndex()
	{
		
	}

	public function actionUpload()
	{
		$this->pageTitle.= "文件上传";
		$this->render("/admin/file/upload", array(
			"action" => "upload",
		));
	}

	public function actionSaveUpload() {
		if ( empty($_POST) ) {
			throwException(404, "没有POST数据。");
		}
		$p = $_POST;

		//die(dump($p));
		
		$loc = Yii::getPathOfAlias('webroot')."/downloads/";
		$file_path = $loc.$p['filename'];
		$new_loc = $loc.$p['category']."/".$p['filename'];

		// move the file
		if (rename($file_path, $new_loc)) {

			$appoint = gmtTime($p['appoint-date']."-".$p['appoint-hour']."-".$p['appoint-minute']);
			$download = Download::model()->find("filename='".$p['filename']."'");

			// new file
			if ( empty($download) ) {
				$download = new Download;
				$download->attributes = array(
					"title" => $p['title'],
					"filename" => $p['filename'],
					"created" => gmtTime(),
					"appoint" => $appoint,
					"hit" => 0,
					"user" => user()->id,
					"group" => $p['category']
				);
				// save the records and update enqueue issue 
				if ($download->save()) {
					if ($p['category'] == "text") {
						sql('update {{enqueue_issue}} set status="done" where start_date > (select (unix_timestamp(now()) - 28800) as time) order by ID limit 1')->query();
					}
					$this->redirect("/admin/file/upload?msg=121");
				} else {
					dump($download->getErrors());
				}
			// file already exists, replace it
			} else {
				Download::model()->updateByPk($download->ID, array(
					"appoint" => $appoint,
				));
				$this->redirect("/admin/file/upload?msg=122");
			}
		} else {
			throwException(500, "文件 ".$file_path." 移动至 ".$new_loc." 错误。");
		}
	}

	public function actionList() {

		$files = Download::model()
				->findAll(array('order'=>'ID desc', 'limit' => 20));
		
		$this->pageTitle.= "文件列表";
		$this->render("/admin/file/list", array("files" => $files));
	}

	public function actionLog( $id = 0 ) {

		$rawData = DownloadLog::model()->with("user")
				->findAllByAttributes( array("download_id" => (int)$id) );

		$dataProvider = new CArrayDataProvider($rawData, array(
			'id' => 'download_log',
			'sort' => array(
				'attributes' => array('id', 'user_id', 'created'),
			),
			'pagination' => array(
				'pageSize' => 20
			),
		));

		$this->pageTitle.= "文件下载记录";
		$this->render("/admin/file/log", array(
			"dataProvider" => $dataProvider,
		));

	}
}
?>