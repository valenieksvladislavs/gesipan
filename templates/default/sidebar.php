<div id="sidebar">
	<ul>
	<?php
		if(is_array($categories)) {
			foreach($categories as $key=>$value) {
				if($value['next']) {
					echo "<li>".$value[0];
					echo "<ul>";
					foreach($value['next'] as $k=>$v) {
						echo "<li><a href='?action=categories&id=".$k."'>".$v."</a></li>";
					}
					echo "</ul>";
					echo "</li>";
				}
			}
		}
		?>
	</ul>
</div>