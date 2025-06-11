<?php 
require_once("DB_Connect.php");
session_start();

// Get values from UI
if(isset($_GET['apply_Filter'])){
    // $subject = filter_input(INPUT_GET, "subject", FILTER_SANITIZE_SPECIAL_CHARS);
    $semester = filter_input(INPUT_GET, "semester", FILTER_SANITIZE_SPECIAL_CHARS);
    $dept = filter_input(INPUT_GET, "department", FILTER_SANITIZE_SPECIAL_CHARS);

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
            <span><p>&#9742; +338910530723 / 8910530723</p></span>
        </div>
	</header>
  
  <div class="layout">
    <div class="sidebar">
      <nav>
        <a href="admin_dashboard.php">Dashboard</a>
        <a id="active" href="studentProfile.php">Student Profile</a>
        <a href="">Marks</a>
        <a href="">Attendance</a>
        <a href="faculty_profile_admin.php">Faculty Profile</a>
        <a href="/SRMS/SRMS_25/admin_view_docs.php">Uploaded Documents</a>
        <a href="T_AddLogin.php">Add/Remove User</a>
        <a href="reset_password_UI.php">Reset Password</a>
        <a href="/SRMS/SRMS_25/logout.php"> Log out</a>
      </nav>
    </div>

    <div class="main-content">
		<div class="card">
			<h2>Student Profile</h2>
			<form action="" method="GET">

				<!-- TODO: Add proper subject filters and fix filter UI-->
				<div class="filters">

					<!-- Department Dropdown -->
					<!-- <label for="department">Department:</label> -->
					<select id="department" name="department">
						<option value="">Select Department</option>
						<?php
							$departments = ['CSE','IT','AIML','ECE','EE','ME','CIVIL'];
							foreach($departments as $d) echo "<option value='$d'>$d</option>";
						?>
					</select>

					<!-- Section Dropdown -->
					<!-- <label for="section">Section:</label> -->
					<select id="section" name="section">
						<option value="">Select Section</option>
						<option value="A">Section A</option>
						<option value="B">Section B</option>
						<option value="C">Section C</option>
					</select>
											
					<!-- Group Dropdown -->
					<!-- <label for="group">Group:</label> -->
					<select id="group" name="group">
						<option value="">Select Group</option>
						<option value="A">Group A</option>
						<option value="B">Group B</option>
					</select>

					<!-- Semester Dropdown -->
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

					<!-- Batch Dropdown -->
					<select id="batch" name="batch">
						<option value="">Select Batch</option>
						<?php
							$batch = ['2019-23', '2020-24', '2021-25','2022-26','2023-27','2024-28','2025-29'];
							foreach($batch as $b) echo "<option value='$b'>$b</option>";
						?>
					</select>
				</div>

				<button type="submit" name="apply_Filter">Apply Filters</button>
				<button type="button" name="reset_Filter" onclick="resetFilters()">Reset</button>
				<!-- <input type="submit" name="submit" placeholder="Submit"> -->

			</form>
		</div>

		<div class="card">
			<table>
				<tr>
					<th>College_ID</th>
					<th>University_ID</th>
					<th>Name</th>
					<th>Semester</th>
					<th>DOB</th>
					<th>E-mail</th>
					<th>Gender</th>
					<th>Department</th>
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
										<td><input type='text' name='' value='" . htmlspecialchars($row["semester"]) . "' readonly></td>
										<td><input type='text' name='' value='" . htmlspecialchars($row["dob"]) . "' readonly></td>
										<td><input type='text' name='' value='" . htmlspecialchars($row["email"]) . "' readonly></td>
										<td><input type='text' name='' value='" . htmlspecialchars($row["name"]) . "' readonly></td>
										<td><input type='text' name='' value='" . htmlspecialchars($row["department"]) . "' readonly></td>
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
``` 




