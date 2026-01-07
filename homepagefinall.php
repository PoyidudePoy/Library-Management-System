<?php
session_start();
require_once 'php/db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: loginNOW.php");
    exit();
}

// Fetch announcements from the database
$sql = "SELECT * FROM announcements ORDER BY created_at DESC LIMIT 3";
$result = $conn->query($sql);
$announcements = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $announcements[] = $row;
    }
}
$total_computers = 16; 
$sql = "SELECT COUNT(*) AS occupied FROM computer_availability WHERE status = 'occupied'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$occupied_computers = $row['occupied'];
$available_computers = $total_computers - $occupied_computers;

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
}

header {
    background-color: #159603;
    color: white;
    padding: 10px 15px;
}

.header h3 {
    text-align: right;
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
        top: 0;
        left: -250px;
        transition: left 0.3s ease-in-out;
        display: flex;
        flex-direction: column;
        align-items: center;
        z-index: 1000; 
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
    transition: background-color 0.3s;
}

.sidebar ul li:hover {
    background-color: #fffbc9;
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
    transition: margin-left 0.3s;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.main-content.shift {
    margin-left: 250px;
}

.toggle-btn {
    position: fixed;
    top: 15px;
    left: 15px;
    background-color: #159603;
    color: white;
    border: none;
    padding: 10px 15px;
    font-size: 18px;
    cursor: pointer;
    border-radius: 5px;
    z-index: 1100; 
}

.toggle-btn:hover {
    background-color: #128b02;
}

.announcements, .feedback {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 20px;
    width: calc(50% - 10px); 
    display: flex;
    flex-direction: column;
}

.announcements {
    background: linear-gradient(135deg, #ffffff, #ffffff);
}

.announcements h2, .feedback h2 {
    color: #159603;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.announcements h2 i, .feedback h2 i {
    color: #128b02;
}

.announcements ul {
    list-style: none;
    padding: 0;
}

.announcements li {
    padding: 10px;
    border-bottom: 1px solid #cccccc;
    display: flex;
    gap: 10px;
    align-items: center;
}

.announcements li:last-child {
    border-bottom: none;
}

.announcements li i {
    color: #128b02;
}

.feedback {
    background: linear-gradient(135deg, #ffffff, #ffd000);
}

.feedback form {
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 100%;
}

.feedback form textarea {
    width: 100%;
    padding: 5px;
    border: 1px solid #cccccc;
    border-radius: 5px;
    font-size: 14px;
}

.feedback form button {
    background-color: #159603;
    color: white;
    border: none;
    padding: 5px 10px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.feedback form button:hover {
    background-color: #128b02;
}

.content-container {
    display: flex;
    justify-content: space-between;
    gap: 20px;
}

.librarians {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    margin-top: 20px;
    opacity: 0; 
    animation: fadeIn 1s forwards; 
}

.librarian {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 15px;
    text-align: center;
    flex: 1; 
    transition: transform 0.3s; 
}

.librarian img {
    border-radius: 50%;
    height: 100px;
    width: 100px;
    margin-bottom: 10px;
}

.librarian h4 {
    margin: 10px 0 0;
    color: #159603;
}

.librarian:hover {
    transform: scale(1.05); 
}

.librarian-title {
    font-size: 28px;
    font-weight: 600;
    color: #128b02;
    text-align: center;
    margin: 30px 0;
    background: linear-gradient(90deg, #d4ffea, #ffffff);
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.computer-status {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 20px;
    width: 250px; 
    flex-grow: 1;
    text-align: center;
}

.computer-status h2 {
    color: #159603;
    margin-bottom: 15px;
}

.computer-status p {
    font-size: 18px;
    margin: 5px 0;
}

.available {
    color: green;
    font-weight: bold;
}

.occupied {
    color: red;
    font-weight: bold;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@media (max-width: 992px) {
    .sidebar {
        width: 180px;
    }
    .main-content.shift {
        margin-left: 180px;
    }
}

/* Tablets and Small Laptops (768px and below) */
@media (max-width: 768px) {
    .sidebar {
        width: 80%; /* Instead of full screen width */
        left: -80%;
    }
    .sidebar.show {
        left: 0;
    }
    .toggle-btn {
        left: 10px;
        top: 10px;
        padding: 8px 12px;
    }
    .main-content.shift {
        margin-left: 0; /* Prevent shifting on small screens */
    }
    
    .content-container {
        flex-direction: column;
        gap: 15px;
    }
    .announcements, .feedback, .computer-status {
        width: 100%;
    }
    .librarians {
        flex-wrap: wrap;
        justify-content: center;
    }
    .librarian {
        flex: 1 1 48%;
    }
}


@media (max-width: 480px) {
    .toggle-btn {
        font-size: 14px;
        padding: 6px 10px;
    }
    .librarians {
        flex-direction: column;
    }
    .librarian {
        flex: 1 1 100%;
    }
    .announcements, .feedback, .computer-status {
        padding: 15px;
    }
    .sidebar ul li {
        padding: 12px;
    }
}



    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="header">
            <h3>Library Management System</h3>
        </div>
    </header>

    <button class="toggle-btn" onclick="toggleSidebar()">☰</button>

    <div class="sidebar" id="sidebar">
        <div class="user-profile">
            <img src="images/OCTlogo.png" alt="User Profile">
        </div>
        <ul>
            <li><i class="fas fa-user"></i><a href="userfinal.php">User </a></li>
            <li><i class="fas fa-book"></i><a href="homepagefinall.php">Home</a></li>
            <li><i class="fas fa-book-reader"></i><a href="book.php">Borrowing of Books</a></li>
            <li><i class="fas fa-desktop"></i><a href="computer.php">Computer Availability</a></li>
            <li><i class="fas fa-sign-out-alt"></i><a href="logout.php">Logout</a></li>        </ul>
    </div>

    <div class="main-content" id="mainContent">
        <div class="content-container">
        <div class="announcements">
    <h2><i class="fas fa-bullhorn"></i> Library Announcements</h2>
    <ul>
        <?php foreach ($announcements as $announcement): ?>
            <li>
                <i class="fas fa-info-circle"></i> <?php echo htmlspecialchars($announcement['announcement_text']); ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="computer-status">
            <h2><i class="fas fa-desktop"></i> Computer Availability</h2>
            <p>Total PCs: <strong><?php echo $total_computers; ?></strong></p>
            <p>Available PCs: <span class="available"><?php echo $available_computers; ?></span></p>
            <p>Occupied PCs: <span class="occupied"><?php echo $occupied_computers; ?></span></p>
        </div>
    </div>
</div>
            
     
        <h1 class="librarian-title"> Meet The School Librarians</h1>
        <div class="librarians">
            <div class="librarian">
                <img src="images/OCTlogo.png" alt="Librarian 1">
                <h4>Librarian Head </h4>
                <h3> Ms.Arline T. Dela Cruz, RL </h3>
            </div>
            <div class="librarian">
                <img src="images/OCTlogo.png" alt="Librarian 2">
                <h4>Librarian 2</h4>
                <h3> Ms. Lars</h3>
            </div>
            <div class="librarian">
                <img src="images/OCTlogo.png" alt="Librarian 3">
                <h4>Librarian 3</h4>
                <h3> Ms. Regine</h3>
            </div>
            <div class="librarian">
                <img src="images/OCTlogo.png" alt="Librarian 4">
                <h4>Librarian 4</h4>
                <h3> Ms. hie</h3>
            </div>
        </div>
    </div>
        </div>

    <script>
      function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');

    sidebar.classList.toggle('show');

   
    if (window.innerWidth > 768) {
        mainContent.classList.toggle('shift');
    } else {
        mainContent.classList.remove('shift'); 
    }
}


document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.querySelector('.toggle-btn');
    
    if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
        sidebar.classList.remove('show');
    }
});
    </script>
</body>
</html>