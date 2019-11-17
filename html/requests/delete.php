<?php
session_start();
require("../../core/pdo_connect.php");
require("../../parts/login_auth.php");


if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])){
    $id = $_REQUEST['id'];

    $requests = $db->prepare('DELETE FROM requests WHERE id = ?');
    $requests->execute(array($id));
}

header('Location: /requests/index.php');
exit();
?>