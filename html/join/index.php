<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">

<!--外部ファイル読み込み-->
<link rel="stylesheet" type="text/css" href="/assets/css/slick.css"/>
<link rel="stylesheet" type="text/css" href="/assets/css/slick-theme.css"/>
<link rel="stylesheet" type="text/css" href="/assets/css/join/index.css"/>
<script type="text/javascript" src="/assets/js/jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="/assets/js/slick.min.js"></script>
<!---->

<?php
session_start();
require("../../core/pdo_connect.php");
require("../../parts/login_auth.php");

$admins = $db->query('SELECT * FROM admin');
?>
</head>

<div class="table-box">
<a href="/join/new.php"><span class="new-join">管理者新規作成</span></a>
<table border="1">
    <tr>
        <th>職員番号</th>
        <th>作成日</th>
    </tr>
<?php
foreach($admins as $admin):
?>
    <tr>
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
</div>


