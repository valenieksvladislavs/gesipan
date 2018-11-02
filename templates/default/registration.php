<h1>Регистрация</h1>

<div class="edit-post">
<form method="POST">
	<label for='reg_login'>Логин</label>
	<input type='text' name='reg_login' value="<?php echo $_SESSION['reg']['login']; unset ($_SESSION['reg']['login']); ?>" id='reg_login' />

	<label for='reg_password'>Пароль</label>
	<input type='password' name='reg_password' id='reg_password' />

	<label for='reg_password_confirm'>Подтвердите пароль</label>
	<input type='password' name='reg_password_confirm' id='reg_password_confirm' />

	<label for='reg_email'>Почта</label>
	<input type='text' name='reg_email' value="<?php echo $_SESSION['reg']['email']; unset ($_SESSION['reg']['email']); ?>" id='reg_email' />

	<label for='reg_name'>Имя</label>
	<input type='text' name='reg_name' value="<?php echo $_SESSION['reg']['name']; unset ($_SESSION['reg']['name']); ?>" id='reg_name' />

	<label for='reg_phone'>Телефон</label>
	<input type='text' name='reg_phone' value="<?php echo $_SESSION['reg']['phone']; unset ($_SESSION['reg']['phone']); ?>" id='reg_phone' />

	<input type='submit' name='reg' value='Зарегестрироваться' />
</form>
</div>