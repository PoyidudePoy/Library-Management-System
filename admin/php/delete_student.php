<?php
require_once 'db_connection.php';
session_start(); 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['student_id'])) {
    $student_id = $_POST['student_id']; 

    if (!empty($student_id)) {
        // Prepare delete statement (CASCADE will delete borrow records automatically)
        $sql = "DELETE FROM your_students WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $student_id); // Use "i" for integer
        $stmt->execute();

        // Check if student was deleted
        if ($stmt->affected_rows > 0) {
            $_SESSION['message'] = "Student deleted successfully.";
        } else {
            $_SESSION['error'] = "Student not found.";
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "No student selected for deletion.";
    }
} else {
    $_SESSION['error'] = "Invalid request.";
}

// Redirect back to the user page with messages
header("Location: ../user.php");
exit;
?>
