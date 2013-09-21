<?
/**
* 
*/
class PublishController extends Controller
{
	
	function init() {
		if ( isGuest() )
			$this->redirect("/login");
		
		$this->layout = "/layouts/admin";
		$this->pageTitle = Yii::t("general", "XYZPedia"). " - ";
	}

	public function actionIndex() {
		$this->redirect("/admin/publish/decide");
	}


	public function actionDecide() {
		$this->pageTitle.= "定稿";

		$criteria = new CDbCriteria;
		$criteria->condition = 'status="determined"';
		$contribs_raw = Contrib::model()->findAll($criteria);
		$contribs = array();

		// 将数据库中得到的行，转存到新的简单数组中
		Yii::import('ext.facade.ContribFacade');
		foreach($contribs_raw as $contrib) {
			$contribs[] = ContribFacade::convert($contrib);
		}

		// 排序优先级：①用户id，②预约时间，③投稿时间
		usort($contribs, 'cmp_by_id');
		Yii::import('ext.WordsCount');
		Yii::import('ext.XYZDate');
		Yii::import('ext.__');

		// 数一下每个用户有多少终审通过的稿件
		$nums_determined = array();
		foreach ($contribs as $contrib) {
			$nums_determined[] = $contrib->author->id;
		}
		$nums_determined = array_count_values($nums_determined);

		// 对于这些用户，数一下他们之前投稿多少
		$nums_contrib = $nums_determined;
		foreach ($nums_determined as $k => $v) {
			$contrib_num = sql("select count(id) from {{contrib}} where author_id=".$k." and status!='sent'")->queryScalar();
			$post_num = sql("select count(id) from {{post2author}} where author_id=".$k)->queryScalar();
			$nums_contrib[$k] = $contrib_num + $post_num;
		}

		// 过滤相同作者，只留下优先级最高的那一篇
		$_ = new __();
		$contribs = $_->filter($contribs, 'filter');
		usort($contribs, 'cmp_by_time'); 
		// 同作者只有一篇能上色
		$red = array();
		$orange = array();
		$yellow = array();

		// 上色
		foreach ($contribs as $contrib) {

			$u = $contrib->author->id;
			if (in_array($u, $red) || in_array($u, $orange)) {
				continue;
			}

			$d = new XYZDate(gmtTime(), $contrib->created, $contrib->appoint);

			if ( $d->gt_one_month() ) {
				$yellow[] = $u;
				$contrib->color = "yellow";
				$contrib->reason = "稿库里堆了超过一个月哦~";
			}
			if ( $nums_determined[$u] > 3 ) {
				$yellow[] = $u;
				$contrib->color = "yellow";
				$contrib->reason = "该用户终审通过的超过三篇。";
			}
			if ( $nums_contrib[$u] <= 3 ) {
				$yellow[] = $u;
				$contrib->color = "yellow";
				$contrib->reason = "投稿数<=3，鼓励新人。";
			}
			if ( $d->gt_one_month() && $nums_determined[$u] > 3) {
				$orange[] = $u;
				$contrib->color = "orange";
				$contrib->reason = "该用户投稿数超过三篇，这一篇又堆了超过一个月哦~";
			}

			if ( $nums_contrib[$u] <= 1 ) {
				$orange[] = $u;
				$contrib->color = "orange";
				$contrib->reason = "第一次投稿，鼓励新人。";
			}
			if ( $d->gt_two_month() ) {
				$orange[] = $u;
				$contrib->color = "orange";
				$contrib->reason = "稿库里堆了超过两个月哦~";
			}
			if ( $d->appoint_last_week() ) {
				$red[] = $u;
				$contrib->color = "red";
				$contrib->reason = "预约在上周发送。";
			}
			if ( $d->appoint_this_week() ) {
				$red[] = $u;
				$contrib->color = "red";
				$contrib->reason = "预约在本周发送。";
			}
			if ( $d->appoint_later() ) {
				$contrib->color = "black";
				$contrib->reason = "预约在以后发送，本周千万别发哟~";
			}
		}

		// 存在需要撤销的内容
		$queued = Contrib::model()->findAll('status="queued"');

		// 本周的定稿人
		$criteria->condition = 'status="not yet"';
		$criteria->order = 'ID';
		$enqueue_user = EnqueueIssue::model()->find($criteria);

		$this->render("/admin/publish/publish", array(
			"action" => "enqueue",
			"contribs" => $contribs,
			"nums" => $nums_determined,
			"undo"	=> !empty($queued),
			"enqueue_user" => $enqueue_user,
		));
	}

	public function actionLoadMore() {

		$uid = (int)$_POST["uid"];
		$contrib_id = (int)$_POST["except"];
		$this->layout = false;

		$criteria = new CDbCriteria;
		$criteria->addCondition('author_id='.$uid);
		$criteria->addCondition('id!='.$contrib_id);
		$criteria->addCondition('status="determined"');
		
		$contribs_raw = Contrib::model()->findAll($criteria);
		$contribs = array();
		// 将数据库中得到的行，转存到新的简单数组中
		Yii::import('ext.facade.ContribFacade');
		foreach($contribs_raw as $contrib) {
			$contribs[] = ContribFacade::convert($contrib);
		}

		foreach ($contribs as $contrib) {
			$this->render("/admin/publish/publish-template", array(
				"contrib" => $contrib,
				"more" => true
			));
		}
	}

	public function actionPublish() {
		$this->redirect("/admin/publish/decide");
	}

	public function actionGenerate() {
		$data = $_POST["data"];

		$filename = $_POST['from'].'-'.$_POST['to'].'.txt';

		header("Content-type: text/plain");
		header("Accept-Ranges: bytes");
		header("Content-Disposition: attachment; filename=$filename");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header("Pragma: no-cache" );
		header("Expires: 0" ); 

		echo "本期定稿：".user()->nick_name."\r\n";
		echo "本期审稿：\r\n\r\n";
		echo $data;

		sql("update {{contrib}} set `status`='queued' where ID in ".$_POST["id_list"])->query();
	}


	public function actionRevert() {
		sql("update {{contrib}} set `status`='determined' where `status`='queued'")->query();
		$this->redirect("/admin/publish/publish?msg=112");
	}

	public function actionConfirm()
	{
		sql("update {{contrib}} set `status`='sent' where `status`='queued'")->query();
		$this->redirect("/admin/publish/publish?msg=113");
	}


	public function actionAlter() {
		if ( empty($_POST)) {
			throwException(404, "没有POST数据。");
		}
		$name = $_POST['arrange_name'];
		$id = $_POST['arrange_id'];

		$user = User::model()->find("nick_name='".$name."'");
		if ( empty($user)) {
			die(CJSON::encode(array(
				"error" => 1,
				"msg" => "不存在该用户名。",
			)));
		}
		EnqueueIssue::model()->updateByPk($id, array(
			"user_id" => $user->id,
		));
		die(CJSON::encode(array(
			"error" => 0,
			"msg" => '<a href="/author/'.$user->login.'">'.$user->nick_name.'</a>',
		)));
	}


	public function actionAppoint()
	{
		$this->pageTitle.= "定稿安排";
		$criteria = new CDbCriteria();
		$criteria->condition = 'id > 45';
		$criteria->order = 'id';
		$arranges = EnqueueIssue::model()->findAll($criteria);
		$this->render("/admin/publish/appoint", array(
			"action" => "entrust",
			"arranges" => $arranges,
			"user_list" => $this->userList(),
		));
	}

	public function actionAddHits() {
		$id = $_GET['id'];
		$sql = 'update {{enqueue_issue}} set hits=hits+1 where id='.$id;
		sql($sql)->query();
		echo $sql;
	}


	public function actionAddAppoint()
	{
		if ( empty($_POST) ) {
			throwException(404, "没有POST数据。");
		}
		$p = (object)$_POST;

		$start_date = gmtTime($p->start_date."-00-00");
		$last_date = EnqueueIssue::model()->find(array("order" => "ID desc"))->start_date;

		if ( $start_date != $last_date + 604800 ) {
			$data["error"] = 1;
			$data["msg"] = "错误：日期设置不正确，请重新选择，应为 "
				.timeFormat($last_date + 604800, "date")."。";
			die(CJSON::encode($data));
		}

		$user = User::model()->findByAttributes(array("nick_name" => $p->user_name));
		if ( empty($user) ) {
			$data["error"] = 1;
			$data["msg"] = "错误：不存在名为“".$p->user_name."”的用户。";
			die(CJSON::encode($data));
		}

		$enqueue_issue = new EnqueueIssue;
		$enqueue_issue->attributes = array(
			"start_date" => $start_date,
			"user_id" => $user->id,
			"status" => "not yet",
		);
		if ( $enqueue_issue->save() ) {
			$this->layout = false;
			$this->render("/admin/publish/appoint-template", array(
				"now" => gmtTime(),
				"arrange" => $enqueue_issue
			));
		} else {
			dump($enqueue_issue->getErrors());
		}
	}


	public function userList() {

		$users = User::getUserList();

		$str = '[';
		$first_user = true;
		foreach ($users as $user) {
			if ( !$first_user ) {
				$str.= ',';
			}
			$str.= '"'.$user->nick_name.'"';
			$first_user = false;
		}
		$str.= ']';
		return $str;
	}
}

function cmp_by_id($a, $b) {
	if ($a->author->id < $b->author->id) {
		return -1;
	} else if ($a->author->id > $b->author->id) {
		return 1;
	} else {
		if ( $a->appoint > $b->appoint ) {
			return -1;
		} else if ( $a->appoint < $b->appoint ) {
			return 1;
		} else {
			if ( $a->created < $b->created ) {
				return -1;
			} else {
				return 1;
			}
		}
	}
}

function cmp_by_time($a, $b) {

	$d1 = new XYZDate(gmtTime(), $a->created, $a->appoint);
	$d2 = new XYZDate(gmtTime(), $b->created, $b->appoint);

	if ( $d1->appoint_this_week() || $d2->appoint_later() ) return -1;
	if ( $d2->appoint_this_week() || $d2->appoint_later() ) return 1;

	if ($a->appoint > $b->appoint) {
		return -1;
	} else if ($a->appoint < $b->appoint) {
		return 1;
	} else {
		if ($a->created < $b->created) {
			return -1;
		} else {
			return 1;
		}
	}
}

function filter($contrib) {
	static $uids = array();
	if ( !in_array($contrib->author->id, $uids) ) {
		$first = true;
		$uids[] = $contrib->author->id;
	} else {
		$first = false;
	}
	return $first;
}
?>
