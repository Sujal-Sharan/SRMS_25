<?php
require_once("DB_Connect.php");
session_start();

// Get values from UI
// Will only display the result once filters selected and button is clicked
if(isset($_GET['apply_Filter'])){
    $subject = filter_input(INPUT_GET, "subject", FILTER_SANITIZE_SPECIAL_CHARS);
    $semester = filter_input(INPUT_GET, "semester", FILTER_SANITIZE_SPECIAL_CHARS);
    $dept = filter_input(INPUT_GET, "department", FILTER_SANITIZE_SPECIAL_CHARS);

    $section = filter_input(INPUT_GET, "section", FILTER_SANITIZE_SPECIAL_CHARS);
    $group = filter_input(INPUT_GET, "group", FILTER_SANITIZE_SPECIAL_CHARS);


    $sql = "SELECT 
                a.student_id AS student_id, 
                a.subject_id AS subject_id, 
                a.semester AS semester, 
                s.name AS name,
                s.department AS department
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

    $sql .= " GROUP BY a.student_id";

    // Prepare the query
    $stmt = $conn->prepare($sql);

    // Bind parameters dynamically
    if (!empty($values)) {
        $stmt->bind_param($types, ...$values);  // Spread operator to pass the parameters
    }

    $stmt->execute();
    $result = $stmt->get_result();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance</title>
    <link rel="stylesheet" href="Styles/global_base.css">
    <style>
        .sidebar {
            height: calc(100vh - 100px); 
            overflow-y: auto; 
        }
    </style>


</head>
<body>
    <header
        style="background: #1abc9c; color: white; padding: 7px; display: flex; align-items: center; position: fixed; top: 0; width: 100%; z-index: 1000;">
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
                <a href="view_Student_Marks.php">Marks</a>
                <a id='active'href="upload_attendance.php">Attendance</a>
                <a href="faculty_profile_admin.php">Faculty Profile</a>
                <a href="/SRMS/SRMS_25/admin_view_docs.php">Uploaded Documents</a>
                <a href="T_AddLogin.php">Add/Remove User</a>
                <a href="reset_password_UI.php">Reset Password</a>
                <a href="/SRMS/SRMS_25/logout.php"> Log out</a>
            </nav>
        </div>

        <div class="main-content">

            <div class="card">
                <form id="filterForm" action="" method="GET">
                    <h2>Upload Attendance</h2>

                    <div class="filters">
                        
                        <select id="department" name="department">
                            <option value="">Select Department</option>
                            <?php
                                $departments = ['CSE','IT','AIML','ECE','EE','ME','CIVIL'];
                                foreach($departments as $d) echo "<option value='$d'>$d</option>";
                            ?>
                        </select>

                        <select id="semester" name="semester">
                            <option value="">Semester</option>
                            <?php for ($i = 1; $i <= 8; $i++) echo "<option value='$i'>$i</option>"; ?>
                        </select>

                        <select id="section" name="section">
                            <option value="">Section</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                        </select>

                        <select id="group" name="group">
                            <option value="">Group</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="">Both</option>
                        </select>

                        <!--  TODO: Add subjects -->
                        <select id="subject" name="subject"><option value="">Select Subject ID</option></select>
                    </div>

                    <button type="submit" name="apply_Filter">Apply Filters</button>
                </form>
            </div>

            <div class="card">
                <form id="attendanceForm" method="POST" action="upload_save.php">

                    <!-- Submit button to save attendance in DataBase -->
                    <button id="submit" type="submit" name="save">Save Attendance</button>

                    <!-- Set date as current date, can be manually adjusted -->
                    <input type="date" name="attendance_date" id="date" value="<?php echo date('Y-m-d'); ?>">

                    <table id="attendanceTable">
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Department</th>
                            <th>Subject ID</th>
                            <th>Semester</th>
                            <th>Attendance</th>
                        </tr>
                        <?php
                        try{
                            if(isset($result)){
                                if($result->num_rows > 0){
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>
                                                <td><input type='text' name='student_id[]' value='" . htmlspecialchars($row["student_id"]) . "' readonly></td>
                                                <td><input type='text' name='name[]' value='" . htmlspecialchars($row["name"]) . "' readonly></td>
                                                <td><input type='text' name='dept[]' value='" . htmlspecialchars($row["department"]) . "' readonly></td>
                                                <td><input type='text' name='subject_id[]' value='" . htmlspecialchars($row["subject_id"]) . "' readonly></td>
                                                <td><input type='text' name='semester[]' value='" . htmlspecialchars($row["semester"]) . "'></td>

                                                    <!-- Checkbox maps attendance status per student_id -->
                                                <td>
                                                    <input type='hidden' name='all_ids[]' value='" . htmlspecialchars($row["student_id"]) . "'>
                                                    <input type='checkbox' name='attendance[" . $row["student_id"] . "]' value='PRESENT'>
                                                </td>

                                            </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5'>No records found</td></tr>";
                                }
                            }
                        }catch(Exception $e){
                            echo 'Message: ' .$e->getMessage();
                        }
                        ?>
                    </table>
                </form>
            </div>
        </div>
    </div>
</body>
</html>