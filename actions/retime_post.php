<?php
if($_GET['id']) {
	$id_post = (int)$_GET['id'];
}
$post = get_post($id_post);

if($post['id_user'] == $user['user_id'] || $retime_post) {
    $msg = retime_post($id_post);

    if($msg === TRUE) {
        $_SESSION['msg'] = "Объявление продлено на неделю";
        header("Location:".SITE_NAME."/?action=view_post&id=".$id_post);
        exit();
    }
    else{
        $_SESSION['msg'] = $msg;
    }
}
else {
	$_SESSION['msg'] = "У вас нет прав на редактирование данного поста.";
	header("Location:".SITE_NAME."/?action=main");
	exit();
}
?>