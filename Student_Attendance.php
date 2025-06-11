<?php
require_once("DB_Connect.php");
session_start();

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

$sql .= " GROUP BY subject_id ";

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
    <title>My Attendance Report</title>
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
    <header>
        <img src="logo.png" alt="Logo" style="height: 120px; margin-right: 10px;">
        <div style="text-align: center; flex: 1;">
            <h1 style="margin: 0; font-size: 25px; font-weight: bold;">TECHNO INTERNATIONAL NEW TOWN</h1>
            <p style="margin: 0; font-size: 17px;">(Formerly Known as Techno India College Of Technology)</p>
        </div>
        <div style="display: flex; align-items: center; font-size: 15px; margin-left: 2px;">
            <i class="fas fa-phone-alt" style="margin-right: 10px;"></i>
            <span><p>&#9742; +338910530723 / 8910530723</p></span>
        </div>
    </header>

    <div class="layout">
        <div class="sidebar">
            <nav>
                <a href="student.php">Dashboard</a>
                <a id="active" href="student_attendance.php">Attendance</a>
                <a href="marks.php">View Marks</a>
                <a href="upload_file_student_UI.html">Add Documents</a>
                <!-- <a>Update Details</a> -->
                <a href="logout.php">Log out</a>
            </nav>
        </div>

        <div class="main-content">
            <div class="card">
                <h2>My Attendance Report - <?php echo date("F Y"); ?></h2>
                <form action="student_attendance.php" method="get">
                    <div class="filters">

                        <select id="subject" name="subject" required>
                            <option value="">Filter the result by subject</option>
                            <option value="1"> DSA </option>
                            <option value="2"> MATHS </option>
                            <option value="3"> CYBER-LAW </option>
                            <option value="4"> ERP </option>
                            <option value="5"> PROJECT </option>
                        </select>

                        <select id="semester" name="semester" required>
                            <option value="">Filter the result by semester</option>
                            <option value="1">Semester 1</option>
                            <option value="2">Semester 2</option>
                            <option value="3">Semester 3</option>
                            <option value="4">Semester 4</option>
                            <option value="5">Semester 5</option>
                            <option value="6">Semester 6</option>
                            <option value="7">Semester 7</option>
                            <option value="8">Semester 8</option>
                        </select>
                    </div>
                    <input type="submit" name="filter" class="btn" value="Submit">
                </form>
            </div>

            <div class="card">
                <input type="text" id="table_header" readonly name="table_header" value="<?php echo $table_header; ?>">
                <table>
                    <tr>
                        <th>Subject_Id</th>
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