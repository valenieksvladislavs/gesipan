<form method="post">
<input type="text" name="name" />

<label for="categories">Родительская категория:</label>
<select name="parent" id="categories">
	<?php if($categories) { ?>
		<option value="0">Нет</option>
		<?php foreach($categories as $key => $item){ ?>
			<option value="<?php echo $key; ?>"><?php echo $item[0]; ?></option>
			<?php foreach($item['next'] as $k=>$v) { ?>
				<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
			<?php } ?>
		<?php } ?>
	<?php } ?>
</select>
<input type="submit" value="Сохранить" />
</form>