<?php
require_once("DB_Connect.php");
session_start();

//TODO: Creat table for sem marks 

// Define the parameters (these may be empty or null if the user doesn't provide them)

$roll = isset($_SESSION['college_roll']) ? $_SESSION['college_roll'] : NULL;         // Student roll number (already known from student.php)
$semester = isset($_SESSION['current_semester']) ? $_SESSION['current_semester'] : 1;          // Default is current semester 
$subject_id = NULL;      // Optional subject_id (can be empty or null)

if(isset($_GET['filter'])){
    $semester = filter_input(INPUT_GET, "filter_semester", FILTER_SANITIZE_SPECIAL_CHARS);
    $subject_id = filter_input(INPUT_GET, "filter_subject", FILTER_SANITIZE_SPECIAL_CHARS);
}

//TODO: Customize query for sem marks
$sql = "SELECT 
            m.student_id,
            m.subject_id,
            subj.subject_name AS subject_name,
            m.semester AS semester,
            
            CASE 
                WHEN MAX(m.test_type = 'PCA1' AND m.is_absent = TRUE AND m.semester = $semester AND m.marks_obtained IS NULL) THEN 'ABSENT'
                ELSE CAST(MAX(CASE WHEN m.test_type = 'PCA1' AND m.semester = $semester THEN m.marks_obtained END) AS CHAR)
            END AS final,

        FROM 
            marks m
        JOIN 
            students s ON m.student_id = s.college_roll
        JOIN 
            subjects subj ON m.subject_id = subj.subject_id
        WHERE 1";  // 'WHERE 1' always return true

$types = "";   // To hold bind_param types (e.g., "s" for string, "i" for integer)
$values = [];  // To hold the values for binding

// Optional filters
if (!empty($semester)) {
    $sql .= " AND m.semester = ?";
    $types .= "i";
    $values[] = $semester;
}

if (!empty($subject_id)) {
    $sql .= " AND m.subject_id = ?";
    $types .= "i";
    $values[] = $subject_id;
}

if (!empty($roll)) {
    $sql .= " AND s.college_roll = ?";
    $types .= "s";
    $values[] = $roll;
}else{
    $sql .= " AND 1 != 1";  //When college_roll has not been set or not available in DB
}

$sql .= " GROUP BY m.student_id ";  //Required since aggregate MAX used

//Temp query block
$sql = "SELECT * FROM marks WHERE 1 != 1";
// Prepare the query
$stmt = $conn->prepare($sql);

// Bind parameters dynamically
// if (!empty($values)) {
//     $stmt->bind_param($types, ...$values);  // Spread operator to pass the parameters
// }

$stmt->execute();
$result = $stmt->get_result();

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
    <title>Document</title>
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
                <a href="student_attendance.php">Attendance</a>
                <a id="active" href="marks.php">View Marks</a>
                <a href="upload_file_student_UI.html">Add Documents</a>
                <!-- <a>Update Details</a> -->
                <a href="logout.php">Log out</a>
            </nav>
        </div>

        <div class="main-content">
            <div class="card">
                <h2>Semester Marks</h2>
            </div>

            <div class="card">
                <h3>Apply Filters</h3><br>
                <form action="student_Marks_Semester.php" method="get">
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
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Semester</th>
                        <th>Points</th>
                        <th>Credit</th>
                        <th>Credit Points</th>
                    </tr>
                    <?php
                    try{
                        if($result->num_rows > 0){
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . $row["subject_code"] . "</td>
                                        <td>" . $row["subject_name"] . "</td>
                                        <td>" . $row["semester"] . "</td>
                                        <td>" . $row["points"] . "</td>
                                        <td>" . $row["credit"] . "</td>
                                        <td>" . $row["credit_points"] . "</td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No records found</td></tr>";
                        }
                    }catch(Exception $e){
                        echo 'Message: ' .$e->getMessage();
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>

</body>
</html>