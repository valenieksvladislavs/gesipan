<?php
if($_GET['user_id']) {
	$id_user = (int)$_GET['user_id'];
}

$user1 = get_user($id_user);

if($id_user == $user['user_id']) {
	$_SESSION['msg'] = "Нельзя менять свой статус.";
	header("Location:".SITE_NAME."/?action=admin&page=users");
	exit();
}
else {
	if($_POST) {
		$msg = change_role($_POST,$id_user);

		if($msg === TRUE) {
			$_SESSION['msg'] = "Изменения, успешно, внесены.";
			header("Location:".SITE_NAME."/?action=admin&page=users");
			exit();
		}
		else{
			$_SESSION['msg'] = $msg;
		}
	}
}
?>