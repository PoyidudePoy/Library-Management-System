<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: loginNOW.php");
    exit();
}


$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "students_db"; 


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$student_id = $_SESSION['student_id'];
$sql = "SELECT firstName, lastName, grade_level, strand, octEmail FROM your_students WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();


if (!$userData) {
    echo "<script>alert('User not found in database.');</script>";
    exit();
}

$firstName = htmlspecialchars($userData['firstName']);
$lastName = htmlspecialchars($userData['lastName']);
$gradeLevel = htmlspecialchars($userData['grade_level']);
$strand = htmlspecialchars($userData['strand']);
$email = htmlspecialchars($userData['octEmail']);
$userInitials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));


$sql_books = "SELECT book_name, borrow_date, status FROM book_borrowals WHERE student_id = ?";
$stmt_books = $conn->prepare($sql_books);
$stmt_books->bind_param("s", $student_id);
$stmt_books->execute();
$result_books = $stmt_books->get_result();

$stmt->close();
$stmt_books->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
        }

        header {
            background-color: #159603;
            color: white;
            padding: 10px 15px;
        }

        .header h2 {
            padding: 5px 0;
            text-align: right;
            word-spacing: 2px;
        }

        .sidebar {
            width: 250px;
            max-width: 80%;
            background-color: #ffffff;
            color: black;
            height: 100vh;
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            position: fixed;
            left: -250px;
            transition: left 0.3s ease-in-out;
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 1000; /* Ensures sidebar stays on top */
        }

        .sidebar.show {
            left: 0;
        }


        .sidebar .user-profile img {
            border-radius: 50%;
            height: 60px;
            width: 60px;
            margin-bottom: 10px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            width: 100%;
        }

        .sidebar ul li {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid #cccccc;
            cursor: pointer;
        }

        .sidebar ul li:hover {
            background-color: #f0f0f0;
        }

        .sidebar ul li a {
            color: black;
            text-decoration: none;
            flex-grow: 1;
            padding-left: 10px;
        }

        .sidebar ul li i {
            font-size: 18px;
            color: #159603;
        }

        .main-content {
            margin-left: 0;
            padding: 20px;
            flex: 1;
            transition: margin-left 0.3s ease-in-out;
            display: flex;
            flex-direction: column;
            gap: 20px;
            align-items: center;
        }

        .main-content.shift {
            margin-left: 250px;
        }

        .toggle-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: #159603;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 5px;
        }

        .toggle-btn:hover {
            background-color: #128b02;
        }

        .profile-section {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            width: 600px;
        }

        .profile-section .profile-picture {
            flex-shrink: 0;
            margin-right: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            height: 120px;
            width: 120px;
            background-color: #159603;
            color: white;
            font-size: 48px;
            font-weight: bold;
        }

        .profile-section .profile-details h2 {
            margin: 0 0 10px;
            color: #159603;
        }

        .book-request-section {
            background: linear-gradient(135deg, #e6f7e6, #f9f9f9);
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            width: 600px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .book-request-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3);
        }

        .book-request-section h3 {
            margin-bottom: 10px;
            font-size: 24px;
            color: #159603;
            border-bottom: 2px solid #159603;
            padding-bottom: 5px;
        }

        .book-request-section table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .book-request-section table th,
        .book-request-section table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .book-request-section table th {
            background-color: #159603;
            color: white;
        }

        .book-request-section table tr:hover {
            background-color: #f2f2f2;
        }

        .book-request-section table td {
            background-color: white;
        }

        .book-request-section table td[colspan="4"] {
            text-align: center;
            color: #888;
        }

        .book-request-section table td:first-child {
            font-weight: bold;
            color: #159603;
        }
        @media (max-width: 1024px) {
    .sidebar {
        width: 200px;
        left: -200px;
    }

    .sidebar.show {
        left: 0;
    }

    .main-content.shift {
        margin-left: 200px;
    }

    .toggle-btn {
        font-size: 16px;
        padding: 8px 12px;
    }

    .profile-section {
        width: 90%;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .profile-section .profile-picture {
        width: 80px;
        height: 80px;
        font-size: 28px;
    }
}

@media (max-width: 768px) {
    .sidebar {
        width: 180px;
        left: -180px;
    }

    .sidebar .user-profile img {
        width: 50px;
        height: 50px;
    }

    .sidebar ul li {
        font-size: 14px;
        padding: 10px;
    }

    .main-content.shift {
        margin-left: 180px;
    }

    .profile-section {
        padding: 15px;
    }

    .profile-section .profile-picture {
        width: 70px;
        height: 70px;
        font-size: 24px;
    }

    .book-request-section {
        padding: 15px;
    }

    .book-request-section table th, 
    .book-request-section table td {
        padding: 8px;
    }
}
@media (max-width: 600px) {
    .sidebar {
        width: 200px; /* Smaller sidebar for mobile */
        left: -200px;
    }
    .sidebar.show {
        left: 0;
    }
}

@media (max-width: 600px) {
    .main-content.shift {
        margin-left: 200px; /* Adjusted for smaller sidebar */
    }
}
@media (max-width: 480px) {
    .sidebar {
        width: 100%;
        left: -100%;
    }

    .sidebar.show {
        left: 0;
    }

    .main-content.shift {
        margin-left: 0;
    }

    .toggle-btn {
        top: 15px;
        left: 15px;
        font-size: 14px;
        padding: 6px 10px;
    }

    .profile-section {
        width: 100%;
    }

    .book-request-section {
        width: 100%;
    }

    .book-request-section table {
        font-size: 12px;
    }
}
    </style>
</head>
<body>
    <header>
        <div class="header">
            <h2>Library Management System</h2>
        </div>
    </header>

    <button class="toggle-btn" onclick="toggleSidebar()">☰</button>

    <div class="sidebar" id="sidebar">
        <div class="user-profile">
            <img src="images/OCTlogo.png" alt="User Profile">
        </div>
        <ul>
            <li><i class="fas fa-user"></i><a href="userfinal.php">User</a></li>
            <li><i class="fas fa-book"></i><a href="homepagefinall.php">Home</a></li>
            <li><i class="fas fa-book-reader"></i><a href="book.php">Borrowing of Books</a></li>
            <li><i class="fas fa-desktop"></i><a href="computer.php">Computer Availability</a></li>
            <li><i class="fas fa-sign-out-alt"></i><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content" id="mainContent">
        <div class="profile-section">
            <div class="profile-picture"><?= $userInitials ?></div>
            <div class="profile-details">
                <h2><?= $firstName . ' ' . $lastName ?></h2>
                <p><strong>Student ID:</strong> <?= $student_id ?></p> 
                <p><strong>Grade:</strong> <?= $gradeLevel ?></p>
                <p><strong>Strand:</strong> <?= $strand ?></p>
                <p><strong>Email:</strong> <?= $email ?></p> 
                <p><strong>Barcode:</strong> 
                <?php 
                    $barcodePath = "barcodes/" . $student_id . ".png";
                    if (file_exists($barcodePath)) {
                        echo '<img src="'.$barcodePath.'" alt="Student Barcode" style="width:150px; height:auto; margin-left:10px; vertical-align:middle; border:1px solid #ccc; padding:3px;">';
                        echo ' <a href="'.$barcodePath.'" download class="barcode-download" style="margin-left:10px; color:#159603; text-decoration:none; font-weight:bold;">
                                <i class="fas fa-download"></i> Download</a>';
                    } else {
                        echo '<span style="color:red;">Barcode not available</span>';
                    }
                ?>
            </p>
            </div>
        </div>

        <div class="book-request-section">
            <h3>Your Borrowed Books</h3>
            <table>
                <thead>
                    <tr>
                        <th>Student Name</th> 
                        <th>Book Name</th>
                        <th>Borrow Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($book = $result_books->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($firstName) . ' ' . htmlspecialchars($lastName) ?></td>
                            <td><?= htmlspecialchars($book['book_name']) ?></td>
                            <td><?= htmlspecialchars($book['borrow_date']) ?></td>
                            <td><?= htmlspecialchars($book['status']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                    <?php if ($result_books->num_rows == 0): ?>
                        <tr><td colspan="4">No borrowed books yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
      function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    
    // Toggle the sidebar visibility
    sidebar.classList.toggle('show');
    mainContent.classList.toggle('shift');
}

// Close the sidebar when clicking outside of it
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.querySelector('.toggle-btn');
    const mainContent = document.getElementById('mainContent');

    if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
        sidebar.classList.remove('show');
        mainContent.classList.remove('shift');
    }
});

// Prevent immediate closure when clicking the toggle button
document.querySelector('.toggle-btn').addEventListener('click', function(event) {
    event.stopPropagation();
});
    </script>
</body>
</html>