<?php
session_start();
require("../../core/pdo_connect.php");
require("../../parts/login_auth.php");

$admins = $db->query('SELECT * FROM admin');
?>
<p><a href="/join/new.php">新規作成</a></p>


<table border="1">
    <tr>
        <th>id</th>
        <th>職員番号</th>
        <th>作成日</th>
    </tr>
<?php
foreach($admins as $admin):
?>
    <tr>
        <td><?php echo htmlspecialchars($admin['id'], 3); ?></td>
        <td><?php echo htmlspecialchars($admin['teacher_id'], 3); ?></td>
        <td><?php print(mb_substr($admin['created'],0, 10)); ?></td>
        <td><a href="edit.php?id=<?php print($admin['id']); ?>">編集</a></td>
        <td><a href="delete.php?id=<?php print($admin['id']); ?>">削除</a></td>
        <td><a href="../admin_log/index.php?id=<?php print($admin['id']); ?>">履歴</a></td>
    </tr>
<?php
endforeach;
?>
</table>


