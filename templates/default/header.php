<!DOCTYPE html>
<html>
<head>
	<title><?php echo SITE_NAME_HEADER ?></title>
	<link href="<?php echo TEMPLATE; ?>style.css" rel="stylesheet" type="text/css" />
	<link href="https://fonts.googleapis.com/css?family=Roboto:300" rel="stylesheet">
	<link rel='stylesheet' id='genericons-css'  href='<?php echo TEMPLATE; ?>/fonts/genericons/genericons.css' type='text/css' media='all' />
	<link rel='stylesheet' id='ionicons-css'  href='<?php echo TEMPLATE; ?>/fonts/ionicons-2.0.1/css/ionicons.css' type='text/css' media='all' />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo SITE_NAME ?>/js/tinymce/tinymce.min.js"></script>
 	<script>tinymce.init({ selector:'textarea', language:"ru" });</script>
 	<script type="text/javascript">
	 	$(document).ready(function(){
		    $('[data-type="background"]').each(function(){
		        var $bgobj = $(this);
		        $(window).scroll(function() {
		            var yPos = -($(window).scrollTop() / $bgobj.data('speed'));
		            var coords = '0 '+ yPos + 'px';
		            $bgobj.css({ backgroundPosition: coords });
		        });
		    });
		});
	 </script>
</head>
<body>
<header data-type="background" data-speed="3">
<?php if($admin) { ?><div class="layout admin"><a href='?action=admin'>Админ панель</a></div><?php } ?>
<div class="layout header">
	<div class="content">

		<div class="logo">
			<a href="<?php echo SITE_NAME ?>"><img src="<?php echo SITE_NAME.'/'.TEMPLATE ?>images/logo.png" /></a>
			<span><?php echo SITE_DESCRIPTION ?></span>
		</div>

		<div class="authorization">
			<?php if(!$user) { ?>
				<a href="?action=login">Вход</a>
				<a href="?action=registration">Регистрация</a>
			<?php }
			else { ?>
				<span>Добро пожаловать <?php echo $user['name']; ?></span>
				<a href="?action=login&logout=1">Выход</a>
			<?php } ?>
		</div>
	</div>
</div>
<div class="layout menu">
	<div class="content">
		<div class="search">
			<form method="get" class="search-form" >
				<input type="hidden" name="action" value="posts" />
				<input class="search-field" type="text" value="<?php echo $search ?>" name="search" placeholder="Найти" />
				<input class="search-submit" type="submit" id="searchsubmit" value="&#xf4a5;" />
			</form>
		</div>
		<nav id="primary-menu" class="primary-menu">
			<ul>
				<li class="menu-item add"><a href='?action=add_post' class="<?php if($action == 'add_post') {echo 'current';} ?>">Добавить объявление</a></li>
				<li class="menu-item list"><a href='?action=my_posts' class="<?php if($action == 'my_posts') {echo 'current';} ?>">Мои объявления</a></li>
			</ul>
		</nav>
	</div>
</div>
</header>
<div class="layout message">
	<div class="content">
		<?php
			echo $_SESSION['msg'];
			unset ($_SESSION['msg']);
		?>
	</div>
</div>