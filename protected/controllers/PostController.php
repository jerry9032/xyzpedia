<?

class PostController extends Controller {

	var $_ver;
	
	public function init() {
		$this->pageTitle = Yii::t("general", "XYZPedia"). " - ";

		$this->layout = v()."/layouts/main";
	}

	public function actions() {

	}

	public function actionIndex($page = 1) {
		$this->show_post($page);
	}

	public function show_post($page = 1, $order = "time") {

		$this->pageTitle.= get_option("site_descript");

		$criteria = new CDbCriteria();
		$criteria->addCondition("t.`type`='post'");
		$criteria->addCondition("t.`status`='publish'");
		if ( isset($_GET['preview']) && ($_GET['preview'] == 'true')
			&& can('post', 'preview') ) {
		} else {
			$criteria->addCondition("t.`created`<".gmtTime());
		}
		// order
		switch ($order) {
			case "light":
				$criteria->order = 't.light desc'; break;
			default:
				$criteria->order = 't.created desc'; break;
		}
		$limit = (int)get_option("index_post_one_page");
		$criteria->limit = $limit;

		$count = Post::model()->count($criteria);

		$pager = new CPagination($count);
		$pager->pageSize = $limit;
		$pager->setCurrentPage($page - 1);
		$pager->applyLimit($criteria);

		$posts = Post::model()
				->with("submit", "author", "category")
				->findAll($criteria);

		$events = Post::model()->findAllByAttributes( array(
			"type" => "event",
			"status" => "publish",
			"show" => 4,
		) );

		$this->render( v()."/post/index", array(
			"posts"  => $posts,
			"events" => $events,
			"page" => $page,
			"limit" => $limit,
			"order" => $order,
			"count" => $count,
			"page_type" => "index",
			"widgets" => get_option("index_widget_order"),
		));
	}


	public function actionLight($page = 1) {
		$order = "light";
		$this->show_post($page, $order);
	}


	public function actionLightUp() {
		
		$pid = (int)$_POST['pid'];
		if ($pid < 0) {
			$data = array('error' => 1, 'msg' => '文章id错误！');
			die(json_encode($data));
		}

		// update database
		$ar = sql('update {{post}} set light=light+1 where id='.$pid)
			->query()->rowCount;
		if ($ar != 1) {
			$data = array('error' => 1, 'msg' => '文章不存在，可能已删除。');
			die(json_encode($data));
		}

		// verify cookie
		$cookie = Yii::app()->request->getCookies();
		$ck = $cookie['post'.$pid];
		if ( isset($ck) && ($ck->value == 'lighted')) {
			$data = array('error' => 1, 'msg' => "这篇稿子今天已经点亮过了啦~去看看其他词条？");
			die(json_encode($data));
		}

		// set cookie
		$cookie = new CHttpCookie('post'.$pid, 'lighted');
		$cookie->expire = gmtTime() + 60*60*24; // one day
		Yii::app()->request->cookies['post'.$pid] = $cookie;

		$data = array('error' => 0, 'msg' => '点亮成功。');
		die(json_encode($data));
	}


	public function actionCategory($cat_name = "", $page = 1) {

		if ( empty($cat_name) ) {
			throwException(404, "分类名为空。");
		}
		$criteria = new CDbCriteria;
		$criteria->condition = "slug='".$cat_name."'";
		$cat = Category::model()->find($criteria);

		$this->pageTitle.= $cat->name;

		$limit = (int)get_option("index_post_one_page");
		$criteria = new CDbCriteria;
		$criteria->condition = "type='post' and category_id=".$cat->ID;
		$criteria->order = "created desc";
		$criteria->limit = $limit;

		$count = Post::model()->count($criteria);

		$pager = new CPagination($count);
		$pager->pageSize = $limit;
		$pager->setCurrentPage($page - 1);
		$pager->applyLimit($criteria);

		$posts = Post::model()->findAll($criteria);


		$this->render( v()."/post/index", array(
			"posts" => $posts,
			"page" => $page,
			"limit" => $limit,
			"count" => $count,
			"page_type" => "category",
			"widgets" => get_option("index_widget_order"),
			"cat_slug" => $cat->slug,
			"prompt" => "“".$cat->name."”分类下的所有稿件。"
		));
	}


	public function actionSearch($key = "", $page = 1) {

		if ( empty($key) ) {
			throwException(404, "搜索关键字为空。");
		}

		$this->pageTitle.= "搜索含有 ".$key." 的文章";

		$criteria = new CDbCriteria;
		$limit = (int)get_option("index_post_one_page");
		$criteria->condition = "content like '%".$key."%'";
		$criteria->order = "created desc";
		$criteria->limit = $limit;

		$count = Post::model()->count($criteria);

		$pager = new CPagination($count);
		$pager->pageSize = $limit;
		$pager->setCurrentPage($page - 1);
		$pager->applyLimit($criteria);

		$posts = Post::model()->findAll($criteria);

		$this->render( v()."/post/index", array(
			"posts" => $posts,
			"page" => $page,
			"limit" => $limit,
			"count" => $count,
			"page_type" => "search",
			"widgets" => get_option("index_widget_order"),
			"search_key" => $key,
			"prompt" => "含有关键词“".$key."”的所有稿件。"
		));
	}




	public function actionArchive($year = 0, $month = 0, $page = 1) {
		
		//$year = (int)$year;
		//$month = (int)$month;
		//$page = (int)$page;

		$year_now = timeFormat(gmtTime(), "year");

		if ($year < 2008 || $year > $year_now) {
			throwException(404, "Year limit exceeded.");
		}
		if ($month < 0 || $month > 12) {
			throwException(404, "Month limit exceeded.");
		}

		$criteria = new CDbCriteria;
		$criteria->order = "created";
		$limit = 80;
		// year archive
		if ($month == 0) {
			$this->pageTitle.= $year." 年的存档";
			$criteria->limit = $limit;
			$page_type = "year";

			$start_time = gmtTime($year."-01-01-00-00");
			$end_time = gmtTime($year."-12-31-00-00");
		// month archive
		} else {
			$this->pageTitle.= $year . " 年 ". $month . " 月的存档";
			$page_type = "month";

			$start_time = gmtTime($year."-".$month."-01-00-00");
			if ($month == 12) {
				$end_time = gmtTime(($year+1)."-01-01-00-00");
			} else {
				$end_time = gmtTime($year."-".($month+1)."-01-00-00");
			}
		}
		$criteria->condition = "created between ".$start_time." and ".$end_time;
		$count = Post::model()->count($criteria);

		$pager = new CPagination($count);
		$pager->pageSize = $limit;
		$pager->setCurrentPage($page - 1);
		$pager->applyLimit($criteria);

		$posts = Post::model()->findAll($criteria);

		$this->render("/post/archive", array(
			"posts" => $posts,
			"page_type" => $page_type,
			"date" => (object)array(
				"year" => $year,
				"month" => $month,
			),
			"paging" => (object)array(
				"page" => $page,
				"count" => $count,
				"limit" => $limit,
			),
			"widgets" => get_option("index_widget_order"),
		));
	}


	public function actionDetail($year=0, $month=0, $slug="")
	{
		$criteria = new CDbCriteria();
		//$criteria->addCondition("t.`type`='post'");
		$criteria->addCondition("t.`slug`='{$slug}'");

		$post = Post::model()
			->with("submit", "author", "category", "author_info")
			->find($criteria);

		if ( empty($post) ) {
			throwException(404, Yii::t("general", "Can not find specified post."));
		}

		$this->pageTitle.= $post->title;

		$y = date("Y", $post->created);
		$m = date("m", $post->created);

		if ( ($y != $year) || ($m != $month) ) {
			throwException(404, Yii::t("general", "Can not find specified post."));
		}

		// comments
		$criteria = new CDbCriteria();
		$criteria->condition = "t.post_id=".$post->id;
		$criteria->order = "t.created";

		$this->render( v()."/post/detail", array(
			"post" => $post,
			"widgets" => get_option("post_widget_order"),
			"comments" => Comment::model()->with('parent')->findAll($criteria),
		));
	}


	public function actionAddComment() {
		if ( empty( $_POST) ) {
			return;
		}

		$post_id = (int)$_POST['post_id'];
		$parent = (int)$_POST['parent'];

		// notify all authors, 不包括自己
		$authors = Post::model()->findByPk($post_id)->author;
		$author_list = array();
		foreach ($authors as $author) {
			if ( $author->id == user()->id ) { continue; }
			$notify = new Notify;
			$notify->attributes = array(
				"author_id" => user()->id,
				"target_id" => $author->id,
				"post_id" => $post_id,
				"type" => "postreply",
				"created" => gmtTime()
			);
			if ( !$notify->save() ) {
				dump($notify->getErrors());
			}
			$author_list[] = $author->id;
		}

		// at other users
		$content = htmlspecialchars($_POST['comment']);
		// 过滤恶意艾特
		$content = preg_replace('|@([\W\w]+)\(([\d]+)\)|U', '@$1 ($2)', $content);
		$pattern = "|@([\W\w]+)[:,.\s]|U";
		preg_match_all($pattern, $content." ", $regs);
		foreach($regs[1] as $reg) {
			$user = User::model()->find('nick_name="'.$reg.'"');

			if ( !empty($user) ) {
				$reg = '@' . $reg;
				$content = str_replace($reg, $reg.'('.$user->id.')', $content);
				//$content = mb_ereg_replace($reg, $reg.'('.$user->id.')', $content);
				// 如果是艾特自己，跳过
				if ( $user->id == user()->id ) { continue; }
				if ( in_array($user->id, $author_list) ) { continue; }
				// 加上用户ID以便于显示超链接
				$notify = new Notify;
				$notify->attributes = array(
					"author_id" => user()->id,
					"target_id" => $user->id,
					"post_id" => $post_id,
					"type" => "postreply",
					"created" => gmtTime()
				);
				if ( !$notify->save() ) {
					dump($notify->getErrors());
				}
			}
		}

		$comment = new Comment;
		$comment->attributes = array(
			'post_id' => $post_id,
			'author_name' => user()->nick_name,
			'author_ip' => getIP(),
			'created' => gmtTime(),
			'content' => $content,
			'approved' => 1,
			'agent' => $_SERVER["HTTP_USER_AGENT"],
			'parent_id' => $parent, // no cascading
			'user_id' => user()->id
		);

		//dump($comment->attributes);die();

		if ( $comment->save() ) {
			sql("update {{post}} set comments=comments+1 where id=".$post_id)->query();

			$this->renderPartial("/post/comment-template", array(
				'c' => $comment,
			));
		} else {
			dump($comment->getErrors()); die();
		}
	}


	public function actionDeleteComment() {

		if ( empty($_POST['id'] )) {
			json_error('ID 不能为空。');
		}
		
		$id = (int)$_POST['id'];
		$comment = Comment::model()->findByPk($id);

		if ( empty($comment)) {
			json_error('该评论不存在。');
		}

		if ( $comment->user_id == user()->id || isAdmin()) {
			Comment::model()->deleteByPk($id);
			sql("update {{post}} set comments=comments-1 where id=".$comment->post_id)->query();
			json_success('删除成功。');
		} else {
			json_errro('您没有权限删除此评论。');
		}
	}


	public function actionLoadMoreComments() {

		$pid = $_POST['pid'];
		$start = $_POST['start'];
		$end = $_POST['end'];

		$criteria = new CDbCriteria;
		$criteria->condition = 'post_id='. $pid;
		$criteria->offset = $start;
		$criteria->limit = $end - $start + 1;
		$criteria->order = 'id';
		$comments = Comment::model()->findAll($criteria);

		$this->renderPartial('/post/load-more-template', array(
			'start' => $start,
			'end' => $end,
		));
		foreach ($comments as $comment) {
			$this->renderPartial('/post/comment-template', array(
				'c' => $comment
			));
		}
	}
}

?>
