<?php 
require_once("DB_Connect.php");
require_once("session_logout.php");

// Get values from UI
if(isset($_GET['apply_Filter'])){
    // $subject = filter_input(INPUT_GET, "subject", FILTER_SANITIZE_SPECIAL_CHARS);
    $semester = filter_input(INPUT_GET, "semester", FILTER_SANITIZE_SPECIAL_CHARS);
    $dept = filter_input(INPUT_GET, "department", FILTER_SANITIZE_SPECIAL_CHARS);
    $batch = filter_input(INPUT_GET, "batch", FILTER_SANITIZE_SPECIAL_CHARS);

    $section = filter_input(INPUT_GET, "section", FILTER_SANITIZE_SPECIAL_CHARS);
    $group = filter_input(INPUT_GET, "group", FILTER_SANITIZE_SPECIAL_CHARS);

}

$sql = "SELECT 
            college_roll AS student_id, 
            university_roll AS uni_id, 
            name AS name,
            current_semester AS semester, 
			dob AS dob,
			email AS email,
			gender AS gender,
            department AS department,
			batch_year AS batch
        FROM 
            students
        WHERE 1";

$types = "";   // To hold bind_param types (e.g., "s" for string, "i" for integer)
$values = [];  // To hold the values for binding

// Optional filters
if (!empty($semester)) {
    $sql .= " AND current_semester = ?";
    $types .= "i";
    $values[] = $semester;
}

if (!empty($dept)) {
    $sql .= " AND department = ?";
    $types .= "s";
    $values[] = $dept;
}

if (!empty($batch)) {
    $sql .= " AND batch_year = ?";
    $types .= "s";
    $values[] = $batch;
}

$sql .= " GROUP BY college_roll";

// Prepare the query
$stmt = $conn->prepare($sql);

// Bind parameters dynamically
if (!empty($values)) {
    $stmt->bind_param($types, ...$values);  // Spread operator to pass the parameters
}

$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Student Profile View</title>
	<link rel="stylesheet" href="Styles/global_base.css">
  	<link rel="icon" type="image/x-icon" href="logo.png">

	<style>
		/* Style for the card and table to expand properly */
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
	<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    
	<header>
		<img src="logo.png" alt="Logo" style="height: 120px; margin-right: 10px;">
		<div style="text-align: center; flex: 1;">
			<h1 style="margin: 0; font-size: 25px; font-weight: bold;">TECHNO INTERNATIONAL NEW TOWN</h1>
			<p style="margin: 0; font-size: 17px;">(Formerly Known as Techno India College Of Technology)</p>
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
				<a id="active" href="studentProfile.php">Student Profile</a>
				<a href="view_Student_Marks.php">View Marks</a>
				<a href="upload_marks_UI.php">Add Marks</a>
				<a href="view_Student_Attendance.php">View Attendance</a>
				<a href="upload_attendance.php">Add Attendance</a>
				<a href="faculty_profile_admin.php">Faculty Profile</a>
				<a href="view_docs.php">View Documents</a>
				<a href="T_AddLogin.php">Add/Remove User</a>
				<a href="reset_password_UI.php">Reset Password</a>
				<a href="logout.php"> Log out</a>
			</nav>
    	</div>

		<div class="main-content">
			<div class="card">
				<h3>Student Profile Details</h3>
				<form action="" method="GET">
					
					<div class="filters">
						<!-- Department Dropdown -->
						<select name="department" id="department">
							<option value="">Select Department</option>
							<?php
							$departments = ["CSE", "IT", "ECE", "CSBS", "EE", "ME", "AIML", "CIVIL"];

							$selectedDepartment= $_GET['department'] ?? ''; // Use $_POST if you're using POST

							foreach ($departments as $department) {
								$selected = ($selectedDepartment === $department) ? 'selected' : '';
								echo "<option value=\"$department\" $selected>$department</option>";
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

						<!-- Section Dropdown -->
						<!-- <select id="section" name="section">
							<option value="">Select Section</option>
							<option value="A">Section A</option>
							<option value="B">Section B</option>
							<option value="C">Section C</option>
						</select> -->
												
						<!-- Group Dropdown -->
						<!-- <select id="group" name="group">
							<option value="">Select Group</option>
							<option value="A">Group A</option>
							<option value="B">Group B</option>
						</select> -->

						<!-- Batch Dropdown -->
						<select id="batch" name="batch">
							<option value="">Select Batch</option>
							<?php
							$batches = ['2019-23', '2020-24', '2021-25','2022-26','2023-27','2024-28','2025-29'];

							$selectedBatches= $_GET['batch'] ?? ''; // Use $_POST if you're using POST

							foreach ($batches as $batch) {
								$selected = ($selectedBatches === $batch) ? 'selected' : '';
								echo "<option value=\"$batch\" $selected>$batch</option>";
							}
							?>
						</select>
					</div>

					<button class="btn-save" type="submit" name="apply_Filter">Apply Filters</button>
					<!-- <button class="btn-reset" type="button" name="reset_Filter" onclick="resetFilters()">Reset</button> -->

				</form>
			</div>

			<div class="card">
				<table>
					<tr>
						<th>College_ID</th>
						<th>University_ID</th>
						<th>Name</th>
						<th>Department</th>
						<th>Semester</th>
						<th>DOB</th>
						<th>E-mail</th>
						<th>Gender</th>
						<th>Batch</th>
					</tr>
					<?php
					try{
						if(isset($result)){
							if($result->num_rows > 0){
								while ($row = $result->fetch_assoc()) {
									echo "<tr>
											<td><input type='text' name='' value='" . htmlspecialchars($row["student_id"]) . "' readonly></td>
											<td><input type='text' name='' value='" . htmlspecialchars($row["uni_id"]) . "' readonly></td>
											<td><input type='text' name='' value='" . htmlspecialchars($row["name"]) . "' readonly></td>
											<td><input type='text' name='' value='" . htmlspecialchars($row["department"]) . "' readonly></td>
											<td><input type='text' name='' value='" . htmlspecialchars($row["semester"]) . "' readonly></td>
											<td><input type='text' name='' value='" . htmlspecialchars($row["dob"]) . "' readonly></td>
											<td><input type='text' name='' value='" . htmlspecialchars($row["email"]) . "' readonly></td>
											<td><input type='text' name='' value='" . htmlspecialchars($row["name"]) . "' readonly></td>
											<td><input type='text' name='' value='" . htmlspecialchars($row["batch"]) . "' readonly></td>
										</tr>";
								}
							} else {
								echo "<tr><td colspan='9'>No records found</td></tr>";
							}
						}
					}catch(Exception $e){
						echo 'Message: ' . $e->getMessage();
					}
					?>  
				</table>
			</div>
		</div>
	</div>

	<script>
		function resetFilters() {
            document.getElementById("department").value = "";
            document.getElementById("section").value = "";
            document.getElementById("group").value = "";
            document.getElementById("semester").value = "";
            document.getElementById("batch").value = "";
        }
	</script>
</body>
</html>
``` 




