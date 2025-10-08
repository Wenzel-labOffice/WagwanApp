<?php
include '../master/config.php';
session_start();

if (!isset($_SESSION['id'])) {
    echo json_encode([]);
    exit;
}

$current_user = $_SESSION['id'];
$crud = new Crud();
$sql = "
    SELECT sender_id, COUNT(*) as unread_count
    FROM message
    WHERE receiver_id = '$current_user' AND is_read = 0
    GROUP BY sender_id
";

$result = $crud->conn->query($sql);
$unread = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $unread[$row['sender_id']] = $row['unread_count'];
    }
}

echo json_encode($unread);
