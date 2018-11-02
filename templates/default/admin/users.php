<?php if($users) { ?>
	<table class="user-list">
        <tr>
            <th>Имя</th>
            <th>Логин</th>
            <th>э-почта</th>
            <th>Номер телефона</th>
            <th>Статус</th>
        </tr>
        <?php foreach($users as $item) { ?>
            <tr>
                <td><?php echo $item['name']; ?></td>
                <td><?php echo $item['login'] ?></td>
                <td><?php echo $item['email'] ?></td>
                <td><?php echo $item['phone_number'] ?></td>
                <td><a href="/?action=admin&page=change_role&user_id=<?php echo $item['user_id']; ?>"><?php echo $item['role'] ?></a></td>
            </tr>
        <?php } ?>
	</table>
<?php } ?>