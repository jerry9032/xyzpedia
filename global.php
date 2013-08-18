<?
//error_reporting(E_All);

ini_set('date.timezone','Asia/Shanghai');

//date_default_timezone_set("PRC");

defined('DS') or define('DS', DIRECTORY_SEPARATOR);

function app() 
{
	return Yii::app();
}

function cs() 
{
	return Yii::app()->getClientScript();
}

function isGuest()
{
	return Yii::app()->user->isGuest;
}

function uid() 
{
	static $_uid;
	if(!isset($uid))
	{
		$_uid = app()->user->id;
	} 	
	if($_uid == null)	$_uid = 0;
	
	return $_uid;
}

function user() {
	static $_user;
	if(isset($_user)) {
		return $_user;
	} else {
		$_user = User::model()->findByPk(uid());
		return $_user;
	}
}

function usetting() {
	static $_usetting;
	if(isset($_usetting)) {
		return $_usetting;
	} else {
		$_usetting = UserSetting::model()->findByPk(uid());
		return $_usetting;
	}
}

function user_setting_init() {
	Yii::app()->language = usetting()->lang;
}


function dump($target) 
{
	return CVarDumper::dump($target, 10, true);
}

function timeFormat($time, $format='full') 
{

	if ($time == '')
		return '';

	if ($format == 'apart') 
	{
		$unit = 60;
		$p = time() - $time;
		if ($p / $unit < 1) return ($p / 1) . '秒前';
		
		$unit*=60;
		if ($p / $unit < 1) return intval($p / 60) . '分钟前';
		
		$unit*=24;
		if ($p / $unit < 1) return intval($p / 60 / 60) . '小时前';
		
		$unit*=30;
		if ($p / $unit < 1) return intval($p / 60 / 60 / 24) . '天前';
		
		//return timeFormat($time, 'full');
		$format = 'full';
	}
	if ($format == 'full')	return date('Y-m-d H:i:s', $time);
	if ($format == 'date')	return date('Y-m-d ', $time);
	if ($format == 'month')	return date('m-d ', $time);
	if ($format == 'path') return date('Y/m', $time);

	if ($format == 'condensed') return date('YmdHis', $time);
	if ($format == 'chinese') return date("Y 年 m 月 d 日", $time);

	if ($format == 'year') return date('Y', $time);
	if ($format == 'month-only') return date('m', $time);
	if ($format == 'hour') return date('H', $time);
	if ($format == 'minute') return date('i', $time);

	if ($format == 'rfc') return date('r', $time);
}

function gmtTime($dateStr = "") {
	if ( empty($dateStr) )
		$time = time()-date('Z');
	else {
		//mktime(hour,minute,second,month,day,year,is_dst)
		// input format : "2012-10-23-11-39" to parse
		$times = explode("-", $dateStr);
		if (count($times) < 5) {
			return time()-date('Z');
		} else {
			//dump($times);die();
			$time = mktime( (int)$times[3], (int)$times[4], 0,
				(int)$times[1], (int)$times[2], (int)$times[0]);
			$time-= date('Z');
		}
	}
	// bug fix
	$time += 28800;
	return $time;
}

function sql($sql) 
{
	$connection = app()->db;
	$connection->tablePrefix = 'xyz_'; 
	$command = $connection->createCommand($sql);
	
	return $command;
}


function cache() 
{
	return app()->cache;
}

function finish($str = '') 
{
	echo $str;
	app()->end();
	die();
}

function bu($url = null) 
{
	static $baseUrl;
	if($baseUrl === null)
	{
		$baseUrl = Yii::app()->getRequest()->getBaseUrl();
	}
	
	return $url === null ? $baseUrl : $baseUrl . '/' . ltrim($url, '/');
}

function ctl() 
{
	return app()->controller;
}

function throwException($code, $error) 
{

	throw new CHttpException($code, $error);
}

function cookie($key, $value = null) 
{
	if($value === null)
	{
		$cookies = app()->request->cookies;
		return $cookies[$key]->value;		
	}

	app()->request->cookies->add($key, new CHttpCookie($key, $value));
}

function getBrowserInfo()
{
	//header('Content-Type: text/html; charset=utf-8');

	$info = $_SERVER["HTTP_USER_AGENT"];
	$browser = '';	
	$core = '';
	$hot = '';
	
	//检测内核
	if(is_int(strpos($info, 'Trident'))) $core = 'Trident';
	if(is_int(strpos($info, 'WebKit'))) $core = 'Webkit';
	if(is_int(strpos($info, 'Gecko'))) $core = 'Gecko';
	if(is_int(strpos($info, 'Presto'))) $core = 'Presto';		
	
	//检测浏览器类型
	if(is_int(strpos($info, 'MSIE 6'))) $browser = 'IE6';
	else if(is_int(strpos($info, 'MSIE 7'))) $browser = 'IE7';	
	else if(is_int(strpos($info, 'MSIE 8'))) $browser = 'IE8';	
	else if(is_int(strpos($info, 'MSIE 9'))) $browser = 'IE9';	
	else if(is_int(strpos($info, 'MSIE 10'))) $browser = 'IE10';				
	else if(is_int(strpos($info, 'Firefox'))) $browser = 'Firefox';	
	else if(is_int(strpos($info, 'Chrome'))) $browser = 'Chrome';	
	else if(is_int(strpos($info, 'Safari'))) $browser = 'Safari';
	else 	if(is_int(strpos($info, 'Opera'))) $browser = 'Opera';	
		
	//检测国内主流浏览器
	if(is_int(strpos($info, 'SE')) && is_int(strpos($info, 'MetaSr'))) $hot = '搜狗浏览器';
	if(is_int(strpos($info, '360SE'))) $hot = '360安全浏览器';
	if(is_int(strpos($info, '360EE'))) $hot = '360急速浏览器';
	if(is_int(strpos($info, 'QQBrowser'))) $hot = 'QQ浏览器';
	if(is_int(strpos($info, 'Maxthon'))) $hot = '遨游浏览器';
	if(is_int(strpos($info, 'TheWorld'))) $hot = '世界之窗浏览器';
		
	$array = array();
	$array['agent'] = $info;
	$array['core'] = $core;
	$array['browser'] = $browser;
	$array['hot'] = $hot;
	
	return $array;
}

function getTerminal() {
	$info = $_SERVER["HTTP_USER_AGENT"];
	$type = '';
	if (is_int(strpos($info, 'iPad'))) return "ipad";
	if (is_int(strpos($info, 'iPhone'))) return "iphone";
	if (is_int(strpos($info, 'Windows Phone'))) return "wphone";
	if (is_int(strpos($info, 'Android'))) return "aphone";
	return "pc";
}

function v() {
	switch ( getTerminal() ) {
		case 'pc': $ver = ""; break;
		case 'ipad': $ver = ""; break;
		case 'iphone':
		case 'wphone':
		case 'aphone': $ver = "/m"; break;
		default: $ver = "";
	}
	return $ver;
}

function f() {
	switch ( getTerminal() ) {
		case 'pc': $from = 0; break;
		case 'ipad': $from = 10; break;
		case 'iphone': $from = 20; break;
		case 'wphone': $from = 21; break;
		case 'aphone': $from = 22; break;
		default: $from = 0;
	}
	return $from;
}

function d($n) {
	switch ( $n ) {
		//case 0: $des = "来自PC版"; break;
		case 10: $des = "来自iPad"; break;
		case 20: $des = "来自iPhone"; break;
		case 21: $des = "来自Windows Phone"; break;
		case 22: $des = "来自Android"; break;
		default: $des = "";
	}
	return $des;
}


function can($group, $cap) {
	if (isGuest())
		return 0;
	if (user()->role == "administrator")
		return 1;
	if ( empty($group) || empty($cap) )
		return 0;
	//return in_array($cap, caps());
	$groups = array(
		"user" => 0,
		'panel' => 1,
		'post' => 2,
		'contribute' => 3,
		'setting' => 4,
	);
	$caps = caps();
	$caps = $caps[$groups[$group]]['list'];
	//dump($caps);die();
	$caps= explode(",", (string)$caps);
	return in_array($cap, $caps);
}

function caps() {

	static $_caps;

	if (isset($_caps)) {
		return $_caps;

	} else {
		$sql = "select * from {{capability}} "
			  ."where `role`='".user()->role."' order by ID";
		$_caps = sql($sql)->queryAll();

		//if ( empty($caps) || empty($caps[0]['list']) )
		//	return 0;
		//$caps= explode(",", (string)$caps[0]['list']);
		return $_caps;
	}
}

function isAdmin() {
	if ( isGuest() )
		return false;
	else
		return user()->role == "administrator";
}

function get_option($key) {
	$row = sql('select `value` from {{setting}} where `key`="'.$key.'"')
		->queryRow();
	return $row['value'];
}
function set_option($key, $value) {
	sql('update {{setting}} set `value`="'.$value.'"'.
		' where `key`="'.$key.'"')->query();
	return true;
}

function vividFloor($fid) {
	$floors = array (
		1 => "沙发", 2 => "板凳", 3 => "地毯", 4 => "地板", 5 => "地下室",
		6 => "地基", 7 => "地壳", 8 => "地幔", 9 => "地核", 10 => "地心"
	);
	if ($fid <= 0) return "";
	if ($fid > 0 && $fid <= 10) return $floors[$fid];
	return $fid."楼";
}

function postFilter($str) {
	$str = str_replace("\n", "</p><p>", $str);
	$str = "<p>".$str."</p>";
	// wp-content/upload => images
	$str = str_replace('wp-content/uploads', 'images', $str);
	// voice of xyzpedia
	$str = preg_replace("/\<voice\>(.+?)\<\/voice\>/is",'<div id="player_wrap"></div>'.
		'<script type="text/javascript">var url="\\1";</script>'.
		'<script type="text/javascript" src="/js/jquery.jplayer.js"></script>'.
		'<script type="text/javascript" src="/js/jquery.voice.single.js"></script>', $str);
	// poll
	$str = preg_replace("/\<poll\>(.+?)\<\/poll\>/is",'<iframe id="ifm-poll\\1" frameborder="0" width="555px" src="/poll/display/\\1"></iframe>', $str);
	return $str;
}

function commentFilter($str) {
	$str = stripslashes($str);
	$str = str_replace("\n", '<br>', $str);
	$str = preg_replace('~\[(.*?)\]~s', '<img src="/images/face/\\1.gif">', $str);
	//$str = preg_replace('~@([\x4e00-\x9fa5]+)\(([\d]+)\)~s', '<a href="/author/id/\\2">\\1</a>', $str);
	$str = preg_replace('|@([^\s]+)\(([\d]+)\)|U', '<a href="/author/id/$2">@$1</a>', $str);
	$str = preg_replace("#((http|ftp)://[\/\?\&\=\%\+\;\-.\w]+)#i","<a href=\\1>\\1</a>", $str);
	return $str;
}


function getIP() {

	if(!empty($_SERVER["HTTP_CLIENT_IP"]))
		$cip = $_SERVER["HTTP_CLIENT_IP"];

	else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
		$cip = $_SERVER["HTTP_X_FORWARDED_FOR"];

	else if(!empty($_SERVER["REMOTE_ADDR"]))
		$cip = $_SERVER["REMOTE_ADDR"];

	else
		$cip = "";
	
	return $cip;
}


// json return error handle utils
function json_success($msg) {
	die(json_encode( array(
		'error' => 0,
		'msg' => $msg
	)));
}

function json_error($msg) {
	die(json_encode( array(
		'error' => 1,
		'msg' => $msg
	)));
}

?>
