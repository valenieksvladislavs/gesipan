<?php
$posts = get_no_conf_posts();

if($_GET['confirm']) {
	$msg = conf_post($_GET['confirm']);

		if($msg === TRUE) {
			$_SESSION['msg'] = "Изменения, успешно, внесены.";
			header("Location:".SITE_NAME."/?action=admin&page=no-conf-posts");
			exit();
		}
		else{
			$_SESSION['msg'] = $msg;
			header("Location:".SITE_NAME."/?action=admin&page=no-conf-posts");
			exit();
		}
}
?>