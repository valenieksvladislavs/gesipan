<?php
if(!$add_category) {
	$_SESSION['msg'] = "У вас нет прав на редактирование категорий.";
	header("Location:".SITE_NAME."/?action=admin&page=categories");
	exit();
}
else {
	if($_GET['id']) {
		$id_category = $_GET['id'];
	}

	$category = get_category($id_category);

	if($_POST) {
		$msg = edit_category($_POST,$id_category);

		if($msg === TRUE) {
			$_SESSION['msg'] = "Изменения, успешно, внесены.";
			header("Location:".SITE_NAME."/?action=admin&page=categories");
			exit();
		}
		else{
			$_SESSION['msg'] = $msg;
		}
	}
}
?>