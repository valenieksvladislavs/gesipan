<?php
	function db($host,$user,$pass,$db_name) {
		$db = mysql_connect($host,$user,$pass);
		if(!$db) {
			exit(mysql_error());
		}
		if(!mysql_select_db($db_name,$db)) {
			exit(mysql_error());
		}

		mysql_query("SET NAMES UTF8");
	}

	function clear_str($str) {
		return trim(strip_tags($str));
	}

	function actual() {
		$current_date = time();
		mysql_query("UPDATE ".PREF."post SET is_actual='0' WHERE time_over < '$current_date'");
	}

	function registration($post) {
		$login = clear_str($post['reg_login']);
		$password = trim($post['reg_password']);
		$conf_pass = trim($post['reg_password_confirm']);
		$email = clear_str($post['reg_email']);
		$name = clear_str($post['reg_name']);
		$phone = clear_str($post['reg_phone']);

		$msg = '';

		if(empty($login)) {
			$msg .= "<span class='err'>Введите логин </span>";
		}

		if(empty($password)) {
			$msg .= "<span class='err'>Введите пароль </span>";
		}
		else {
			if(strlen($password)<6) {
			    $msg .= "<span class='err'>Длина пароля должна быть не менее 6-ти символов </span>";
			}
		}

		if(empty($email)) {
			$msg .= "<span class='err'>Введите Ваш электронный адресс </span>";
		}
		else {
			if(!preg_match("/[0-9a-z_\.\-]+@[0-9a-z_\.\-]+\.[a-z]{2,4}/i", $email)) {
				$msg .= "<span class='err'>Некорректный Email адресс </span>";
			}
		}

		if(empty($name)) {
			$msg .= "<span class='err'>Введите имя </span>";
		}

		if(empty($phone)) {
			$msg .= "<span class='err'>Укажите номер телефона </span>";
		}
		else {
			if(!preg_match("|^[\d]*$|", $phone)) {
				$msg .= "<span class='err'>Номер телефона может иметь, только, числовое значение </span>";
			}
			else{
				if(strlen($phone)<8 || strlen($phone)>8) {
					$msg .= "<span class='err'>Длина номера телефона должна быть равна 8-ми символам</span>";
				}
			}
		}

		if($msg) {
			$_SESSION['reg']['login'] = $login;
			$_SESSION['reg']['email'] = $email;
			$_SESSION['reg']['name'] = $name;
			$_SESSION['reg']['phone'] = $phone;
			return $msg;
		}

		if($conf_pass == $password) {
			$sql = "SELECT user_id
					FROM ".PREF."users
					WHERE login='%s'";
			$sql = sprintf($sql,mysql_real_escape_string($login));

			$result = mysql_query($sql);

			if(mysql_num_rows($result) > 0) {
				$_SESSION['reg']['email'] = $email;
				$_SESSION['reg']['name'] = $name;
				$_SESSION['reg']['phone'] = $phone;

				return "<span class='err'>Пользователь с таким логином уже существует</span>";
			}

			if(mysql_num_rows($result) < 1) {
				$sql2 = "SELECT user_id
						FROM ".PREF."users
						WHERE email='%s'";
				$sql2 = sprintf($sql2,mysql_real_escape_string($email));

				$result2 = mysql_query($sql2);

				if(mysql_num_rows($result2) > 0) {
					$_SESSION['reg']['login'] = $login;
					$_SESSION['reg']['name'] = $name;
					$_SESSION['reg']['phone'] = $phone;

					return "<span class='err'>Пользователь с данной почтой уже существует</span>";
				}

				if(mysql_num_rows($result2) < 1) {
					$sql3 = "SELECT user_id
							FROM ".PREF."users
							WHERE phone_number='%s'";
					$sql3 = sprintf($sql3,mysql_real_escape_string($phone));

					$result3 = mysql_query($sql3);

					if(mysql_num_rows($result3) > 0) {
						$_SESSION['reg']['login'] = $login;
						$_SESSION['reg']['email'] = $email;
						$_SESSION['reg']['name'] = $name;

						return "<span class='err'>Пользователь с таким телефоном уже существует</span>";
					}
				}
			}

			$password = md5($password);
			$hash = md5(microtime());

			$query = "INSERT INTO ".PREF."users (name,email,password,login,phone_number,hash)
					VALUES ('%s','%s','%s','%s','%s','$hash')";

			$query = sprintf($query,
								mysql_real_escape_string($name),
								mysql_real_escape_string($email),
								$password,
								mysql_real_escape_string($login),
								mysql_real_escape_string($phone)
							);

			$result4 = mysql_query($query);

			if(!$result4) {
				$_SESSION['reg']['login'] = $login;
				$_SESSION['reg']['email'] = $email;
				$_SESSION['reg']['name'] = $name;
				$_SESSION['reg']['phone'] = $phone;
				return "<span class='err'>Ошибка при добавлении пользователя в базу данных</span>".mysql_error();
			}
			else {
				$header = '';
				$header .= "From: ".DOMAIN." \r\n";
				$header .= "Content-Type: text/plain; charset=utf8";

				$subject = "Подтверждение регистрации";

				$mail_body = "Спасибо за регистрацию на нашем сайте. Ваша ссылка для подтверждения учетной записи: ".SITE_NAME."?action=registration&hash=".$hash;

				if (mail($email,$subject,$mail_body,$header)) {
					return TRUE;
				}
				else {
					return "<span class='err'>Ошибка отправки письма</span>";
				}
			}
		}
		else {
			$_SESSION['reg']['login'] = $login;
			$_SESSION['reg']['email'] = $email;
			$_SESSION['reg']['name'] = $name;
			$_SESSION['reg']['phone'] = $phone;
			return "<span class='err'>Вынеправильно подтвердили пароль</span>";
		}
	}

	function confirm() {
		$new_hash = clear_str($_GET['hash']);

		$query = "UPDATE ".PREF."users
					SET confirm='1'
					WHERE hash = '%s'
					";
		$query = sprintf($query,mysql_real_escape_string($new_hash));
		$result = mysql_query($query);

		if(mysql_affected_rows() == 1) {
			return TRUE;
		}
		else {
			return "<span class='err'>Неверный код подтверждения регистрации</span>";
		}
	}

	function login($post) {
		if(empty($post['login']) || empty($post['password'])) {
			return "<span class='err'>Заполните поля</span>";
		}

		$login = clear_str($post['login']);
		$password = md5(trim($post['password']));

		$sql = "SELECT user_id,confirm
				FROM ".PREF."users
				WHERE login = '%s'
				AND password = '%s'";
		$sql = sprintf($sql,mysql_real_escape_string($login),$password);

		$result = mysql_query($sql);

		if(!$result || mysql_num_rows($result) < 1) {
			return "<span class='err'>Неправильный логин или пароль</span>";
		}

		if(mysql_result($result,0,'confirm') == 0) {
			return "<span class='err'>Пользователь с таким логином еще не подтвержден</span>";
		}

		$sess = md5(microtime());

		$sql_update = "UPDATE ".PREF."users SET sess='$sess' WHERE login='%s'";
		$sql_update = sprintf($sql_update,mysql_real_escape_string($login));

		if(!mysql_query($sql_update)) {
			return "<span class='err'>Ошибка авторизации пользователя</span>";
		}

		$_SESSION['sess'] = $sess;

		if($post['remember'] == 1) {
			$time = time() + 10*24*3600;

			setcookie('login',$login,$time);
			setcookie('password',$password,$time);
		}

		return TRUE;
	}



	function logout() {
		unset($_SESSION['sess']);

		setcookie('login','',time()-3600);
		setcookie('password','',time()-3600);

		return TRUE;
	}

	function check_user() {
		if (isset($_SESSION['sess'])) {
			$sess = $_SESSION['sess'];

			$sql = "SELECT user_id, name, email, phone_number, id_role
					FROM ".PREF."users
					WHERE sess='$sess'";
			$result = mysql_query($sql);

			if(!$result || mysql_num_rows($result) < 1) {
				return FALSE;
			}

			return mysql_fetch_assoc($result);
		}
		elseif(isset($_COOKIE['login']) && isset($_COOKIE['password'])) {
			$login = $_COOKIE['login'];
			$password = $_COOKIE['password'];

			$sql = "SELECT user_id, name, id_role
					FROM ".PREF."users
					WHERE login='$login'
					AND password='$password'
					AND confirm ='1'";
			$result2 = mysql_query($sql);

			if(!$result2 || mysql_num_rows($result2) < 1) {
				return FALSE;
			}

			$sess = md5(microtime());

			$sql_update = "UPDATE ".PREF."users SET sess='$sess' WHERE login='%s'";
			$sql_update = sprintf($sql_update,mysql_real_escape_string($login));

			if(!mysql_query($sql_update)) {
				return "<span class='err'>Ошибка авторизации пользователя</span>";
			}

			$_SESSION['sess'] = $sess;

			return mysql_fetch_assoc($result);
		}

		else {
			return FALSE;
		}

	}

	function get_password($email) {
		$email = clear_str($email);

		$sql = "SELECT user_id
				FROM ".PREF."users
				WHERE email='%s'";
		$sql = sprintf($sql,mysql_real_escape_string($email));

		$result = mysql_query($sql);

		if(!$result){
			return "<span class='err'>Невозможно сгенерировать новый пароль</span>";
		}

		if(mysql_num_rows($result) == 1) {
			$chars = "234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";

			$pass = '';

			for($i = 0; $i < 9; $i++) {
				$pass .= substr($chars, rand(1, strlen($chars)) - 1, 1);
			}

			$md5pass = md5($pass);

			$query = "UPDATE ".PREF."users
						SET password='$md5pass'
						WHERE user_id = '".mysql_result($result,0,'user_id')."'";

			$result2 = mysql_query($query);

			if(!$result2) {
				return "<span class='err'>Невозможно сгенерировать пароль</span>";
			}

			$header = '';
			$header .= "From: ".DOMAIN." \r\n";
			$header .= "Content-Type: text/plain; charset=utf8";

			$subject = 'Новый пароль';
			$mail_body = "Ваш новый пароль: ".$pass;

			if (mail($email,$subject,$mail_body,$header)) {
				return TRUE;
			}
			else {
				return "<span class='err'>Ошибка отправки письма</span>";
			}
		}
		else {
			return "<span class='err'>Пользователя с таким почтовым ящиком нет</span>";
		}
	}

	function getPriv($id) {
		$sql = "SELECT ".PREF."priv.name AS priv
				FROM ".PREF."priv
				LEFT JOIN ".PREF."role_priv
					ON ".PREF."role_priv.id_priv = ".PREF."priv.id
				WHERE ".PREF."role_priv.id_role = '$id'";

		$result = mysql_query($sql);

		if(!$result) {
			return FALSE;
		}

		for($i = 0; $i < mysql_num_rows($result);$i++) {
			$row = mysql_fetch_array($result,MYSQL_NUM);
			$arr [] = $row[0];
		}

		return $arr;
	}


	function can($id,$priv_adm) {
		$priv = getPriv($id);

		if(!$priv) {
			$priv = array();
		}

		$arr = array_intersect($priv_adm,$priv);

		if($arr === $priv_adm) {
			return TRUE;
		}

		return FALSE;
	}

	function get_result($result) {
		if(!$result) {
			exit(mysql_error());
		}

		if(mysql_num_rows($result) == 0) {
			return FALSE;
		}

		$row = array();

		for($i = 0;mysql_num_rows($result) > $i; $i++) {
			$row[] = mysql_fetch_array($result,MYSQL_ASSOC);
		}

		return $row;
	}

	function get_section() {
		$sql = "SELECT id,name FROM ".PREF."section";

		$result = mysql_query($sql);

		return get_result($result);
	}

	function get_town() {
		$sql = "SELECT id,name FROM ".PREF."cities";

		$result = mysql_query($sql);

		return get_result($result);
	}

	function get_role() {
		$sql = "SELECT id,name FROM ".PREF."role";

		$result = mysql_query($sql);

		return get_result($result);
	}

	function get_categories() {
		$sql = "SELECT id,name,parent_id FROM ".PREF."categories";
		$result = mysql_query($sql);

		if(!$result) {
			exit(mysql_error());
		}

		if(mysql_num_rows($result) == 0) {
			return FALSE;
		}

		$categories = array();

		for($i = 0; mysql_num_rows($result) > $i; $i++) {
			$row = mysql_fetch_array($result,MYSQL_ASSOC);

			if(!$row['parent_id']) {
				$categories[$row['id']][] = $row['name'];
			}
			else {
				$categories[$row['parent_id']]['next'][$row['id']] = $row['name'];
			}
		}

		return $categories;
	}

	function generate_str() {
		$chars = "23456789qertyupasdfghjklzxcvbnm";

		$str = "";

		for($i = 0;$i < 5;$i++) {
			$str .= substr($chars, rand(1, strlen($chars)) - 1, 1);
		}

		return $str;
	}

	function get_img() {
		$width = 160;
		$height = 80;

		$img = imagecreatetruecolor($width, $height);

		$background_color = imagecolorallocate($img,mt_rand(133,255),mt_rand(133,255),mt_rand(133,255));
		$point_color = imagecolorallocate($img,mt_rand(50,150),mt_rand(50,150),mt_rand(50,150));

		imagefilledrectangle($img,0,0,$width,$height,$background_color);

		for($h = mt_rand(1,10);$h<$height;$h = $h + mt_rand(1,10)) {
			for ($v = mt_rand(1,30);$v < $width;$v = $v + mt_rand(1,30)) { 
				imagesetpixel($img,$v,$h,$point_color);
			}
		}

		$str = generate_str();
		$_SESSION["str_cap"] = $str;

		$fonts_p = "fonts/";

		$d = opendir($fonts_p);
		while(FALSE !=($file = readdir($d))) {
			if($file == "." || $file == "..") {
				continue;
			}
			$fonts[] = $file;
		}

		$x = mt_rand(10,20);
		for($i = 0;$i < strlen($str);$i++) {
			$n = mt_rand(0,count($fonts) - 1);
			$font = $fonts_p.$fonts[$n];
			$color = imagecolorallocate($img,mt_rand(0,80),mt_rand(0,80),mt_rand(0,80));
			$size = mt_rand(15,35);
			$angle = mt_rand(-30,30);
			$y = mt_rand(40,45);

			imagettftext($img,$size,$angle,$x,$y,$color,$font,$str[$i]);
			$x = $x + $size;
		}

		for($c = 0;$c < mt_rand(5,8); $c++) {
			imageline($img,mt_rand(0,intval($width * 0.1)),mt_rand(0,intval($height * 0.3)),mt_rand(intval($width * 0.8),$width),mt_rand(intval($height * 0.6),$height),$point_color);
		}

		header("Content-Type: image/png");
		imagepng($img);
		imagedestroy($img);
	}

	function img_resize($file_name,$type) {
		switch($type) {
			case 'jpeg':
			case 'pjpeg':
				$img_id = imagecreatefromjpeg(FILES.$file_name);
			break;
			case 'png':
			case 'x-png':
				$img_id = imagecreatefrompng(FILES.$file_name);
			break;
			case 'gif':
				$img_id = imagecreatefromgif(FILES.$file_name);
			break;
		}

		$width = imageSX($img_id);
		$height = imageSY($img_id);

		if($width > $height) {
			$k = round($height/IMG_SIZE,2);
		}
		else {
			$k = round($width/IMG_SIZE,2);
		}

		$mini_width = round($width/$k);
		$mini_height = round($height/$k);

		$img_res_id = imagecreatetruecolor($mini_width,$mini_height);
		$img_crop_id = imagecreatetruecolor(IMG_SIZE, IMG_SIZE);

		imagecopyresampled($img_res_id,
									$img_id,
									0,
									0,
									0,
									0,
									$mini_width,
									$mini_height,
									$width,
									$height
									);
		if($width > $height) {
			imagecopyresampled($img_crop_id,
										$img_res_id,
										0,
										0,
										($mini_width-IMG_SIZE)/2,
										0,
										IMG_SIZE,
										IMG_SIZE,
										IMG_SIZE,
										$mini_height
										);
		}
		else {
			imagecopyresampled($img_crop_id,
										$img_res_id,
										0,
										0,
										0,
										($mini_height-IMG_SIZE)/2,
										IMG_SIZE,
										IMG_SIZE,
										$mini_width,
										IMG_SIZE
										);
		}

		$img = imagejpeg($img_crop_id,MINI.$file_name,100);


		imagedestroy($img_id);
		imagedestroy($img_dest_id);
		imagedestroy($img_crop_id);

		if($img) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}

	function add_post($post,$user_id) {
		$title = clear_str($post['title']);
		$text = $post['text'];
		$id_categories = (int)($post['categories']);
		$id_section = (int)($post['section']);
		$id_town = (int)($post['town']);
		$price = (float)($post['price']);
		$date = time();
		$time_over = $date + 30*(60*60*24);

		$msg = '';

		if(empty($title)) {
			$msg .= "<span class='err'>Введите заголовок</span>";
		}

		if(empty($text)) {
			$msg .= "<span class='err'>Введите текст</span>";
		}

		if(empty($price)) {
			$msg .= "<span class='err'>Укажите цену</span>";
		}

		if($msg) {
			$_SESSION['add']['title'] = $title;
			$_SESSION['add']['text'] = $text;
			$_SESSION['add']['price'] = $price;
			return $msg;
		}

		$id_img = uniqid(true, true);

		$img_types = array("jpeg"=>"image/jpeg",
							"jpeg-e"=>"image/pjpeg",
							"png"=>"image/png",
							"x-png"=>"image/png",
							"gif"=>"image/gif"
							);

		if(($_FILES['def_img']['tmp_name'])) {
			if(($_FILES['def_img']['error'])) {
				$_SESSION['add']['title'] = $title;
				$_SESSION['add']['text'] = $text;
				$_SESSION['add']['price'] = $price;
				return "Ошибка при загрузке изображения";
			}

			$type_img = array_search($_FILES['def_img']['type'],$img_types);
			if(!$type_img)	{
				$_SESSION['add']['title'] = $title;
				$_SESSION['add']['text'] = $text;
				$_SESSION['add']['price'] = $price;
				return "Формат файла не поддерживается";
			}

			if($_FILES['def_img']['size'] > (2*1024*1024)) {
				$_SESSION['add']['title'] = $title;
				$_SESSION['add']['text'] = $text;
				$_SESSION['add']['price'] = $price;
				return "Размер изображения не должен превышать 2mb";
			}

			$img = $id_img;
			$img .= substr($_FILES['def_img']['name'],strripos($_FILES['def_img']['name'],"."));

			if(!move_uploaded_file($_FILES['def_img']['tmp_name'],FILES.$img)) {
				$_SESSION['add']['title'] = $title;
				$_SESSION['add']['text'] = $text;
				$_SESSION['add']['price'] = $price;
				return "Ошибка при загрузке изображения";
			}

			if(!img_resize($img,$type_img)) {
				$_SESSION['add']['title'] = $title;
				$_SESSION['add']['text'] = $text;
				$_SESSION['add']['price'] = $price;
				return "Ошибка при загрузке изображения";
			}
		}

		if(empty($post['capcha']) || $post['capcha'] !== $_SESSION['str_cap']) {
			$_SESSION['add']['title'] = $title;
			$_SESSION['add']['text'] = $text;
			$_SESSION['add']['price'] = $price;
			return "Не правильно введен код";
		}

		unset($_SESSION['str_cap']);

		$sql = "INSERT INTO ".PREF."post(
									title,
									text,
									date,
									id_user,
									id_categories,
									id_section,
									id_town,
									img,
									time_over,
									price)
									VALUES(
									'$title',
									'$text',
									'$date',
									'$user_id',
									'$id_categories',
									'$id_section',
									'$id_town',
									'$img',
									'$time_over',
									'$price')";
		$result = mysql_query($sql);

		if(!$result) {
			$_SESSION['add']['title'] = $title;
			$_SESSION['add']['text'] = $text;
			$_SESSION['add']['price'] = $price;
			return mysql_error();
		}

		return TRUE;
	}

	function edit_post($post,$post_id) {
		$title = clear_str($post['title']);
		$text = $post['text'];
		$id_categories = (int)($post['categories']);
		$id_section = (int)($post['section']);
		$id_town = (int)($post['town']);
		$price = (float)($post['price']);

		$msg = '';

		if(empty($title)) {
			$msg .= "<span class='err'>Введите заголовок</span>";
		}

		if(empty($text)) {
			$msg .= "<span class='err'>Введите текст</span>";
		}

		if(empty($price)) {
			$msg .= "<span class='err'>Укажите цену</span>";
		}

		if($msg) {
			return $msg;
		}

		$id_img = uniqid(true, true);

		$img_types = array("jpeg"=>"image/jpeg",
							"jpeg-e"=>"image/pjpeg",
							"png"=>"image/png",
							"x-png"=>"image/png",
							"gif"=>"image/gif"
							);

		if(($_FILES['def_img']['tmp_name'])) {
			if(($_FILES['def_img']['error'])) {
				$_SESSION['add']['title'] = $title;
				$_SESSION['add']['text'] = $text;
				$_SESSION['add']['price'] = $price;
				return "Ошибка при загрузке изображения";
			}

			$type_img = array_search($_FILES['def_img']['type'],$img_types);
			if(!$type_img)	{
				$_SESSION['add']['title'] = $title;
				$_SESSION['add']['text'] = $text;
				$_SESSION['add']['price'] = $price;
				return "Формат файла не поддерживается";
			}

			if($_FILES['def_img']['size'] > (2*1024*1024)) {
				$_SESSION['add']['title'] = $title;
				$_SESSION['add']['text'] = $text;
				$_SESSION['add']['price'] = $price;
				return "Размер изображения не должен превышать 2mb";
			}

			$img = $id_img;
			$img .= substr($_FILES['def_img']['name'],strripos($_FILES['def_img']['name'],"."));

			if(!move_uploaded_file($_FILES['def_img']['tmp_name'],FILES.$img)) {
				$_SESSION['add']['title'] = $title;
				$_SESSION['add']['text'] = $text;
				$_SESSION['add']['price'] = $price;
				return "Ошибка при загрузке изображения";
			}

			if(!img_resize($img,$type_img)) {
				$_SESSION['add']['title'] = $title;
				$_SESSION['add']['text'] = $text;
				$_SESSION['add']['price'] = $price;
				return "Ошибка при загрузке изображения";
			}
		}

		if(empty($post['capcha']) || $post['capcha'] !== $_SESSION['str_cap']) {
			return "<span class='err'>Не правильно введен код</span>";
		}

		unset($_SESSION['str_cap']);

		$sql = "UPDATE ".PREF."post SET
									title = '$title',
									text = '$text',
									id_categories = '$id_categories',
									id_section = '$id_section',
									id_town = '$id_town',
									price = '$price',
									img = '$img'
									WHERE id='$post_id'";
		$result = mysql_query($sql);

		if(!$result) {
			return mysql_error();
		}

		return TRUE;
	}

	function retime_post($id_post) {
		$date = time();
		$time_over = $date + 7*(60*60*24);

		$sql = "UPDATE ".PREF."post SET time_over = '$time_over', is_actual = '1' WHERE id='$id_post'";
		$result = mysql_query($sql);

		if(!$result) {
			return mysql_error();
		}

		return TRUE;
	}

	function delete_post($id_post) {
		$sql = "DELETE FROM ".PREF."post WHERE id='$id_post'";
		$result = mysql_query($sql);

		if(!$result) {
			return mysql_error();
		}

		return TRUE;
	}

	function get_my_posts($user,$id_categories,$id_categories,$sort,$id_section,$id_town,$start,$num,$search) {
		if($sort == "date-desc") {
			$sort_by = "date";
			$order = "DESC";
			$_SESSION['posts']['sort'] = $sort;
		}
		elseif($sort == "date-asc") {
			$sort_by = "date";
			$order = "ASC";
			$_SESSION['posts']['sort'] = $sort;
		}
		elseif($sort == "price-desc") {
			$sort_by = "price";
			$order = "DESC";
			$_SESSION['posts']['sort'] = $sort;
		}
		elseif($sort == "price-asc") {
			$sort_by = "price";
			$order = "ASC";
			$_SESSION['posts']['sort'] = $sort;
		}
		else {
			$sort_by = "date";
			$order = "DESC";
			$_SESSION['posts']['sort'] = "date-desc";
		}

		if(empty($id_town)) {
			$id_town = "%%";
		}

		if(empty($id_section)) {
			$id_section = "%%";
		}

		if(empty($id_categories)) {
			$id_categories = '%%';
		}

		$sql = "SELECT 	".PREF."post.id,
						".PREF."post.title,
						img,
						text,
						date,
						".PREF."cities.name AS town,
						price,
						confirm,
						is_actual,
						time_over
				FROM 	".PREF."post
				LEFT JOIN ".PREF."cities ON ".PREF."cities.id = ".PREF."post.id_town
				WHERE ".PREF."post.id_user = '$user'
				AND ".PREF."post.id_categories like '$id_categories'
				AND ".PREF."post.id_town like '$id_town'
				AND ".PREF."post.id_section like '$id_section'
				AND ".PREF."post.text like '%$search%'
				ORDER by ".$sort_by." ".$order." LIMIT ".$start.", ".$num."
						";
		$result = mysql_query($sql);
		return get_result($result);
	}

	function excerpt($str) {
		$post_text = clear_str($str);
		$chars = strlen($post_text);

		if($chars > ANNOUNCE_LENGTH) {
			$announce = iconv_substr($post_text,0,ANNOUNCE_LENGTH, "UTF-8");
			$announce .= "...";
		}
		else {
			$announce = $post_text;
		}

		return $announce;
	}

	function get_post($id_post) {
		$sql = "SELECT 	".PREF."post.id,
						title,
						img,
						text,
						id_town,
						date,
						".PREF."cities.name AS town,
						price,
						".PREF."post.confirm,
						is_actual,
						time_over,
						".PREF."post.id_user,
						".PREF."users.name AS uname,
						".PREF."users.email,
						".PREF."users.phone_number AS phone,
						".PREF."categories.id AS id_categories,
						".PREF."section.id AS id_section
				FROM 	".PREF."post
				LEFT JOIN ".PREF."users ON ".PREF."users.user_id = ".PREF."post.id_user
				LEFT JOIN ".PREF."categories ON ".PREF."categories.id = ".PREF."post.id_categories
				LEFT JOIN ".PREF."section ON ".PREF."section.id = ".PREF."post.id_section
				LEFT JOIN ".PREF."cities ON ".PREF."cities.id = ".PREF."post.id_town
				WHERE ".PREF."post.id = '$id_post'
				ORDER by date DESC
						";
		$result = mysql_query($sql);

		$row = get_result($result);

		return $row[0];
	}

	function send_mail($post,$destination) {
		$email = $post['email'];
		$subject = $post['subject'];
		$text = $post['text'];

		$msg = "";

		if(empty($email)) {
			$msg .= "<span class='err'>Введите Ваш электронный адресс </span>";
		}
		else {
			if(!preg_match("/[0-9a-z_\.\-]+@[0-9a-z_\.\-]+\.[a-z]{2,4}/i", $email)) {
				$msg .= "<span class='err'>Некорректный Email адресс </span>";
			}
		}

		if(empty($subject)) {
			$msg .= "<span class='err'>Укажите тему письма </span>";
		}

		if(empty($text)) {
			$msg .= "<span class='err'>Введите текс письма </span>";
		}

		if($msg) {
			$_SESSION['send']['subject'] = $subject;
			$_SESSION['send']['text'] = $text;
			return $msg;
		}

		$header = '';
		$header .= "From: ".DOMAIN." \r\n";
		$header .= "Content-Type: text/html; charset=utf8";

		$mail_body = "От:".$email."<br />".$text;

		if (mail($destination,$subject,$mail_body,$header)) {
			return TRUE;
		}
		else {
			return "<span class='err'>Ошибка отправки письма</span>";
		}
	}


	function delete_img($img,$post_id) {
		$sql = "UPDATE ".PREF."post SET img = '' WHERE id='$post_id'";
		$result = mysql_query($sql);

		if(!$result) {
			return mysql_error();
		}

		return TRUE;
	}

	function get_posts($id_categories,$id_categories,$sort,$id_section,$id_town,$start,$num,$search) {
		if($sort == "date-desc") {
			$sort_by = "date";
			$order = "DESC";
			$_SESSION['posts']['sort'] = $sort;
		}
		elseif($sort == "date-asc") {
			$sort_by = "date";
			$order = "ASC";
			$_SESSION['posts']['sort'] = $sort;
		}
		elseif($sort == "price-desc") {
			$sort_by = "price";
			$order = "DESC";
			$_SESSION['posts']['sort'] = $sort;
		}
		elseif($sort == "price-asc") {
			$sort_by = "price";
			$order = "ASC";
			$_SESSION['posts']['sort'] = $sort;
		}
		else {
			$sort_by = "date";
			$order = "DESC";
			$_SESSION['posts']['sort'] = "date-desc";
		}

		if(empty($id_town)) {
			$id_town = "%%";
		}

		if(empty($id_section)) {
			$id_section = "%%";
		}

		if(empty($id_categories)) {
			$id_categories = '%%';
		}
		$sql = "SELECT 	".PREF."post.id,
						".PREF."post.title,
						img,
						text,
						date,
						".PREF."cities.name AS town,
						price,
						confirm,
						is_actual,
						time_over
				FROM 	".PREF."post
				LEFT JOIN ".PREF."cities ON ".PREF."cities.id = ".PREF."post.id_town
				WHERE ".PREF."post.confirm = '1'
				AND ".PREF."post.is_actual = '1'
				AND ".PREF."post.id_categories like '$id_categories'
				AND ".PREF."post.id_town like '$id_town'
				AND ".PREF."post.id_section like '$id_section'
				AND ".PREF."post.text like '%$search%'
				ORDER by ".$sort_by." ".$order." LIMIT ".$start.", ".$num."
						";
		$result = mysql_query($sql);
		return get_result($result);
	}

	function get_no_conf_posts() {
		$sql = "SELECT 	".PREF."post.id,
						".PREF."post.title,
						img,
						text,
						date,
						".PREF."cities.name AS town,
						price,
						confirm,
						is_actual,
						time_over
				FROM 	".PREF."post
				LEFT JOIN ".PREF."cities ON ".PREF."cities.id = ".PREF."post.id_town
				WHERE ".PREF."post.confirm = '0'
				ORDER by date desc
						";
		$result = mysql_query($sql);
		return get_result($result);
	}

	function conf_post($id) {
		$sql = "UPDATE ".PREF."post SET confirm = '1' WHERE id='$id'";

		$result = mysql_query($sql);

		if(!$result) {
			return mysql_error();
		}

		return TRUE;
	}

	function get_users() {
		$sql = "SELECT 	".PREF."users.user_id,
						".PREF."users.login,
						".PREF."users.name,
						".PREF."users.email,
						".PREF."users.phone_number,
						".PREF."role.name AS role
				FROM 	".PREF."users
				LEFT JOIN ".PREF."role ON ".PREF."role.id = ".PREF."users.id_role
				ORDER by ".PREF."users.name DESC
						";
		$result = mysql_query($sql);
		return get_result($result);
	}

	function get_user($id_user) {
		$sql = "SELECT 	".PREF."users.user_id,
						".PREF."users.login,
						".PREF."users.name,
						".PREF."users.email,
						".PREF."users.phone_number,
						".PREF."role.name AS role
				FROM 	".PREF."users
				LEFT JOIN ".PREF."role ON ".PREF."role.id = ".PREF."users.id_role
				WHERE ".PREF."users.user_id = '$id_user'
				ORDER by ".PREF."users.name DESC
						";
		$result = mysql_query($sql);

		$row = get_result($result);

		return $row[0];
	}

	function change_role($post,$user_id) {
		$role = $post['role'];

		$sql = "UPDATE ".PREF."users SET id_role = '$role' WHERE user_id='$user_id'";

		$result = mysql_query($sql);

		if(!$result) {
			return mysql_error();
		}

		return TRUE;
	}

	function get_category($id_category) {
		$sql = "SELECT 	name,parent_id
				FROM 	".PREF."categories
				WHERE id = '$id_category'";
		$result = mysql_query($sql);

		$row = get_result($result);

		return $row[0];
	}

	function edit_category($post,$id_category) {
		$name = $post['name'];
		$parent_id = $post['parent'];

		$sql = "UPDATE ".PREF."categories SET name = '$name', parent_id = '$parent_id' WHERE id='$id_category'";

		$result = mysql_query($sql);

		if(!$result) {
			return mysql_error();
		}

		return TRUE;
	}

	function add_category($post) {
		$name = $post['name'];
		$parent_id = $post['parent'];

		if(!$name) {
			return "<span class='err'>Укажите имя категории</span>";
		}

		$sql = "INSERT INTO ".PREF."categories (name, parent_id) values('$name', '$parent_id')";

		$result = mysql_query($sql);

		if(!$result) {
			return mysql_error();
		}

		return TRUE;
	}
?>