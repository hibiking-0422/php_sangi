<?php
session_start();
require("../../../core/pdo_connect.php");

if (!isset($_SESSION['requests'])){
  header('Location: new.php');
  exit();
}

if(!empty($_SESSION['requests'])){
  $statement = $db->prepare('INSERT INTO requests SET request=?, created=NOW()');
  $statement->execute(array(
    $_SESSION['requests']['request']
  ));
  unset($_SESSION['requests']);
  $_SESSION['comp'] = "リクエスト完了!";
  
  header('Location: ../index.php');
  exit();
}
?>