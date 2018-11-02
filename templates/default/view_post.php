<h1><?php echo $post['title']; ?></h1>

<div class="edit">
	<?php if($post['id_user'] == $user['user_id'] || $edit_post) { ?>
		<a href="?action=edit_post&id=<?php echo $post['id']; ?>">Изменить</a>

		<a href="?action=delete_post&id=<?php echo $post['id']; ?>">Удалить</a>
	<?php } ?>
</div>

<?php if($post['img']) {?><a href="files/<?php echo $post['img']; ?>"><img src="files/mini/<?php echo $post['img']; ?>" alt="" /></a><?php } else { ?><img src="templates/default/images/no-photo.jpg" alt="" /><?php } ?>

<div class="post_description">
	<p><?php echo $post[text]; ?></p>

	<table>
	<tr>
	<th>Номер телефона:</th>
	<td><?php echo $post['phone'] ?></td>
	</tr>
	<tr>
	<th>Электронная почта:</th>
	<td><a href="mailto:<?php echo $post['email'] ?>"><?php echo $post['email'] ?></a></td>
	</tr>
	<tr>
	<th>Город:</th>
	<td><?php echo $post['town'] ?></td>
	</tr>
	<tr>
	<th>Цена:</th>
	<td><?php echo number_format($post['price'], 2, '.', ''); ?>&#8364</td>
	</tr>
	<tr>
	<th>Дата:</th>
	<td><?php echo date('d-m-Y',$post["date"]); ?></td>
	</tr>
	</table>
</div>

<div class="send-mail">
<?php if($post['id_user'] != $user['user_id']) { ?>
	<?php
		echo $_SESSION['msg'];
		unset ($_SESSION['msg']);
	?>
	<form method="post">
		<h2>Отправить письмо на почту</h2>
		<label for="email">Мой электронный адрес:</label>
		<input type='text' name='email' value="<?php echo $user['email']; ?>" id='email' />
		<label for="subject">Тема письма:</label>
		<input type='text' name='subject' value="<?php echo $_SESSION['send']['subject']; unset ($_SESSION['send']['subject']); ?>" id='subject' />
		<label for="text">Текст письма:</label>
		<textarea name="text" id="text"><?php echo $_SESSION["send"]["text"]; unset ($_SESSION["ыутв"]["text"]); ?></textarea>
		<input type="submit" name="send" value="Отправить" />
	</form>
<?php } ?>
</div>