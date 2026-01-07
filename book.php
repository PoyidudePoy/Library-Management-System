<?php
session_start();
include('php/db_connection.php');


if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");  
    exit();
}


$student_id = $_SESSION['student_id'];
$firstName = $_SESSION['firstName'];
$lastName = $_SESSION['lastName'];
$gradeLevel = $_SESSION['grade_level'];
$strand = $_SESSION['strand'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookName = $_POST['bookName'];
    $borrowDate = $_POST['borrowDate'];
    $status = 'Using';

  
    $sql = "INSERT INTO book_borrowals (student_id, firstName, lastName, book_name, borrow_date, status) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $student_id, $firstName, $lastName, $bookName, $borrowDate, $status);

    if ($stmt->execute()) {
        
        echo "<script>
                alert('Book borrowing request submitted successfully.');
                window.location.href = 'userfinal.php';
              </script>";
        exit();
    } else {
        echo "<script>alert('Error submitting book borrowing request.');</script>";
    }

    $stmt->close();
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
            margin-left: 100px;
            padding: 40px;
            flex: 1;
            transition: margin-left 0.3s;
            display: flex; 
            justify-content: center;
            align-items: flex-start;
            text-align: left;
        }

        .main-content.shift {
            margin-left: 200px;
        }

        .toggle-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: #159603;
            color: white;
            border: none;
            padding: 8px 12px; 
            font-size: 16px; 
            cursor: pointer;
            border-radius: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 40px; 
        }

        .toggle-btn:hover {
            background-color: #128b02;
        }

        .borrow-container {
            background-color: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            text-align: left;
        }

        .borrow-container h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #4a5568;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #4a5568;
            font-size: 15px;
            font-weight: 500;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
            transition: border-color 0.3s ease;
            background-color: #f9fafb;
        }

        input:focus, select:focus {
            border-color: #3182ce;
            outline: none;
        }

        button {
            background-color: #009921;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: #2caa12;
            transform: translateY(-2px);
        }

        #borrowStatus {
            margin-top: 20px;
            font-size: 15px;
            text-align: center;
            font-weight: 600;
        }
        @media (max-width: 768px) {
    .toggle-btn {
        top: 10px;
        left: 10px;
        font-size: 14px;
        padding: 8px;
        width: 35px;
        height: 35px;
    }

    .sidebar {
        width: 200px;
    }

    .main-content {
        margin-left: 0;
        padding: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .borrow-container {
        padding: 20px;
        max-width: 500px;
    }

    input, select, button {
        padding: 10px;
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .toggle-btn {
        top: 8px;
        left: 8px;
        font-size: 12px;
        padding: 6px;
        width: 30px;
        height: 30px;
    }

    .sidebar {
        width: 180px;
        left: -180px;
    }

    .main-content {
        padding: 15px;
        text-align: center;
    }

    .borrow-container {
        padding: 15px;
        width: 100%;
        max-width: 350px;
    }

    input, select, button {
        padding: 8px;
        font-size: 12px;
    }

    .header h2 {
        font-size: 18px;
        text-align: center;
    }
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
            <li><i class="fas fa-desktop"></i><a href="computer.php">Computer Availability</a></li>
            <li><i class="fas fa-sign-out-alt"></i><a href="loginNOW.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="borrow-container">
            <h2>Borrow a Book</h2>

            <form method="POST" action="">
                <!-- Automatically Display Student Name -->
                <label for="studentName">Student Name</label>
                <input type="text" id="studentName" value="<?= htmlspecialchars($firstName . ' ' . $lastName) ?>" readonly>

                <!-- Automatically Display Grade Level -->
                <label for="studentGrade">Grade</label>
                <input type="text" id="studentGrade" value="<?= htmlspecialchars($gradeLevel) ?>" readonly>

                <!-- Automatically Display Strand -->
                <label for="studentStrand">Strand</label>
                <input type="text" id="studentStrand" value="<?= htmlspecialchars($strand) ?>" readonly>

                <!-- Book Name Input -->
                <label for="bookName">Book Name</label>
                <input type="text" id="bookName" name="bookName" placeholder="Enter Book Name" required>

                <!-- Borrow Date -->
                <label for="borrowDate">Date Borrowed</label>
                <input type="text" id="borrowDate" name="borrowDate" value="<?php echo date('Y-m-d'); ?>" readonly>

                <!-- Static Status Field -->
                <label for="status">Status</label>
                <input type="text" id="status" value="Using" readonly>

                <button type="submit">Submit</button>
                <p id="borrowStatus"></p>
            </form>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            sidebar.classList.toggle('show');
            mainContent.classList.toggle('shift');
        }

        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.querySelector('.toggle-btn');
            const mainContent = document.getElementById('mainContent');

            if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                sidebar.classList.remove('show');
                mainContent.classList.remove('shift');
            }
        });

        function borrowBook() {
            const bookName = document.getElementById("bookName").value.trim();
            const borrowDate = document.getElementById("borrowDate").value.trim();
            const status = document.getElementById("borrowStatus");

            if (bookName === "" || borrowDate === "") {
                status.textContent = "Please fill in all fields before submitting.";
                status.style.color = "#e53e3e";
                return;
            }

            // Simulate submission
            status.textContent = `Book borrowing request submitted successfully. Status: Using.`;
            status.style.color = "#38a169";
        }
    </script>

</body>
</html>
