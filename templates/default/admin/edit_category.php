<form method="post">
<input type="text" name="name" value="<?php echo $category['name']; ?>" />

<label for="categories">Родительская категория:</label>
<select name="parent" id="categories">
	<?php if($categories) { ?>
		<option value="0">Нет</option>
		<?php foreach($categories as $key => $item){ ?>
			<option <?php if($category['parent_id'] == $key){echo "selected";} ?> value="<?php echo $key; ?>"><?php echo $item[0]; ?></option>
		<?php } ?>
	<?php } ?>
</select>
<input type="submit" value="Сохранить" />
</form>