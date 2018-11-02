<?php if($total > 1) { ?>
	<div class="pagination">
		<?php echo $pagination; ?>
	</div>
<?php } ?>

<div class="sidebar">
<form method="get">
<input type="hidden" name="action" value="<?php echo $action; ?>" />
<input type="hidden" name="page" value="<?php echo $page; ?>" />

<label for="sort">Сортировать по</label>
<select name="sort" id="sort">
	<option value="date-desc" <?php if($sort == "date-desc") {echo  "selected";} ?>>Дате вверх</option>
	<option value="date-asc" <?php if($sort == "date-asc") {echo  "selected";} ?>>Дате вниз</option>
	<option value="price-desc" <?php if($sort == "price-desc") {echo  "selected";} ?>>Цене вверх</option>
	<option value="price-asc" <?php if($sort == "price-asc") {echo  "selected";} ?>>Цене вниз</option>
</select>

<label for="categories">Категория:</label>
<select name="categories" id="categories">
	<option value="">Все</option>
	<?php if($categories) { ?>
		<?php foreach($categories as $key => $item){ ?>
			<optgroup label="<?php echo $item[0] ?>">
			<?php foreach ($item[next] as $k => $v) { ?>
				<option value="<?php echo $k; ?>" <?php if($id_categories == $k) {echo "selected";} ?>><?php echo $v; ?></option>
			<?php } ?>
			</optgroup>
		<?php } ?>
	<?php } ?>
</select>

<label for="town">Город:</label>
<select name="town" id="town">
	<option value="">Все</option>
	<?php if($town) { ?>
		<?php foreach ($town as $item) { ?>
			<option value="<?php echo $item[id]; ?>" <?php if($id_town == $item['id']) {echo "selected";} ?>><?php echo $item[name]; ?></option>
		<?php } ?>
	<?php } ?>
</select>

<label for="section">Тип сделки:</label>
<select name="section" id="section">
	<option value="">Все</option>
	<?php if($section) { ?>
		<?php foreach ($section as $item) { ?>
			<option value="<?php echo $item[id]; ?>" <?php if($id_section == $item['id']) {echo "selected";} ?>><?php echo $item[name]; ?></option>
		<?php } ?>
	<?php } ?>
</select>

<label for="search">Искомое слово:</label>
<input type="text" name="search" id="search" value="<?php echo $search; ?>" placeholder="Найти" />

<input type="submit" value="Сортировать" />
</form>
</div>

<div class="site-main">

<?php if($posts) { ?>
	<ul class="post-list">
	<?php foreach($posts as $item) { ?>
		<li>
			<a href="?action=view_post&id=<?php echo $item['id']; ?>" class="miniature">
				<?php if($item['img']) {?><img src="files/mini/<?php echo $item['img']; ?>" alt="" /><?php } else { ?><img src="templates/default/images/no-photo.jpg" alt="" /><?php } ?>
			</a>
			<div class="post-announce">
				<h2><a href="?action=view_post&id=<?php echo $item['id']; ?>"><?php echo $item['title']; ?></a></h2>

				<p><?php echo excerpt($item['text']); ?></p>
				<ul class="sub-list">
					<li><?php echo $item['town']; ?></li>

					<li>Цена: <span class="price"><?php echo number_format($item['price'], 2, '.', ''); ?>&#8364</span></li>
				</ul>
			</div>
		</li>
	<?php } ?>
	</ul>
<?php } ?>
</div>

<?php if($total > 1) { ?>
	<div class="pagination">
		<?php echo $pagination; ?>
	</div>
<?php } ?>