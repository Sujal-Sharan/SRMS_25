<?php
require_once("DB_Connect.php");
require_once("session_logout.php");

// Define the parameters (these may be empty or null if the user doesn't provide them)

$roll = isset($_SESSION['college_roll']) ? $_SESSION['college_roll'] : NULL;         // Student roll number (already known from student.php)
$semester = isset($_SESSION['current_semester']) ? $_SESSION['current_semester'] : 1;          // Default is current semester 
$subject_id = NULL;      // Optional subject_id (can be empty or null)

if(isset($_GET['filter'])){
    $semester = filter_input(INPUT_GET, "semester", FILTER_SANITIZE_SPECIAL_CHARS);
    $subject_id = filter_input(INPUT_GET, "subject", FILTER_SANITIZE_SPECIAL_CHARS);
}

$sql = "SELECT 
            subj.subject_id AS subject_id,
            subj.subject_code AS subject_code,
            subj.subject_name AS subject_name,
            m.semester AS semester,
            
            CASE 
                WHEN MAX(m.test_type = 'PCA1' AND m.is_absent = TRUE AND m.semester = $semester AND m.marks_obtained IS NULL) THEN 'ABSENT'
                ELSE CAST(MAX(CASE WHEN m.test_type = 'PCA1' AND m.semester = $semester THEN m.marks_obtained END) AS CHAR)
            END AS PCA1,
            
            CASE 
                WHEN MAX(m.test_type = 'PCA2' AND m.is_absent = TRUE AND m.semester = $semester AND m.marks_obtained IS NULL) THEN 'ABSENT'
                ELSE CAST(MAX(CASE WHEN m.test_type = 'PCA2' AND m.semester = $semester THEN m.marks_obtained END) AS CHAR)
            END AS PCA2

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

$sql .= " GROUP BY m.subject_id ";  //Required since aggregate MAX used

// Prepare the query
$stmt = $conn->prepare($sql);

// Bind parameters dynamically
if (!empty($values)) {
    $stmt->bind_param($types, ...$values);  // Spread operator to pass the parameters
}

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
    <title>PCA Marks</title>
    <link rel="stylesheet" href="Styles/global_base.css">
    <link rel="icon" type="image/x-icon" href="logo.png">

    <style>
        .btn{
            background-color: rgb(50, 225, 47);
            border: 1px, solid, black;
            margin: 20px;
            margin-bottom: 2px;
            border-radius: 4px;
            padding: 10px;
        }
        .btn:hover{
            background-color: rgb(43, 193, 41);
            border: 2px, solid, black;
            cursor: pointer;
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
            <span><p>Logged in as <?php echo ($_SESSION['name']) ?? $_SESSION['user_id'] ?></p></span>
        </div>
    </header>

    <div class="layout">
        <div class="sidebar">
            <nav>
                <a href="student.php">Dashboard</a>
                <a href="student_attendance.php">Attendance</a>
                <a id="active" href="marks.php">View Marks</a>
                <a href="upload_file_student_UI.php">Add Documents</a>
                <!-- <a>Update Details</a> -->
                <a href="logout.php">Log out</a>
            </nav>
        </div>

        <div class="main-content">
            <div class="card">
                <h2>PCA Marks</h2><br>
                <h4>Apply Filters</h4>
                <form action="student_PCA.php" method="get">
                    <div class="filters">

                        <!-- Subject Dropdown -->
                        <select name="subject" id="subject" required>
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
                        <select id="semester" name="semester" required>
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
                    <input type="submit" name="filter" class="btn" value="Submit">
                </form>
            </div>

            <div class="card">
                <input type="text" id="table_header" readonly name="table_header" value="<?php echo $table_header; ?>">
                <table>
                    <tr>
                        <th>Subject</th>
                        <th>Semester</th>
                        <th>PCA_1</th>
                        <th>PCA_2</th>
                    </tr>
                    <?php
                    try{
                        if($result->num_rows > 0){
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . $row["subject_name"] . "</td>
                                        <td>" . $row["semester"] . "</td>
                                        <td>" . $row["PCA1"] . "</td>
                                        <td>" . $row["PCA2"] . "</td>
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