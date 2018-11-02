<?php

if(!$user)	{
	$_SESSION['msg'] = "Авторизируйтесь, чтобы опубликовать объявление";
	header("Location:".SITE_NAME."/?action=login");
	exit();
}
elseif(!$add_post) {
	$_SESSION['msg'] = "У вас нет прав на публикацию объявлений";
	header("Location:".SITE_NAME);
	exit();
}
else{
	if($_POST) {
		$msg = add_post($_POST,$user['user_id']);

		if($msg === TRUE) {
			$_SESSION['msg'] = "Объявление, успешно, добавлено и будет опубликовано, после проверки модератором.";
			header("Location:".SITE_NAME."/?action=my_posts");
			exit();
		}
		else{
			$_SESSION['msg'] = $msg;
		}
	}
}
?>