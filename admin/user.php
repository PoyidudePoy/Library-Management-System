<?php
session_start();
require_once 'php/db_connection.php';


if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}


$admin_id = $_SESSION['admin_id'];
$sql = "SELECT name, contact_number, email_acc FROM admins WHERE admin_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$stmt->close();


$sql_students = "SELECT id, student_id, firstName, lastName, octEmail,  grade_level, strand FROM your_students";
$result_students = $conn->query($sql_students);
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
            transition: margin-left 0.3s;
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
        .student-section {
            background: linear-gradient(135deg, #e6f7e6, #f9f9f9);
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            width: 80%;
            margin: 0 auto;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .student-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3);
        }
        .student-section h3 {
            font-size: 24px;
            color: #159603;
            border-bottom: 2px solid #159603;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background-color: white;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #159603;
            color: white;
        }
        tr:hover {
            background-color: #f2f2f2;
        }
        td[colspan="6"] {
            text-align: center;
            color: #888;
        }
        .delete-btn {
        background-color: #e74c3c;
        color: white;
        border: none;
        padding: 8px 12px;
        font-size: 14px;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s ease;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }
        thead input {
            width: 100%;
            padding: 3px;
            border: 1px solid #ccc;
            border-radius: 10px;
            text-align: center;
            font-size: 14px;
        }
                .students {  
            transition: margin-left 0.3s ease-in-out;  
            margin-left: 0; 
        }

        .students.shift {  
            margin-left: 250px; 
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
            <li><i class="fas fa-user"></i><a href="user.php">User</a></li>
            <li><i class="fas fa-book"></i><a href="homepage.php">Home</a></li>
            <li><i class="fas fa-book-reader"></i><a href="borrow_list.php">Borrowing of Books</a></li>
            <li><i class="fas fa-desktop"></i><a href="computer.php">Computer Availability</a></li>
            <li><i class="fas fa-barcode"></i><a href="logbook.php">Logbook</a></li>
            <li><i class="fas fa-sign-out-alt"></i><a href="php/logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content" id="mainContent">
        <div class="profile-section">
            <div class="profile-picture">
                <i class="fas fa-user"></i>
            </div>
            <div class="profile-details">
                <h2>Admin Information</h2>
                <p><strong>Name:</strong> <?= htmlspecialchars($admin['name']) ?></p>
                <p><strong>Contact Number:</strong> <?= htmlspecialchars($admin['contact_number']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($admin['email_acc']) ?></p>
            </div>
        </div>
    </div>

    <div class="student-section">
    <h3>Registered Students</h3>
    <form action="php/delete_selected_students.php" method="POST" id="deleteForm" onsubmit="return confirm('Are you sure you want to delete selected students?');">
        <table id="studentTable">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll" onclick="toggleSelectAll()"></th>
                    <th><input type="text" onkeyup="filterTable(1)" placeholder="Search ID"></th>
                    <th><input type="text" onkeyup="filterTable(2)" placeholder="Search First Name"></th>
                    <th><input type="text" onkeyup="filterTable(3)" placeholder="Search Last Name"></th>
                    <th><input type="text" onkeyup="filterTable(4)" placeholder="Search Email"></th>
                    <th><input type="text" onkeyup="filterTable(5)" placeholder="Search Grade"></th>
                    <th><input type="text" onkeyup="filterTable(6)" placeholder="Search Strand"></th>
                    <th>Action</th>
                </tr>
                <tr>
                    <th>Select</th>
                    <th>Student ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Grade Level</th>
                    <th>Strand</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($student = $result_students->fetch_assoc()): ?>
                    <tr>
    <td>
        <input type="checkbox" name="selected_students[]" value="<?= $student['id'] ?>" class="student-checkbox">
    </td>
    <td contenteditable="true" data-id="<?= $student['id'] ?>" data-column="student_id"><?= htmlspecialchars($student['student_id']) ?></td>
    <td contenteditable="true" data-id="<?= $student['id'] ?>" data-column="firstName"><?= htmlspecialchars($student['firstName']) ?></td>
    <td contenteditable="true" data-id="<?= $student['id'] ?>" data-column="lastName"><?= htmlspecialchars($student['lastName']) ?></td>
    <td contenteditable="true" data-id="<?= $student['id'] ?>" data-column="octEmail"><?= htmlspecialchars($student['octEmail']) ?></td>
    <td contenteditable="true" data-id="<?= $student['id'] ?>" data-column="grade_level"><?= htmlspecialchars($student['grade_level']) ?></td>
    <td contenteditable="true" data-id="<?= $student['id'] ?>" data-column="strand"><?= htmlspecialchars($student['strand']) ?></td>
    <td>
        <form action="php/delete_student.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this student?');">
            <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
            <button type="submit" class="delete-btn">Delete</button>
        </form>
    </td>
</tr>
                <?php endwhile; ?>
                <?php if ($result_students->num_rows == 0): ?>
                    <tr><td colspan="8">No students registered yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <button type="submit" class="delete-selected-btn" id="deleteSelectedBtn" disabled>Delete Selected</button>
    </form>
</div>

    <script>
         function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const studentSection = document.querySelector('.students'); 

   
    sidebar.classList.toggle('show');
    mainContent.classList.toggle('shift');
    studentSection.classList.toggle('shift');  
}


document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.querySelector('.toggle-btn');
    const mainContent = document.getElementById('mainContent');
    const studentSection = document.querySelector('.students'); 

    if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
        sidebar.classList.remove('show');
        mainContent.classList.remove('shift');
        studentSection.classList.remove('shift');  
    }
});

        function filterTable(columnIndex) {
            let input = document.querySelectorAll("thead input")[columnIndex].value.toUpperCase();
            let table = document.getElementById("studentTable");
            let rows = table.getElementsByTagName("tr");

            for (let i = 2; i < rows.length; i++) { 
                let cells = rows[i].getElementsByTagName("td");
                if (cells[columnIndex]) {
                    let textValue = cells[columnIndex].textContent || cells[columnIndex].innerText;
                    rows[i].style.display = textValue.toUpperCase().indexOf(input) > -1 ? "" : "none";
                }
            }
        }
        function toggleSelectAll() {
        let checkboxes = document.querySelectorAll('.student-checkbox');
        let selectAllCheckbox = document.getElementById('selectAll');
        checkboxes.forEach(checkbox => checkbox.checked = selectAllCheckbox.checked);
        toggleDeleteButton();
    }

    function toggleDeleteButton() {
        let checkboxes = document.querySelectorAll('.student-checkbox:checked');
        let deleteBtn = document.getElementById('deleteSelectedBtn');
        deleteBtn.disabled = checkboxes.length === 0;
    }

    document.querySelectorAll('.student-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', toggleDeleteButton);
    });

    document.addEventListener("DOMContentLoaded", function () {
    const table = document.getElementById("studentTable");
    const editableCells = table.querySelectorAll("tbody td:not(:first-child):not(:nth-child(2)):not(:last-child)"); 
    

    editableCells.forEach(cell => {
        cell.setAttribute("contenteditable", "true"); 

        cell.addEventListener("blur", function () { 
            const newValue = this.textContent.trim();
            const row = this.parentElement;
            const studentId = row.querySelector("input[name='selected_students[]']").value;
            const columnIndex = [...row.children].indexOf(this); 

           
            if (confirm("Do you want to save the changes?")) {
                saveChanges(studentId, columnIndex, newValue);
            } else {
                
                location.reload();
            }
        });
    });

    function saveChanges(studentId, columnIndex, newValue) {
        fetch("php/update_student.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `student_id=${studentId}&column_index=${columnIndex}&new_value=${encodeURIComponent(newValue)}`
        })
        .then(response => response.text())
        .then(data => {
            alert(data); 
        })
        .catch(error => console.error("Error:", error));
    }
});

function toggleSelectAll() {
    let checkboxes = document.querySelectorAll(".student-checkbox");
    let selectAll = document.getElementById("selectAll");
    checkboxes.forEach(checkbox => checkbox.checked = selectAll.checked);
    toggleDeleteButton();
}

document.querySelectorAll(".student-checkbox").forEach(checkbox => {
    checkbox.addEventListener("change", toggleDeleteButton);
});

function toggleDeleteButton() {
    let selected = document.querySelectorAll(".student-checkbox:checked").length > 0;
    document.getElementById("deleteSelectedBtn").disabled = !selected;
}
    </script>
    <style>
    .delete-selected-btn {
        background-color: #e74c3c;
        color: white;
        border: none;
        padding: 10px 15px;
        font-size: 16px;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s ease;
        display: block;
        margin: 10px auto;
        width: 200px;
    }

    .delete-selected-btn:disabled {
        background-color: #ccc;
        cursor: not-allowed;
    }

    .delete-selected-btn:hover:not(:disabled) {
        background-color: #c0392b;
    }

    #selectAll {
        cursor: pointer;
        transform: scale(1.2);
    }

    .student-checkbox {
        transform: scale(1.1);
        cursor: pointer;
    }
</style>
</body>
</html>
