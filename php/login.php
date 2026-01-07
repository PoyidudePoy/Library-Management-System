<?php
session_start();
include('db_connection.php');  

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
error_log(print_r($data, true));

$octEmail = $data['octEmail'] ?? '';  
$password = $data['password'] ?? '';  

if (empty($octEmail) || empty($password)) {
    error_log("Missing email or password: Email: $octEmail, Password: $password");
    echo json_encode(["success" => false, "message" => "Email and Password are required."]);
    exit();
}

$sql = "SELECT student_id, firstName, lastName, octEmail, email_password, grade_level, strand FROM your_students WHERE octEmail = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $octEmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['email_password'])) {  
        $_SESSION['student_id'] = $user['student_id']; 
        $_SESSION['firstName'] = $user['firstName'];
        $_SESSION['lastName'] = $user['lastName'];
        $_SESSION['octEmail'] = $user['octEmail'];
        $_SESSION['grade_level'] = $user['grade_level']; 
        $_SESSION['strand'] = $user['strand'];

        echo json_encode(["success" => true, "message" => "Login successful"]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid password."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid OCT Email."]);
}

$stmt->close();
$conn->close();
?>