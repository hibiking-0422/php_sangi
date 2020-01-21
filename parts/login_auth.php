<?php
session_start();
require("../../core/pdo_connect.php");
require("header.php");

if(isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()){
    $_SESSION['time'] = time();

    $admins = $db->prepare('SELECT * FROM admin WHERE id = ?');
    $admins->execute(array($_SESSION['id']));
    $admin = $admins->fetch();
}else {
    header('Location: /index.php');
    exit();
}
?>