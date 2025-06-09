<?php
require_once("DB_Connect.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_attendance'])) {
    $subject_id = $_POST['subject'];
    $faculty_id = $_POST['faculty'];
    $date = $_POST['date'];
    $students = $_POST['student_id'];

    foreach ($students as $sid) {
        $is_present = isset($_POST["present_$sid"]) ? 1 : 0;
        $sql = "INSERT INTO attendance(student_id, subject_id, faculty_id, attendance_date, is_present)
                VALUES ('$sid', '$subject_id', '$faculty_id', '$date', '$is_present')
                ON DUPLICATE KEY UPDATE is_present='$is_present'";
        mysqli_query($conn, $sql);
    }
    echo "<script>alert('Attendance successfully saved!');</script>";
}
?>

<!DOCTYPE html>
<html>
    <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - Student Record Management System</title>
    <link rel="stylesheet" href="Styles/global_base.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        header {
            background: #1abc9c;
            color: white;
            padding: 0;
            display: flex;
            align-items: center;
            position: fixed;
            top: 0;
            width: 100%;
            height: 110px;
            z-index: 1000;
        }

        header img {
            height: 100px;
            padding: 5px;
        }

        .container {
            display: flex;
            margin-top: 110px;
            border-radius: 6px;
        }

        .sidebar {
            width: 220px; /* Reduced size */
            height: 100vh;
            background-color: #001f3f;
            position: fixed;
            left: 0;
            top: 0;
            color: white;
            padding-top: 20px;

        }

        .sidebar a, .sidebar button {
        display: block;
        margin: 10px 20px;
        padding: 10px;
        background: #002f5f;
        color: white;
        text-align: left;
        border: none;
        text-decoration: none;
        border-radius: 5px;
        }

        .sidebar .active {
        background-color: gold;
        color: black;
        }

        /*.sidebar nav a {
            display: block;
            color: white;
            background-color: #123456;
            margin: 10px;
            padding: 10px;
            text-decoration: none;
            border-radius: 10px;
            text-align: center;
            border-radius: 20px 6px 6px 20px;
        }

        .sidebar nav a:hover,
        .sidebar nav a.active {
            background-color: #ffc107;
            color: black;
            border-radius: 20px 6px 6px 20px;
        }*/
        .main-content {
        margin-left: 230px; /* Should be slightly more than sidebar width */
        padding: 20px;
        background-color: #f2f2f2;
        min-height: 100vh;
        }

        /* Optional: Fix header */
        .header {
        background-color: #00bfa5;
        padding: 15px 230px 15px 230px; /* Left padding matches main-content */
        color: white;
        font-weight: bold;
        text-align: center;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 99;
        }



        /* TODO: Some style changes to nav elements, such as active, hover and base */
        #active {
            color: black;
            border-color: #666;
        }

        .content {
            margin-left: 226px;
            padding: 20px;
            flex: 1;
            background: #f4f4f4;
            min-height: calc(100vh - 110px);
        }

        .dashboard-cards {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            padding-top: 45px;
        }

        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            flex: 1;
            min-width: 200px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            font-size: 1rem;
            color: #666;
            text-align: center;
        }

        .card .value {
            font-size: 1.5rem;
            margin-top: 10px;
            text-align: center;
        }

        .recent-activity,
        .student-overview {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th,
        table td {
            padding: 10px;
            border-bottom: 1px solid #ccc;
            text-align: left;
        }

        /* Card styling */
        .task-card {
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .task-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
        }

        /* Colored header bar */
        .card-header {
            font-weight: bold;
            font-size: 1rem;
            padding: 10px;
            border-radius: 6px 6px 0 0;
            color: white;
            text-align: center;
        }

        .card-header.blue {
            background-color: #007bff;
        }

        .card-header.green {
            background-color: #28a745;
        }

        .card-header.orange {
            background-color: #fd7e14;
        }

        /* Task list, input and buttons */
        .task-list {
            list-style: none;
            padding-left: 0;
            margin-top: 15px;
        }

        .task-input-box {
            display: flex;
            margin-top: 10px;
            align-items: center;
        }

        .task-input {
            flex: 1;
            padding: 6px 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .save-btn {
            margin-left: 8px;
            background-color: #17a2b8;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 6px 14px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }

        .save-btn:hover {
            background-color: #138496;
        }

        .add-task-btn {
            margin-top: 12px;
            background-color: #ffc107;
            color: black;
            border: none;
            border-radius: 4px;
            padding: 6px 14px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        .add-task-btn:hover {
            background-color: #e0a800;
        }

        .task-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .done-btn {
            background: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 5px 10px;
            margin-left: 10px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .done-btn:hover {
            background-color: #218838;
        }


        .task-list li {
            margin-bottom: 0;
            display: block;
        }

        .task-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
        }

        .task-list hr {
            border: 0;
            border-top: 1px solid #ddd;
            margin: 0;
        }
    </style>
</head>

<body>
    <header
        style="background: #1abc9c; color: white; padding: 0px; display: flex; align-items: center; position: fixed; top: 0; width: 100%; z-index: 1000;">
        <img src="logo.png" alt="Logo" style="height: 120px; margin-right: 10px;">
        <div style="text-align: center; flex: 1;">
            <h1 style="margin: 0; font-size: 25px; font-weight: bold;">TECHNO INTERNATIONAL NEW TOWN</h1>
            <p style="margin: 0; font-size: 17px;">(Formerly Known as Techno India College Of Technology)</p>
        </div>
        <div style="display: flex; align-items: center; font-size: 15px; margin-left: 2px;">
            <i class="fas fa-phone-alt" style="margin-right: 10px;"></i>
            <span>
                <p>&#9742; +338910530723 / 8910530723</p>
            </span>
        </div>
    </header>

    <div class="container">
            <div class="sidebar">
            <nav>
                <a href="admin_dashboard.php">Dashboard</a>
                <a href="studentProfile.php">Student Profile</a>
                <a href="view_Student_Marks.php">Marks</a>
                <a id='active'href="attendance_upload(new).php">Attendance</a>
                <a href="faculty_profile_admin.php">Faculty Profile</a>
                <a href="/SRMS/SRMS_25/admin_view_docs.php">Uploaded Documents</a>
                <a href="T_AddLogin.php">Add/Remove User</a>
                <a href="reset_password_UI.php">Reset Password</a>
                <a href="/SRMS/SRMS_25/logout.php"> Log out</a>
            </nav>
        </div>
    </div>




<h2>📋 Attendance Upload Panel</h2>

<div class="main-content">
<form id="filterForm">
    <select id="department" name="department" required>
        <option value="">Select Department</option>
        <?php
        $departments = ['CSE','IT','AIML','ECE','EE','ME','CIVIL'];
        foreach($departments as $d) echo "<option value='$d'>$d</option>";
        ?>
    </select>

    <select id="year" name="year" required>
        <option value="">Year</option>
        <?php for ($i = 1; $i <= 4; $i++) echo "<option value='$i'>$i</option>"; ?>
    </select>

    <select id="semester" name="semester" required>
        <option value="">Semester</option>
        <?php for ($i = 1; $i <= 8; $i++) echo "<option value='$i'>$i</option>"; ?>
    </select>

    <select id="section" name="section" required>
        <option value="">Section</option>
        <option value="A">A</option>
        <option value="B">B</option>
        <option value="C">C</option>
    </select>

    <select id="group" name="group" required>
        <option value="">Group</option>
        <option value="G1">G1</option>
        <option value="G2">G2</option>
        <option value="Both">Both</option>
    </select>

    <select id="subject" name="subject" required><option value="">Select Subject ID</option></select>
    <select id="faculty" name="faculty" required><option value="">Select Faculty ID</option></select>
    <input type="date" name="attendance_date" id="date" required>

    <button type="button" onclick="loadStudents()">Load Students</button>
</form>

<form id="attendanceForm" method="POST" class="hidden">
    <input type="hidden" name="subject" id="form_subject">
    <input type="hidden" name="faculty" id="form_faculty">
    <input type="hidden" name="date" id="form_date">
    <table id="attendanceTable">
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Faculty ID</th>
                <th>Subject ID</th>
                <th>Attendance(Present/Absent)</th>
                
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    <button type="submit" name="save_attendance">Save Attendance</button>
</form>
</div>
<script>
    document.querySelectorAll("#department, #year, #semester").forEach(el => {
        el.addEventListener("change", () => {
            let dept = document.getElementById("department").value;
            let year = document.getElementById("year").value;
            let sem = document.getElementById("semester").value;

            if(dept && year && sem){
                fetch("", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `fetch_subjects_faculty=1&dept=${dept}&year=${year}&sem=${sem}`
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById("subject").innerHTML = data.subjects;
                    document.getElementById("faculty").innerHTML = data.faculty;
                });
            }
        });
    });

    function loadStudents(){
        const form = document.getElementById("filterForm");
        const data = new FormData(form);
        data.append("fetch_students", 1);

        fetch("", { method: "POST", body: data })
        .then(res => res.json())
        .then(data => {
            const tbody = document.querySelector("#attendanceTable tbody");
            tbody.innerHTML = "";
            if(data.length === 0) return alert("No students found!");

            data.forEach(stu => {
                let row = document.createElement("tr");
                row.innerHTML = `
                    <td><input type="hidden" name="student_id[]" value="${stu.student_id}">${stu.student_id}</td>
                    <td>${stu.name}</td>
                    <td><input type="checkbox" name="present_${stu.student_id}" value="1"></td>
                `;
                tbody.appendChild(row);
            });

            document.getElementById("form_subject").value = document.getElementById("subject").value;
            document.getElementById("form_faculty").value = document.getElementById("faculty").value;
            document.getElementById("form_date").value = document.getElementById("date").value;

            document.getElementById("attendanceForm").classList.remove("hidden");
        });
    }
</script>

<?php
// Handle dynamic subject & faculty fetch
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fetch_subjects_faculty'])) {
    $dept = $_POST['dept'];
    $year = $_POST['year'];
    $sem = $_POST['sem'];

    $subjects = mysqli_query($conn, "SELECT * FROM subjects WHERE department='$dept' AND year='$year' AND semester='$sem'");
    $faculty = mysqli_query($conn, "SELECT * FROM faculty_details WHERE department='$dept'");

    $subject_options = "<option value=''>Select Subject</option>";
    while($s = mysqli_fetch_assoc($subjects)) {
        $subject_options .= "<option value='{$s['subject_id']}'>{$s['subject_name']}</option>";
    }

    $faculty_options = "<option value=''>Select Faculty</option>";
    while($f = mysqli_fetch_assoc($faculty)) {
        $faculty_options .= "<option value='{$f['faculty_id']}'>{$f['name']}</option>";
    }

    echo json_encode(["subjects" => $subject_options, "faculty" => $faculty_options]);
    exit;
}

// Handle student fetch
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fetch_students'])) {
    $dept = $_POST['department'];
    $year = $_POST['year'];
    $sem = $_POST['semester'];
    $section = $_POST['section'];
    $group = $_POST['group'];

    $query = "SELECT student_id, name FROM students WHERE department='$dept' AND year='$year' AND semester='$sem' AND section='$section'";
    if ($group != "Both") $query .= " AND group_name='$group'";
    
    $res = mysqli_query($conn, $query);
    $data = [];
    while($row = mysqli_fetch_assoc($res)) $data[] = $row;
    echo json_encode($data);
    exit;
}
?>
</body>
</html>
