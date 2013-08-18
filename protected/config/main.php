<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'XYZPedia',
	'language'=>'zh_cn',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'*',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
	),

	'defaultController' => 'post',
	// application components
	'components'=>array(
		'session' => array(
			'autoStart' => true, 
		),

		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin' => true,
			'identityCookie' => 'xyzpedia',
			'returnUrl' => '/',
			'loginUrl' => '/login',
		),
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName' => false,
			'rules'=>array(

				//'/' => '/site/unavailable',
				//'/<anything:[.\w\W]+>' => '/site/unavailable',

				// special pages
				'/download' => '/page/download',
				'/about' => '/page/about',
				'/voice' => '/page/voice',
				'/basic-info' => '/page/basicinfo',

				// post detail/archive
				'/hot' => '/post/light',
				'/hot/page/<page:\d+>' => '/post/light',
				'/page/<page:\d+>' => '/post/index',
				'/category/<cat_name:\w+>' => '/post/category',
				'/category/<cat_name:\w+>/page/<page:\d+>' => '/post/category',
				'/search/<key:[^\/]+>' => '/post/search',
				'/search/<key:[^\/]+>/page/<page:\d+>' => '/post/search',
				'<year:\d+>/<month:\d+>/<slug:[\W\w-]+>' => '/post/detail',
				'<year:\d+>/<month:\d+>' => '/post/archive',
				'<year:\d+>' => '/post/archive',
				'<year:\d+>/page/<page:\d+>' => '/post/archive',

				// event
				'/event/<slug:[\w-]+>' => '/page/event',

				// contribute page
				'/contribute/list/<type:\w+>' => '/contribute/list',
				'/contribute/list/<type:\w+>/<key:[.\w\W]+>' => '/contribute/list',
				'/contribute/id/<id:\d+>' => '/contribute/detail',
				'/contribute/id/<id:\d+>/edit' => '/contribute/submit',
				// contribute comment
				'/contribute/comment/<action:\w+>' => '/contribute/comment',

				// ????
				'/admin' => '/admin/info',
				'/admin/user/search/<key:[.\w\W]+>' => '/admin/user/search',
				'/register' => '/login/register',
				'/logout' => '/login/logout',

				// user page
				'/user' => '/user/profile',
				'/author/id/<id:\d+>' => 'user/home/id',
				//'/author/yoyo' => '/user/home/yoyo',
				//'/author/yoyo/original' => '/user/home/yoyooriginal',
				'/author/<login:[\W\w]+>' => '/user/home/page',
				'/notify/contrib/<type:\w+>' => '/notify/contrib',
				'/notify/comment/<type:\w+>' => '/notify/comment',

				// default
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',

				// temoprary
				'<path:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>'=>'<path>/<controller>/<action>',
				'<path:\w+>/<controller:\w+>/<action:\w+>/<type:\w+>'=>'<path>/<controller>/<action>',
			),
		),
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		*/
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=xyzpedia',
			'emulatePrepare' => true,
			'username' => 'xyzpedia',
			'password' => '*',
			'charset' => 'utf8',
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		'siteurl' => 'http://xyzpedia.org/',
		// this is used in contact page
		'adminEmail'=>'jerryshen.zju@gmail.com',

		'weibo' => array(
			"WB_AKEY" => '560274513',
			"WB_SKEY" => '443e2bfb50cce1589b69df6eaf016135',
			"TOKEN" => '*',
			"WB_CALLBACK_URL" => 'http://xyzpedia.org/weibo/callback.php'
		),
	),

);

?>
