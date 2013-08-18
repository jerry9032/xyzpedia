<?
/**
* 
*/
class CategoryController extends Controller
{
	
	function init() {
		if ( isGuest() )
			$this->redirect("/login");
		
		$this->layout = "/layouts/admin";
		$this->pageTitle = Yii::t("general", "XYZPedia"). " - ";
	}

	public function actionIndex() {
		$this->forward("/admin/category/list");
	}

	public function actionList() {
		// check cap
		if ( !can("post", "list") ) {
			throwException(404, Yii::t("admin", "No access."));
		}
		$this->pageTitle.= Yii::t("admin", "Category");
		
		$categories = Category::model()->findAll();
		$this->render("/admin/category/list", array(
			"action" => "category",
			"categories" => $categories,
		));
	}
}
?>