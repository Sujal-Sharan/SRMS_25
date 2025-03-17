<?php
include_once("DB_Connect.php");
session_start();

// TODO: Add a filter to sort by present / absent.
// $stmt = $conn->prepare("SELECT * FROM attendance WHERE userId = ? AND status = 'Present' ORDER BY date DESC");

$stmt = $conn->prepare("SELECT * FROM attendance WHERE userId = ? AND status IN ('Present', 'Absent') ORDER BY date DESC");
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
    <table>
        <tr>
            <th>Name / ID</th>
            <th>Date</th>
            <th>Status</th>
        </tr>
        <?php
            if($result->num_rows > 0){
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
    </div>
</body>
</html>
<?php

?>