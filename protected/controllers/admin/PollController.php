<?
/**
* 
*/
class PollController extends Controller
{
	
	function init() {
		$this->layout = '/layouts/admin';
		$this->pageTitle = Yii::t('general', 'XYZPedia') . ' - ';
	}

	public function actionIndex()
	{
		
	}

	public function actionNew()
	{
		$this->pageTitle.= "新建投票";
		$this->render("/admin/poll/edit");
	}

	public function actionInsertPoll()
	{
		$p = (object)$_POST;
		$poll = new PollQuestion;
		$poll->attributes = array(
			'title' => $p->title,
			'created' => gmtTime(),
			'multiple' => 0,
			'expire' => gmtTime($p->date. '-'. $p->hour. '-'. $p->minute),
			'order' => $p->order,
			'hidden_type' => $p->hidden,
			'force_login' => $p->login
		);
		if ( $poll->save() ) {
			$this->redirect('/admin/poll/edit/'.$poll->id.'?msg=152');
		} else {
			$poll->getErrors();
		}
	}

	public function actionResult( $id = 0 )
	{
		$poll = PollQuestion::model()->findByPk($id);
		if ( empty($poll) ) {
			throwException(404, "找不到该投票。");
		}
		$criteria = new CDbCriteria;
		$criteria->addCondition("question_id=".$poll->id);
		// switch ($poll->order) {
		// 	case '0': $criteria->order = "id"; break;
		// 	case '1': $criteria->order = "id desc"; break;
		// 	case '2': $criteria->order = "votes"; break;
		// 	case '3': $criteria->order = "votes desc"; break;
		// 	case '4': $criteria->order = "`order`"; break;
		// 	case '5': $criteria->order = "`order` desc"; break;
		// 	default: $criteria->order = "id"; break;
		// }
		$criteria->order = "votes";
		$answers = PollAnswer::model()->findAll($criteria);
		$this->pageTitle.= '投票结果';
		$this->render('/admin/poll/result', array(
			'poll' => $poll,
			'answers' => $answers
		));
	}

	public function actionDetail( $id = 0 )
	{
		$poll = PollQuestion::model()->findByPk($id);
		if ( empty($poll) ) {
			throwException(404, "找不到该投票。");
		}

		$criteria = new CDbCriteria;
		$criteria->addCondition('question_id='.$id);

		$rawData = PollLog::model()->findAll($criteria);
		$dataProvider = new CArrayDataProvider($rawData, array(
			'id' => 'poll-detail',
			'sort' => array(
				'attributes' => array(
					'id', 'user_id', 'answer_id', 'created',
					'answer.votes'
				),
				'defaultOrder'=>'id',
			),
			'pagination' => array(
				'pageSize' => 20,
			),
		));
		$this->pageTitle.= '投票详情';
		$this->render("/admin/poll/detail", array(
			"poll" => $poll,
			"dataProvider" => $dataProvider,
		));
	}

	public function actionList()
	{
		$rawData = PollQuestion::model()->findAll();
		$dataProvider = new CArrayDataProvider($rawData, array(
			'id' => 'poll-list',
			'sort' => array(
				'attributes' => array(
					'id', 'created', 'votes', 'voters',
					'created', 'expire'
				),
				'defaultOrder'=>'id',
			),
			'pagination' => array(
				'pageSize' => 20,
			),
		));
		$this->pageTitle.= '投票列表';
		$this->render("/admin/poll/list", array(
			"dataProvider" => $dataProvider,
		));
	}

	public function actionEdit( $id = 0 )
	{
		$poll = PollQuestion::model()->findByPk($id);
		if ( $id == 0 || empty($poll)) {
			throwException(404, "找不到该投票。");
		}
		$this->pageTitle.= '编辑投票';
		$this->render('/admin/poll/edit', array(
			"poll" => $poll
		));
	}

	public function actionAddAnswer() {
		$p = (object)$_POST;
		$answer = new PollAnswer;
		$answer->attributes = array(
			'question_id' => $p->qid,
			'answer' => $p->answer,
			'link' => $p->link,
			'order' => (int)$p->order,
			'votes' => 0,
		);
		if ($answer->save()) {
			$this->layout = false;
			$this->render('/admin/poll/answer-template', array(
				'poll' => $answer->poll,
				'answer' => $answer
			));
		} else {
			$answer->getErrors();
		}
	}

	public function actionUpdateAnswer()
	{
		$p = (object)$_POST;
		PollAnswer::model()->updateByPk($p->aid, array(
			'link' => $p->link,
			'order' => $p->order,
			'answer' => $p->answer
		));
		die(json_encode(array(
			'error' => 0
		)));
	}


	public function actionDeleteAnswer()
	{
		$aid = $_POST['aid'];

		// 先得到选项本身，以便于之后使用
		$answer = PollAnswer::model()->with('poll')->findByPk($aid);
		// 如果该选项已经被投过票，那么不让删除
		if ( $answer->votes > 0 )
			die(json_encode(array('error'=> 1, 'msg'=>'该选项已获得投票，不支持删除。')));

		// 删除这个选项，返回删除的行数
		$success = PollAnswer::model()->deleteByPk($aid);
		// 删除这个选项相关的投票记录
		// PollLog::model()->deleteAllByAttributes( array('answer_id' => $aid) );
		// 在主投票中减去相应的票数
		sql('update {{poll_question}} set votes=votes-'.$answer->votes.
			' where id='.$answer->poll->id)->query();

		if ( $success ) {
			$data = array('error' => 0);
		} else {
			$data = array('error' => 1, 'msg' => '删除失败。');
		}
		die(json_encode($data));
	}

	public function actionUpdatePoll()
	{
		$p = (object)$_POST;
		PollQuestion::model()->updateByPk($p->qid, array(
			'title' => $p->title,
			'multiple' => $p->multiple,
			'expire' => gmtTime($p->date . '-' . $p->hour . '-'. $p->minute),
			'hidden_type' => $p->hidden,
			'force_login' => $p->login,
			'order' => $p->order,
			'succ_prompt' => $p->prompt
		));
		$this->redirect('/admin/poll/edit/'.$p->qid.'?msg=151');
	}


	public function actionSearch()
	{
		$key = $_POST['key'];

		if ( empty($key) ) {
			die(json_encode(array(
				"error" => 1,
				"msg" => "关键字不能为空。"
			)));
		}
		// search user
		$criteria = new CDbCriteria;
		$criteria->condition = 'login like "%'.$key.'%" or nick_name like "%'.$key.'%"';
		$users = User::model()->findAll($criteria);
		$userList = array();
		foreach ($users as $user) {
			$userList[] = array(
				"answer" => "@". $user->nick_name,
				"answer_link" => "/author/". $user->login
			);
		}

		// search post
		$criteria->condition = 'title like "%'.$key.'%" or serial like "%'.$key.'%"';
		$posts = Post::model()->findAll($criteria);
		$postList = array();
		foreach ($posts as $post) {
			$authors = array();
			foreach ( $post->author as $author ) {
				$authors[] = $author->nick_name;
			}
			foreach ( $post->author_no_reg as $author) {
				if ( !empty($author->author_name) )
					$authors[] = $author->author_name;
			}
			$postList[] = array(
				"answer" => $post->title,
				"author" => implode(" & ", $authors),
				"serial" => $post->serial,
				"answer_link" => "/".timeFormat($post->created, "path"). "/". $post->slug,
			);
		}

		die(json_encode(array(
			"error" => 0,
			"users" => $userList,
			"posts" => $postList
		)));
	}
}
?>