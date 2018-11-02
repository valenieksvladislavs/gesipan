<?php
if(!$add_category) {
	$_SESSION['msg'] = "У вас нет прав на добавление категорий.";
	header("Location:".SITE_NAME."/?action=admin&page=categories");
	exit();
}
else {
	if($_POST) {
		$msg = add_category($_POST);

		if($msg === TRUE) {
			$_SESSION['msg'] = "Категория добавлена.";
			header("Location:".SITE_NAME."/?action=admin&page=categories");
			exit();
		}
		else{
			$_SESSION['msg'] = $msg;
		}
	}
}
?>