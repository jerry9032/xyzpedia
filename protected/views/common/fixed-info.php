<?
while(1){

// if have no msg, break it
if (  isset( $_GET['msg'] )  ) {
	$msg_id = (int) $_GET['msg'];
} else {
	break;
}

if ( $msg_id < 1000 ) {
	$type = "info";
} elseif ( $msg_id >= 1000 && $msg_id < 2000 ) {
	$type = "warning";
	$msg_id -= 1000;
} elseif ( $msg_id >= 2000 ) {
	$type = "danger";
	$msg_id -= 2000;
} else {
	break;
}

// if msg_id not correct, break
if ( $msg_id >= 100 && $msg_id < 200 ) {
	$scope = "admin";
} elseif ( $msg_id >= 200 && $msg_id < 300 ) {
	$scope = 'user';
} else {
	break;
}


$msg = array(
	'admin' => array(
		// post
		101 => 'Post Success.',
		102 => 'Modify post success.',
		103 => 'Delete post success.',
		// contribution
		111 => "Determine contribution success.",
		112 => "Undo determine contribution success.",
		113 => "Confirm determine contribution success.",
		// file
		121 => 'Upload success.',
		122 => 'Replace success.',
		// setting
		131 => "Modify setting success.",
		132 => "Submit contribution success.",
		133 => "Adjust widget order success.",
		// polling
		151 => "Modify poll success.",
		152 => "Create poll success.",
	),
	'user' => array(
		// info
		201 => "Modify basic info success.",
		202 => "Modify contact info success.",
		203 => "Modify personal tag success.",
		204 => "Modify education & creer info success.",
		// setting
		205 => "Modify basic setting success.",
		206 => "Modify personal page setting success.",
		// request
		221 => "Apply for forward success.",
		222 => "You already can download.",
		223 => "You have already applied for forward.",
	),
);


if ( isset( $msg[$scope][$msg_id]) ) { ?>

	<div class="alert alert-<?=$type?> alert-fixed">
		<?=Yii::t('general', "Prompt: ")?><?=Yii::t($scope, $msg[$scope][$msg_id])?>
	</div>
	<script type="text/javascript">
	(function($){
		setTimeout(function(){
			$('.alert-fixed').fadeOut(500);
		}, 3000);
	})(jQuery);
	</script><?

}

break;
}

?>