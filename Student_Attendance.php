<?php
require_once("DB_Connect.php");
require_once("session_logout.php");
// session_start();

// Get the current month and year dynamically
$currentMonth = date('m'); // Example: 02 for February
$currentYear = date('Y');  // Example: 2025

//Filter variables
$roll = $_SESSION['college_roll'];         // Student roll number (already known from student.php)
$semester = $_SESSION['current_semester'];          // Optional semester param, set to current sem by deafult

if(isset($_GET['filter'])){
    $semester = filter_input(INPUT_GET, "semester", FILTER_SANITIZE_SPECIAL_CHARS);
    $subject_id = filter_input(INPUT_GET, "subject", FILTER_SANITIZE_SPECIAL_CHARS);
}

$sql = "SELECT
    a.student_id AS student_id,
    s.subject_id AS subject_id,
    s.subject_name AS subject_name,
    a.semester AS semester,
    COUNT(CASE WHEN a.status = 'Present' THEN 1 END) AS days_present  ,
    COUNT(CASE WHEN (a.status = 'Present' OR a.status = 'Absent') THEN 1 END) AS total_working_days,
    ( ((COUNT(CASE WHEN a.status = 'Present' THEN 1 END)) * 100 ) / COUNT(CASE WHEN (a.status = 'Present' OR a.status = 'Absent') THEN 1 END) ) AS attendance_percentage
FROM attendance a
JOIN subjects s
ON a.subject_id = s.subject_id
WHERE 1";

$types = "";   // To hold bind_param types (e.g., "s" for string, "i" for integer)
$values = [];  // To hold the values for binding

// Optional filters
if (!empty($roll)) {
    $sql .= " AND a.student_id = ?";
    $types .= "s";
    $values[] = $roll;
}

if (!empty($semester)) {
    $sql .= " AND a.semester = ?";
    $types .= "i";
    $values[] = $semester;
}

if (!empty($subject_id)) {
    $sql .= " AND a.subject_id = ?";
    $types .= "i";
    $values[] = $subject_id;
}

$sql .= " GROUP BY a.subject_id ";

// Prepare the query
$stmt = $conn->prepare($sql);

// Bind parameters dynamically
if (!empty($values)) {
    $stmt->bind_param($types, ...$values);  // Spread operator to pass the parameters
}

$stmt->execute();
$result = $stmt->get_result();

//Table display header
$table_header = "ALL SEMESTER   ";
switch($semester){
    case "1":
        $table_header = "FIRST SEMESTER";
        break;
    case "2":
        $table_header = "SECOND SEMESTER";
        break;
    case "3":
        $table_header = "THIRD SEMESTER";
        break;
    case "4":
        $table_header = "FOURTH SEMESTER";
        break;
    case "5":
        $table_header = "FIFTH SEMESTER";
        break;
    case "6":
        $table_header = "SIXTH SEMESTER";
        break;
    case "7":
        $table_header = "SEVENTH SEMESTER";
        break;
    case "8":
        $table_header = "EIGHTH SEMESTER";
        break;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Attendance Report</title>
    <link rel="stylesheet" href="Styles/global_base.css">
    <link rel="icon" type="image/x-icon" href="logo.png">

    <style>
        /* .btn{
            background-color: rgb(50, 225, 47);
            border: 1px, solid, black;
            margin: 20px;
            margin-bottom: 2px;
            padding: 10px;
        }
        .btn:hover{
            background-color: rgb(43, 193, 41);
            border: 2px, solid, black;
        } */
        #table_header{
            border: none;
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
        <img src="logo.png" alt="Logo" style="height: 120px; margin-right: 10px;">
        <div style="text-align: center; flex: 1;">
            <h1 style="margin: 0; font-size: 25px; font-weight: bold;">TECHNO INTERNATIONAL NEW TOWN</h1>
            <p style="margin: 0; font-size: 17px;">(Formerly Known as Techno India College Of Technology)</p>
        </div>
        <div style="display: flex; align-items: center; font-size: 15px; margin-left: 2px;">
            <i class="fas fa-phone-alt" style="margin-right: 10px;"></i>
            <span><p>Logged in as <?php echo ($_SESSION['name']) ?? $_SESSION['user_id'] ?></p></span>
        </div>
    </header>

    <div class="layout">
        <div class="sidebar">
            <nav>
                <a href="student.php">Dashboard</a>
                <a id="active" href="student_attendance.php">Attendance</a>
                <a href="marks.php">View Marks</a>
                <a href="upload_file_student_UI.php">Add Documents</a>
                <!-- <a>Update Details</a> -->
                <a href="logout.php">Log out</a>
            </nav>
        </div>

        <div class="main-content">
            <div class="card">
                <h2>My Attendance Report - <?php echo date("F Y"); ?></h2>
                <form action="student_attendance.php" method="GET">
                    <div class="filters">
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
                    <input type="submit" name="filter" class="btn-save" value="Submit">
                </form>
            </div>

            <div class="card">
                <input type="text" id="table_header" readonly name="table_header" value="<?php echo $table_header; ?>">
                <table>
                    <tr>
                        <th>Subject</th>
                        <th>Semester</th>
                        <th>Present Days</th>
                        <th>Total Working Days</th>
                        <th>Attendance %</th>
                    </tr>

                    <?php
                    try{
                        if(isset($result)){
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                #Adding row background color as visual filter
                                $rowColor = ((round($row['attendance_percentage'], 2)) < 60) ? "style='background-color: #FFCCCC; color: red;'" : "style='background-color:rgb(172, 235, 166); color: green;'";

                                echo "<tr>
                                        <td>{$row['subject_name']}</td>
                                        <td>{$row['semester']}</td>
                                        <td>{$row['days_present']}</td>
                                        <td>{$row['total_working_days']}</td>
                                        <td $rowColor>" . round($row['attendance_percentage'], 2) . "%</td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No records found</td></tr>";
                        }
                    }
                    }catch(Exception $e){
                        echo 'Message: ' .$e->getMessage();
                    }
                    mysqli_close($conn);
                    ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>