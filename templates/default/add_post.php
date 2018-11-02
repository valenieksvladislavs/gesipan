<div class="edit-post">
	<form method="POST" enctype="multipart/form-data">
		<label for="title">Заголовок:</label>
		<input type='text' name='title' value="<?php echo $_SESSION['add']['title']; unset ($_SESSION['add']['title']); ?>" id='title' />

		<label for="text">Текст объявления:</label>
		<textarea name="text" id="text"><?php echo $_SESSION["add"]["text"]; unset ($_SESSION["add"]["text"]); ?></textarea>

		<label for="categories">Категория:</label>
		<select name="categories" id="categories">
			<?php if($categories) {
				foreach($categories as $key => $item){
					echo "<optgroup label=".$item[0].">";
					foreach ($item[next] as $k => $v) {
						echo "<option value=".$k.">".$v."</option>";
					}
					echo "</optgroup>";
				}
			} ?>
		</select>

		<label for="section">Тип сделки:</label>
		<select name="section" id="section">
			<?php if($section) {
				foreach ($section as $item) {
					echo "<option value='".$item[id]."'>".$item[name]."</option>";
				}
			} ?>
		</select>
		
		<label for="town">Город:</label>
		<select name="town" id="town">
			<?php if($town) {
				foreach ($town as $item) {
					echo "<option value='".$item[id]."'>".$item[name]."</option>";
				}
			} ?>
		</select>

		<label for="def_img">Основное изображение:</label>
		<input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
		<input type="file" name="def_img" id="def_img" />

		<label for="price">Цена:</label>
		<input type="text" name="price" id="price" value="<?php echo $_SESSION["add"]["price"]; unset ($_SESSION["add"]["price"]); ?>" />

		<label for="capcha">Введите код с картинки</label>
		<img src="capcha.php"><input type="text" name="capcha" id="capcha" />

		<input type="submit" name="add" value="Опубликовать" />
	</form>
</div>