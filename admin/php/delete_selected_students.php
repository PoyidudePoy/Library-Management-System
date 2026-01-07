<?php
require_once 'db_connection.php';
session_start(); 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selected_students'])) {
    $selected_students = $_POST['selected_students']; 

    if (!empty($selected_students)) {
        // Convert all selected student IDs to strings
        $selected_students = array_map('strval', $selected_students);

        // Prepare placeholders for SQL query (?, ?, ?)
        $placeholders = implode(',', array_fill(0, count($selected_students), '?'));

        // Prepare delete statement for `your_students`
        $sql = "DELETE FROM your_students WHERE id IN ($placeholders)";
        $stmt = $conn->prepare($sql);

        // Bind parameters dynamically
        $stmt->bind_param(str_repeat("i", count($selected_students)), ...$selected_students);
        $stmt->execute();

        // Check if students were deleted
        if ($stmt->affected_rows > 0) {
            $_SESSION['message'] = "Selected students deleted successfully.";
        } else {
            $_SESSION['error'] = "No students found to delete.";
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "No students selected for deletion.";
    }
} else {
    $_SESSION['error'] = "Invalid request.";
}

// Redirect back to the user page with messages
header("Location: ../user.php");
exit;

$stmt->execute();
if ($stmt->error) {
    $_SESSION['error'] = "Database error: " . $stmt->error;
}
?>
