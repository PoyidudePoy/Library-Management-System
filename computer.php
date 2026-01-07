<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: loginNOW.php");
    exit();
}


$host = "localhost";
$username = "root"; 
$password = ""; 
$database = "students_db"; 

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$student_id = $_SESSION['student_id'];
$query = "SELECT firstName, lastName, grade_level, strand FROM your_students WHERE student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();


$fullName = $student['firstName'] . ' ' . $student['lastName'];
$query = "
    SELECT ca.computer_id, ca.status, ca.student_id, s.firstName, s.lastName, s.grade_level, s.strand
    FROM computer_availability ca
    LEFT JOIN your_students s ON ca.student_id = s.student_id
";
$result = $conn->query($query);
$computerStates = [];
while ($row = $result->fetch_assoc()) {
    $computerStates[$row['computer_id']] = $row;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Computer Availability</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #ffffff;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        header {
            background-color: #159603;
            color: white;
            padding: 5px 10px;
            display: center;
            align-items: left;
            justify-content: space-between;
            width: 100%;
        }

        .header h3 {
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
            padding: 20px;
            flex: 1;
            transition: margin-left 0.3s;
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
        .computer-container {
            text-align: left;
            background-color: white;
            padding: 0px 40px 30px; 
            border-radius: 20px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 600px; 
            margin: auto; 
            margin-top: 80px;
        }

        .computer-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-top: 10px; 
        }
        .computer {
            padding: 30px;
            border-radius: 10px;
            cursor: pointer;
            text-align: center;
            font-weight: bold;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .computer:hover {
            transform: scale(1.05);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .available {
            background-color: #4caf50;
            color: white;
        }
        .occupied {
            background-color: #f44336;
            color: white;
        }
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            width: 80%;
            max-width: 400px;
        }
        .modal input, .modal select, .modal button {
            display: block;
            margin: 10px 0;
            padding: 12px;
            font-size: 16px;
            width: 100%;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        .modal input:focus, .modal select:focus {
            border-color: #4caf50;
            outline: none;
        }
        .modal button {
            background-color: #4caf50;
            color: white;
            cursor: pointer;
            border: none;
        }
        .modal button:hover {
            background-color: #45a049;
        }
        .checkbox-container {
            display: flex;
            align-items: center;
            margin-top: 15px;
        }
        .checkbox-container label {
            font-size: 16px;
            margin-left: 10px;
        }
        
        
        .checkbox-container input[type="checkbox"] {
            display: none; 
        }

        .checkbox-container .checkbox-label {
            display: inline-block;
            position: relative;
            width: 50px;
            height: 30px;
            background-color: #ccc;
            border-radius: 30px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .checkbox-container .checkbox-label:before {
            content: '';
            position: absolute;
            top: 4px;
            left: 4px;
            width: 22px;
            height: 22px;
            background-color: white;
            border-radius: 50%;
            transition: transform 0.3s;
        }

        .checkbox-container input[type="checkbox"]:checked + .checkbox-label {
            background-color: #4caf50; 
        }

        .checkbox-container input[type="checkbox"]:checked + .checkbox-label:before {
            transform: translateX(20px);
        }
        .own-occupied {
    background-color: orange;
    color: white;
}
@media (max-width: 768px) {
            .computer-grid {
                grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
            }

            .toggle-btn {
                padding: 8px;
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            .computer-grid {
                grid-template-columns: repeat(auto-fit, minmax(60px, 1fr));
            }

            .toggle-btn {
                top: 10px;
                left: 10px;
                font-size: 14px;
                padding: 6px;
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
            <li><i class="fas fa-user"></i><a href="userfinal.php">User</a></li>
            <li><i class="fas fa-book"></i><a href="homepagefinall.php">Home</a></li>
            <li><i class="fas fa-book-reader"></i><a href="book.php">Borrowing of Books</a></li>
            <li><i class="fas fa-desktop"></i><a href="computer.php">Computer Availability</a></li>
            <li><i class="fas fa-sign-out-alt"></i><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="computer-container">
        <h2>Computer Availability</h2>
        <div id="computers" class="computer-grid"></div>
    </div>

    <div id="editModal" class="modal">
    <h3>Student Information</h3>
    <div id="studentInfo">
        <input type="text" id="studentName" placeholder="Student Name" value="<?php echo $fullName; ?>" readonly />
        <select id="studentGrade" disabled>
            <option value="">Select Grade</option>
            <option value="11" <?php echo $student['grade_level'] == '11' ? 'selected' : ''; ?>>Grade 11</option>
            <option value="12" <?php echo $student['grade_level'] == '12' ? 'selected' : ''; ?>>Grade 12</option>
        </select>
        <select id="studentStrand" disabled>
            <option value="">Select Strand</option>
            <option value="STEM" <?php echo $student['strand'] == 'STEM' ? 'selected' : ''; ?>>STEM</option>
            <option value="ABM" <?php echo $student['strand'] == 'ABM' ? 'selected' : ''; ?>>ABM</option>
            <option value="HUMSS" <?php echo $student['strand'] == 'HUMSS' ? 'selected' : ''; ?>>HUMSS</option>
            <option value="TVL-ICT" <?php echo $student['strand'] == 'TVL-ICT' ? 'selected' : ''; ?>>TVL-ICT</option>
            <option value="TVL-H.E" <?php echo $student['strand'] == 'TVL-H.E' ? 'selected' : ''; ?>>TVL-H.E</option>
        </select>
    </div>
    <div class="checkbox-container">
        <input type="checkbox" id="occupiedStatus" />
        <label for="occupiedStatus" class="checkbox-label"></label>
        <label for="occupiedStatus">Occupied</label>
    </div>
    <button onclick="saveDetails()">Save</button>
    <button onclick="closeModal()">Cancel</button>
</div>

    <div class="main-content" id="mainContent">
       
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
        const studentDetails = {
    firstName: "<?php echo $student['firstName']; ?>", 
    lastName: "<?php echo $student['lastName']; ?>",  
    fullName: "<?php echo $fullName; ?>",             
    gradeLevel: "<?php echo $student['grade_level']; ?>",
    strand: "<?php echo $student['strand']; ?>",
    studentId: "<?php echo $student_id; ?>"
     };

        
     const computers = Array.from({ length: 16 }, (_, i) => {
    const computerId = i + 1;
    const computerState = <?php echo json_encode($computerStates); ?>[computerId] || {
    computer_id: computerId,
    status: 'available',
    student_id: null, 
    firstName: '',
    lastName: '',
    grade_level: '',
    strand: ''
};
    return {
        id: computerId,
        status: computerState.status,
        studentName: computerState.firstName + ' ' + computerState.lastName,
        studentGrade: computerState.grade_level,
        studentStrand: computerState.strand,
        occupied: computerState.status === 'occupied',
        studentId: computerState.student_id 
    };
});

        let selectedComputer = null;

        function renderComputers() {
    const container = document.getElementById("computers");
    container.innerHTML = "";

    computers.forEach(computer => {
        const div = document.createElement("div");

      
        if (computer.occupied) {
            if (computer.studentId === studentDetails.studentId) {
                div.className = "computer own-occupied"; 
            } else {
                div.className = "computer occupied"; 
            }
        } else {
            div.className = "computer available"; 
        }

        div.textContent = `PC ${computer.id}`;

       
        if (computer.occupied && computer.studentId !== studentDetails.studentId) {
            div.onclick = () => alert("This PC is already occupied by another student.");
        } else {
            div.onclick = () => openEditModal(computer);
        }

        container.appendChild(div);
    });
}
        function openEditModal(computer) {
    selectedComputer = computer;
    
    document.getElementById("studentName").value = studentDetails.fullName;
    document.getElementById("studentGrade").value = studentDetails.gradeLevel;
    document.getElementById("studentStrand").value = studentDetails.strand;
    document.getElementById("occupiedStatus").checked = computer.occupied;

    
    document.getElementById("studentName").readOnly = true;
    document.getElementById("studentGrade").disabled = true;
    document.getElementById("studentStrand").disabled = true;

    document.getElementById("editModal").style.display = "block";
}

        function saveDetails() {
    if (selectedComputer) {
        const isOccupied = document.getElementById("occupiedStatus").checked;

        if (!isOccupied) {
          
            fetch('php/delete_computer.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ computer_id: selectedComputer.id })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                  
                    selectedComputer.status = 'available';
                    selectedComputer.studentName = '';
                    selectedComputer.studentGrade = '';
                    selectedComputer.studentStrand = '';
                    selectedComputer.occupied = false;
                    selectedComputer.studentId = null;
                    closeModal();
                } else {
                    alert("Failed to unoccupy the computer: " + result.message);
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("An error occurred while updating the computer state.");
            });
        } else {
           
            const data = {
                computer_id: selectedComputer.id,
                student_id: studentDetails.studentId,
                firstName: studentDetails.firstName,
                lastName: studentDetails.lastName,
                grade_level: studentDetails.gradeLevel,
                strand: studentDetails.strand,
                status: 'occupied'
            };

            fetch('php/update_computer.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                 
                    selectedComputer.status = 'occupied';
                    selectedComputer.studentName = studentDetails.fullName;
                    selectedComputer.studentGrade = studentDetails.gradeLevel;
                    selectedComputer.studentStrand = studentDetails.strand;
                    selectedComputer.occupied = true;
                    selectedComputer.studentId = studentDetails.studentId;
                    closeModal();
                } else {
                    alert("Failed to update the computer state: " + result.message);
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("An error occurred while updating the computer state.");
            });
        }
    }
}



        function closeModal() {
            document.getElementById("editModal").style.display = "none";
            renderComputers();
        }

        document.getElementById("occupiedStatus").addEventListener('change', (e) => {
            const isOccupied = e.target.checked;
            if (!isOccupied && selectedComputer.studentId === studentDetails.studentId) {
              
                document.getElementById("studentName").value = '';
                document.getElementById("studentGrade").value = '';
                document.getElementById("studentStrand").value = '';
            }
        });

        renderComputers();
    </script>
</body>
</html>