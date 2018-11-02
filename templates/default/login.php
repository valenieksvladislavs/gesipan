<h1>Авторизируйтесь</h1>
<div>
<?php
	echo $_SESSION['msg'];
	unset ($_SESSION['msg']);
?>
</div>

<form method="POST">
	<label for='login'>Логин</label>
	<input type='text' name='login' id='login' />
	<label for='password'>Пароль</label>
	<input type='password' name='password' id='password' />
	<label for='remember'>Запомнить меня</label>
	<input type='checkbox' name='remember' id='remember' value="1" />
	<input type='submit' value='Вход' />
</form>
<a href="?action=registration">Регистрация</a> | <a href="?action=returnpas">Забыли пароль?</a>