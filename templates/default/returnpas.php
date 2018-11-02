<h1>Введите свой почтовый адрес</h1>
<div>
<?php
	echo $_SESSION['msg'];
	unset ($_SESSION['msg']);
?>
</div>

<form method="POST">
	<input type='text' name='email' id='email' />
	<input type='submit' value="Отправить" />
</form>