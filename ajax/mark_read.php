<?php
include '../master/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sender_id'])) {
    $current_user = $_SESSION['id'];
    $sender_id = $_POST['sender_id'];
    $sql = "UPDATE message SET is_read = 1 WHERE sender_id = '$sender_id' AND receiver_id = '$current_user' AND is_read = 0";
    $crud = new Crud();
    $result = $crud->conn->query($sql);

    echo $result ? "success" : "error";
}
