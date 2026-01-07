<?php
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['status'])) {
    $bookId = intval($_POST['id']);
    $newStatus = $_POST['status'];

    $sql = "UPDATE book_borrowals SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $newStatus, $bookId);

    $response = ["success" => false];

    if ($stmt->execute()) {
        $response["success"] = true;
    } else {
        $response["error"] = $stmt->error;
    }

    $stmt->close();
    $conn->close();

    echo json_encode($response);
} else {
    echo json_encode(["success" => false, "error" => "Invalid request"]);
}
?>