<?php
require_once("DB_Connect.php");
require_once("session_logout.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marks Choice</title>
    <link rel="stylesheet" href="Styles/global_base.css">
    <link rel="icon" type="image/x-icon" href="logo.png">

    <style>
        .wrap-card{
            display: flex;
        }
        .card{
            height: 100px;
            width: 140px;
            margin-left: 80px;
            margin-top: 30px;
            background-color: #e6b800;
            align-content: center;
            border: 2px, solid,rgb(157, 156, 156);
        }

        .card:hover{
            background-color:rgb(189, 165, 69);
            border: 2px, solid, rgb(67, 66, 66);
        }
    </style>
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
                <a href="student.php">Dashboard</a>
                <a href="student_attendance.php">Attendance</a>
                <a id="active" href="marks.php">View Marks</a>
                <a href="upload_file_student_UI.php">Add Documents</a>
                <!-- <a>Update Details</a> -->
                <a href="logout.php">Log out</a>
            </nav>
        </div>

        <div class="main-content">

            <h2>Choose</h2>


            <div class="wrap-card">
                <div class="card" onclick="navigateTo('student_CA.php')">CA Marks</div>
                <div class="card" onclick="navigateTo('student_PCA.php')">PCA Marks</div>
                <!-- <div class="card" onclick="navigateTo('student_Marks_Semester.php')">Semester Marks</div> -->
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
