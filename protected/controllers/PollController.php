<?
/**
* 
*/
class PollController extends Controller
{
	
	function init()
	{
		
	}

	public function actionIndex()
	{
		
	}


	public function actionDisplay( $id = 0 )
	{
		$poll = PollQuestion::model()->findByPk($id);
		if ( !empty($poll)) {
			$this->layout = false;
			$this->render("/page/poll", array(
				'poll' => $poll
			));
		}
	}

	public function actionSubmit()
	{
		$p = (object)$_POST;

		// voted
		$poll = PollQuestion::model()->findByPk($p->qid);
		if ( $poll->force_login ) {
			$voted = PollLog::model()->exists("user_id=".user()->id. " and question_id=".$p->qid);
		} else {
			$voted = PollLog::model()->exists("ip='" .getIP(). "' and question_id=".$p->qid);
		}
		
		if ($voted) {
			die(json_encode(array(
				"error" => 1,
				"msg" => "您已经投过票了。"
			)));
		}

		// save vote result
		// TODO: limit the number of votes
		$answers = explode(',', $p->answer);
		sql('update {{poll_question}} set votes=votes+'.count($answers).', voters=voters+1 where id='.$p->qid)->query();
		sql('update {{poll_answer}} set votes=votes+1 where id in ('.$p->answer.')')->query();
		foreach ($answers as $answer) {
			$log = new PollLog;
			$log->attributes = array(
				"user_id" => isGuest() ? 0: user()->id,
				'ip' => getIP(),
				"question_id" => $p->qid,
				"answer_id" => $answer,
				"created" => gmtTime(),
				"anonymous" => ($p->anonymous == "true") ? 1 : 0
			);
			$log->save();
		}
		die(json_encode(array("error" => 0)));
	}
}
?>