<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: loginNOW.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
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
            display: center;
            align-items: left;
            justify-content: space-between;
        }

        .header h2 {
            padding: 5px 0;
            text-align: right;
            word-spacing: 2px;
        }

        .sidebar {
            width: 250px;
            background-color: #ffffff;
            color: black;
            height: 100vh;
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            position: fixed;
            left: -250px;
            transition: left 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .sidebar.show {
            left: 0;
        }

        .sidebar .user-profile {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .sidebar .user-profile img {
            border-radius: 50%;
            height: 60px;
            width: 60px;
            margin-right: 15px;
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
            padding: 40px;
            flex: 1;
            transition: margin-left 0.3s;
            display: flex; 
            justify-content: center;
            align-items: flex-start;
            text-align: left;
        }

        .main-content.shift {
            margin-left: 100px;
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
        .image-container {
            text-align: center;
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .image-container img {
            max-width: 30%;
            height: auto;
            border-radius: 10px;
        }

      
        
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
                    <li><i class="fas fa-wifi"></i><a href="wifi2.php">WiFi Connection</a></li>
                    <li><i class="fas fa-desktop"></i><a href="computer.php">Computer Availability</a></li>
                    <li><i class="fas fa-sign-out-alt"></i><a href="logout.php">Logout</a></li>        </ul>
    </div>
    <div class="image-container">
        <img id="centeredImage" src="images/qr_code.jpg" alt="Centered Image">
        <p class="wifi-details"><strong>WiFi Name:</strong> Library WiFi</p>
        <p class="wifi-details"><strong>Password:</strong> library_octlearningcommons</p>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            sidebar.classList.toggle('show');
            mainContent.classList.toggle('shift');
        }

        // Close the sidebar if the user clicks outside of it
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.querySelector('.toggle-btn');
            const mainContent = document.getElementById('mainContent');

            if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                sidebar.classList.remove('show');
                mainContent.classList.remove('shift');
            }
        });
    </script>
</body>
</html>
