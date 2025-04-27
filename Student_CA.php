<?php
require_once("DB_Connect.php");
session_start();

// Define the parameters (these may be empty or null if the user doesn't provide them)
$roll = $_SESSION['college_roll'];         // Student roll number (already known from student.php)
$semester = NULL;          // Optional semester param (can be empty or null)
$subject_id = NULL;      // Optional subject_id (can be empty or null)

$sql = "SELECT 
            m.student_id,
            m.subject_id AS subject_id,
            m.semester AS sem,
            s.name AS student_name,
            
            CASE 
                WHEN MAX(m.test_type = 'CA1' AND m.is_absent = TRUE AND m.marks_obtained IS NULL) THEN 'ABSENT'
                ELSE CAST(MAX(CASE WHEN m.test_type = 'CA1' THEN m.marks_obtained END) AS CHAR)
            END AS CA1,
            
            CASE 
                WHEN MAX(m.test_type = 'CA2' AND m.is_absent = TRUE AND m.marks_obtained IS NULL) THEN 'ABSENT'
                ELSE CAST(MAX(CASE WHEN m.test_type = 'CA2' THEN m.marks_obtained END) AS CHAR)
            END AS CA2,
            
            CASE 
                WHEN MAX(m.test_type = 'CA3' AND m.is_absent = TRUE AND m.marks_obtained IS NULL) THEN 'ABSENT'
                ELSE CAST(MAX(CASE WHEN m.test_type = 'CA3' THEN m.marks_obtained END) AS CHAR)
            END AS CA3,
            
            CASE 
                WHEN MAX(m.test_type = 'CA4' AND m.is_absent = TRUE AND m.marks_obtained IS NULL) THEN 'ABSENT'
                ELSE CAST(MAX(CASE WHEN m.test_type = 'CA4' THEN m.marks_obtained END) AS CHAR)
            END AS CA4

        FROM 
            marks m
        JOIN 
            students s ON m.student_id = s.college_roll
        WHERE 1";  // 'WHERE 1' is a trick to always return true (helps us add conditions easily)

$types = "";   // To hold bind_param types (e.g., "s" for string, "i" for integer)
$values = [];  // To hold the values for binding

// Optional filters
if (!empty($roll)) {
    $sql .= " AND s.college_roll = ?";
    $types .= "s"; // assuming roll is a string (adjust if it's numeric)
    $values[] = $roll;
}

if (!empty($semester)) {
    $sql .= " AND m.semester = ?";
    $types .= "i"; // assuming semester is an integer
    $values[] = $semester;
}

if (!empty($subject_id)) {
    $sql .= " AND m.subject_id = ?";
    $types .= "i"; // assuming subject_id is an integer
    $values[] = $subject_id;
}

// Prepare the query
$stmt = $conn->prepare($sql);

// Bind parameters dynamically
if (!empty($values)) {
    $stmt->bind_param($types, ...$values);  // Spread operator to pass the parameters
}

// TEST AREA END
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="Styles/sidebar.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('https://cdn2.advanceinfotech.org/bharatdirectory.in/1200x675/business/3135/techno-2-1709631821.webp') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: left;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            /* display: flex; */
            padding: 46px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            width: 70%;
            height:46%; 
            text-align: center;
        }
        h2 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f4b400;
            color: white;
            font-weight: bold;
        }
        td {
            background-color: #fff;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>{Logo}  TINT</h2>
        <nav>
            <a href="/SRMS/SRMS_25/student.php">Dashboard</a>
            <a href="/SRMS/SRMS_25/attendance.php">Attendance</a>
            <a href="/SRMS/SRMS_25/marks.php" id="active"   >View Marks</a>
            <a>Documents</a>
            <a>Update Details</a>
            <a>Settings</a>
            <a href="/SRMS/SRMS_25/logout.php">Log out</a>
        </nav>
    </div>

    <div class="container">
        <h2>Internal Marks (PCA)</h2>

        <table>
            <tr>
                <th>Subject</th>
                <th>Semester</th>
                <th>CA 1</th>
                <th>CA 2</th>
                <th>CA 3</th>
                <th>CA 4</th>
            </tr>
            <?php
                if($result->num_rows > 0){
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row["subject_id"] . "</td>
                                <td>" . $row["sem"] . "</td>
                                <td>" . $row["CA1"] . "</td>
                                <td>" . $row["CA2"] . "</td>
                                <td>" . $row["CA3"] . "</td>
                                <td>" . $row["CA4"] . "</td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No records found</td></tr>";
                }
            ?>
        </table>
    </div>

</body>
</html>


