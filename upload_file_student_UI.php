<?php
require_once("DB_Connect.php");
require_once("session_logout.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Document</title>
    <link rel="stylesheet" href="Styles/global_base.css" />
    <link rel="icon" type="image/x-icon" href="logo.png">

    <style>
        #readonly{
            background-color:rgb(154, 151, 150);
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
                <a href="marks.php">View Marks</a>
                <a id="active" href="upload_file_student_UI.php">Add Documents</a>
                <!-- <a>Update Details</a> -->
                <a href="logout.php">Log out</a>
            </nav>
		</div>

        <div class="main-content">
            <div class="card">
                <h2>Upload Student Document</h2><br>
                <form action="upload_file_handler.php" method="post" enctype="multipart/form-data">
                    <label>User ID:</label>
                    <input id="readonly" type="text" name="student_id" value="<?= htmlspecialchars($_SESSION['user_id']) ?>" readonly><br><br>

                    <label>Optional File Name:</label>
                    <input type="text" name="file_name"><br><br>

                    <label>Select file:</label>
                    <input type="file" name="document" required><br><br>

                    <button class="btn-save" type="submit">Upload</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>