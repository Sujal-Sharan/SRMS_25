<?php
$servername = "localhost";
$username = "root";  // Change as per your DB credentials
$password = "";
$dbname = "srms"; // Change as per your DB name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname); 

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the current month and year dynamically
$currentMonth = date('m'); // Example: 02 for February
$currentYear = date('Y');  // Example: 2025

// SQL query to fetch attendance percentage
$sql = "
    SELECT
        student_id,
        MONTH(attendance_date) AS month,
        YEAR(attendance_date) AS year,
        COUNT(CASE WHEN attendance_status = 'Present' THEN 1 END) AS total_present_days,
        COUNT(*) AS total_working_days,
        (COUNT(CASE WHEN attendance_status = 'Present' THEN 1 END) * 100.0) / COUNT(*) AS attendance_percentage
    FROM attendance
    WHERE MONTH(attendance_date) = $currentMonth AND YEAR(attendance_date) = $currentYear
    GROUP BY student_id, YEAR(attendance_date), MONTH(attendance_date)
    ORDER BY student_id, year, month;
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance Report</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>

<h2>Student Attendance Report - <?php echo date("F Y"); ?></h2>

<table>
    <tr>
        <th>Student ID</th>
        <th>Month</th>
        <th>Year</th>
        <th>Present Days</th>
        <th>Total Working Days</th>
        <th>Attendance %</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['student_id']}</td>
                    <td>{$row['month']}</td>
                    <td>{$row['year']}</td>
                    <td>{$row['total_present_days']}</td>
                    <td>{$row['total_working_days']}</td>
                    <td>" . round($row['attendance_percentage'], 2) . "%</td>
                </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No records found</td></tr>";
    }
    ?>

</table>

</body>
</html>

<?php
$conn->close();
?>
