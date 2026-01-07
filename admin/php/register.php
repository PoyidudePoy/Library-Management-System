<?php

require_once 'db_connection.php'; 


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $name = $_POST['name'];                
    $contact_number = $_POST['contact_number']; 
    $email = $_POST['email_acc'];            
    $password = $_POST['email_password'];    

   
    if (empty($name) || empty($contact_number) || empty($email) || empty($password)) {
        echo "Please fill in all fields.";
        exit;
    }

    
    $stmt = $conn->prepare("SELECT * FROM admins WHERE email_acc = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "This email is already registered.";
        exit;
    }

    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    
    $insert_stmt = $conn->prepare("INSERT INTO admins (name, contact_number, email_acc, email_password) VALUES (?, ?, ?, ?)");
    $insert_stmt->bind_param("ssss", $name, $contact_number, $email, $hashed_password);

    if ($insert_stmt->execute()) {
       
        header("Location: ../login.php"); 
        exit;
    } else {
        echo "Error creating account. Please try again.";
    }

    
    $stmt->close();
    $insert_stmt->close();
    $conn->close();
} else {
   
    header("Location: register.php"); 
    exit;
}
?>