<?php
include_once("DB_Connect.php");
session_start();
$executed = false;

if(isset($_GET['submit'])){
    $roll = filter_input(INPUT_GET, "roll", FILTER_SANITIZE_SPECIAL_CHARS); //Taking 'roll' as input
    $sub = filter_input(INPUT_GET, "subject", FILTER_SANITIZE_SPECIAL_CHARS);   //[Optional] Taking 'subject' as input
    // $roll = $_SESSION['roll'];   //Value fetched from session

    if(empty($roll)){
        echo "Please provide a valid roll";
    }
    else{
        //'subject' not filled
        if(empty($sub)){
            $stmt = $conn->prepare("SELECT * FROM internal WHERE roll = ?");
            $stmt->bind_param("s", $roll);
        }
        else{
            $stmt = $conn->prepare("SELECT * FROM internal WHERE roll = ? AND subject = ?");
            $stmt->bind_param("ss", $roll, $sub);
        }
        
        $executed = true;
        $stmt->execute();
        $result = $stmt->get_result();
    }
}
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
    </style>
</head>
<body>
    <div class="container">
        <form>
            <input type="text" placeholder="Enter your roll" name="roll"><br>
            <input type="text" placeholder="Enter subject [Optional]" name="subject"><br>
            <input type="submit" value="Submit" name="submit">
        </form>
        
        <h2>Internal Marks(Practical Continous Assessment)</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Roll</th>
                <th>Subject</th>
                <th>PCA1</th>
                <th>PCA2</th>
            </tr>
            <?php
            if($executed){
                if($result->num_rows > 0){
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row["name"] . "</td>
                                <td>" . $row["roll"] . "</td>
                                <td>" . $row["subject"] . "</td>
                                <td>" . $row["pca1"] . "</td>
                                <td>" . $row["pca2"] . "</td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No records found</td></tr>";
                }
            }
            ?>
        </table>
    </div>
</body>
</html>


