<?php
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST["student_id"];
    $column_index = $_POST["column_index"];
    $new_value = trim($_POST["new_value"]);

   
    $columns = ["student_id", "firstName", "lastName", "octEmail", "grade_level", "strand"];
    $column_name = $columns[$column_index - 1]; 

    
    $sql = "UPDATE your_students SET $column_name = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_value, $student_id);

    if ($stmt->execute()) {
        echo "Changes saved successfully!";
    } else {
        echo "Error updating record!";
    }

    $stmt->close();
    $conn->close();
}
?>