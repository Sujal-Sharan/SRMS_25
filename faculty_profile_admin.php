<?php
require_once("DB_Connect.php");
session_start();

function buildTable($conn, $department, $designation, $search, $page, $limit) {
    $where = "WHERE 1=1";
    if (!empty($department)) $where .= " AND department = '$department'";
    if (!empty($designation)) $where .= " AND designation = '$designation'";
    if (!empty($search)) $where .= " AND (name LIKE '%$search%' OR email LIKE '%$search%')";

    $start_from = ($page - 1) * $limit;

    $sql = "SELECT * FROM faculty $where LIMIT $start_from, $limit";
    $result = $conn->query($sql);

    $sql_count = "SELECT COUNT(faculty_id) as total FROM faculty $where";
    $count_result = $conn->query($sql_count);
    $row = $count_result->fetch_assoc();
    $total_records = $row['total'];
    $total_pages = ceil($total_records / $limit);

    ob_start();
    echo '<table>';
    echo '<tr>
            <th>ID</th>
            <th>Name</th>
            <th>Department</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Designation</th>
            <th>Subjects</th>
          </tr>';
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['faculty_id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['department']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['phone']}</td>
                    <td>{$row['designation']}</td>
                  </tr>";
        }
    } else {
        echo '<tr><td colspan="5">No records found</td></tr>';
    }
    echo '</table>';

    // Pagination
    echo "<ul class='pagination'>";
    if ($page > 1) echo "<li><a href='#' onclick='goToPage(".($page - 1).")'>Prev</a></li>";
    for ($i = 1; $i <= $total_pages; $i++) {
        $active = $i == $page ? "active" : "";
        echo "<li><a href='#' class='$active' onclick='goToPage($i)'>$i</a></li>";
    }
    if ($page < $total_pages) echo "<li><a href='#' onclick='goToPage(".($page + 1).")'>Next</a></li>";
    echo "</ul>";

    return ob_get_clean();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dept = $_POST['department'] ?? '';
    $cat = $_POST['category'] ?? '';
    $search = $_POST['search'] ?? '';
    $page = $_POST['page'] ?? 1;
    echo buildTable($conn, $dept, $cat, $search, $page, 5);
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Faculty Profile - Admin</title>
    <link rel="stylesheet" href="Styles/global_base.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            background: white;
            min-height: 100vh;
        }
        .sidebar {
            margin-top: 80px;
            height: 800px;
            background-color: rgba(10, 25, 49, 0.85);
            color: white;
            padding: 16px;
            width: 226px;
            position: fixed;
            overflow-y: auto;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .container {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
            box-sizing: border-box;
        }
        h3 {
            font-size: 1.8rem;
            color: #333;
            text-align: center;
            margin-top: 100px;
        }
        .filters {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .filters select, .filters input {
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 200px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 16px;
        }
        table th {
            background: #1abc9c;
            color: white;
            padding: 15px;
            text-align: center;
        }
        table td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }
        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin-top: 20px;
        }
        .pagination li {
            margin: 0 5px;
        }
        .pagination a {
            padding: 8px 14px;
            border: 1px solid #ccc;
            text-decoration: none;
            color: black;
        }
        .pagination a.active {
            background-color: green;
            color: white;
        }
        header {
            background: #1abc9c;
            color: white;
            padding: 7px;
            display: flex;
            align-items: center;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <header style="background: #1abc9c; color: white; padding: 7px; display: flex; align-items: center; position: fixed; top: 0; width: 100%; z-index: 1000;">
        <img src="logo.png" alt="Logo" style="height: 100px; margin-right: 20px;">
        <div style="text-align: center; flex: 1;">
            <h1 style="margin: 0; font-size: 24px; font-weight: bold;padding-left: 20px;">TECHNO INTERNATIONAL NEWTOWN</h1>
            <p style="margin: 0; font-size: 14px;">(Formerly Known as Techno India College Of Technology)</p>
        </div>
        <div style="display: flex; align-items: center; font-size: 14px; margin-left: 5px;">
            <i class="fas fa-phone-alt" style="margin-right: 5px;"></i>
            <span><p>&#9742; +338910530723 / 8910530723</p></span>
        </div>
    </header>

    <div class="layout">
        <div class="sidebar">
            <nav>
                <a href="/SRMS/SRMS_25/admin_dashboard.php">Dashboard</a>
                <a href="/SRMS/SRMS_25/test.php">Attendance</a>
                <a href="/SRMS/SRMS_25/test.php">View Marks</a>
                <a href="/SRMS/SRMS_25/faculty_details.html"  id="active">Faculty Details</a>
                <a>Update Details</a>
                <a>Settings</a>
                <a href="/SRMS/SRMS_25/logout.php">Log out</a>
            </nav>
        </div>
    </div>

    <div class="container">
        <h3>Faculty Profile</h3>
        <div class="filters">
            <select id="deptFilter">
                <option value="">All Departments</option>
                <option value="CSE">CSE</option>
                <option value="IT">IT</option>
                <option value="ECE">ECE</option>
                <option value="ME">ME</option>
            </select>
            <select id="catFilter">
                <option value="">All Categories</option>
                <option value="Senior Professor">Senior Professor</option>
                <option value="Assistant Professor">Assistant Professor</option>
                <option value="Lab Instructor">Lab Instructor</option>
                <option value="Technician">Technician</option>
            </select>
            <input type="text" id="searchBox" placeholder="Search by name or email">
        </div>  
        <div id="facultyTable"></div>
    </div>

    <script>
        let currentPage = 1;

        function fetchData() {
            const dept = document.getElementById('deptFilter').value;
            const cat = document.getElementById('catFilter').value;
            const search = document.getElementById('searchBox').value;

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "faculty_profile_admin.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xhr.onload = function () {
                if (xhr.status === 200) {
                    document.getElementById("facultyTable").innerHTML = xhr.responseText;
                }
            };

            xhr.send(`department=${dept}&category=${cat}&search=${search}&page=${currentPage}`);
        }

        function goToPage(page) {
            currentPage = page;
            fetchData();
        }

        document.getElementById('deptFilter').addEventListener('change', () => { currentPage = 1; fetchData(); });
        document.getElementById('catFilter').addEventListener('change', () => { currentPage = 1; fetchData(); });
        document.getElementById('searchBox').addEventListener('keyup', () => { currentPage = 1; fetchData(); });

        window.onload = fetchData;
    </script>
</body>
</html>
