<?php
if($user)	{
	header("Location:".SITE_NAME);
	exit();
}
else{
	if(isset($_POST['email'])){
		$msg = get_password($_POST['email']);

		if($msg === TRUE) {
			$_SESSION['msg'] = "Новый пароль выслан Вам на почту";
			header("Location:?action=login");
			exit();
		}
		else {
			$_SESSION['msg'] = $msg;
		}
	}
}
?>