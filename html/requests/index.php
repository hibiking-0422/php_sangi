<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<link href="/assets/css/requests/index.css" rel="stylesheet" type="text/css">

<?php
session_start();
require("../../core/pdo_connect.php");
require("../../parts/login_auth.php");

$requests = $db->query('SELECT * FROM requests');
?>
</head>

<table border="1">
    <tr>
        <th>リクエスト</th>
        <th>作成日</th>
    </tr>

<?php
foreach($requests as $request):
?>
    <tr>
        <td><?php echo htmlspecialchars($request['request'], 3); ?></td>
        <td><?php echo htmlspecialchars($request['created'], 3); ?></td>
        <td><a href="delete.php?id=<?php print($request['id']); ?>">削除</a></td>
    </tr>
<?php
endforeach;
?>

</table>