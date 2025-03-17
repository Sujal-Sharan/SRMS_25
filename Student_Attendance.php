<?php
include_once("DB_Connect.php");
session_start();

// TODO: Add a filter to sort by present / absent.
// $stmt = $conn->prepare("SELECT * FROM attendance WHERE userId = ? AND status = 'Present' ORDER BY date DESC");

//All dates
$stmt1 = $conn->prepare("SELECT * FROM attendance WHERE userId = ? AND status IN ('Present', 'Absent') ORDER BY date DESC");

// $stmt = $conn->prepare("SELECT userId, date, status, ((COUNT(DISTINCT CASE WHEN status = 'Present' THEN 1 END) * 100) / COUNT(DISTINCT date) AS percent FROM attendance WHERE userId = ? AND status IN ('Present', 'Absent') ORDER BY date DESC");

//Percentage only
$stmt = $conn->prepare("
    SELECT 
        userId,
        status,
        (COUNT(DISTINCT CASE WHEN status = 'Present' THEN date END) * 100.0) / COUNT(DISTINCT date) AS percent 
    FROM attendance 
    WHERE userId = ? 
    AND status IN ('Present', 'Absent') 
    GROUP BY userId
");

$stmt->bind_param("s", $_SESSION['userId']);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
    <?php if ($_SESSION['role'] != "Admin") { ?>
    <table border="1">

        <?php
            if($result->num_rows > 0){
                echo "<tr>
                        <th>Name / ID</th>
                        <th>Status</th>
                        <th>Percent</th>
                    </tr>";
                    
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["userId"] . "</td>
                            <td>" . $row["status"] . "</td>
                            <td>" . $row["percent"] . "</td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No records found</td></tr>";
            }
        ?>
    </table>
<?php } else { ?>
    <table border="1">
        <?php
            if($result->num_rows > 0){
                echo "<tr>
                        <th>Name / ID</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["userId"] . "</td>
                            <td>" . $row["date"] . "</td>
                            <td>" . $row["status"] . "</td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No records found</td></tr>";
            }
        ?>
    </table>
<?php } ?>
    </div>
</body>
</html>
<?php

?>