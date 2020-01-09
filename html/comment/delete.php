<?php
session_start();
require("../../core/pdo_connect.php");
require("../../parts/login_auth.php");


if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])){
    $id = $_REQUEST['id'];

    $admins = $db->prepare('DELETE FROM comments WHERE id = ?');
    $admins->execute(array($id));
    $admin  = $admins->fetch();
}
$book_id = $_SESSION['book_id'];
unset($_SESSION['book_id']);

header('Location: /book_mana/show.php?id='.$book_id);
exit();
?>