<?php
session_start();
require("../../core/pdo_connect.php");
require("../../parts/login_auth.php");

if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])){
    $id = $_REQUEST['id'];

    $admins = $db->prepare('SELECT * FROM admin WHERE id = ?');
    $admins->execute(array($id));
    $admin  = $admins->fetch();
}

$books_sql = 'SELECT * FROM books WHERE admin_id =' . $id;
$books = $db->query($books_sql);

$edit_log_sql = 'SELECT * FROM edit_log WHERE t_id=' . $id;
$edit_logs = $db->query($edit_log_sql);

$delete_log_sql = 'SELECT * FROM delete_log WHERE admin_id=' . $id;
$delete_logs = $db->query($delete_log_sql);
?>


<p>登録履歴</P>
<table border="1">
<?php
foreach ($books as $book):
?>
<tr>
    <td><?php echo htmlspecialchars($book['book_id'], 3); ?></td>
    <td><a href="/book_mana/show.php?id=<?php print($book['id']); ?>"><?php echo htmlspecialchars($book['book_name'], 3); ?></a></td>　
    <td><?php echo htmlspecialchars($book['created'], 3); ?></td>
</tr>
<?php
endforeach;
?>
</table>
<br>

<p>編集履歴</P>
<table border="1">
<?php
foreach ($edit_logs as $edit_log):
$b_id_sql = 'SELECT * FROM books WHERE id=' .  $edit_log['b_id'];
$b_ids = $db->query($b_id_sql);
$b_id = $b_ids->fetch();
?>
<tr>
    <td><?php echo htmlspecialchars($b_id['book_id'], 3); ?></td>　
    <td><a href="/book_mana/show.php?id=<?php print($b_id['id']); ?>"><?php echo htmlspecialchars($b_id['book_name'], 3); ?></a></td>
    <td><?php echo htmlspecialchars($edit_log['created'], 3); ?></td>
</tr>
<?php
endforeach;
?>
</table>
<br>
<p>削除履歴</p>

<table border="1">
<?php
foreach($delete_logs as $delete_log):
?>
<tr>
    <td><?php echo htmlspecialchars($delete_log['book_id'], 3); ?></td>　
    <td><?php echo htmlspecialchars($delete_log['book_name'], 3); ?><?php echo htmlspecialchars($delete_log['created'], 3); ?></td>
</tr>
<?php
endforeach;
?>
</table>

<br>
<br>
<br>