<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_GET['id'];
    $announcement_text = $_POST['announcement_text'];

    
    $stmt = $conn->prepare("UPDATE announcements SET announcement_text = ? WHERE id = ?");
    $stmt->bind_param("si", $announcement_text, $id);
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>