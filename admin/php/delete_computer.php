<?php
$host = "localhost";
$username = "root"; 
$password = ""; 
$database = "students_db"; 

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $computerId = $data["computer_id"];

    $query = "DELETE FROM computer_availability WHERE computer_id =?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $computerId);
    if ($stmt->execute()) {
        $response = array("success" => true, "message" => "Computer state updated successfully.");
    } else {
        $response = array("success" => false, "message" => "Failed to update computer state: ". $conn->error);
    }
    $stmt->close();
    $conn->close();
    echo json_encode($response);
} else {
    $response = array("success" => false, "message" => "Invalid request method.");
    echo json_encode($response);
}
$stmt->execute();
if ($stmt->error) {
    $_SESSION['error'] = "Database error: " . $stmt->error;
}
?>