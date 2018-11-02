<div class="admin-menu">
    <ul>
        <li class="icon-users"><a href="/?action=admin&page=users" class="<?php if($page == "users") {echo "current";} ?>">Пользователи</a></li>
        <li class="icon-categories"><a href="/?action=admin&page=categories" class="<?php if($page == "categories") {echo "current";} ?>">Категории</a></li>
        <li class="icon-posts"><a href="/?action=admin&page=no-conf-posts" class="<?php if($page == "no-conf-posts") {echo "current";} ?>">Не подтвержденные объявления</a></li>
    </ul>
</div>