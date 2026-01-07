<?php
session_start();  
require_once __DIR__ . '/../vendor/autoload.php'; 

use Picqer\Barcode\BarcodeGeneratorPNG;


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "students_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}


$student_id  = isset($_POST['student_id'])  ? trim($_POST['student_id'])  : '';
$firstName   = isset($_POST['firstName'])   ? trim($_POST['firstName'])   : '';
$lastName    = isset($_POST['lastName'])    ? trim($_POST['lastName'])    : '';
$octEmail    = isset($_POST['octEmail'])    ? trim($_POST['octEmail'])    : '';
$email_password = isset($_POST['email_password']) ? trim($_POST['email_password']) : ''; 
$grade_level = isset($_POST['grade_level']) ? trim($_POST['grade_level']) : '';
$strand      = isset($_POST['strand'])      ? trim($_POST['strand'])      : '';


if (empty($student_id) || empty($firstName) || empty($lastName) || empty($octEmail) || empty($email_password) || empty($grade_level) || empty($strand)) {
    die("❌ Error: All fields are required!");
}


$check_sql = $conn->prepare("SELECT student_id FROM your_students WHERE student_id = ?");
$check_sql->bind_param("s", $student_id);
$check_sql->execute();
$result = $check_sql->get_result();

if ($result->num_rows > 0) {
    die("❌ Error: A student with this ID already exists. Please use a different ID.");
}


$hashed_password = password_hash($email_password, PASSWORD_BCRYPT);


$insert_sql = $conn->prepare("INSERT INTO your_students (student_id, firstName, lastName, octEmail, email_password, grade_level, strand) 
                              VALUES (?, ?, ?, ?, ?, ?, ?)");
$insert_sql->bind_param("sssssis", $student_id, $firstName, $lastName, $octEmail, $hashed_password, $grade_level, $strand);

if ($insert_sql->execute()) {
   
    $generator = new BarcodeGeneratorPNG();
    $barcode = $generator->getBarcode($student_id, BarcodeGeneratorPNG::TYPE_CODE_128);
    
    
    $barcode_dir = __DIR__ . '/../barcodes/';  
    if (!is_dir($barcode_dir) && !mkdir($barcode_dir, 0777, true)) {
        die("❌ Error: Failed to create barcode directory.");
    }

    $barcode_file = $barcode_dir . $student_id . ".png";
    file_put_contents($barcode_file, $barcode);

    $relative_barcode_path = '/lms_project/barcodes/' . $student_id . '.png';  

  
    echo "
    <html>
        <head>
            <style>
                body, html {
                    margin: 0;
                    padding: 0;
                    height: 100%;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    background-color: #f4f4f9;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                }

                .container {
                    text-align: center;
                    padding: 20px;
                    border-radius: 10px;
                    background: #fff;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                    width: 100%;
                    max-width: 500px;
                }

                img {
                    display: block;
                    margin: 0 auto;
                    max-width: 100%;
                    height: auto;
                    background-color: white;
                    padding: 5px;
                    border: 1px solid #ccc;
                }

                a {
                    display: inline-block;
                    padding: 10px 20px;
                    background-color: rgb(24, 202, 33);
                    color: white;
                    text-decoration: none;
                    border-radius: 5px;
                    font-size: 16px;
                    margin-top: 20px;
                }

                a:hover {
                    background-color: rgb(15, 101, 13);
                }

                h2 {
                    font-size: 24px;
                    color: #333;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2>✅ Account created successfully!</h2>
                <p>Your account has been registered. Please use your EMAIL and PASSWORD to log in. <br>
                the barcode you see is used to scan when you are inside the library</p>
                <img src='" . htmlspecialchars($relative_barcode_path) . "' alt='Barcode'>
                <a href='" . htmlspecialchars($relative_barcode_path) . "' download>Download Barcode</a>
                <br>
                <a href='../loginNOW.php'>Go to Login</a>
            </div>
        </body>
    </html>
    ";
} else {
    die("❌ Error: " . $insert_sql->error);
}

$conn->close();
?>
