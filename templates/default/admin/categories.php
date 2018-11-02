<ul class="menu-categories">
	<?php
		if(is_array($categories)) {
			foreach($categories as $key=>$value) {
				if($value['next']) { ?>
					<li class="menu-item">
						<h2><a href="/?action=admin&page=edit_category&id=<?php echo $key; ?>"><?php echo $value[0]; ?></a></h2>
						<ul class="sub-menu">
						<?php foreach($value['next'] as $k=>$v) { ?>
							<li><a href='?action=admin&page=edit_category&id=<?php echo $k ?>'><?php echo $v ?></a></li>
						<?php } ?>
						</ul>
					</li>
				<?php }
			}
		}
	?>
</ul>
<a href="/?action=admin&page=add_category">Добавить новую категорию</a>