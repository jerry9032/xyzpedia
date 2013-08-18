<?

class ContributeController extends Controller
{

	public function init()
	{
		if ( isGuest() ) {
			$this->redirect("/login");
		}
		Yii::app()->language = 'zh_cn';
		$this->pageTitle = Yii::t("general", "XYZPedia"). " - ";

		$this->layout = v()."/layouts/main";
	}


	public function actionIndex() {
		$this->redirect("/contribute/guide");
	}

	public function actionGuide() {
		$this->render("/contribute/guide", array(
			"action" => "guide",
		));
	}

	public function actionList($type = "my", $key = "") {

		$this->pageTitle.= Yii::t("contribute", "Contribution List");

		$headerScopeList = array("my", "edit", "all");
		$headerStatusList = array( "pending", "modify", "determined", "sent", "draft", "hanging");
		$numberList = array("author", "editor", "category");
		$wordsList = array("search");

		if ( in_array($type, $numberList) ) {
			$id = ( int ) $key;
		} elseif ( in_array($type, $wordsList) ) {
			$key = $key; // injection
		}
		$c = new CDbCriteria;

		switch ($type) {
			case 'my':		$c->condition = 'author_id = '.user()->id; break;
			case 'edit':	$c->condition = 'editor_id = '.user()->id; break;
			case 'all':		break;
			case 'pending':	$c->condition = 't.status = "pending" or t.status = "pass" or t.status = "neglected"'; break;
			case 'modify':	$c->condition = 't.status = "modify" or t.status = "back"'; break;
			case 'search':	$c->condition = 't.content like "%'.$key.'%"'; break;
			case 'author':	$c->condition = 'author_id = '.$id; break;
			case 'editor':	$c->condition = 'editor_id = '.$id; break;
			case 'category':$c->condition = 'category_id = '.$id; break;
			default:		$c->condition = 't.status = "'.$type.'"'; break;
		}
		// 草稿功能，草稿不显示在除了“我的稿件”之外的任何地方
		if ($type != 'my' && $type != 'draft') {
			$c->addCondition('t.status!="draft"');
		}
		//dump($c);
		$rawData = Contrib::model()
				->with("author", "editor", "category")
				->findAll( $c );

		$dataProvider = new CArrayDataProvider($rawData, array(
			'id' => 'contrib-list',
			'sort' => array(
				'attributes' => array(
					'id', 'title', 'author_id', 'created', 'status',
					'editor_id', 'category_id', 'comments', 'created',
				),
				'defaultOrder'=>'id desc',
			),
			'pagination' => array(
				'pageSize' => 20,
			),
		));

		$this->render( v()."/contribute/list", array(
			"action"		=> "list",
			"type"			=> $type,
			"scopeList"		=> $headerScopeList,
			"statusList"	=> $headerStatusList,
			"dataProvider"	=> $dataProvider
		));
	}


	public function actionSubmit($id = 0) {

		$this->pageTitle.= Yii::t("contribute", "Contribute to XYZPedia");

		// edit page
		$contrib = Contrib::model()->findByPk($id);

		if ($id > 0) {
			if ( empty($contrib) ) {
				throwException(404, "Can not find specific contribution.");
			}
			$can_edit = (user()->id == $contrib->author_id) || can("contribute", "updateall")
				|| ( can("contribute", "first") && (user()->id == $contrib->editor_id) ) ;

			$is_author = (user()->id == $contrib->author_id);
			$is_editor = (user()->id == $contrib->editor_id) && can("contribute", "first");
			$is_committee = can("contribute", "updateall");
			$is_admin = isAdmin();

			$can_edit = $is_author || $is_editor || $is_committee;

			if ( !$can_edit ) {
				throwException(500, "You have no permission to edit this contribution.");
			}
			if ( $contrib->mode == 1 && !isAdmin() && !$is_author) {
				throwException(500, "稿件处于半授权模式，编辑只能给出意见，无法修改。");
			}
		}
		$this->render( v()."/contribute/edit", array(
			"action" => "submit",
			"r" => $contrib,
			"cats" => Category::getCatList()
		));
	}


	public function actionUpdate($id = 0) {

		$p = $_POST;

		if ( isset($p['mobile']) )
			Contrib::model()->updateByPk($id, array(
				"title" => $p["contrib-title"],
				"content" => $p["contrib-content"],
				"changed" => gmtTime(),
				"category_id" => ($p["contrib-category"] > 0)? $p["contrib-category"]: 6,
			));

		else
			Contrib::model()->updateByPk($id, array(
				"title" => $p["contrib-title"],
				"excerpt" => $p["contrib-excerpt"],
				"content" => $p["contrib-content"],
				"changed" => gmtTime(),
				"organization" => ($p["represent"] == "org"? $p["organization"]: ""),
				"category_id" => ($p["contrib-category"] > 0)? $p["contrib-category"]: 6,
				"status" => $p["contrib-status"],
				"represent" => $p["represent"],
				"mode" => $p["contrib-mode"],
				"appoint" => ( empty($p["appoint"])? 0: gmtTime($p["appoint"]."-00-00") ),
			));

		$this->redirect('/contribute/id/'.$id.'/edit?msg=1');
	}


	public function actionInsert() {

		$p = $_POST;
		
		$contrib = new Contrib;
		$contrib->attributes = array(
			"title" => $p["contrib-title"],
			"excerpt" => $p["contrib-excerpt"],
			"content" => $p["contrib-content"],
			"author_id" => user()->id,
			"created" => gmtTime(),
			"changed" => 0,
			"organization" => ($p["represent"] == "org"? $p["organization"]: ""),
			"category_id" => empty($p["contrib-category"])? 6: $p["contrib-category"],
			"status" => $p["contrib-status"],
			"represent" => $p["represent"],
			"mode" => $p["contrib-mode"],
			"appoint" => ( empty($p["appoint"])? 0: gmtTime($p["appoint"]."-00-00") ),
			"from" => f(),
		);
		if ($contrib->save()) {
			$this->redirect('/contribute/id/'.$contrib->id.'/edit?msg=2');
		} else {
			dump($contrib->getErrors());die();
		}
	}


	public function actionDetail($id = 0) {
		$this->pageTitle.= Yii::t("contribute", "Contribution Detail");
		// check out one's submission
		// contribution
		$contrib = Contrib::model()
			->with('author', 'category', 'editor')
			->findByPk($id);
		if ($id <= 0 || !isset($contrib)) {
			die("Invalid ID.");
		}

		// comments
		$c = new CDbCriteria();
		$c->condition = 'contrib_id = '.$id;
		$c->offset = 0;
		$c->order = "id";
		//$c->limit = 5;
		$commentList = ContribComment::model()->findAll($c);
		$this->render( v()."/contribute/detail", array(
			"action" => "detail",
			"id" => $id,
			"r" => $contrib,
			"cs" => $commentList
		));
	}

	public function actionDelete() {
		
		$id = $_POST['id'];

		if ( !Contrib::model()->deleteByPk($id) ) {
			$data = array(
				"error" => 1,
				"msg" => "Contribution can not be deleted.",
			);
			die(CJSON::encode($data));
		}

		ContribComment::model()->deleteAll("contrib_id=".$id);

		$data = array("error" => 0);
		die(CJSON::encode($data));
	}

	public function actionEditLock() {

		$pid = $_REQUEST['pid'];
		$uid = $_REQUEST["uid"];

		Contrib::model()->updateByPk($pid, array(
			"edit_lock" => gmtTime(),
			"edit_locker" => $uid,
		));
	}


	public function actionEnqueue()
	{
		$id = (int)$_POST['id'];
		//$id = (int)$_GET['id'];

		if ($id <= 0) {
			return "[]";
		}

		Yii::import("ext.WordsCount");
		$contrib = Contrib::model()->findByPk($id);
		$s = $contrib->content;
		$s.= $contrib->author->nick_name . "/编·";
		$s.= $contrib->category->name;
		// 去掉行首空格
		$s = preg_replace('/^[ ]+(.*?)/m', '$1', $s);
		// 去掉换行符
		$s = str_replace(array("\r", "\n"), array("", ""), $s);
		// 去掉转义的反斜杠
		$s = stripslashes($s);

		$title = $contrib->title;

		$split = WordsCount::split($title, $s);

		die(CJSON::encode($split));
	}


	public function actionComment($action = "") {
		switch ( $action ) {
		case 'add':
			$p = $_POST;

			if ( empty($p) || empty($p["contrib_id"])) {
				throwException(404, "没有Post数据。");
			}
			$id = $p["contrib_id"];
			$content = htmlspecialchars($p["comment"]);
			// 过滤恶意艾特
			$content = preg_replace('|@([\W\w]+)\(([\d]+)\)|U', '@$1 ($2)', $content);

			$contrib = Contrib::model()->findByPk($id);
			// send the notify to contrib author
			if ( user()->id != $contrib->author_id ) {
				$notify = new Notify;
				$notify->attributes = array(
					"author_id" => user()->id,
					"target_id" => $contrib->author_id,
					"post_id" => $id,
					"type" => "comment",
					"created" => gmtTime(),
				);
				$notify->save();
			}

			// AT other users
			$pattern = "|@([\W\w]+)[:,.\s]|U";
			preg_match_all($pattern, $content." ", $regs);
			//dump($regs);
			foreach($regs[1] as $reg) {

				$user = User::model()->findByAttributes( array('nick_name' => $reg) );

				if ( !empty($user) ) {
					//dump($user->id);
					// $content = mb_ereg_replace($reg, $reg.'('.$user->id.')', $content);
					$reg = '@' . $reg;
					$content = str_replace($reg, $reg.'('.$user->id.')', $content);
					//dump($content);
					if (($contrib->author_id == $user->id) || ($user->id == user()->id))
						continue;
					$notify = new Notify;
					$notify->attributes = array(
						"author_id" => user()->id,
						"target_id" => $user->id,
						"post_id" => $id,
						"type" => "reply",
						"created" => gmtTime()
					);
					$notify->save();
				}
			}
			$comment = new ContribComment;
			$comment->attributes = array(
				"contrib_id" => $id,
				"author_id" => user()->id,
				"comment" => $content,
				"created" => gmtTime(),
				"from" => f(),
			);

			if ($comment->save()) {
				// update the comment count
				sql("update {{contrib}} set comments=comments+1 where id=".$id)->query();

				$this->layout = false;
				$this->render( v()."/contribute/comment-template", array(
					"c" => $comment,
					"fid" => -1,
				));
			} else {
				dump($contrib->getErrors()); die();
			}
			break;
		
		case 'delete':
			if ( empty($_POST['id'] ))
				die(json_encode( array(
					'error' => 1,
					'msg' => 'ID 不能为空。'
				)));
			$id = (int)$_POST['id'];
			$comment = ContribComment::model()->findByPk($id);

			if ( empty($comment))
				die(json_encode( array(
					'error' => 1,
					'msg' => '该评论不存在。'
				)));
			if ( $comment->author_id == user()->id || isAdmin()) {
				ContribComment::model()->deleteByPk($id);
				sql("update {{contrib}} set comments=comments-1 where id=".$comment->contrib_id)->query();
				die(json_encode( array(
					'error' => 0,
					'msg' => '删除成功。'
				)));
			} else {
				die(json_encode( array(
					'error' => 1,
					'msg' => '您没有权限删除此评论。'
				)));
			}

		case 'list':

			if ( !isAdmin() ) {
				throwException(404, "您没有权限查看审稿评论页面。");
			}
			$c = new CDbCriteria;
			$c->order = "t.created desc";
			$c->limit = isset($_GET["number"]) ? (int)$_GET['number']: 20;
			$comments = ContribComment::model()
					->with("author", "contrib")
					->findAll( $c );

			$this->pageTitle.= "审稿评论列表";
			$this->render("/contribute/commentlist", array(
				"action" => "comment",
				"comments" => $comments,
			));
			break;
		default: 
			throwException(404, "对于评论的操作非法。");
			break;
		}
	}


	public function actionLoadMoreComments()
	{
		$cid = $_POST['cid'];
		$start = $_POST['start'];
		$end = $_POST['end'];

		$criteria = new CDbCriteria;
		$criteria->condition = 'contrib_id='. $cid;
		$criteria->offset = $start;
		$criteria->limit = $end - $start + 1;
		$criteria->order = 'id';
		$comments = ContribComment::model()->findAll($criteria);

		$this->renderPartial('/contribute/load-more-template', array(
			'start' => $start,
			'end' => $end,
		));
		$fid = $start+1;
		foreach ($comments as $comment) {
			$this->renderPartial('/contribute/comment-template', array(
				'c' => $comment,
				'fid' => $fid++
			));
		}
	}

	public function actionTest() {
		$type = "my";
		$page = 2;
		$param = new StdClass;
		$param->page = $page;
		$obj = $this->buildObj($type, $param);

		$contribList = Contrib::model()->findAll($obj["criteria"]);
		$i = 0;
		foreach($contribList as $c) {
			$data[$i++] = array(
				"ID" => $c->id,
				"title" => $c->title
			);
		}
		die(CJSON::encode($data));
	}
}