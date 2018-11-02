<ul class="categories">
<?php
	if(is_array($categories)) {
		foreach($categories as $key=>$value) {
			if($value['next']) { ?>
				<li class="menu-item">
					<h2><?php echo $value[0]; ?></h2>
					<ul class="sub-menu">
					<?php foreach($value['next'] as $k=>$v) { ?>
						<li><a href='?action=posts&categories=<?php echo $k ?>'><?php echo $v ?></a></li>
					<?php } ?>
					</ul>
				</li>
			<?php }
		}
	}
?>
</ul>