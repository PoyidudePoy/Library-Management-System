<?php
session_start();

// If the request is valid, mark the student as scanned in
if (isset($_POST['scanned']) && $_POST['scanned'] === "true") {
    $_SESSION['scanned_in'] = true;
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "Invalid request."]);
}
?>