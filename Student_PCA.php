<?php
include_once("DB_Connect.php");
session_start();

// TODO: Add a filter to only show marks from current sem. (Would need changes in student_records to get current sem)
// Might remove name and roll fields

$stmt = $conn->prepare("SELECT * FROM marks_pca WHERE roll = ?");
$stmt->bind_param("s", $_SESSION['roll']);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
        .sidebar {
            width: 250px;
            background: #0A1931;
            color: white;
            padding: 20px;
            height: 100vh;
        }
        .sidebar .profile {
            font-weight: bold;
            margin-bottom: 20px;
        }
        .sidebar nav ul {
            list-style: none;
            padding: 0;
        }
        .sidebar nav ul li {
            margin: 10px 0;
        }
        .sidebar nav ul li a {
            color: white;
            text-decoration: none;
            padding: 10px;
            display: block;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="profile">Welcome User</div>
        <nav>
            <ul>
                <li><a href="#">Go To Super Admin</a></li>
                <li ><a>My College</a>
                    <ul>
                        <li class="active"><a href="#">Dashboard</a></li>
                        <li><a href="#">Enquiry</a></li>
                    </ul>
                </li>
                <li><a href="#">Management</a></li>
            </ul>
        </nav>
    </div>
    <div class="container">
        <h2>Internal Marks(Continous Assessment)</h2>

        <table>
            <tr>
                <th>Name</th>
                <th>Roll</th>
                <th>Subject Name</th>
                <th>Subject Code</th>
                <th>PCA1</th>
                <th>PCA2</th>
            </tr>
            <?php
                if($result->num_rows > 0){
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row["name"] . "</td>
                                <td>" . $row["roll"] . "</td>
                                <td>" . $row["subject_code"] . "</td>
                                <td>" . $row["subject_name"] . "</td>
                                <td>" . $row["pca1"] . "</td>
                                <td>" . $row["pca2"] . "</td>
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


