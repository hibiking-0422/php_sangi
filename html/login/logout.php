<?php
session_start();
require("../../parts/login_auth.php");
$_SESSION = array();
session_destroy();

header("Location: logout.php");
exit();
?>