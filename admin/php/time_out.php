<?php
session_start();
require_once 'db_connection.php';

// Set the timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'])) {
    $student_id = trim($_POST['student_id']);

    // Check database connection
    if (!$conn) {
        echo json_encode(["error" => "Database connection failed"]);
        exit;
    }

    // Get the current time in the Philippines timezone
    $time_out = date("Y-m-d H:i:s"); // Current time in 24-hour format

    // Update time_out in the logbook
    $query = "UPDATE logbook SET time_out = ? WHERE student_id = ? AND time_out IS NULL";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $time_out, $student_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "time_out" => $time_out]);
    } else {
        echo json_encode(["error" => "No matching record found or already timed out"]);
    }
} else {
    echo json_encode(["error" => "Invalid request"]);
}
?>