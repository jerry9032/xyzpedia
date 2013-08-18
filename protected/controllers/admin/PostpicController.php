<?
/**
* 
*/
class PostPicController extends Controller {

	function init() {
		if ( isGuest() )
			$this->redirect("/login");
		
		$this->layout = "/layouts/admin";
		$this->pageTitle = Yii::t("general", "XYZPedia"). " - ";
	}


	public function actionUpload($id = 0) {
		// if id == 0, new post
		// else, modify post

		$this->layout = false;

		if ( !isset($_FILES['file']) ) {
	
			$this->render("/admin/pic/upload", array(
				"id" => $id,
			));

		} else {
	
			$url = "/images/".timeFormat(time(), "path")."/";
			
			$dir = Yii::getPathOfAlias('webroot').$url;
			$file = $_FILES["file"];


			if ( ($file["type"] != "image/gif") && ($file["type"] != "image/jpeg") && ($file["type"] != "image/pjpeg") ) {
				$data = array(
					"error" => 1,
					"message" => Yii::t("admin", "Wrong file type, only accept GIF/JPG."),
					"link" => 1,
				);
				$this->render("/admin/pic/crop", array(
					"id" => $id,
					"data" => $data,
				));
				return ;
			}

			if ($file["size"] > 200 * 1024) {
				$data = array(
					"error" => 1,
					"message" => Yii::t("admin", "File size limit in 200KB."),
					"link" => 1,
				);
				$this->render("/admin/pic/crop", array(
					"id" => $id,
					"data" => $data,
				));
				return ;
			}

			if ($file["error"] > 0) {
				$data = array(
					"error" => $file["error"],
					"message" => Yii::t("admin", "File upload error."),
					"link" => 1,
				);
				$this->render("/admin/pic/crop", array(
					"id" => $id,
					"data" => $data,
				));
				return ;
			}
			//if (file_exists($dir.$_FILES["file"]["name"])) {
			//	$msg = $_FILES["file"]["name"] . " already exists. ";
			//} else {

			$new_file_name = timeFormat(time(), 'condensed').".jpg";
			move_uploaded_file( $_FILES["file"]["tmp_name"], $dir.$new_file_name);

			//$msg = "Stored in: " . $dir . $_FILES["file"]["name"];
			//}

			$this->render("/admin/pic/crop", array(
				"id" => $id,
				"data" => array("error" => 0),
				"url" => $url.$new_file_name, //$_FILES["file"]["name"]
			));
		}
	}

	public function actionResize() {
		//dump($_GET); die();
		$scale = $_GET['scale'];
		$x = $_GET['x'] * $scale;
		$y = $_GET['y'] * $scale;
		$w = $_GET['w'] * $scale;
		$h = $_GET['h'] * $scale;
		$id = $_GET['id'];

		// src = "/images/2012/10/20121018191232.jpg";
		$src = $_GET['src'];
		// dir = "C:/fakepath/images/2012/10/20121018191232.jpg";
		$dir = Yii::getPathOfAlias('webroot') . $src;

		// create a empty picture
		// header("Content-type: image/jpeg");
		$target = @imagecreatetruecolor($w, $h)
  			or die("Cannot Initialize new GD image stream");
  		// create a image object
  		$source = imagecreatefromjpeg($dir);

  		// fill in
  		imagecopy( $target, $source, 0, 0, $x, $y, $w, $h);

		// save the picture
		$namePos = strripos($dir, ".");
		// name = "C:/fakepath/images/2012/10/20121018191232";
		$name = substr($dir, 0, $namePos);
		// cropName = "C:/fakepath/images/2012/10/20121018191232-crop.jpg";
		$cropName = $name."-crop.jpg";
		
		$returnNamePos = strripos($cropName, "/");
		$returnName = substr($cropName, $returnNamePos - 7);

		// create file
		imagejpeg($target, $cropName, 100);
		// will delete the original picture
		unlink($dir);
		// mkdir($newPath);
		Header("location: /admin/postpic/uploadsuccess/{$id}?name=".$returnName);

		//echo "resize success!";
	}

	public function actionUploadSuccess($id) {
		$this->layout = false;
		$this->render("/admin/pic/uploadsuccess", array(
			"id" => $id,
			"name" => $_GET["name"],
		));
	}
}

?>