<?php
require_once("DB_Connect.php");
require_once("session_logout.php");

// function buildTable($conn, $department, $designation, $search, $page, $limit) {
//     $where = "WHERE 1=1";
//     if (!empty($department)) $where .= " AND department = '$department'";
//     if (!empty($designation)) $where .= " AND designation = '$designation'";
//     if (!empty($search)) $where .= " AND (name LIKE '%$search%' OR email LIKE '%$search%')";

//     $start_from = ($page - 1) * $limit;

//     $sql = "SELECT * FROM faculty $where LIMIT $start_from, $limit";
//     $result = $conn->query($sql);

//     $sql_count = "SELECT COUNT(faculty_id) as total FROM faculty $where";
//     $count_result = $conn->query($sql_count);
//     $row = $count_result->fetch_assoc();
//     $total_records = $row['total'];
//     $total_pages = ceil($total_records / $limit);

//     ob_start();
//     echo '<table>';
//     echo '<tr>
//             <th>ID</th>
//             <th>Name</th>
//             <th>Department</th>
//             <th>Email</th>
//             <th>Phone</th>
//             <th>Designation</th>
//             <th>Subjects</th>
//           </tr>';
//     if ($result && $result->num_rows > 0) {
//         while ($row = $result->fetch_assoc()) {
//             echo "<tr>
//                     <td>{$row['faculty_id']}</td>
//                     <td>{$row['name']}</td>
//                     <td>{$row['department']}</td>
//                     <td>{$row['email']}</td>
//                     <td>{$row['phone']}</td>
//                     <td>{$row['designation']}</td>
//                     <td>{$row['subjects']}</td>
//                   </tr>";
//         }
//     } else {
//         echo '<tr><td colspan="5">No records found</td></tr>';
//     }
//     echo '</table>';

//     // Pagination
//     echo "<ul class='pagination'>";
//     if ($page > 1) echo "<li><a href='#' onclick='goToPage(".($page - 1).")'>Prev</a></li>";
//     for ($i = 1; $i <= $total_pages; $i++) {
//         $active = $i == $page ? "active" : "";
//         echo "<li><a href='#' class='$active' onclick='goToPage($i)'>$i</a></li>";
//     }
//     if ($page < $total_pages) echo "<li><a href='#' onclick='goToPage(".($page + 1).")'>Next</a></li>";
//     echo "</ul>";

//     return ob_get_clean();
// }

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $dept = $_POST['department'] ?? '';
//     $cat = $_POST['category'] ?? '';
//     $search = $_POST['search'] ?? '';
//     $page = $_POST['page'] ?? 1;
//     echo buildTable($conn, $dept, $cat, $search, $page, 5);
//     exit;
// }
// Get values from UI
if(isset($_GET['apply_Filter'])){
    $dept = filter_input(INPUT_GET, "department", FILTER_SANITIZE_SPECIAL_CHARS);
    $designation = filter_input(INPUT_GET, "designation", FILTER_SANITIZE_SPECIAL_CHARS);

}

$sql = "SELECT 
            faculty_id, 
            user_id, 
            name,
            department,
            designation,
            email,
            joined_at
        FROM 
            faculty
        WHERE 1 ";

$types = "";   // To hold bind_param types (e.g., "s" for string, "i" for integer)
$values = [];  // To hold the values for binding

// Optional filters
if (!empty($dept)) {
    $sql .= " AND department = ? ";
    $types .= "s";
    $values[] = $dept;
}

if (!empty($designation)) {
    $sql .= " AND designation = ? ";
    $types .= "s";
    $values[] = $designation;
}

$sql .= " GROUP BY faculty_id ";

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
    <title>Faculty Profile - Admin</title>
    <link rel="stylesheet" href="Styles/global_base.css">
	<link rel="icon" type="image/x-icon" href="logo.png">

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    
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
				<a href="studentProfile.php">Student Profile</a>
				<a href="view_Student_Marks.php">View Marks</a>
				<a href="upload_marks_UI.php">Add Marks</a>
				<a href="view_Student_Attendance.php">View Attendance</a>
				<a href="upload_attendance.php">Add Attendance</a>
				<a id="active" href="faculty_profile_admin.php">Faculty Profile</a>
				<a href="/SRMS/SRMS_25/admin_view_docs.php">Uploaded Documents</a>
				<a href="T_AddLogin.php">Add/Remove User</a>
				<a href="reset_password_UI.php">Reset Password</a>
				<a href="logout.php"> Log out</a>
			</nav>
		</div>

        <div class="main-content">
            <div class="card">
                <h2>Faculty Profile</h2>
                <form action="" method="GET">
                    <div class="filters">

						<!-- Department Dropdown -->
						<select name="department" id="department">
							<option value="">Select Department</option>
							<?php
							$departments = ["CSE", "IT", "ECE", "CSBS", "EE", "ME", "AIML", "CIVIL"];

							$selectedDepartment = $_GET['department'] ?? '';

							foreach ($departments as $department) {
								$selected = ($selectedDepartment === $department) ? 'selected' : '';
								echo "<option value=\"$department\" $selected>$department</option>";
							}
							?>
						</select>

                        <select id="designation" name="designation">
                            <option value="">Select Designation</option>
							<?php
							$designations = ["HOD", "Senior Professor", "Assistant Professor", "Lab Instructor", "Technician"];

							$selectedDesignation = $_GET['designation'] ?? '';

							foreach ($designations as $designation) {
								$selected = ($selectedDesignation === $designation) ? 'selected' : '';
								echo "<option value=\"$designation\" $selected>$designation</option>";
							}
							?>
                        </select>
                    </div>

                    <button class="btn-save" type="submit" name="apply_Filter">Apply Filters</button>
                </form>
            </div>  

        <div class="card">
			<button class="btn-save" onclick="exportTableToCSV()">Export CSV</button>
            <table id="myTable">
                <tr>
                    <th>Faculty_Id</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Designation</th>
                    <th>Email</th>
                    <th>Joined_At</th>
                </tr>
                <?php
                try{
					if(isset($result)){
						if($result->num_rows > 0){
							while ($row = $result->fetch_assoc()) {
								echo "<tr>
										<td><input type='text' name='' value='" . htmlspecialchars($row["user_id"]) . "' readonly></td>
										<td><input type='text' name='' value='" . htmlspecialchars($row["name"]) . "' readonly></td>
										<td><input type='text' name='' value='" . htmlspecialchars($row["department"]) . "' readonly></td>
										<td><input type='text' name='' value='" . htmlspecialchars($row["designation"]) . "' readonly></td>
										<td><input type='text' name='' value='" . htmlspecialchars($row["email"]) . "' readonly></td>
										<td><input type='text' name='' value='" . htmlspecialchars($row["joined_at"]) . "' readonly></td>
									</tr>";
							}
						} else {
							echo "<tr><td colspan='7'>No records found</td></tr>";
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
