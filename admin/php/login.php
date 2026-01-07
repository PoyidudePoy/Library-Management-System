<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email_acc'];
    $password = $_POST['email_password'];

    if (empty($email) || empty($password)) {
        echo "Please fill in all fields.";
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM admins WHERE email_acc = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "No account found with that email.";
        exit;
    }

    $user = $result->fetch_assoc();

    if (password_verify($password, $user['email_password'])) {
        $_SESSION['admin_id'] = $user['admin_id'];
        $_SESSION['email_acc'] = $user['email_acc'];
        echo "success";
        exit;
    } else {
        echo "Incorrect password.";
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ./login.php");
    exit;
}
?>