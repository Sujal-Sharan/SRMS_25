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
</head>
<body>
    <form> <!-- 'roll' can be removed for 'student' as storing it as session var in 'student.php'   -->
        <input type="text" placeholder="Enter your roll" name="roll"><br>   
        <input type="text" placeholder="Enter subject [Optional]" name="subject"><br>
        <input type="submit" value="Submit" name="submit">
    </form>
    
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
            //Display Data in Table if query has been executed
            if($executed){
                if($result->num_rows > 0){
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
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
            }
            ?>
        </table>
    </div>
</body>
</html>