<?php
require_once "includes/init.php";
session_unset();
session_destroy();
if (isset($_COOKIE['username'])) {
    setcookie("username", "", time() - 3600, "/", "", false, true); 
}
header("Location: index.php");
exit();
?>