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
				<a href="?action=admin&page=no-conf-posts&confirm=<?php echo $item['id'] ?>">Подтвердить</a>
				<ul class="sub-list">
					<li><?php echo $item['town']; ?></li>

					<li>Цена: <span class="price"><?php echo number_format($item['price'], 2, '.', ''); ?>&#8364</span></li>
				</ul>
			</div>
		</li>
	<?php } ?>
	</ul>
<?php } ?>