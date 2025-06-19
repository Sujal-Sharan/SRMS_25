<?php
require_once("DB_Connect.php");
require_once("session_logout.php");

// Get values from UI
// Will only display the result once filters selected and button is clicked
if(isset($_GET['apply_Filter'])){
    $dept = filter_input(INPUT_GET, "department", FILTER_SANITIZE_SPECIAL_CHARS);
    $subject = filter_input(INPUT_GET, "subject", FILTER_SANITIZE_SPECIAL_CHARS);
    $semester = filter_input(INPUT_GET, "semester", FILTER_SANITIZE_SPECIAL_CHARS);
    $testType = filter_input(INPUT_GET, "test_type", FILTER_SANITIZE_SPECIAL_CHARS);

    $sql = "SELECT 
                m.student_id AS student_id,
                m.test_type AS test_type,
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
    $values = [$testType, $semester, $testType, $semester];  // To hold the values for binding

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

    if (!empty($testType)) {
        $sql .= " AND m.test_type = ?";
        $types .= "s";
        $values[] = $testType;
    }

    if (!empty($semester)) {
        $sql .= " AND m.semester = ?";
        $types .= "s";
        $values[] = $semester;
    }

    $sql .= " GROUP BY subj.subject_id, subj.subject_code, subj.subject_name, m.semester";

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
    <title>Upload Marks</title>
    <link rel="stylesheet" href="Styles/global_base.css" />
  	<link rel="icon" type="image/x-icon" href="logo.png">

    <style>
        .card {
			background-color: white;
			padding: 20px;
			margin-top: 20px;
			border-radius: 10px;
			box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
			width: 100%;
			overflow-x: auto;
		}

		.card table {
			width: 100%;
			border-collapse: collapse;
		}

		.card th, .card td {
			border: 1px solid #ccc;
			padding: 10px;
			text-align: center;
		}

		.card input[type="text"] {
			border: none;
			outline: none;
			background: transparent;
			text-align: center;
			/* width: 100%;  */
			width: auto;
			font-size: 14px;
			color: #333;
		}

		.card input[type="text"] {
			background-color:rgb(241, 241, 241);
		}
		.card input[type="text"]:read-only {
			background-color: transparent;
		}

		.card input[type="text"]::selection {
			background: #1abc9c;
			color: white;
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
				<a href="view_Student_Marks.php">View Marks</a>
				<a id="active" href="upload_marks_UI.php">Add Marks</a>
				<a href="view_Student_Attendance.php">View Attendance</a>
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
                <h2>Upload Student Marks</h2>
                <form id="filterForm" action="" method="GET">
                    <div class="filters">

                        <!-- Exam Type Dropdown -->
                        <select id="test_type" name="test_type">
                            <option value="">Select Exam Type</option>
							<?php
							$tests = ["CA1", "CA2", "CA3", "CA4", "PCA1", "PCA2"];

							$selectedTest = $_GET['test_type'] ?? ''; // Use $_POST if you're using POST

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

                    <button class="btn-save" type="submit" name="apply_Filter">Apply Filters</button>
                </form>
            </div>

            <div class="card">
                <form action="preview_table.php" method="POST" enctype="multipart/form-data">
                    <input type="file" name="csv_file" required>

                    <!-- Hidden field, table name wll be pre-set as given in database -->
                    <input type="text" name="table" value="marks" hidden>
                    <button class="btn-save" type="submit">Preview</button>
                </form>
            </div>

            <div class="card">
                <form action="upload_marks_Backend.php" method="POST">

                    <button class="btn-save" id="submit" type="submit" name="save">Save Marks</button>

                    <table>
                        <tr>
                            <th>Student_Id</th>
                            <th>Student_Name</th>
                            <th>Subject</th>
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
                                                <td><input type='text' name='subject' value='" . htmlspecialchars($row["subject_name"]) . "' readonly></td>
                                                <td><input type='text' name='test' value='" . htmlspecialchars($row["test_type"]) . "' readonly></td>
                                                <td><input type='text' name='semester' value='" . htmlspecialchars($row["semester"]) . "' readonly></td>
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
</body>
</html>