<?php
require_once("DB_Connect.php");
session_start();

$stmt = $conn->prepare("SELECT subject_id, semester, 
    MAX(CASE WHEN test_type = 'PCA1' THEN marks_obtained END) AS PCA1_marks, 
    MAX(CASE WHEN test_type = 'PCA2' THEN marks_obtained END) AS PCA2_marks 
    FROM marks 
    WHERE student_id = ?");

$stmt->bind_param("s", $_SESSION['college_roll']);
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
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
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
            width: 100%;
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
        input[type="text"], input[type="submit"] {
            width: 90%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #f4b400;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            width: 95%;
        }
        input[type="submit"]:hover {
            background-color: #e69a00;
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

</body>
</html>


