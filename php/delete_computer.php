<?php
include('db_connection.php');
session_start();
if (!isset($_SESSION['student_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}


$host = "localhost";
$username = "root"; 
$password = ""; 
$database = "students_db"; 

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}


$data = json_decode(file_get_contents('php://input'), true);
$computer_id = $data['computer_id'];


$query = "DELETE FROM computer_availability WHERE computer_id = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    error_log("Failed to prepare DELETE statement: " . $conn->error);
    echo json_encode(['success' => false, 'message' => 'Failed to prepare SQL statement']);
    exit();
}

$stmt->bind_param("i", $computer_id);


if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    error_log("Failed to execute DELETE statement: " . $stmt->error);
    echo json_encode(['success' => false, 'message' => 'Failed to delete from database']);
}

$stmt->close();
$conn->close();
?>