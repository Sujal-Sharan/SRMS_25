<?php
require_once("DB_Connect.php");
session_start();

// Define the parameters (these may be empty or null if the user doesn't provide them)

$roll = $_SESSION['college_roll'];         // Student roll number (already known from student.php)
$semester = NULL;          // Optional semester param (can be empty or null)
$subject_id = NULL;      // Optional subject_id (can be empty or null)

if(isset($_GET['filter'])){
    $semester = filter_input(INPUT_GET, "filter_semester", FILTER_SANITIZE_SPECIAL_CHARS);
    $subject_id = filter_input(INPUT_GET, "filter_subject", FILTER_SANITIZE_SPECIAL_CHARS);
}

$sql = "SELECT 
            m.student_id,
            m.subject_id AS subject_id,
            m.semester AS sem,
            s.name AS student_name,
            
            CASE 
                WHEN MAX(m.test_type = 'PCA1' AND m.is_absent = TRUE AND m.marks_obtained IS NULL) THEN 'ABSENT'
                ELSE CAST(MAX(CASE WHEN m.test_type = 'PCA1' THEN m.marks_obtained END) AS CHAR)
            END AS CA1,
            
            CASE 
                WHEN MAX(m.test_type = 'PCA2' AND m.is_absent = TRUE AND m.marks_obtained IS NULL) THEN 'ABSENT'
                ELSE CAST(MAX(CASE WHEN m.test_type = 'PCA2' THEN m.marks_obtained END) AS CHAR)
            END AS CA2

        FROM 
            marks m
        JOIN 
            students s ON m.student_id = s.college_roll
        WHERE 1";  // 'WHERE 1' always return true

$types = "";   // To hold bind_param types (e.g., "s" for string, "i" for integer)
$values = [];  // To hold the values for binding

// Optional filters
if (!empty($roll)) {
    $sql .= " AND s.college_roll = ?";
    $types .= "s";
    $values[] = $roll;
}

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
    <title>Document</title>
    <link rel="stylesheet" href="Styles/global_base.css">
</head>
<body>
    <header>Display PCA Marks</header>
    <div class="layout">
        <div class="sidebar">
            <h2>{Logo}  TINT</h2>
            <nav>
                <a href="/SRMS/SRMS_25/student.php">Dashboard</a>
                <a href="/SRMS/SRMS_25/attendance.php">Attendance</a>
                <a href="/SRMS/SRMS_25/marks.php" id="active">View Marks</a>
                <a>Documents</a>
                <a>Update Details</a>
                <a>Settings</a>
                <a href="/SRMS/SRMS_25/logout.php">Log out</a>
            </nav>
        </div>

        <div class="main-content">
            <header>
                <h3>Internal Marks (PCA)</h3>
            </header>

            <div class="card">
                <h3>Apply Filters</h3><br>
                <form action="student_CA.php" method="get">
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
                <table>
                    <tr>
                        <th>Subject</th>
                        <th>Semester</th>
                        <th>PCA_1</th>
                        <th>PCA_2</th>
                    </tr>
                    <?php
                        if($result->num_rows > 0){
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . $row["subject_id"] . "</td>
                                        <td>" . $row["semester"] . "</td>
                                        <td>" . $row["PCA1_marks"] . "</td>
                                        <td>" . $row["PCA2_marks"] . "</td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No records found</td></tr>";
                        }
                    ?>
                </table>
            </div>
        </div>
    </div>

</body>
</html>


