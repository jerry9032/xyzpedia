<?

/**
* 
*/
class PageController extends Controller {

	function init() {
		$this->pageTitle = Yii::t("general", "XYZPedia") . ' - ';
		$this->layout = "/layouts/admin";
	}

	public function actionNew() {
		$this->pageTitle.= "新建通告";
		$this->render("/admin/page/edit");
	}

	public function actionInsert()
	{
		$p = $_POST;
		$page = new Post;
		$page->attributes = array(
			'submit_id' => user()->id,
			'title' => $p['page-title'],
			'slug' => $p['page-slug'],
			'content' => $p['page-content'],
			'category_id' => 6,
			'excerpt' => $p['page-excerpt'],
			'created' => gmtTime($p['page-date'] .'-' .$p['page-hour'].'-'.$p['page-minute']),
			'type' => 'event',
			'show' => $p['page-show'],
			'status' => $p['page-status'],
			'thumbnail' => $p['page-thumbnail'],
		);
		if ( $page->save()) {
			$this->redirect('/admin/page/edit/'.$page->id.'?msg=101');
		} else {
			dump($page->getErrors());
		}
		
	}

	public function actionList() {
		$this->pageTitle.= Yii::t("admin", "Page List");
		// check capability
		if ( !isAdmin() ) {
			throwException(404, Yii::t("admin", "您没有列出页面所需要的权限。"));
		}
		$rawData = Post::model()->findAllByAttributes( array("type" => "event") );
		$dataProvider = new CArrayDataProvider($rawData, array(
			'id' => 'event-list',
			'sort' => array(
				'attributes' => array(
					'id', 'title', 'submit_id',
					'category_id', 'comments', 'created',
				),
				'defaultOrder'=>'id desc',
			),
			'pagination' => array(
				'pageSize' => 20,
			),
		));
		$this->render("list", array(
			"user_list" => User::getUserListString(),
			"dataProvider" => $dataProvider,
		));
	}

	public function actionEdit( $id = 0 ) {
		$this->pageTitle.= "Edit Page";

		// check existence
		$page = Post::model()->findByPk($id);
		if ( empty($page) ) {
			throwException(404, Yii::t('admin', 'The specified page cannot be found.'));
		}
		// check cap
		if ( !isAdmin() ) {
			throwException(404, Yii::t("admin", "您没有修改通告所需要的权限。"));
		}

		$this->render("edit", array(
			"page" => $page,
		));
	}

	public function actionUpdate( $id = 0 ) {
		if ( $id <= 0 ) {
			throwException(404, Yii::t('The specified page cannot be found.'));
		}
		$p = $_POST;

		Post::model()->updateByPk($id, array(
			"title" => $p["page-title"],
			"slug" => trim($p["page-slug"]),
			"created" => gmtTime($p['page-date'].'-'.$p['page-hour'].'-'.$p['page-minute']),
			"changed" => gmtTime(),
			"content" => $p["page-content"],
			"excerpt" => trim($p["page-excerpt"]),
			"status" => $p["page-status"],
			'show' => $p['page-show'],
			"thumbnail" => $p["page-thumbnail"],
		));

		$this->redirect('/admin/page/edit/'.$id.'?msg=102');
	}
}

?>