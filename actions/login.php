<?php
if(isset($_GET['logout'])) {
	$msg = logout();

	if($msg === TRUE) {
		$_SESSION['msg'] = "Вы вышли из системы";
		header("Location:".SITE_NAME);
		exit();
	}
}

if($user)	{
	header("Location:".SITE_NAME);
	exit();
}
else{
	if(isset($_POST['login']) && isset($_POST['password'])) {
		$msg = login($_POST);

		if($msg === TRUE) {
			header("Location:".SITE_NAME);
			exit();
		}
		else {
			$_SESSION['msg'] = $msg;
		}
	}
}
?>