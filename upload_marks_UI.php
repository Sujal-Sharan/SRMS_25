<?php
require_once("DB_Connect.php");
session_start();

// Get values from UI
// Will only display the result once filters selected and button is clicked
if(isset($_GET['apply_Filter'])){
    $dept = filter_input(INPUT_GET, "department", FILTER_SANITIZE_SPECIAL_CHARS);
    $subject = filter_input(INPUT_GET, "subject", FILTER_SANITIZE_SPECIAL_CHARS);

    $semester = filter_input(INPUT_GET, "semester", FILTER_SANITIZE_SPECIAL_CHARS);
    $test = filter_input(INPUT_GET, "test", FILTER_SANITIZE_SPECIAL_CHARS);

    // TODO: Add section and group to student table
    $section = filter_input(INPUT_GET, "section", FILTER_SANITIZE_SPECIAL_CHARS);
    $group = filter_input(INPUT_GET, "group", FILTER_SANITIZE_SPECIAL_CHARS);


    $sql = "SELECT 
                m.student_id AS student_id,
                s.name AS student_name,
                subj.subject_id AS subject_id,
                subj.subject_code AS subject_code,
                subj.subject_name AS subject_name,
                m.semester AS semester,

                CASE 
                    WHEN MAX(m.test_type = ? AND m.is_absent = TRUE AND m.semester = ? AND m.marks_obtained IS NULL) THEN 'ABSENT'
                    ELSE CAST(MAX(CASE WHEN m.test_type = ? AND m.semester = ? THEN m.marks_obtained END) AS CHAR)
                END AS marks

            FROM 
                marks m
            JOIN 
                students s ON m.student_id = s.college_roll
            JOIN 
                subjects subj ON m.subject_id = subj.subject_id
            WHERE 1";  // 'WHERE 1' always return true


    $types = "sisi";   // To hold bind_param types (e.g., "s" for string, "i" for integer)
    $values = [$test, $semester, $test, $semester];  // To hold the values for binding

    // Optional filters
    if (!empty($subject)) {
        $sql .= " AND m.subject_id = ?";
        $types .= "i";
        $values[] = $subject;
    }

    if (!empty($dept)) {
        $sql .= " AND s.department = ?";
        $types .= "s";
        $values[] = $dept;
    }

    $sql .= " GROUP BY m.student_id";

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
    <title>Document</title>
    <link rel="stylesheet" href="Styles/global_base.css" />

</head>
<body>
    <header>Top Header</header>

    <div class="layout">
        <div class="sidebar">
            <nav>
                <a href="admin_dashboard.php">Dashboard</a>
                <a href="studentProfile.php">Student Profile</a>
                <a href="view_Student_Marks.php">View Marks</a>
                <a id="active" href="upload_marks_UI.php">Add Marks</a>
                <a href="view_Student_Attendance.php">View Attendance</a>
                <a href="upload_attendance.php">Add Attendance</a>
                <a href="faculty_profile_admin.php">Faculty Profile</a>
                <a href="/SRMS/SRMS_25/admin_view_docs.php">Uploaded Documents</a>
                <a href="T_AddLogin.php">Add/Remove User</a>
                <a href="reset_password_UI.php">Reset Password</a>
                <a href="/SRMS/SRMS_25/logout.php"> Log out</a>
            </nav>
        </div>

        <div class="main-content">
            <header>Upload Marks</header>

            <div class="card">
                <form id="filterForm" action="" method="GET">
                    <div class="filters">
                        
                        <select id="department" name="department" required>
                            <option value="">Select Department</option>
                            <?php
                                $departments = ['CSE','IT','AIML','ECE','EE','ME','CIVIL'];
                                foreach($departments as $d) echo "<option value='$d'>$d</option>";
                            ?>
                        </select>

                        <select id="semester" name="semester" required>
                            <option value="">Semester</option>
                            <?php for ($i = 1; $i <= 8; $i++) echo "<option value='$i'>$i</option>"; ?>
                        </select>

                        <select id="subject" name="subject">
                            <option value="">Select Subject</option>
                        </select>
                        
                        <select id="test" name="test" required>
                            <option value="">Select Test</option>
                            <option value="CA1">CA1</option>
                            <option value="CA2">CA2</option>
                            <option value="CA3">CA3</option>
                            <option value="CA4">CA4</option>
                            <option value="PCA1">PCA1</option>
                            <option value="PCA2">PCA2</option>
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
                    </div>

                    <button type="submit" name="apply_Filter">Apply Filters</button>
                    <button type="button" name="reset_Filter" onclick="resetFilters()">Reset</button>
                </form>
            </div>

            <div class="card">
                <form action="upload_marks_Backend.php" method="POST">

                    <button id="submit" type="submit" name="save">Save Marks</button>

                    <table>
                        <tr>
                            <th>Student_Id</th>
                            <th>Student_Name</th>
                            <th>Subject_Id</th>
                            <th>Test</th>
                            <th>Semester</th>
                            <th>Marks</th>
                        </tr>
                        <?php
                        try{
                            if(isset($result)){
                                if($result->num_rows > 0){
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>
                                                <td><input type='text' name='student_id[]' value='" . htmlspecialchars($row["student_id"]) . "' readonly></td>
                                                <td><input type='text' name='name[]' value='" . htmlspecialchars($row["student_name"]) . "' readonly></td>
                                                <td><input type='text' name='subject' value='" . htmlspecialchars($row["subject_id"]) . "' readonly></td>
                                                <td><input type='text' name='test' value='" . htmlspecialchars($test) . "' readonly></td>
                                                <td><input type='text' name='semester' value='" . htmlspecialchars($semester) . "' readonly></td>
                                                <td><input type='text' name='mark[]' value='" .  htmlspecialchars($row["marks"]) . "' " . (is_null($row["marks"]) ? "" : "readonly") . "></td>
                                            </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6'>No records found</td></tr>";
                                }
                            }
                        }catch(Exception $e){
                            echo 'Message: ' . $e->getMessage();
                        }
                        ?>  
                    </table>
                </form>
            </div>
        </div>
    </div>

    <script>
        function resetFilters() {
            document.getElementById("department").value = "";
            // document.getElementById("semester").value = "";
            document.getElementById("section").value = "";
            document.getElementById("group").value = "";
            // document.getElementById("subject").value = "";
            // document.getElementById("searchInput").value = "";
        }
    </script>
</body>
</html>