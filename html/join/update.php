<?php
session_start();
require("../../core/pdo_connect.php");
require("../../parts/login_auth.php");


$statement = $db->prepare('UPDATE admin SET teacher_id = ?,password = ?, updated = NOW() WHERE id = ?');
$statement->execute(array(
    $_SESSION['update']['teacher_id'],
    sha1($_SESSION['update']['password']),
    $_SESSION['update']['id']

));
header('Location: /join/index.php');
exit();
?>
