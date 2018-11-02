<?php
if($user)	{
	header("Location:".SITE_NAME);
	exit();
}
else{
	if($_GET['hash']) {
		$confirm = confirm();
		if($confirm === TRUE) {
			$_SESSION['msg'] = "Ваша учетная запись активирована. Можете авторизироваться на сайте.";
			header("Location:".SITE_NAME."/?action=login");
			exit();
		}
		else {
			$_SESSION['msg'] = $msg;
		}
	}

	if(isset($_POST['reg'])) {
		$msg = registration($_POST);

		if($msg === TRUE) {
			$_SESSION['msg'] = "Вы успешно зарегестрировались на сайте. И для подтверждения регистрации Вам на почту отправлено письмо с инструкциями.";
			header("Location:".SITE_NAME);
			exit();
		}
		else {
			$_SESSION['msg'] = $msg;
		}
	}

	$content = "registration.php";
}
?>