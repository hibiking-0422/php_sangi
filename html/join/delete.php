<?php
session_start();
require("../../core/pdo_connect.php");
require("../../parts/login_auth.php");


if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])){
    $id = $_REQUEST['id'];

    $admins = $db->prepare('DELETE FROM admin WHERE id = ?');
    $admins->execute(array($id));
    $admin  = $admins->fetch();
}

$edit_logs = $db->prepare('DELETE FROM edit_log WHERE t_id = ?');
$edit_logs->execute(array($id));

header('Location: /join/index.php');
exit();
?>