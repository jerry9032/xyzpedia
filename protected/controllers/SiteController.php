<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		//$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		$this->layout = "/layouts/error";
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else {
				if ( in_array($error['code'], array(404, 500)) ) {
					$this->render((string)$error['code'], $error);
				} else {
					$this->render("error", $error);
				}
			}
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	public function actionPost()
	{
		session_start();
		dump($_SESSION);
	}

	public function actionWeiboTest()
	{
		session_start();
		Yii::import('ext.weibo.*');
		$appkey = Yii::app()->params->weibo['WB_AKEY'];
		$appsec = Yii::app()->params->weibo['WB_SKEY'];
		$c = new SaeTClientV2( $appkey , $appsec , $_SESSION['token']['access_token'] );

		$status = '自动图片发送测试 at '.timeFormat(gmtTime());
		$pic_path = 'http://xyzpedia.org/images/logo.png';
		$c->upload( $status, $pic_path );
	}


	public function actionCron() {

		$criteria = new CDbCriteria;
		$criteria->addCondition('appoint<='. gmtTime());
		$criteria->addCondition('status="pending"');
		$crons = Cron::model()->findAll($criteria);

		foreach ($crons as $cron) {
			switch ($cron->action) {
				case 'weibo':
					$post = $cron->post;
					if ( empty($post) ) break;

					Yii::import('ext.weibo.*');
					$param	= Yii::app()->params->weibo;
					$key	= $param['WB_AKEY'];
					$secret	= $param['WB_SKEY'];
					$token	= $param['TOKEN'];
					$c = new SaeTClientV2($key, $secret, $token);

					$siteurl = Yii::app()->params->siteurl;
					$status = '【小宇宙百科'.$post->serial. '】';
					$status.= $post->title . ' - ' . $post->excerpt;
					$status.= '原文地址：';
					$status.= $siteurl.timeFormat($post->created, 'path').'/'.$post->slug;
					$pic_path =  $siteurl . 'images/' . $post->thumbnail;

					$ret = $c->upload( $status, $pic_path );
					if ( isset($ret['error_code']) && $ret['error_code'] > 0 ) {
						Cron::model()->updateByPk($cron->id, array(
							'status' => 'error',
							'error' => $ret['error_code'] . ':' . $ret['error']
						));
					} else {
						Cron::model()->updateByPk($cron->id, array(
							'status' => 'done',
							'error' => '',
						));
						}
					break;
				default: break;
			}
		}
	}

	public function actionCrontest()
	{
		set_time_limit(10);
		$interval = 1; // unit by second
		do {
			echo "hello";
			sleep($interval);
		} while(1);
	}

	public function actionFileAjaxUpload($type = '') 
	{
		Yii::import("ext.EAjaxUpload.qqFileUploader");
		$folder = './downloads/';

		$allowedExtensions = array('txt', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'mp3'); 
		$sizeLimit = 10 * 1024 * 1024;
		$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
		$result = $uploader->handleUpload($folder, true);
		if($result['success'])
		{
			$result['error'] = 0;
		}
		else
		{
			$result['error'] = 1;
		}
		
		die(json_encode($result));

	}

	public function actionMailList() {
		$users=UserInfo::model()->findall();

		foreach($users as $user)
		  echo $user->email.", ";
	}


	public function actionAgent()
	{
		echo $_SERVER["HTTP_USER_AGENT"];
	}

	public function actionUnavailable() {
		throwException(400, "网站维护中，请稍后访问。");
	}

	public function actionRefreshScore() {
		$command = "UPDATE `xyz_user_info` t1 SET  `score` = ( 
(SELECT COUNT(*) FROM xyz_contrib WHERE author_id = t1.id and status != 'hanging' ) *5 +
(SELECT COUNT(*) FROM xyz_contrib WHERE editor_id = t1.id ) *3 +
(SELECT COUNT(*) FROM xyz_contrib_comment WHERE author_id = t1.id ) * 0.1 +
(SELECT COUNT(*) FROM xyz_comment WHERE user_id = t1.id ) * 0.1);";
		sql($command)->query();
	}


	/////////////// cron actions //////////////////
	public function actionCronWeibo() {
		$day_time = (gmtTime() + 28800) % 86400;
		
		$criteria = new CDbCriteria;
		$criteria->addCondition("`time`<=".$day_time);
		$criteria->addCondition("`time`>".($day_time - 60));

		$cron = Cron::model()->find($criteria);
		$func_table = (object)array(
			array('post', 'today', 'weibo', 'cron_today'),
			array('post', 'history', 'weibo', 'cron_history'),
			array('post', 'lweek', 'weibo', 'cron_last_week'),
			array('post', 'lmonth', 'weibo', 'cron_last_month'),
		);
		if ( isset($cron) ) {

			foreach($func_table as $item) {
				if ($cron->type == $item[0] &&
					$cron->subtype == $item[1] &&
					$cron->action == $item[2]) {
					call_user_func(array($this, $item[3]));
					break;
				}
			}

		}
	}

	public function actionCronToday() {
		$this->cron_today();
	}

	public function cron_today() {
		// 寻找当天八点半左右一小时发布的词条
		echo "cron_today <br> ";

		$now = gmtTime();
		$tmp = (int)($now / 86400) * 86400 + 1800;

		$criteria = new CDbCriteria;
		$criteria->addCondition("created>=" . ($tmp - 3600));
		$criteria->addCondition("created<=" . ($tmp + 3600));

		$post = Post::model()->find($criteria);
		$this->push_to_weibo($post);
	}

	public function cron_history() {
		echo "cron_history <br> ";

		$order = get_option("cron_push_weibo_order");
		switch ($order) {
			case 'created':
				$this->cron_by_time();
				break;
			default:
				break;
		}
	}

	public function cron_last_week() {
		echo "cron_last_week <br> ";
		$this->cron_last_time(7);
	}

	public function cron_last_month() {
		echo "cron_last_month <br> ";
		$this->cron_last_time(30);
	}

	public function cron_last_time($day) {
		// 寻找N天之前的八点半左右一小时发布的词条
		echo "cron_last_time <br> ";
		echo $day . "<br>";

		$gmt = gmtTime() - $day * 86400;
		$tmp = (int)($gmt / 86400) * 86400 + 1800;

		$criteria = new CDbCriteria;
		$criteria->addCondition("created>=" . ($tmp - 3600));
		$criteria->addCondition("created<=" . ($tmp + 3600));

		$post = Post::model()->find($criteria);
		$this->push_to_weibo($post);
	}

	public function cron_by_time() {
		echo "cron_by_time <br> ";

		$offset = (int)get_option("cron_push_weibo_created");

		$criteria = new CDbCriteria;
		$criteria->order = "created";
		$criteria->offset = $offset;

		$post = Post::model()->find($criteria);
		$this->push_to_weibo($post);

		set_option("cron_push_weibo_created", $offset + 1);
	}

	public function push_to_weibo($post) {
		if ( empty($post) ) {
			echo "empty post \n";
			return;
		}
		echo $post->id;
		Yii::import('ext.weibo.*');
		Yii::import('ext.WordsCount');

		$param	= Yii::app()->params->weibo;
		$key	= $param['WB_AKEY'];
		$secret	= $param['WB_SKEY'];
		$token	= $param['TOKEN'];
		$c = new SaeTClientV2($key, $secret, $token);

		$siteurl = Yii::app()->params->siteurl;
		$status = '【小宇宙百科'.$post->serial. '】';

		$status.= $post->title . ' - ' . $post->excerpt;
		if (WordsCount::length($status) > 115) {
			$excerpt = WordsCount::substr($excerpt, 0, 114). "…";
		}
		$status.= $siteurl.timeFormat($post->created, 'path').'/'.$post->slug;
		$status.= "（分享自@小宇宙百科网）";
		$pic_path =  $siteurl . 'images/' . $post->thumbnail;

		$ret = $c->upload( $status, $pic_path );

		dump(date("Y-n-j H:i:s") . "<br>");
		dump($ret);
	}
}
