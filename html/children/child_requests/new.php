<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">

<!--外部ファイル読み込み-->
<link rel="stylesheet" type="text/css" href="/assets/css/slick.css"/>
<link rel="stylesheet" type="text/css" href="/assets/css/slick-theme.css"/>
<link rel="stylesheet" type="text/css" href="/assets/css/children/children_request/new.css"/>
<!---->

<?php
session_start();
require("../../../core/pdo_connect.php");

if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])){
    $id = $_REQUEST['id'];
}

if(!empty($_POST)){

    //空白チェック
    if($_POST['request'] == ''){
        $error['request'] = 'blank';
    }
    
    //入力に空白が無ければ実行
    if(empty($error)){
        $_SESSION['requests'] = $_POST;
        header('Location: create.php');
        exit(); 
    }
}

?>

</head>

<div class="form-box">
<form action="" method="post" >

        <p><h1>ほしいほんのなまえをいれてね</h1></p>
        <p><textarea class="form-font" name="request" cols="30" rows="1"></textarea>
        <?php if($error['request'] == 'blank'): ?>
        <p>*ほんのなまえをいれてね</P>
        <?php endif; ?>
        </p>
    <div><input class="submit-button" type="submit" value="送信"></div>
    <a href="../index.php"><div class="back-button">もどる</div></a>
</form>