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


$query = "INSERT INTO computer_availability (computer_id, student_id, firstName, lastName, grade_level, strand, status)
          VALUES (?, ?, ?, ?, ?, ?, ?)
          ON DUPLICATE KEY UPDATE
          student_id = VALUES(student_id),
          firstName = VALUES(firstName),
          lastName = VALUES(lastName),
          grade_level = VALUES(grade_level),
          strand = VALUES(strand),
          status = VALUES(status)";

$stmt = $conn->prepare($query);
if (!$stmt) {
    error_log("Failed to prepare the SQL statement: " . $conn->error);
    echo json_encode(['success' => false, 'message' => 'Failed to prepare the SQL statement: ' . $conn->error]);
    exit();
}


$stmt->bind_param(
    "issssss",
    $data['computer_id'],
    $data['student_id'],
    $data['firstName'],
    $data['lastName'],
    $data['grade_level'],
    $data['strand'],
    $data['status']
);


if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    error_log("Failed to execute the SQL statement: " . $stmt->error);
    echo json_encode(['success' => false, 'message' => 'Failed to update the database: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>