<?

function addMenu($id, $cap) {
	if ( ! $cap ) return ;
	echo "<li class=\"nav-header\">";
	echo Yii::t('admin/sidebar', ucfirst($id));
	echo "</li>\n";
}
function addSubMenu($id, $cap, $url, $icon) {
	if ( ! $cap )  return ;
	echo "<li id=\"{$id}\"><a href=\"{$url}\">";
	echo "<i class=\"icon-{$icon}\"></i> <span>";
	echo Yii::t('admin/sidebar', ucfirst($id));
	echo "</span></a></li>\n";
}
function addDevider() {
	echo "<li class=\"divider\"></li>\n";
}

?>

<div class="well" style="padding: 8px 0;">
	<ul class="nav nav-list" id="admin-left-nav"><?

		addSubMenu("summary", 1, "/admin/info", "home");

		// post management
		$can_create = can("post", "create");
		$can_list = can("post", "list");
		addMenu("post", $can_create || $can_list);
		addSubMenu("postnew", $can_create, "/admin/post/new", "file");
		addSubMenu("postlist", $can_list, "/admin/post/list", "th-list");
		addSubMenu("category", $can_list, "/admin/category", "list-alt");

		addMenu("event", isAdmin());
		addSubMenu("pagenew", isAdmin(), "/admin/page/new", "file");
		addSubMenu("eventlist", isAdmin(), "/admin/page/list", "th-list");

		// contribution management
		$can_assign = can("contribute", "assign");
		$can_entrust = can("contribute", "entrust");
		$can_enqueue = can("contribute", "enqueue");
		addMenu("contribute", $can_assign || $can_entrust || $can_enqueue);
		addSubMenu("assign", $can_assign, "/admin/contribute/assign", "edit");
		addSubMenu("appoint", $can_entrust, "/admin/publish/appoint", "film");
		addSubMenu("publish", $can_enqueue, "/admin/publish/decide", "lock");

		// poll
		addMenu("poll", isAdmin());
		addSubMenu("pollnew", isAdmin(), "/admin/poll/new", "align-left");
		addSubMenu("polllist", isAdmin(), "/admin/poll/list", "list");

		$can_upload = can("contribute", "upload");
		// file management
		addMenu("filemanage", $can_upload);
		addSubMenu("upload", $can_upload, "/admin/file/upload",	"upload");
		addSubMenu("filelist", $can_upload, "/admin/file/list", "folder-open");

		// user management
		$can_list = can("user", "list");
		$can_promote = can("user", "promote");
		$can_manage_cap = can("user", "managecap");
		addMenu("usermanage", $can_list || $can_promote || $can_manage_cap);
		addSubMenu("userlist", $can_list, "/admin/user/list", "user");
		addSubMenu("uinfolist", $can_list, "/admin/user/infolist", "user");
		addSubMenu("capability", $can_manage_cap, "/admin/user/capability", "exclamation-sign");
		addSubMenu("apply", $can_promote, "/admin/user/apply", "play");

		// setting
		$can_basic = can("setting", "basic");
		$can_reading = can("setting", "reading");
		$can_widget = can("setting", "widget");
		addMenu("sitemanage", $can_basic || $can_reading || $can_widget);
		addSubMenu("general", $can_basic, "/admin/setting/general", "cog");
		//addSubMenu("reading", "阅读", $can_reading, "/admin/setting/reading", "zoom-in");
		addSubMenu("widget", $can_widget, "/admin/setting/widget", "th-large");
		addSubMenu("cron", $can_basic, "/admin/setting/cron", "time");

		addDevider();
		addSubMenu("help", 1, "#", "flag"); ?>
	</ul>
</div>
<script type="text/javascript" src="/js/common/menu-highlighter.js"></script>