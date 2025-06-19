<?php
require_once("DB_Connect.php");
require_once("session_logout.php");

// Get values from UI
if(isset($_GET['submit'])){
    $subject = filter_input(INPUT_GET, "subject", FILTER_SANITIZE_SPECIAL_CHARS);
    $test = filter_input(INPUT_GET, "exam", FILTER_SANITIZE_SPECIAL_CHARS);
    $semester = filter_input(INPUT_GET, "semester", FILTER_SANITIZE_SPECIAL_CHARS);
    $dept = filter_input(INPUT_GET, "department", FILTER_SANITIZE_SPECIAL_CHARS);
}

$sql = "SELECT 
            m.student_id AS student_id, 
            m.subject_id AS subject_id, 
            subj.subject_name AS subject_name,
            m.semester AS semester, 
            m.test_type AS test_type,
            m.marks_obtained AS marks_obtained, 
            m.is_absent AS is_absent,
            s.name AS name,
            s.department AS department
        FROM 
            marks m
        JOIN 
            students s ON m.student_id = s.college_roll
        JOIN 
            subjects subj ON m.subject_id = subj.subject_id
        WHERE 1 ";

$types = "";   // To hold bind_param types (e.g., "s" for string, "i" for integer)
$values = [];  // To hold the values for binding

// Optional filters
if (!empty($test)) {
    $sql .= " AND m.test_type = ?";
    $types .= "s";
    $values[] = $test;
}

if (!empty($subject)) {
    $sql .= " AND m.subject_id = ?";
    $types .= "i";
    $values[] = $subject;
}

if (!empty($semester)) {
    $sql .= " AND m.semester = ?";
    $types .= "i";
    $values[] = $semester;
}

if (!empty($dept)) {
    $sql .= " AND s.department = ?";
    $types .= "s";
    $values[] = $dept;
}

$sql .= " GROUP BY m.student_id, subj.subject_id, m.semester, subj.subject_name";

// Prepare the query
$stmt = $conn->prepare($sql);

// Bind parameters dynamically
if (!empty($values)) {
    $stmt->bind_param($types, ...$values);  // Spread operator to pass the parameters
}

$stmt->execute();
$result = $stmt->get_result();

// Table header
$table_header = "";
if(isset($semester)){
    switch($semester){
        case "1":
            $table_header = "FIRST SEMESTER ";
            break;
        case "2":
            $table_header = "SECOND SEMESTER ";
            break;
        case "3":
            $table_header = "THIRD SEMESTER ";
            break;
        case "4":
            $table_header = "FOURTH SEMESTER ";
            break;
        case "5":
            $table_header = "FIFTH SEMESTER ";
            break;
        case "6":
            $table_header = "SIXTH SEMESTER ";
            break;
        case "7":
            $table_header = "SEVENTH SEMESTER ";
            break;
        case "8":
            $table_header = "EIGHTH SEMESTER ";
            break;
    }
}
else{
    $table_header = " ALL SEMESTER ";
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
    <title>View Marks</title>
    <link rel="stylesheet" href="Styles/global_base.css">
  	<link rel="icon" type="image/x-icon" href="logo.png">

    <style>
        /* TODO: Fix bug: Text goes over box bound */
        #table_header{
            border: none;
            width: 300px;
            margin-left: 50px;
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
            <span><p>Logged in as <?php echo $_SESSION['user_id'] ?></p></span>
        </div>
    </header>

    <div class="layout">
		<div class="sidebar">
			<nav>
				<a href="admin_dashboard.php">Dashboard</a>
				<a href="studentProfile.php">Student Profile</a>
				<a id="active" href="view_Student_Marks.php">View Marks</a>
				<a href="upload_marks_UI.php">Add Marks</a>
				<a href="view_Student_Attendance.php">View Attendance</a>
				<a href="upload_attendance.php">Add Attendance</a>
				<a href="faculty_profile_admin.php">Faculty Profile</a>
				<a href="upload_file_UI.html">Upload Document</a>
				<a href="view_docs.php">View Documents</a>
				<a href="T_AddLogin.php">Add/Remove User</a>
				<a href="reset_password_UI.php">Reset Password</a>
				<a href="logout.php"> Log out</a>
			</nav>
		</div>

        <div class="main-content">
            <div class="card" >
                <h2>View Students Marks</h2>
                
                <form action="" method="GET">

                    <div class="filters">

                        <!-- Exam Type Dropdown -->
                        <select id="exam" name="exam">
                            <option value="">Select Exam Type</option>
							<?php
							$tests = ["CA1", "CA2", "CA3", "CA4", "PCA1", "PCA2"];

							$selectedTest = $_GET['exam'] ?? ''; // Use $_POST if you're using POST

							foreach ($tests as $test) {
								$selected = ($selectedTest === $test) ? 'selected' : '';
								echo "<option value=\"$test\" $selected>$test</option>";
							}
							?>
                        </select>

                        <!-- Department Dropdown -->
						<select name="department" id="department">
							<option value="">Select Department</option>
							<?php
							$departments = ["CSE", "IT", "ECE", "CSBS", "EE", "ME", "AIML", "CIVIL"];

							$selectedDepartment = $_GET['department'] ?? ''; // Use $_POST if you're using POST

							foreach ($departments as $department) {
								$selected = ($selectedDepartment === $department) ? 'selected' : '';
								echo "<option value=\"$department\" $selected>$department</option>";
							}
							?>
						</select>

                        <!-- Subject Dropdown -->
                        <select name="subject" id="subject">
                            <option value="">Select Subject</option>
                            <?php
                            $subjects = ["TEST_SUBJECT","ENGLISH", "ETHICS", "DSA", "MATHS", "PHYSICS", "CHEMISTRY","BIOLOGY", "ADVMATHS", "CYBERLAW", "ERP", "ECOMMERCE"];
                            $selectedSubject = $_GET['subject'] ?? '';

                            for ($i = 1; $i <= 10; $i++) {
                                $selected = ($selectedSubject == $i) ? 'selected' : '';
                                echo "<option value=\"$i\" $selected> $subjects[$i] </option>";
                            }
                            ?>
                        </select>

                        <!-- Semester Dropdown  -->
                        <select id="semester" name="semester">
                            <option value="">Select Semester</option>
                            <?php
                            $selectedSemester = $_GET['semester'] ?? '';
                            for ($i = 1; $i <= 8; $i++) {
                                $selected = ($selectedSemester == $i) ? 'selected' : '';
                                echo "<option value=\"$i\" $selected>Semester $i</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <input class="btn-save" type="submit" name="submit" placeholder="Submit">
                </form>
            </div>

            <div class="card">
                <button class="btn-save" onclick="exportTableToCSV()">Export CSV</button>
                <input type="text" id="table_header" readonly name="table_header" value="<?php echo $table_header; ?>">
                <table id="myTable">
                    <tr>
                        <th>Student_Id</th>
                        <th>Student_Name</th>
                        <th>Department</th>
                        <th>Subject</th>
                        <th>Semester</th>
                        <th>Test</th>
                        <th>Marks</th>
                    </tr>
                    <?php
                    try{
                        if($result->num_rows > 0){
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . $row["student_id"] . "</td>
                                        <td>" . $row["name"] . "</td>
                                        <td>" . $row["department"] . "</td>
                                        <td>" . $row["subject_name"] . "</td>
                                        <td>" . $row["semester"] . "</td>
                                        <td>" . $row["test_type"] . "</td>
                                        <td>" . $marks = is_null($row['marks_obtained']) ? 'Absent' : $row['marks_obtained'] . "</td>
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
        
    function exportTableToCSV() {
        const table = document.getElementById("myTable");
        let csv = [];
        for (let row of table.rows) {
            let rowData = [];
            for (let cell of row.cells) {
                rowData.push(cell.textContent);
            }
            csv.push(rowData.join(","));
        }

        const csvBlob = new Blob([csv.join("\n")], { type: "text/csv" });
        const url = URL.createObjectURL(csvBlob);
        const a = document.createElement("a");
        a.href = url;
        a.download = "marks_export.csv";
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }
  </script>
</body>
</html>