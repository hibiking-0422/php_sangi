<?php
session_start();
require("../../core/pdo_connect.php");
require("../../parts/login_auth.php");

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

  header('Location: /requests/index.php');
  exit();
}
?>