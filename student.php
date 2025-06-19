<?php
require_once("DB_Connect.php");
require_once("session_logout.php");

// Get student details from DB
$stmt = $conn->prepare("SELECT * FROM students WHERE college_roll = ?");
$stmt->bind_param("s", $_SESSION['user_id']);
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
    <link rel="icon" type="image/x-icon" href="logo.png">

</head>
<body>
    <header>
        <img src="logo.png" alt="Logo" style="height: 120px; margin-right: 10px;">
        <div style="text-align: center; flex: 1;">
            <h1 style="margin: 0; font-size: 25px; font-weight: bold;">TECHNO INTERNATIONAL NEW TOWN</h1>
            <p style="margin: 0; font-size: 17px;">(Formerly Known as Techno India College Of Technology)</p>
        </div>
        <div style="display: flex; align-items: center; font-size: 15px; margin-left: 2px;">
            <i class="fas fa-phone-alt" style="margin-right: 10px;"></i>
            <span><p>Logged in as <?php echo ($_SESSION['name']) ?? $_SESSION['user_id'] ?></p></span>
        </div>
    </header>

    <div class="layout">
        <div class="sidebar">
            <nav>
                <a href="student.php" id="active">Dashboard</a>
                <a href="student_attendance.php">Attendance</a>
                <a href="marks.php">View Marks</a>
                <a href="upload_file_student_UI.php">Add Documents</a>
                <!-- <a>Update Details</a> -->
                <a href="logout.php">Log out</a>
            </nav>
        </div>

        <div class="main-content">
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
        </div>
    </div>

    <script>
        function navigateTo(url) {
            window.location.href = url;
        }

        setTimeout(() => {
            alert("Your session is about to expire!");
        }, 25 * 60 * 1000); // Warn after 25 minutes


        // Listen for logout events from other tabs
        window.addEventListener('storage', function(event) {
            if (event.key === 'logout-event') {
                alert("You have been logged out from another tab.");
                window.location.href = "login.php";
            }
        });
    </script>

</body>
</html>
