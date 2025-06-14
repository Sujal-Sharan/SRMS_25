<?php
require_once("DB_Connect.php");
session_start();

// Get values from UI
if(isset($_GET['submit'])){
    $subject = filter_input(INPUT_GET, "subject", FILTER_SANITIZE_SPECIAL_CHARS);
    $semester = filter_input(INPUT_GET, "semester", FILTER_SANITIZE_SPECIAL_CHARS);
    $dept = filter_input(INPUT_GET, "department", FILTER_SANITIZE_SPECIAL_CHARS);

    $section = filter_input(INPUT_GET, "section", FILTER_SANITIZE_SPECIAL_CHARS);
    $group = filter_input(INPUT_GET, "group", FILTER_SANITIZE_SPECIAL_CHARS);

}

$sql = "SELECT 
            a.student_id AS student_id, 
            a.subject_id AS subject_id, 
            a.semester AS semester, 
            s.name AS name,
            s.department AS department,

            COUNT(CASE WHEN a.status = 'Present' THEN 1 END) AS days_present,
            COUNT(CASE WHEN (a.status = 'Present' OR a.status = 'Absent') THEN 1 END) AS total_working_days,
            (((COUNT(CASE WHEN a.status = 'Present' THEN 1 END)) * 100 ) / COUNT(CASE WHEN (a.status = 'Present' OR a.status = 'Absent') THEN 1 END)) AS attendance_percentage
        FROM 
            attendance a
        JOIN 
            students s ON a.student_id = s.college_roll
        WHERE 1";

$types = "";   // To hold bind_param types (e.g., "s" for string, "i" for integer)
$values = [];  // To hold the values for binding

// Optional filters
if (!empty($subject)) {
    $sql .= " AND a.subject_id = ?";
    $types .= "i";
    $values[] = $subject;
}

if (!empty($semester)) {
    $sql .= " AND a.semester = ?";
    $types .= "i";
    $values[] = $semester;
}

if (!empty($dept)) {
    $sql .= " AND s.department = ?";
    $types .= "s";
    $values[] = $dept;
}

$sql .= " GROUP BY a.student_id LIMIT 50";

// Prepare the query
$stmt = $conn->prepare($sql);

// Bind parameters dynamically
if (!empty($values)) {
    $stmt->bind_param($types, ...$values);  // Spread operator to pass the parameters
}

$stmt->execute();
$result = $stmt->get_result();


// Table header
$table_header = "ATTENDANCE ";
if(isset($semester)){
    switch($semester){
        case "1":
            $table_header .= "FIRST SEMESTER ";
            break;
        case "2":
            $table_header .= "SECOND SEMESTER ";
            break;
        case "3":
            $table_header .= "THIRD SEMESTER ";
            break;
        case "4":
            $table_header .= "FOURTH SEMESTER ";
            break;
        case "5":
            $table_header .= "FIFTH SEMESTER ";
            break;
        case "6":
            $table_header .= "SIXTH SEMESTER ";
            break;
        case "7":
            $table_header .= "SEVENTH SEMESTER ";
            break;
        case "8":
            $table_header .= "EIGHTH SEMESTER ";
            break;
    }
}
else{
    $table_header .= " ALL SEMESTER ";
}

if(isset($dept)){
    $table_header .= $dept;
}
else{
    $table_header .= " ALL DEPARTMENT ";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance</title>
    <link rel="stylesheet" href="Styles/global_base.css">
    <style>
        #table_header{
            border: none;
            text-align: center;
            width: 350px;
            margin-left: 200px;
            margin-bottom: 10px;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            font-size: medium;
        }
    </style>
</head>
<body>
    <header>
        <img src="logo.png" alt="Logo" style="height: 100px; margin-right: 20px;">
        <div style="text-align: center; flex: 1;">
            <h1 style="margin: 0; font-size: 24px; font-weight: bold;">TECHNO INTERNATIONAL NEWTOWN</h1>
            <p style="margin: 0; font-size: 14px;">(Formerly Known as Techno India College Of Technology)</p>
        </div>
        <div style="display: flex; align-items: center; font-size: 14px; margin-left: 5px;">
            <i class="fas fa-phone-alt" style="margin-right: 5px;"></i>
            <span>
                <p>&#9742; +338910530723 / 8910530723</p>
            </span>
        </div>
    </header>

    <div class="layout">
		<div class="sidebar">
			<nav>
				<a href="admin_dashboard.php">Dashboard</a>
				<a href="studentProfile.php">Student Profile</a>
				<a href="view_Student_Marks.php">View Marks</a>
				<a href="upload_marks_UI.php">Add Marks</a>
				<a id="active" href="view_Student_Attendance.php">View Attendance</a>
				<a href="upload_attendance.php">Add Attendance</a>
				<a href="faculty_profile_admin.php">Faculty Profile</a>
				<a href="/SRMS/SRMS_25/admin_view_docs.php">Uploaded Documents</a>
				<a href="T_AddLogin.php">Add/Remove User</a>
				<a href="reset_password_UI.php">Reset Password</a>
				<a href="logout.php"> Log out</a>
			</nav>
		</div>

        <div class="main-content">
            <div class="card">
                <h2>View Students Attendance</h2>
                <form action="" method="GET">

                    <!-- TODO: Add proper subject filters and fix filter UI-->
                    <div class="filters">

                        <!-- Department Dropdown -->
                        <!-- <label for="department">Department:</label> -->
                        <select id="department" name="department" onchange="handleDepartmentChange()">
                            <option value="">Select Department</option>
                            <option value="CSE">CSE</option>
                            <option value="ECE">ECE</option>
                            <option value="IT">IT</option>
                        </select>

                        <!-- Section Dropdown -->
                        <!-- <label for="section">Section:</label> -->
                        <select id="section" name="section" onchange="handleSectionChange()" disabled>
                            <option value="">Select Section</option>
                        </select>
                                                
                        <!-- Group Dropdown -->
                        <!-- <label for="group">Group:</label> -->
                        <select id="group" name="group" disabled>
                            <option value="">Select Group</option>
                            <option value="A">Group A</option>
                            <option value="B">Group B</option>
                        </select>
                    </div>

                    <div class="filters">

                        <!-- Sem Type Dropdown -->
                        <select id="semester" name="semester">
                            <option value="">Select Semester</option>
                            <option value="1">Semester 1</option>
                            <option value="2">Semester 2</option>
                            <option value="3">Semester 3</option>
                            <option value="4">Semester 4</option>
                            <option value="5">Semester 5</option>
                            <option value="6">Semester 6</option>
                            <option value="7">Semester 7</option>
                            <option value="8">Semester 8</option>
                        </select>

                        <!-- TODO: Subject Dropdown -->
                        <!-- Will display name and value will be subject_id -->
                        <select id="subject" name="subject" disabled>
                            <option value="">Select Subject</option>
                        </select>
                    </div>

                    <input type="submit" name="submit" placeholder="Submit">
                </form>
            </div>

            <div class="card">
                <input type="text" id="table_header" readonly name="table_header" value="<?php echo $table_header; ?>">
                <table>
                    <tr>
                        <th>Student_Id</th>
                        <th>Student_Name</th>
                        <th>Department</th>
                        <th>Subject_Id</th>
                        <th>Semester</th>
                        <th>Present</th>
                        <th>Total_Days</th>
                        <th>Percentage</th>
                    </tr>
                    <?php
                    try{
                        if($result->num_rows > 0){
                            while ($row = $result->fetch_assoc()) {
                                // TOOD: Add custom colour breakpoints instead of hard coded
                                $rowColor = ((round($row['attendance_percentage'], 2)) < 60) ? "style='background-color: #FFCCCC; color: red;'" : "style='background-color:rgb(172, 235, 166); color: green;'";

                                echo "<tr>
                                        <td>" . $row["student_id"] . "</td>
                                        <td>" . $row["name"] . "</td>
                                        <td>" . $row["department"] . "</td>
                                        <td>" . $row["subject_id"] . "</td>
                                        <td>" . $row["semester"] . "</td>
                                        <td>" . $row["days_present"] . "</td>
                                        <td>" . $row["total_working_days"] . "</td>
                                        <td $rowColor>" . $row["attendance_percentage"] . "</td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No records found</td></tr>";
                        }
                    }catch(Exception $e){
                        echo 'Message: ' .$e->getMessage();
                    }
                    ?>
                </table>
            </div>
            <div id="result">
                
            </div>
        </div>
    </div>

    <script>
        function handleDepartmentChange() {
            const dept = document.getElementById("department").value;
            const section = document.getElementById("section");
            const subject = document.getElementById("subject");
            const group = document.getElementById("group");

            // Reset
            section.innerHTML = '<option value="">Select Section</option>';
            section.disabled = true;
            subject.innerHTML = '<option value="">Select Subject</option>';
            subject.disabled = true;
            group.disabled = true;
            group.value = "";

            let sections = [];

                switch (dept) {
                case "IT":
                    sections = ["A", "B"];
                    break;
                case "CSE":
                case "ECE":
                default:
                    sections = ["A", "B", "C"];
                    break;
                }

            if (dept) {
                section.disabled = false;
                subject.disabled = false;

                sections.forEach(sec => {
                const opt = document.createElement("option");
                opt.value = sec;
                opt.textContent = sec;
                section.appendChild(opt);
                });
            }
        }

        function handleSectionChange() {
            const sectionVal = document.getElementById("section").value;
            const group = document.getElementById("group");

            group.disabled = !sectionVal;
            if (!sectionVal) {
                group.value = "";
            }
        }
  </script>
</body>
</html>