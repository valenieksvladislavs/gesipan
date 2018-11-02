<?php
header("Content-Type:text/html;charset=UTF-8");

session_start();

require_once "config.php";
require_once "functions.php";

db(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);

actual();

$town = get_town();
$categories = get_categories();
$section = get_section();
$user = check_user();
$role = get_role();

if($user) {
	$add_post = can($user['id_role'],array("ADD_POST"));
	$moderation = can($user['id_role'],array("MODERATION"));
	$delete_post = can($user['id_role'],array("DELETE_POST"));
	$retime_post = can($user['id_role'],array("RETIME_POST"));
	$edit_post = can($user['id_role'],array("EDIT_POST"));
	$add_category = can($user['id_role'],array("ADD_CATEGORY"));
	$admin = can($user['id_role'],array("ADMIN"));
}

$action = clear_str($_GET['action']);
if(!$action) {
	$action = "main";
}

if(file_exists(ACTIONS.$action.".php")) {
	include ACTIONS.$action.".php";
}
else {
	$action = "main";
}

require_once TEMPLATE."index.php";
?>