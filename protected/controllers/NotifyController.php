<?

class NotifyController extends Controller {
	
	function init() {
		
	}

	public function actionIndex()
	{
		//$this->render("/user/notify");
	}

	public function actionContrib($type = 'edit') {
		$criteria = new CDbCriteria();
		switch ($type) {
			case 'edit':
				$criteria->condition = 't.status = "pending" and editor_id='.user()->id;
				break;
			case 'modify':
				$criteria->condition = 't.status = "modify" and author_id='.user()->id;
				break;
			case 'assign':
				$criteria->condition = 't.status = "neglected"';
				break;
			case 'final':
				$criteria->condition = 't.status = "pass"';
				break;
			default:
				die();
				break;
		}
		
		$contribObjs = Contrib::model()->
					with('author')->findAll($criteria);

		$contribs = array();

		foreach ($contribObjs as $contribObj) {
			$contribs[] = array(
				"id" => $contribObj->id,
				"href" => "/contribute/id/" . $contribObj->id,
				"author" => $contribObj->author->nick_name,
				"title" => $contribObj->title,
				"date" => timeFormat($contribObj->created, 'full'),
			);
		}

		die(CJSON::encode($contribs));
	}


	public function actionComment($type = 'comment') {
		$criteria = new CDbCriteria();
		switch ($type) {
			case 'comment':
				$criteria->condition = 'type = "comment" and target_id='.user()->id;
				break;
			case 'at':
				$criteria->condition = 'type = "reply" and target_id='.user()->id;
				break;
			default:
				die();
				break;
		}
		
		$contribObjs = Notify::model()->
					with('author', 'contrib')->findAll($criteria);

		$contribs = array();

		foreach ($contribObjs as $contribObj) {
			if ( isset($contribObj->contrib) ) {
				$contribs[] = array(
					"id" => $contribObj->contrib->id,
					"href" => "/contribute/id/" . $contribObj->contrib->id,
					"author" => $contribObj->author->nick_name,
					"title" => $contribObj->contrib->title,
					"date" => timeFormat($contribObj->created, 'full'),
				);
			} else {
				Notify::model()->deleteByPk($contribObj->ID);
			}
		}

		die(CJSON::encode($contribs));
	}


	public function actionPostReply($type="postreply") {
		$criteria = new CDbCriteria();
		switch ($type) {
			case 'postreply':
				$criteria->condition = 't.type = "postreply" and t.target_id='.user()->id;
				break;
			default:
				die();
				break;
		}
		
		$postObjs = Notify::model()->
					with('author', 'post')->findAll($criteria);

		$posts = array();

		foreach ($postObjs as $postObj) {
			$posts[] = array(
				"id" => $postObj->post->id,
				"href" => "/" . timeFormat($postObj->post->created, "path") . "/"
							. $postObj->post->slug . "#comment",
				"author" => $postObj->author->nick_name,
				"title" => $postObj->post->title,
				"date" => timeFormat($postObj->created, 'full'),
			);
		}

		die(CJSON::encode($posts));
	}

	public function actionDelete() {
		
		$contrib_id = $_POST['contrib_id'];

		Notify::model()->deleteAll("target_id='".user()->id
			."' and post_id='".$contrib_id."'");

		echo 1;
	}

	public function actionSendSMS() {

		if ( !can("contribute", "notify") ) {
			echo "您没有发送提醒的权限。";
			exit;
		}

		$mode = $_POST["mode"];
		$uid = $_POST["uid"];
		$cid = $_POST["cid"];

		if ( empty($uid) ) {
			echo "用户ID为空";
			exit;
		}

		$user = User::model()->findByPk($uid);

		if( !empty($cid) ) {
			$contrib = Contrib::model()->findByPk($cid);
		}
		switch($mode) {
			case "review":
				$msg = '小百科网站上有一篇稿子《'.$contrib->title.'》需要您审核哟~~【请访问xyzpedia.org】';
				break;
			case "modify":
				$msg = '去小百科官网看看嘛，您的稿子《'.$contrib->title.'》需要修改喔~改完记得切换回待审状态再提交喔~';
				break;
			case "enqueue":
				$msg = '这周是你定稿喔，亲！记得去催催稿库里稿子们的进度吧~';
				break;
			default:
				$msg = "欢迎多多给小宇宙百科投稿喔~";
				break;
		}

		//dump($user);die();
		if ( empty($user->mobile) ) {
			echo "该用户手机号为空。";
			exit;
		}

		$operation_array = array(
			0 => "移动",
			1 => "移动G3",
			2 => "联通GSM",
			3 => "联通沃3G",
			4 => "电信GSM",
			5 => "电信3G天翼"
		);
		// 判断是否能发飞信
		switch (substr($user->mobile, 0, 3)) {
			case 130:
			case 131:
			case 132:
			case 155:
			case 156:
				$type = 2; // 联通GSM
				break;
			case 185:
			case 186:
				$type = 3; // 联通沃3G
				break;
			case 133:
			case 153:
				$type = 4; // 电信GSM
				break;
			case 180:
			case 189:
				$type = 5; // 电信3G天翼
				break;
			default:
				$type = 0; // 移动
		}

		// 运营商不是移动
		if ($type >= 2) {
			echo "抱歉，该用户运营商为".$operation_array[$type]."，无法发送。";
			exit;
		}


		Yii::import("ext.PHPFetion");
		$fetion = new PHPFetion('18267197908', '2008xyzbk1227');
		$result = $fetion->send($user->mobile, $msg);

		// 没有加好友，可能失败
		if (empty($result)) {
			$success = 0;
		} else {
			$success = mb_strpos($result, "成功");
		}

		if ($success) {
			echo "发送成功";
		} else {
			echo "发送失败";
		}
	}


	public function actionSendMail() {

		if ( !can("contribute", "notify") ) {
			echo "您没有发送提醒的权限。";
			exit;
		}
		if ( empty($_POST)) {
			echo "没有POST数据。";
			exit;
		}

		$mode = $_POST["mode"];
		$uid = $_POST["uid"];
		$cid = $_POST["cid"];

		// $mode = $_GET["mode"];
		// $uid = $_GET["uid"];
		// $cid = $_GET["cid"];

		$user = User::model()->findByPk($uid);
		$user_info = UserInfo::model()->findByPk($uid);
		//dump($user_info);

		if ( empty($user) || empty($user_info)) {
			echo "用户不存在。";
			exit;
		}
		if ( empty($user_info->email) ) {
			echo "该用户邮箱为空。";
			exit;
		}

		if( !empty($cid) ) {
			$contrib = Contrib::model()->findByPk($cid);
			$link = '<a href="http://xyzpedia.org/contribute/id/'.$contrib->id.'">'.$contrib->title.'</a>';
		}
		$msg = "<p>亲爱的小百科用户 <strong>".$user->nick_name."</strong>：</p>";
		switch($mode) {
			case "review":
				$subject = "【小宇宙百科】稿件待审提醒";
				$msg.= '<p>小百科网站上有一篇稿子《'.$link.'》需要您审核哟~</p>';
				$msg.= '<p>【请访问xyzpedia.org】</p>';
				break;
			case "modify":
				$subject = "【小宇宙百科】稿件修改提醒";
				$msg.= '<p>去小百科官网看看嘛，您的稿子《'.$link.'》需要修改喔~</p>';
				$msg.= '<p>改完记得切换回待审状态再提交喔~</p>';
				break;
			case "enqueue":
				$subject = "【小宇宙百科】定稿提醒";
				$msg.= '<p>这周是你定稿喔，亲！记得去催催稿库里稿子们的进度吧~</p>';
				break;
			default:
				$subject = "【小宇宙百科】^-^";
				$msg.= "<p>欢迎多多给小宇宙百科投稿喔~</p>";
				break;
		}
		$msg.="系统邮件，请勿回复。";
		$msg.= "<p>&nbsp;</p>";
		$msg.= "<p style='text-align:right;'>小宇宙百科网站 敬上</p>";
		$msg.= "<p style='text-align:right;'>".timeFormat(gmtTime(), "chinese")."</p>";

		$recipient = (object)array(
			"addr" => $user_info->email,
			"name" => $user->nick_name,
		);
		Yii::import("ext.PHPMailSender");
		$res = PHPMailSender::sendMail($recipient, $subject, $msg);
		if ($res->status == 1) {
			echo "邮件发送成功。";
		} else {
			echo $res->info;
		}
	}

}

?>