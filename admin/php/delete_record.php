<?php
require_once 'db_connection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['student_id'])) {
        echo json_encode(["error" => "Invalid request"]);
        exit;
    }

    $studentID = $_POST['student_id'];

    $stmt = $conn->prepare("DELETE FROM logbook WHERE student_id = ?");
    $stmt->bind_param("s", $studentID);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Failed to delete record."]);
    }

    $stmt->close();
    $conn->close();
}
?>
