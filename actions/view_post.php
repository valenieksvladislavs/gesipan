<?php
if($_GET['id']) {
	$id_post = (int)$_GET['id'];
}

$post = get_post($id_post);
$img_s = explode("|",$post['img_s']);

if($post['id_user'] != $user['user_id']) {
	if($_POST) {
		$msg = send_mail($_POST,$post['email']);

		if($msg === TRUE) {
			$_SESSION['msg'] = "Письмо отправлено.";
		}
		else{
			$_SESSION['msg'] = $msg;
		}
	}
}
?>