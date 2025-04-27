<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Marks Dashboard</title>
    <link rel="stylesheet" href="Styles/global_base.css">
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
    <header>Marks Re-direction Page</header>
    <div class="layout">
        <div class="sidebar">
            <h2>{Logo}  TINT</h2>
            <nav>
                <a href="/SRMS/SRMS_25/student.php">Dashboard</a>
                <a href="/SRMS/SRMS_25/student_attendance.php">Attendance</a>
                <a href="/SRMS/SRMS_25/marks.php" id="active"   >View Marks</a>
                <a>Documents</a>
                <a>Update Details</a>
                <a>Settings</a>
                <a href="/SRMS/SRMS_25/logout.php">Log out</a>
            </nav>
        </div>

        <div class="main-content">
            <header>
                <h2>Student Marks Categories</h2>
            </header>
            <br>

            <div class="wrap-card">
                <div class="card" onclick="navigateTo('student_CA.php')">CA Marks</div>
                <div class="card" onclick="navigateTo('student_PCA.php')">PCA Marks</div>
                <div class="card">Semester Marks</div>
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
