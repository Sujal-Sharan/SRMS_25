<?php
require_once("DB_Connect.php");
session_start();

// Get student details from DB
$stmt = $conn->prepare("SELECT * FROM students WHERE college_roll = ?");
$stmt->bind_param("s", $_SESSION['user_Id']);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="Styles/global_base.css">
</head>
<body>
    <header>
        Student Dashboard
    </header>

    <div class="layout">
        <div class="sidebar">
        <img src="logo.png" alt="Logo" style="height: 100px; margin-left: 50px;">
            <nav>
                <a href="/SRMS/SRMS_25/student.php" id="active">Dashboard</a>
                <a href="/SRMS/SRMS_25/student_attendance.php">Attendance</a>
                <a href="/SRMS/SRMS_25/marks.php">View Marks</a>
                <a>Documents</a>
                <a>Update Details</a>
                <a>Settings</a>
                <a href="/SRMS/SRMS_25/logout.php">Log out</a>
            </nav>
        </div>

        <div class="main-content">
            <!-- <div class="profile-section">
                <img src="profile.jpg" alt="Profile Picture" ><br>
            </div> -->
            <div class="card">
                <h3>Student Details</h3>
                <?php
                try{
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<br><p>Name : " . $row["name"] . "</p><br>" .
                                "<p>University Roll : " . $row["university_roll"] . "</p><br>" .
                                "<p>College Roll : " . $row["college_roll"] . "</p><br>" .
                                "<p>Department : " . $row["department"] . "</p><br>" .
                                "<p>Current Semester : " . $row["current_semester"] . "</p><br>" .
                                "<p>Batch : " . $row["batch_year"] . "</p><br>" .
                                "<p>Email : " . $row["email"] . "</p><br>" .
                                "<p>Phone No. : " . $row["phone"] . "</p><br>";

                            $_SESSION['name'] = $row["name"];
                            $_SESSION['current_semester'] = $row["current_semester"];
                            $_SESSION['university_roll'] = $row["university_roll"];
                            $_SESSION['college_roll'] = $row["college_roll"];
                            $_SESSION['department'] = $row["department"];
                            $_SESSION['batch_year'] = $row["batch_year"];
                            
                        }
                    } else {
                        echo "No records found";
                    }
                }catch(Exception $e){
                    echo 'Message: ' .$e->getMessage();
                }
                ?>
            </div>

            <div class="card">
                <h3>Actions</h3>
                <br>
                <button class="btn" onclick="navigateTo('marks.php')">View Marks</button>
                <button class="btn" onclick="navigateTo('student_attendance.php')">Attendance</button>
                <button class="btn">View Documents</button>
                <button class="btn">Update Details</button>
            </div>
        </div>
    </div>
    
    <script>
        function navigateTo(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
