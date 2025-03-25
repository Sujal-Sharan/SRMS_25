<?php
include_once("DB_Connect.php");
session_start();

// Get the current month and year dynamically
$currentMonth = date('m'); // Example: 02 for February
$currentYear = date('Y');  // Example: 2025

// Old SQL query to fetch attendance percentage
$sql_1 = "
    SELECT
        userId,
        MONTH(date) AS month,
        YEAR(date) AS year,
        COUNT(CASE WHEN status = 'Present' THEN 1 END) AS total_present_days,
        COUNT(*) AS total_working_days,
        (COUNT(CASE WHEN status = 'Present' THEN 1 END) * 100.0) / COUNT(*) AS attendance_percentage
    FROM attendance
    WHERE MONTH(date) = $currentMonth AND YEAR(date) = $currentYear
    GROUP BY userId, YEAR(date), MONTH(date)
    ORDER BY userId, year, month;
";

//----  Test Area Start --

// Default values (optional filtering)
$filters = [];
$params = [];
$types = "";

// Check if user provided a `userId`
if (!empty($_GET['userId'])) {
    $filters[] = "userId = ?";
    $params[] = $_GET['userId'];
    $types .= "i"; // Integer
}

// Check if user provided a `month`
if (!empty($_GET['month'])) {
    $filters[] = "MONTH(date) = ?";
    $params[] = $_GET['month'];
    $types .= "i"; // Integer
}

// Check if user provided a `year`
if (!empty($_GET['year'])) {
    $filters[] = "YEAR(date) = ?";
    $params[] = $_GET['year'];
    $types .= "i"; // Integer
}

// Base SQL query
$sql = "
    SELECT
        userId,
        MONTH(date) AS month,
        YEAR(date) AS year,
        COUNT(CASE WHEN status = 'Present' THEN 1 END) AS total_present_days,
        COUNT(CASE WHEN (status = 'Present' OR status = 'Absent') THEN 1 END) AS total_working_days,
        ( ((COUNT(CASE WHEN status = 'Present' THEN 1 END)) * 100 ) / COUNT(CASE WHEN (status = 'Present' OR status = 'Absent') THEN 1 END) ) AS attendance_percentage
    FROM attendance
";

//-- Testing Alternate Query--
$sql_test = "
WITH attendance_summary AS (
    SELECT 
        userId, 
        MONTH(date) AS month, 
        YEAR(date) AS year,
        COUNT(CASE WHEN status = 'Present' THEN 1 END) AS total_present_days,
        COUNT(CASE WHEN status IN ('Present', 'Absent') THEN 1 END) AS total_working_days
    FROM attendance
    GROUP BY userId, YEAR(date), MONTH(date)
    ORDER BY userId, year, month
)
SELECT 
    userId, 
    month, 
    year, 
    total_present_days, 
    total_working_days, 
    (total_present_days * 100.0) / NULLIF(total_working_days, 0) AS attendance_percentage
FROM attendance_summary;
";
// --Test Alternate END --

// Add filters if any were provided
if (!empty($filters)) {
    $sql .= " WHERE " . implode(" AND ", $filters);
}

// Add grouping and ordering
$sql .= " GROUP BY userId, YEAR(date), MONTH(date) ORDER BY userId, year, month";

// Prepare statement
$stmt = $conn->prepare($sql);

// Bind parameters if any exist
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

//-- Test Area END --

// $result = $conn->query($sql);
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
                #Adding row background color as visual filter
                $rowColor = ((round($row['attendance_percentage'], 2)) < 70) ? "style='background-color: #FFCCCC; color: red;'" : "";

                echo "<tr $rowColor>
                        <td>{$row['userId']}</td>
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
