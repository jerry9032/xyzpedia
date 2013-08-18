<?
/**
* 
*/
class PostController extends Controller {

	function init() {
		if ( isGuest() )
			$this->redirect("/login");
		
		$this->layout = "/layouts/admin";
		$this->pageTitle = Yii::t("general", "XYZPedia"). " - ";
	}

	public function actionIndex() {
		$this->redirect("/admin/post/list");
	}


	// render a new post page
	public function actionNew() {
		$this->pageTitle.= "发布新文章";
		if ( !can("post", "create") ) {
			throwException(404, Yii::t("admin", "您没有创建文章所需要的权限。"));
		}
		$this->render('edit', array(
			"action" => "postnew",
			"user_list" => User::getUserListString(),
			"cats" => Category::getCatList(),
		));
	}

	// handle new post request
	public function actionInsert() {
		if ( !can("post", "create") ) {
			throwException(404, Yii::t("admin", "No access."));
		}
		$p = $_POST;

		$post = new Post;

		$post->attributes = array(
			"title" => $p["post-title"],
			"submit_id" => user()->id,
			"serial" => trim($p["post-serial"]),
			"slug" => trim($p["post-slug"]),
			"created" => gmtTime($p['post-date']."-".$p['post-hour']."-".$p['post-minute']),
			"changed" => 0,
			"content" => $p["post-content"],
			"excerpt" => $p["post-excerpt"],
			"category_id" => empty($p["post-category"])? 6: $p["post-category"],
			"thumbnail" => $p["post-thumbnail"],
			"status" => $p["post-status"]
		);

		if($post->save()) {

			$id = $post->id;

			$authors = explode("&", $p["post-author"]);
			foreach ($authors as $author) {
				$author_id = sql('select id from {{user}} where nick_name="'.$author.'"')
				->queryScalar();

				// insert new
				$p2a = new Post2Author;
				if ( $author_id != false ) {
					$p2a->attributes = array(
						"post_id" => $id,
						"author_id" => $author_id,
						"author_name" => "",
					);
				} else {
					$p2a->attributes = array(
						"post_id" => $id,
						"author_id" => 0,
						"author_name" => $author,
					);
				}
				if ( !$p2a->save() ) {
					dump($p2a->getErrors());
					die();
				}
			}
			$this->redirect('/admin/post/edit/'.$post->id.'?msg=101');
		} else {
			dump($post->getErrors());die();
		}
	}


	// render a post list page
	public function actionList() {
		$this->pageTitle.= Yii::t("admin", "Post List");
		// check capability
		if ( !can("post", "list") ) {
			throwException(404, Yii::t("admin", "您没有列出文章所需要的权限。"));
		}
		$rawData = Post::model()->with("submit", "category")
				->findAllByAttributes( array("type" => "post") );
		$dataProvider = new CArrayDataProvider($rawData, array(
			'id' => 'post-list',
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
			"action" => "postlist",
			"dataProvider" => $dataProvider,
			"user_list" => User::getUserListString(),
		));
	}


	// change the submit user from $_POST
	public function actionAlterSubmit() {
		if ( empty($_POST) ) {
			die(CJSON::encode(array(
				"error" => 1,
				"msg" => "没有POST数据。",
			)));
		}
		$p = (object)$_POST;
		$user = User::model()->findByAttributes( array("nick_name" => $p->submit_name) );
		if ( empty($user) ) {
			die(CJSON::encode($data = array(
				"error" => 1,
				"msg" => "不存在该用户名。",
			)));
		}
		Post::model()->updateByPk($p->post_id, array(
			"submit_id" => $user->id,
		));
		die(CJSON::encode(array(
			"error" => 0,
			"msg" => '<a href="/author/'.$user->login.'">'.$user->nick_name.'</a>',
		)));
	}



	public function actionEdit( $id = 0 ) {
		$this->pageTitle.= "Edit Post";

		// check existence
		$post = Post::model()->findByPk($id);
		if ( empty($post) ) {
			throwException(404, Yii::t('admin', 'The specified post cannot be found.'));
		}
		// check cap
		if ( !isGuest() && ((user()->id == $post->submit_id) || can("post", "updateall")) ) {
		} else {
			throwException(404, Yii::t("admin", "您没有修改文章所需要的权限。"));
		}

		$this->render("edit", array(
			"action" => "postlist",
			"post" => $post,
			"user_list" => User::getUserListString(),
			"cats" => Category::getCatList(),
		));
	}


	public function actionValidateSlug()
	{
		while(1) {
		if ( empty($_POST["slug"]) ) {
			$data = array(
				"error" => 1,
				"msg" => "POST 数据为空。",
			);
			break;
		}
		// format with dash
		Yii::import("ext.Formmating");
		$id = $_POST["post_id"];
		$slug = Formmating::sanitize_title_with_dashes( $_POST["slug"] );
		// underline score
		$slug = str_replace("_", "-", $slug);
		// dupilicate dashes
		$slug = preg_replace('/[\-]+/', '-', $slug);
		if ( empty($slug) ) {
			$data = array(
				"error" => 1,
				"msg" => "链接不能为空。",
			);
			break;
		}
		// check existence.
		$post = Post::model()->findByAttributes(array("slug" => $slug));
		if ( !empty($post) && ($post->id != $id)) {
			$data = array(
				"error" => 1,
				"msg" => "<a href='/".timeFormat($post->created, "path")
					."/".$post->slug."' target='_blank'>这篇</a> 已经用了。",
			);
			break;
		}
		$data = array(
			"error" => 0,
			"slug" => $slug,
			"msg" => "请提交后更新。",
		); break;
		}
		die(CJSON::encode($data));
	}


	public function actionUpdate( $id = 0 ) {
		if ( $id <= 0 ) {
			throwException(404, Yii::t('The specified post cannot be found.'));
		}
		$p = $_POST;

		$authors = explode("&", $p["post-author"]);

		// delete old data
		sql('delete from {{post2author}} where post_id='.$id)->query();

		foreach ($authors as $author) {
			$author_id = sql('select id from {{user}} where nick_name="'.$author.'"')->queryScalar();

			// insert new
			$p2a = new Post2Author;
			if ( $author_id != false ) {
				$p2a->attributes = array(
					"post_id" => $id,
					"author_id" => $author_id,
					"author_name" => "",
				);
			} else {
				$p2a->attributes = array(
					"post_id" => $id,
					"author_id" => 0,
					"author_name" => $author,
				);
			}
			if ( !$p2a->save() ) {
				dump($p2a->getErrors());
				die();
			}
		}

		Post::model()->updateByPk($id, array(
			"title" => $p["post-title"],
			"slug" => trim($p["post-slug"]),
			"serial" => trim($p["post-serial"]),
			"created" => gmtTime($p['post-date']."-".$p['post-hour']."-".$p['post-minute']),
			"changed" => gmtTime(),
			"content" => $p["post-content"],
			"excerpt" => trim($p["post-excerpt"]),
			"category_id" => empty($p["post-category"])? 6: $p["post-category"],
			"thumbnail" => $p["post-thumbnail"],
			"status" => $p["post-status"]
		));

		$this->redirect('/admin/post/edit/'.$id.'?msg=102');
	}



	public function actionDelete( $id = 0 ) {
		$post = Post::model()->findByPk($id);
		// check cap
		if ( !isGuest() &&
			(((user()->id == $post->submit) && can("post", "delete"))
			|| can("post", "deleteall")) ) {
		} else {
			throwException(404, Yii::t("admin", "No access."));
		}
		// delete it
		Post::model()->deleteByPk($id);
		$this->redirect("/admin/post/list?msg=103");
	}


	// directly upload picture in server
	public function actionUpload() {
		$this->layout = false;
		$this->render('/admin/post/upload');
	}
	public function actionUpsucc() {
		$url = "/images/".timeFormat(time(), "path")."/";
		$dir = Yii::getPathOfAlias('webroot').$url;

		$file = $_FILES["file"];
		$new_file_name = timeFormat(time(), 'condensed').".jpg";
		move_uploaded_file( $file["tmp_name"], $dir.$new_file_name);

		$this->layout = false;
		$this->render('/admin/post/upsucc', array(
			'imgurl' => $url.$new_file_name
		));
	}
}
?>