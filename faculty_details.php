<?php
require_once("DB_Connect.php");
require_once("session_logout.php");

if(isset($_GET['submit'])){
    $dept = filter_input(INPUT_GET, "department", FILTER_SANITIZE_SPECIAL_CHARS);

}

$sql = "SELECT 
            f.user_id AS faculty_id,
            f.name AS name, 
            f.department AS department, 
            f.designation AS designation,
            f.phone AS phone,
            f.email AS email,
            f.joined_at AS joined_at
        FROM 
            faculty f
        WHERE 1";

$types = "";   // To hold bind_param types (e.g., "s" for string, "i" for integer)
$values = [];  // To hold the values for binding

// Optional filters
if (!empty($dept)) {
    $sql .= " AND f.department = ?";
    $types .= "s";
    $values[] = $dept;
}

$sql .= " GROUP BY f.user_id, f.joined_at";

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Details</title>
    <link rel="stylesheet" href="Styles/global_base.css">
    <link rel="icon" type="image/x-icon" href="logo.png">
    
</head>
<body>
    <header>
        <img src="logo.png" alt="Logo" style="height: 100px; margin-right: 20px;">
        <div style="text-align: center; flex: 1;">
            <h1 style="margin: 0; font-size: 24px; font-weight: bold;">TECHNO INTERNATIONAL NEWTOWN</h1>
            <p style="margin: 0; font-size: 14px;">(Formerly Known as Techno India College Of Technology)</p>
        </div>
        <div style="display: flex; align-items: center; font-size: 14px;">
            <i class="fas fa-phone-alt" style="margin-right: 5px;"></i>
            <span><p>Logged in as <?php echo ($_SESSION['name']) ?? $_SESSION['user_id'] ?></p></span>
        </div>
    </header>
    
    <div class="layout">
        <div class="sidebar"> 
            <nav>
                <a href="faculty_dashboard.php">Dashboard</a>
                <a href="faculty_view_attendace.php">View Attendance</a>
                <a href="faculty_upload_attendance.php">Update Attendance</a>
                <a href="faculty_view_marks.php">View Marks</a>
                <a href="faculty_upload_marks.php">Add Marks</a>
                <a id="active" href="faculty_details.php">Faculty Details</a>
                <a href="logout.php">Log out</a>
            </nav>
        </div>

        <div class="main-content">
            <div class="card">
                <h3>Faculty Details</h3>
                <form action="" method="GET">
                    <div class="filters">

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
                    </div>

                    <input class="btn-save" type="submit" name="submit" placeholder="Submit">
                </form> 
            </div>

            <div class="card">
                <button class="btn-save" onclick="exportTableToCSV()">Export CSV</button>
                <table id="myTable">
                    <tr>
                        <th>Faculty_Id</th>
                        <th>Faculty Name</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Joining</th>
                    </tr>
                    <?php
                    try{
                        if(isset($result)){
                            if($result->num_rows > 0){
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td><input type='text' name='faculty_id[]' value='" . htmlspecialchars($row["faculty_id"]) . "' readonly></td>
                                            <td><input type='text' name='name[]' value='" . htmlspecialchars($row["name"]) . "' readonly></td>
                                            <td><input type='text' name='department[]' value='" . htmlspecialchars($row["department"]) . "' readonly></td>
                                            <td><input type='text' name='designation[]' value='" . htmlspecialchars($row["designation"]) . "' readonly></td>
                                            <td><input type='text' name='phone[]' value='" . htmlspecialchars($row["phone"]) . "' readonly></td>
                                            <td><input type='text' name='email[]' value='" . htmlspecialchars($row["email"]) . "' readonly></td>
                                            <td><input type='text' name='joined[]' value='" . htmlspecialchars($row["joined_at"]) . "' readonly></td>
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
