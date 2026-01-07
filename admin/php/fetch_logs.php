<?php
date_default_timezone_set('Asia/Manila');

require_once 'db_connection.php';

header('Content-Type: application/json');

if (!$conn) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

$query = "
    SELECT 
        l.student_id, 
        s.firstName, 
        s.lastName, 
        s.octEmail, 
        s.grade_level, 
        s.strand, 
        l.purpose, 
        DATE_FORMAT(l.time_in, '%Y-%m-%d %H:%i') AS time_in, 
        DATE_FORMAT(l.time_out, '%Y-%m-%d %H:%i') AS time_out
    FROM logbook l 
    JOIN your_students s ON l.student_id = s.student_id 
    WHERE l.time_out IS NULL  -- Only fetch students who have NOT timed out
    ORDER BY l.time_in DESC";

$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(["error" => "Query failed: " . mysqli_error($conn)]);
    exit;
}

$logbook_entries = [];
while ($row = mysqli_fetch_assoc($result)) {
    $logbook_entries[] = $row;
}

echo json_encode($logbook_entries);
?>
