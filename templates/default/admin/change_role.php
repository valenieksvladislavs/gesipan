<form method="post">
    <table>
        <tr>
            <th>Имя:</th>
            <td><?php echo $user1['name']; ?></td>
        </tr>
        <tr>
            <th>Логин:</th>
            <td><?php echo $user1['login']; ?></td>
        </tr>
        <tr>
            <th>э-почта:</th>
            <td><?php echo $user1['email']; ?></td>
        </tr>
        <tr>
            <th>Номер телефона:</th>
            <td><?php echo $user1['phone_number']; ?></td>
        </tr>
        <tr>
            <th><label for="role">Статус:</label></th>
            <td>
                <select name="role" id="role">
                    <?php if($role) { ?>
                        <?php foreach ($role as $item) { ?>
                            <option value="<?php echo $item[id]; ?>" <?php if($user1['role'] == $item['name']) {echo "selected";} ?>><?php echo $item['name']; ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </td>
        </tr>
    </table>

    <input type="submit" value="Сохранить" />

</form>