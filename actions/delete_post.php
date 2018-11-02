<?php
if($_GET['id']) {
	$id_post = (int)$_GET['id'];
}
$post = get_post($id_post);

if($post['id_user'] == $user['user_id'] || $delete_post) {
    $msg = delete_post($id_post);

    if($msg === TRUE) {
        $_SESSION['msg'] = "Объявление удалено";
        header("Location:".SITE_NAME."/?action=my_posts");
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