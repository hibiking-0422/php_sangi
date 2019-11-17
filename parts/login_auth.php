<?php
session_start();
require("../../core/pdo_connect.php");

if(isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()){
    $_SESSION['time'] = time();

    $admins = $db->prepare('SELECT * FROM admin WHERE id = ?');
    $admins->execute(array($_SESSION['id']));
    $admin = $admins->fetch();
}else {
    header('Location: /login/login.php');
    exit();
}
?>
<p><a href = "../book_mana/index.php">本の管理</a>　
<a href="/join/index.php">ユーザ管理</a>　
<a href="/requests/index.php">本のリクエスト</a>
<a href="/login/logout.php">ログアウト</a></p>
<hr>