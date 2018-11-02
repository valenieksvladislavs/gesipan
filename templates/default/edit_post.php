<div class="edit-post">
	<form method="post" enctype="multipart/form-data">
	<?php if($post['img']) { ?>
		<img src="files/mini/<?php echo $post['img']; ?>" alt="" />
		<a href="?action=edit_post&id=<?php echo $id_post; ?>&delete_img=1">Удалить изображение</a>
	<?php }else { ?>
		<label for="def_img">Основное изображение:</label>
		<input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
		<input type="file" name="def_img" id="def_img" />
	<?php } ?>

	<label for="title">Заголовок:</label>
	<input type='text' name='title' value="<?php if($_POST['title']){echo $_POST['title'];}else{echo $post['title'];} ?>" id='title' />

	<label for="text">Текст объявления:</label>
	<textarea name="text" id="text"><?php if($_POST['text']){echo $_POST['text'];}else{echo $post[text];} ?></textarea>

	<label for="categories">Категория:</label>
	<select name="categories" id="categories">
		<?php if($categories) {
			foreach($categories as $key => $item){ ?>
				<optgroup label="<?php echo $item[0]; ?>">";
				<?php foreach ($item[next] as $k => $v) { ?>
					<option <?php if($_POST['categories']){if($_POST['categories'] == $k){echo "selected";}}else{if($post['id_categories'] == $k){echo "selected";}} ?> value="<?php echo $k ?>"><?php echo $v ?></option>;
				<?php } ?>
				</optgroup>
			<?php } ?>
		<?php } ?>
	</select>

	<label for="section">Тип сделки:</label></th>
	<select name="section" id="section">
		<?php if($section) { ?>
			<?php foreach ($section as $item) { ?>
				<option <?php if($_POST['section']){if($_POST['section'] == $item[id]){echo "selected";}}else{if($post['id_section'] == $item[id]){echo "selected";}} ?> value="<?php echo $item[id]; ?>"><?php echo $item[name]; ?></option>;
			<?php } ?>
		<?php } ?>
	</select>

	<label for="town">Город:</label>
	<select name="town" id="town">
		<?php if($town) { ?>
			<?php foreach ($town as $item) { ?>
				<option <?php if($_POST['town']){if($_POST['town'] == $item[id]){echo "selected";}}else{if($post['id_town'] == $item[id]){echo "selected";}} ?> value="<?php echo $item[id]; ?>"><?php echo $item[name]; ?></option>;
			<?php } ?>
		<?php } ?>
	</select>

	<label for="price">Цена:</label>
	<input type="text" name="price" id="price" value="<?php if($_POST['price']){echo $_POST['price'];}else{echo $post['price'];} ?>" />

	<label for="capcha">Введите код с картинки</label>

	<img src="capcha.php"><input type="text" name="capcha" id="capcha" />

	<input type="submit" name="edit" value="Сохранить" />
	</form>
</div>