<?php
session_start();
require_once 'php/db_connection.php';


if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
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
    transition: margin-left 0.3s ease-in-out;
    display: flex;
    flex-direction: column;
    gap: 20px;
}


.main-content.shift {
    margin-left: 250px;
}

.toggle-btn {
    position: fixed;
    top: 20px;
    left: 20px;
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


.container {
    width: 90%;
    margin: auto;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    transition: margin-left 0.3s ease-in-out;
}


.container.shift {
    margin-left: 250px;
}

h2 {
    text-align: center;
    color: #159603;
}


.table-container {
    width: 100%;
    overflow-x: auto;
    margin-top: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    background: white;
    padding: 15px;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 8px;
    overflow: hidden;
}

th, td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #159603;
    color: white;
    font-weight: bold;
}


th input {
    width: 90%;
    padding: 8px;
    border: 2px solid #ccc;
    border-radius: 5px;
    text-align: center;
    font-size: 14px;
    outline: none;
    transition: all 0.3s ease-in-out;
    background-color: rgb(175, 228, 175);
}

th input:focus {
    border-color: #0f7302;
    box-shadow: 0 0 5px rgba(21, 150, 3, 0.5);
}

thead {
    position: sticky;
    top: 0;
    background: white;
    z-index: 2;
}

tr:nth-child(even) {
    background-color: #f1f1f1;
}


select {
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 16px;
    background-color: #fff;
    border: 2px solid #159603;
    cursor: pointer;
    transition: all 0.3s ease-in-out;
    outline: none;
}

select:hover {
    background-color: #e8ffe8;
}

select:focus {
    border-color: #0f7302;
    box-shadow: 0 0 8px rgba(21, 150, 3, 0.5);
}


.pagination {
    margin-top: 20px;
    display: flex;
    justify-content: space-between;
}

.pagination button {
    padding: 10px 15px;
    border: none;
    background-color: #159603;
    color: white;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
}

.pagination button:hover {
    background-color: #0f7302;
}

.pagination button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}


.timeout-btn {
    background-color: #e63946;
    color: white;
    font-size: 14px;
    font-weight: bold;
    padding: 8px 14px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease-in-out;
    box-shadow: 2px 2px 5px rgba(230, 57, 70, 0.4);
}

.timeout-btn:hover {
    background-color: #d62828;
    box-shadow: 4px 4px 8px rgba(230, 57, 70, 0.6);
}

.disabled-btn {
    background-color: #ccc;
    color: #666;
    font-size: 14px;
    font-weight: bold;
    padding: 8px 14px;
    border: none;
    border-radius: 8px;
    cursor: not-allowed;
    box-shadow: none;
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
            <img src="images/OCTlogo.png" alt="User  Profile">
        </div>
        <ul>
            <li><i class="fas fa-user"></i><a href="user.php">User </a></li>
            <li><i class="fas fa-book"></i><a href="homepage.php">Home</a></li>
            <li><i class="fas fa-book-reader"></i><a href="borrow_list.php">Borrowing of Books</a></li>
            <li><i class="fas fa-desktop"></i><a href="computer.php">Computer Availability</a></li>
            <li><i class="fas fa-barcode"></i><a href="logbook.php">Logbook</a></li>
            <li><i class="fas fa-sign-out-alt"></i><a href="php/logout.php">Logout</a></li>
        </ul>
    </div>
   

    <div class="container">
        <h2>Log Book time in </h2>
        
                <div style="display: flex; justify-content: center; margin-bottom: 5px;"> 
                <input type="password" id="barcodeScanner" placeholder="Scan Barcode Here" 
    readonly 
    style="padding: 20px; margin-left: 70px; font-size: 16px; border: 1px solid #ccc; border-radius: 5px; width: 300px; text-align: center;">
                    <a href="bookhistory.php" style="margin-left: 20px; font-size: 16px; color: #159603; text-decoration: none;">Book History</a>
                    
                </div>
                

                <div class="table-container">
    <table id="studentTable">
        <thead>
            <tr>
                <th><input type="text" id="searchStudentID" placeholder="🔍 Student ID"></th>
                <th><input type="text" id="searchFirstName" placeholder="🔍 First Name"></th>
                <th><input type="text" id="searchLastName" placeholder="🔍 Last Name"></th>
                <th><input type="text" id="searchOctEmail" placeholder="🔍 Email"></th>
                <th><input type="text" id="searchGradeLevel" placeholder="🔍 Grade"></th>
                <th><input type="text" id="searchStrand" placeholder="🔍 Strand"></th>
                <th><input type="text" id="searchPurpose" placeholder="🔍 Purpose"></th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Action</th>
            </tr>
            <tr class="header-row">
                <th>Student ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Octemail</th>
                <th>Grade Level</th>
                <th>Strand</th>
                <th>Purpose</th> 
                <th>Time In</th>
                <th>Time Out</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="tableBody"></tbody>
    </table>
</div>
            
        <div class="pagination">
            <button id="prevBtn" onclick="prevPage()" disabled>Previous</button>
            <button id="nextBtn" onclick="nextPage()">Next</button>
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
            const mainContent = document    .getElementById('mainContent');

            if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                sidebar.classList.remove('show');
                mainContent.classList.remove('shift');
            }
        });

        function formatTime(timeString) {
    if (!timeString) return "--";
    
    const date = new Date(timeString);
    return date.toLocaleString("en-US", { 
        timeZone: "Asia/Manila", 
        year: 'numeric', 
        month: '2-digit', 
        day: '2-digit', 
        hour: '2-digit', 
        minute: '2-digit', 
        second: '2-digit', 
        hour12: true 
    });
}

let students = [];
let currentPage = 1;
const rowsPerPage = 10;

function displayTable(page) {
    const start = (page - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const tableBody = document.getElementById("tableBody");
    tableBody.innerHTML = "";

    const currentEntries = students.slice(start, end);
    currentEntries.forEach(student => {
        const timeOutButton = student.time_out 
        ? `<button class="disabled-btn" disabled>Timed Out</button>` 
        : `<button class="timeout-btn" onclick="timeOutStudent('${student.student_id}')">Time Out</button>`;
        tableBody.innerHTML += `
            <tr>
                <td>${student.student_id}</td>
                <td>${student.firstName}</td>
                <td>${student.lastName}</td>
                <td>${student.octEmail}</td>
                <td>${student.grade_level}</td>
                <td>${student.strand}</td>
                <td>${student.purpose}</td>
                <td>${formatTime(student.time_in)}</td>
                <td>${formatTime(student.time_out)}</td>
                <td>${timeOutButton}</td> <!-- Time Out button -->
            </tr>`;
    });

    document.getElementById("prevBtn").disabled = page === 1;
    document.getElementById("nextBtn").disabled = end >= students.length;
}

function timeOutStudent(studentID) {
    if (!confirm("Are you sure you want to time out this student?")) {
        return;
    }

    fetch("php/time_out.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `student_id=${encodeURIComponent(studentID)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Student timed out successfully!");
            
           
            fetch("bookhistory.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `student_id=${encodeURIComponent(studentID)}`
            })
            .then(response => response.json())
            .then(historyData => {
                console.log("Record sent to book history:", historyData);
            })
            .catch(error => console.error("Error sending record to book history:", error));

            location.reload(); 
        } else {
            alert(data.error || "Failed to time out student. Please try again.");
        }
    })
    .catch(error => console.error("Error timing out student:", error));
}

function nextPage() {
    currentPage++;
    displayTable(currentPage);
}

function prevPage() {
    currentPage--;
    displayTable(currentPage);
}

document.addEventListener("DOMContentLoaded", function () {
    const barcodeInput = document.getElementById("barcodeScanner");

    
    barcodeInput.focus();

    barcodeInput.addEventListener("focus", function () {
        this.select();
    });

    barcodeInput.addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            let studentID = this.value.trim();
            if (!studentID) {
                alert("Please scan a valid barcode.");
                return;
            }
            checkStudentExists(studentID);
            this.value = "";    
        }
    });

    function fetchLogEntries() {
    fetch("php/fetch_logs.php")
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }
            
            
            students = data.filter(student => !student.time_out);
            
            displayTable(currentPage); 
        })
        .catch(error => console.error("Error fetching logs:", error));
}

function checkStudentExists(studentID) {
        fetch("php/fetch_student.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `student_id=${encodeURIComponent(studentID)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let purpose = prompt("Enter the purpose of visit:");
                if (!purpose) {
                    alert("Purpose is required.");
                    return;
                }
                insertLog(studentID, purpose);
            } else {
                alert("Student ID not found. Please try again.");
            }
        })
        .catch(error => console.error("Error checking student:", error));
    }
});
    function insertLog(studentID, purpose) {
        fetch("php/fetch_student.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `student_id=${encodeURIComponent(studentID)}&purpose=${encodeURIComponent(purpose)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Student log entry recorded successfully!");
                barcodeInput.value = "";
                fetchLogEntries(); 
            } else {
                alert(data.error || "Failed to log entry. Please try again.");
            }
        })
        .catch(error => console.error("Error inserting log:", error));
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const searchInputs = [
        "searchStudentID", "searchFirstName", "searchLastName", "searchOctEmail",
        "searchGradeLevel", "searchStrand", "searchPurpose"
    ];

    searchInputs.forEach(id => {
        document.getElementById(id).addEventListener("input", filterTable);
    });

    function filterTable() {
        const filters = searchInputs.map(id => document.getElementById(id).value.toLowerCase());
        const tableRows = document.querySelectorAll("#tableBody tr");

        tableRows.forEach(row => {
            const rowData = [...row.children].map(cell => cell.textContent.toLowerCase());
            const matches = filters.every((filter, i) => rowData[i].includes(filter));
            row.style.display = matches ? "" : "none";
        });
    }
});

    </script>


    </script>
</body>
</html>