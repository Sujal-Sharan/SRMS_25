<?php
require_once("DB_Connect.php");
session_start();

// Get the current month and year dynamically
$currentMonth = date('m'); // Example: 02 for February
$currentYear = date('Y');  // Example: 2025

//Filter variables
$roll = $_SESSION['college_roll'];         // Student roll number (already known from student.php)
$semester = 1;          // Optional semester param, set to current sem by deafult
// $semester = $_SESSION['semester']; //TODO: Add sem to session variable
$subject_id = NULL;      // Optional subject_id (can be empty or null)

if(isset($_GET['filter'])){
    $semester = filter_input(INPUT_GET, "filter_semester", FILTER_SANITIZE_SPECIAL_CHARS);
    $subject_id = filter_input(INPUT_GET, "filter_subject", FILTER_SANITIZE_SPECIAL_CHARS);
}

$sql = "SELECT
    student_id,
    subject_id,
    semester,
    COUNT(CASE WHEN status = 'Present' THEN 1 END) AS days_present  ,
    COUNT(CASE WHEN (status = 'Present' OR status = 'Absent') THEN 1 END) AS total_working_days,
    ( ((COUNT(CASE WHEN status = 'Present' THEN 1 END)) * 100 ) / COUNT(CASE WHEN (status = 'Present' OR status = 'Absent') THEN 1 END) ) AS attendance_percentage
FROM attendance
WHERE 1";

$types = "";   // To hold bind_param types (e.g., "s" for string, "i" for integer)
$values = [];  // To hold the values for binding

// Optional filters
if (!empty($roll)) {
    $sql .= " AND student_id = ?";
    $types .= "s";
    $values[] = $roll;
}

if (!empty($semester)) {
    $sql .= " AND semester = ?";
    $types .= "i";
    $values[] = $semester;
}

if (!empty($subject_id)) {
    $sql .= " AND subject_id = ?";
    $types .= "i";
    $values[] = $subject_id;
}

$sql .= " GROUP BY subject_id";

// Prepare the query
$stmt = $conn->prepare($sql);

// Bind parameters dynamically
if (!empty($values)) {
    $stmt->bind_param($types, ...$values);  // Spread operator to pass the parameters
}

$stmt->execute();
$result = $stmt->get_result();

//Table display header
$table_header = "ERROR";
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
    <title>Student Attendance Report</title>
    <link rel="stylesheet" href="Styles/global_base.css">
    <style>
        .btn{
            background-color: rgb(50, 225, 47);
            border: 1px, solid, black;
            margin: 20px;
            margin-bottom: 2px;
            padding: 10px;
        }
        .btn:hover{
            background-color: rgb(43, 193, 41);
            border: 2px, solid, black;
        }
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
    <header>Attendance</header>
    <div class="layout">
        <div class="sidebar">
        <img src="logo.png" alt="Logo" style="height: 100px; margin-left: 50px;">
            <nav>
                <a href="/SRMS/SRMS_25/student.php">Dashboard</a>
                <a href="/SRMS/SRMS_25/student_attendance.php" id="active">Attendance</a>
                <a href="/SRMS/SRMS_25/marks.php">View Marks</a>
                <a>Documents</a>
                <a>Update Details</a>
                <a>Settings</a>
                <a href="/SRMS/SRMS_25/logout.php">Log out</a>
            </nav>
        </div>

        <div class="main-content">
            <header>
                <h2>Student Attendance Report - <?php echo date("F Y"); ?></h2>
            </header>

            <div class="card">
                <form action="student_attendance.php" method="get">
                    <div class="filters">
                        <select id="filter_subject" name="filter_subject">
                            <option value="">Filter the result by subject</option>
                            <option value="Option1">Option 1</option>
                            <option value="Option2">Option 2</option>
                        </select>
                        <select id="filter_semester" name="filter_semester">
                            <option value="">Filter the result by semester</option>
                            <option value="1">Semester 1</option>
                            <option value="2">Semester 2</option>
                        </select>
                    </div>
                    <input type="submit" name="filter" class="btn" value="Submit">
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
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                #Adding row background color as visual filter
                                $rowColor = ((round($row['attendance_percentage'], 2)) < 60) ? "style='background-color: #FFCCCC; color: red;'" : "style='background-color:rgb(172, 235, 166); color: green;'";

                                echo "<tr>
                                        <td>{$row['subject_id']}</td>
                                        <td>{$row['semester']}</td>
                                        <td>{$row['days_present']}</td>
                                        <td>{$row['total_working_days']}</td>
                                        <td $rowColor>" . round($row['attendance_percentage'], 2) . "%</td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No records found</td></tr>";
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