<?php
if($_GET['id']) {
	$id_post = (int)$_GET['id'];
}
$post = get_post($id_post);

if($post['id_user'] == $user['user_id'] || $edit_post) {
	if($_GET['delete_img']) {
		$delete_img = $_GET['delete_img'];
		if ($delete_img == 1) {
			$msg = delete_img($delete_img,$id_post);

			if($msg === TRUE) {
				$_SESSION['msg'] = "Изображение удалено.";
				header("Location:".SITE_NAME."/?action=edit_post&id=".$id_post);
				exit();
			}
			else{
				$_SESSION['msg'] = $msg;
			}
		}
	}
	if($_POST) {
		$msg = edit_post($_POST,$id_post);

		if($msg === TRUE) {
			$_SESSION['msg'] = "Изменения, успешно, внесены.";
			header("Location:".SITE_NAME."/?action=edit_post&id=".$id_post);
			exit();
		}
		else{
			$_SESSION['msg'] = $msg;
		}
	}
}
else {
	$_SESSION['msg'] = "У вас нет прав на редактирование данного поста.";
	header("Location:".SITE_NAME."/?action=main");
	exit();
}
?>