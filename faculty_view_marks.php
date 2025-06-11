<?php
require_once("DB_Connect.php");
session_start();

$dept = $_SESSION['department'];

// Get values from UI
if(isset($_GET['submit'])){
    $subject = filter_input(INPUT_GET, "subject", FILTER_SANITIZE_SPECIAL_CHARS);
    $test = filter_input(INPUT_GET, "exam", FILTER_SANITIZE_SPECIAL_CHARS);
    $semester = filter_input(INPUT_GET, "semester", FILTER_SANITIZE_SPECIAL_CHARS);

    $section = filter_input(INPUT_GET, "section", FILTER_SANITIZE_SPECIAL_CHARS);
    $group = filter_input(INPUT_GET, "group", FILTER_SANITIZE_SPECIAL_CHARS);
}

$sql = "SELECT 
            m.student_id AS student_id, 
            m.subject_id AS subject_id, 
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
        WHERE 1 ";

// ON m.student_id = s.college_roll


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

// Only first 50 results are displayed (Will removed after pagination)
$sql .= " ORDER BY s.college_roll LIMIT 50 ";

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
    <style>
        /* TODO: Fix bug: Text goes over box bound */
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
        <img src="logo.png" alt="Logo" style="height: 100px; margin-right: 20px;">
        <div style="text-align: center; flex: 1;">
            <h1 style="margin: 0; font-size: 24px; font-weight: bold;">TECHNO INTERNATIONAL NEWTOWN</h1>
            <p style="margin: 0; font-size: 14px;">(Formerly Known as Techno India College Of Technology)</p>
        </div>
        <div style="display: flex; align-items: center; font-size: 14px; margin-left: 5px;">
            <i class="fas fa-phone-alt" style="margin-right: 5px;"></i>
            <span>
                <p>&#9742; +338910530723 / 8910530723</p>
            </span>
        </div>
    </header>

    <div class="layout">
		<div class="sidebar">
            <nav>
                <a href="faculty_dashboard.php">Dashboard</a>
                <a href="faculty_view_attendace.php">View Attendance</a>
                <a id="active" href="faculty_view_marks.php">View Marks</a>
                <a href="/SRMS/SRMS_25/faculty_details.html">Faculty Details</a>
                <a>Update Details</a>
                <a>Settings</a>
                <a href="logout.php">Log out</a>
            </nav>
		</div>

        <div class="main-content">
            <div class="card" >
                <h2>View Students Marks</h2>
                
                <form action="" method="GET">

                    <!-- TODO: Add proper subject filters and fix filter UI-->
                    <div class="filters">

                        <!-- Exam Type Dropdown -->
                        <select id="exam" name="exam">
                            <option value="">Select Exam Type</option>
                            <option value="CA1">CA 1</option>
                            <option value="CA2">CA 2</option>
                            <option value="CA3">CA 3</option>
                            <option value="CA4">CA 4</option>
                            <option value="PCA1">PCA 1</option>
                            <option value="PCA2">PCA 2</option>
                        </select>

                        <!-- Section Dropdown -->
                        <!-- <label for="section">Section:</label> -->
                        <select id="section" name="section">
                            <option value="">Select Section</option>
                        </select>
                                                
                        <!-- Group Dropdown -->
                        <!-- <label for="group">Group:</label> -->
                        <select id="group" name="group">
                            <option value="">Select Group</option>
                            <option value="A">Group A</option>
                            <option value="B">Group B</option>
                        </select>

                        <!-- TODO: Subject Dropdown -->
                        <!-- Will display name and value will be subject_id -->
                        <select id="subject" name="subject" disabled>
                            <option value="">Select Subject</option>
                        </select>

                        <!-- Sem Type Dropdown -->
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
                    </div>

                    <input type="submit" name="submit" placeholder="Submit">
                </form>
            </div>

            <div class="card">
                <input type="text" id="table_header" readonly name="table_header" value="<?php echo $table_header; ?>">
                <table>
                    <tr>
                        <th>Student_Id</th>
                        <th>Student_Name</th>
                        <th>Department</th>
                        <th>Subject_Id</th>
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
                                        <td>" . $row["subject_id"] . "</td>
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
</body>
</html>