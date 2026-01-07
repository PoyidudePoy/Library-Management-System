<?php
date_default_timezone_set('Asia/Manila');
require_once 'db_connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'])) {
    $student_id = trim($_POST['student_id']);
    
    // Check database connection
    if (!$conn) {
        echo json_encode(["error" => "Database connection failed"]);
        exit;
    }

    // Fetch student details
    $query = "SELECT student_id, firstName, lastName, octEmail, grade_level, strand FROM your_students WHERE student_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if ($student) {
        // If only student_id is provided, just verify it exists
        if (!isset($_POST['purpose'])) {
            echo json_encode(["success" => true]);
            exit;
        }

        // Insert into logbook with timestamp in 24-hour format
        $purpose = trim($_POST['purpose']);
        $time_in = date("Y-m-d H:i:s"); // Use 24-hour format for database storage

        $insertQuery = "INSERT INTO logbook (student_id, purpose, time_in) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("sss", $student_id, $purpose, $time_in);
        $stmt->execute();

        // Format time_in for display in 12-hour format with AM/PM
        $time_in_display = date("Y-m-d h:i A", strtotime($time_in)); // Convert to 12-hour format

        // Append purpose & time_in to student data
        $student['purpose'] = $purpose;
        $student['time_in'] = $time_in_display; // This is in 12-hour format with AM/PM
        $student['time_out'] = null;

        echo json_encode(["success" => true, "student" => $student]);
    } else {
        echo json_encode(["error" => "Student not found"]);
    }
} else {
    echo json_encode(["error" => "Invalid request"]);
}
?>