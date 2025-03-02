<?php
include_once("DB_Connect.php");
session_start();

$stmt = $conn->prepare("SELECT * FROM internal WHERE name = 'AA'"); //TODO: Bind parameter
// $stmt->bind_param("s", $_SESSION['username']);
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
    <h2>Internal Marks</h2> 
    <div class="container">
        <table>
            <tr>
                <th>Name</th>
                <th>Roll</th>
                <th>Subject</th>
                <th>CA1</th>
                <th>CA2</th>
                <th>CA3</th>
                <th>CA4</th>
            </tr>

            <?php
            //Display Data in Table
            //TODO: Remove 'TIDINT' from result and generate proper query.
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["TIDINT"] . "</td>
                            <td>" . $row["name"] . "</td>
                            <td>" . $row["roll"] . "</td>
                            <td>" . $row["subject"] . "</td>
                            <td>" . $row["ca1"] . "</td>
                            <td>" . $row["ca2"] . "</td>
                            <td>" . $row["ca3"] . "</td>
                            <td>" . $row["ca4"] . "</td>
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