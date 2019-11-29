<link rel="stylesheet" type="text/css" href="/assets/css/children/request.css"/>
<?php
session_start();
require("../../../core/pdo_connect.php");

$requests = $db->query('SELECT * FROM requests');
?>
<p><a href="../index.php">いちらんにもどる</a>
<a href="new.php">あたらしいリクエスト</a></p>


<table border="1">
    <tr>
        <th>リクエスト</th>
        <th>さくせいび</th>
    </tr>

<?php
foreach($requests as $request):
?>
    <tr>
        <td><?php echo htmlspecialchars($request['request'], 3); ?></td>
        <td><?php echo htmlspecialchars($request['created'], 3); ?></td>
    </tr>
<?php
endforeach;
?>

</table>