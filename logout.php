<?php
session_start();
include('master/config.php');
$auth = new Auth();
if (isset($_SESSION['id'])) {
    $userId = $_SESSION['id'];
    $auth->conn->query("UPDATE `register_user` SET `status`='offline' WHERE `regid`=" . $userId);
}
$_SESSION = [];
session_destroy();
header("Location: login.php");
exit();
