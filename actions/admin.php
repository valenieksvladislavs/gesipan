<?php
if(!$user) {
    $_SESSION['msg'] = "Авторизируйтесь, чтобы получить доступ к админ панели.";
    header("Location:".SITE_NAME."/?action=login");
    exit();
}
elseif(!$admin) {
    $_SESSION['msg'] = "У вас нет прав доступа к админ панели.";
    header("Location:".SITE_NAME."/?action=main");
    exit();
}
else {
    $page = clear_str($_GET['page']);
    if(!$page) {
        $page = "no-conf-posts";
    }

    if(file_exists(ADMIN.$page.".php")) {
        include ADMIN.$page.".php";
    }
    else {
        $page = "no-conf-posts";
    }
}
?>