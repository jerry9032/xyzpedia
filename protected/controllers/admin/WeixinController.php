<?php

class WeixinController extends CController {

	function init() {
		if ( isGuest() )
			$this->redirect("/login");

		$this->layout = "/layouts/admin";
		$this->pageTitle = Yii::t("general", "XYZPedia"). " - ";
	}

	public function actionEdit($id) {
		$post = Post::model()->findByPk($id);
		if (empty($post)) {
			throwException(404, Yii::t('admin', 'The specified post cannot be found.'));
		}
		// check cap
		//if ( !isGuest() && ((user()->id == $post->submit_id) || can("post", "updateall")) ) {
		//} else {
		//	throwException(404, Yii::t("admin", "您没有修改文章所需要的权限。"));
		//}
		
		$this->render("/admin/post/weixin", array(
			"post" => $post,
		));
	}

	public function stripHash($url) {
		$hashPos = strrpos($url, "#");
		if ($hashPos !== false) {
			$url = substr($url, 0, $hashPos);
		}
		return $url;
	}

	public function parseProxy($url) {
		$urlPos = strpos($url, "http%3A%2F%2F");
		if ($urlPos !== false) {
			$url = urldecode(substr($url, $urlPos));
		}
		$urlPos = strpos($url, "&token=");
		if ($urlPos !== false) {
			$url = substr($url, 0, $urlPos);
		}
		return $url;
	}

	public function actionUpdate($id) {
		
		$p = $_POST;
		$wxurl = $p["post-wxurl"];
		$wxpic = $p["post-wxpic"];

		if (strpos($wxurl, "msg") === false &&
			strpos($wxurl, "mid") === false) {
			throwException(500, "微信链接有误，请检查后重试。");
		}

		if (strpos($wxpic, "pic") === false) {
			throwException(500, "微信题图有误，请检查后重试。");
		}

		Post::model()->updateByPk($id, array(
			"wxtitle" => $p["post-wxtitle"],
			"wxurl" => $this->stripHash(trim($p["post-wxurl"])),
			"wxpic" => $this->parseProxy(trim($p["post-wxpic"])),
			"wxexcerpt" => $p["post-wxexcerpt"]
		));
		$this->redirect("/admin/weixin/edit/".$id."?msg=102");
	}

}

?>
