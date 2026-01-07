<?php
session_start();
require_once 'php/db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}


if (isset($_POST['update_status'])) {
    if (!empty($_POST['selected_books']) && isset($_POST['bulk_status'])) {
        $selectedBooks = $_POST['selected_books'];
        $newStatus = $_POST['bulk_status'];

        $ids = implode(',', array_map('intval', $selectedBooks)); 
        $sqlUpdate = "UPDATE book_borrowals SET status = '$newStatus' WHERE id IN ($ids)";

        if ($conn->query($sqlUpdate) === TRUE) {
            echo "<script>alert('Statuses updated successfully!'); window.location.href='borrow_list.php';</script>";
        } else {
            echo "<script>alert('Error updating records: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Please select books and a status to update!');</script>";
    }
}


$sql = "SELECT id, student_id, firstName, lastName, book_name, borrow_date, status 
        FROM book_borrowals 
        WHERE status = 'Using' 
        ORDER BY firstName ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowed Books List</title>
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
    padding: 30px 15px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

header h2 {
    margin: 0;
    padding: 5px 0;
    word-spacing: 2px;
    flex-grow: 1;
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
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    background: linear-gradient(135deg, #e6f7e6, #f9f9f9);
    border-radius: 16px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    padding: 30px;
    width: 90%;
    margin: 20px auto;
    transition: transform 0.3s, box-shadow 0.3s;
}

.main-content:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3);
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
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 45px;
    height: 45px;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.toggle-btn:hover {
    background-color: #128b02;
    transform: scale(1.1);
}


table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 10px;
    background-color: white;
    margin-top: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 12px;
    overflow: hidden;
    transition: box-shadow 0.3s ease;
}

table:hover {
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

th, td {
    padding: 14px 20px;
    border: 1px solid #ddd;
    text-align: center;
    font-size: 16px;
}

th {
    background-color: #159603;
    color: white;
    cursor: pointer;
    font-weight: bold;
    text-transform: uppercase;
}

tr {
    background-color: #ffffff;
    transition: background-color 0.3s ease;
}

tr:hover {
    background-color: #f2f2f2;
}

td {
    border-radius: 5px;
}


.status-dropdown {
    padding: 10px 14px;
    font-size: 16px;
    border-radius: 6px;
    border: 1px solid #ccc;
    cursor: pointer;
    transition: border-color 0.3s ease, transform 0.2s ease;
}

.status-dropdown:hover {
    border-color: #159603;
    transform: scale(1.05);
}


.bulk-actions {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    margin: 20px auto;
    background: #ffffff;
    padding: 15px 20px;
    border-radius: 12px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    width: fit-content;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.bulk-actions:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
}


.select-all {
    transform: scale(1.3);
    cursor: pointer;
}


.update-btn {
    background-color: #159603;
    color: white;
    padding: 12px 18px;
    border: none;
    cursor: pointer;
    border-radius: 8px;
    font-size: 16px;
    font-weight: bold;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.update-btn:hover {
    background-color: #128b02;
    transform: scale(1.1);
}
    </style>
</head>
<body>
    <header>
        <h2>Borrowed Books List</h2>
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

    <div class="main-content">
        <h2>Borrowed Books</h2>

        <form method="POST">
            <div class="bulk-actions">
                <input type="checkbox" id="select-all" class="select-all"> Select All
                <select name="bulk_status" class="status-dropdown">
                    <option value="Using">Using</option>
                    <option value="Returned">Returned</option>
                </select>
                <button type="submit" name="update_status" class="update-btn">Update Status</button>
            </div>

            <table>
    <tr>
        <th>Select</th>
        <th>ID</th>
        <th>Student ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Book Name</th>
        <th>Borrow Date</th>
        <th>Status</th>
    </tr>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr id="row-<?= $row['id'] ?>">
                <td><input type="checkbox" name="selected_books[]" value="<?= $row['id'] ?>"></td>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['student_id']) ?></td>
                <td><?= htmlspecialchars($row['firstName']) ?></td>
                <td><?= htmlspecialchars($row['lastName']) ?></td>
                <td><?= htmlspecialchars($row['book_name']) ?></td>
                <td><?= htmlspecialchars($row['borrow_date']) ?></td>
                <td>
                    <select class="status-dropdown" onchange="updateStatus(<?= $row['id'] ?>, this.value)">
                        <option value="Using" <?= $row['status'] == 'Using' ? 'selected' : '' ?>>Using</option>
                        <option value="Returned" <?= $row['status'] == 'Returned' ? 'selected' : '' ?>>Returned</option>
                    </select>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="8">No borrowed books found.</td>
        </tr>
    <?php endif; ?>
</table>
        </form>
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
        document.getElementById('select-all').addEventListener('change', function() {
    let checkboxes = document.querySelectorAll('input[name="selected_books[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

    
function updateStatus(bookId, newStatus) {
    fetch('php/update_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${bookId}&status=${newStatus}`
    })
    .then(response => response.json()) 
    .then(data => {
        if (data.success) {
            alert("Status updated successfully!");
            if (newStatus === "Returned") {
                let row = document.getElementById(`row-${bookId}`);
                if (row) row.remove(); 
            }
        } else {
            alert("Failed to update status.");
        }
    })
    .catch(error => console.error("Error:", error));
}
</script>

</body>
</html>

<?php $conn->close(); ?>