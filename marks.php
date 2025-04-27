<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Marks Dashboard</title>
    <link rel="stylesheet" href="marks.css">
    <link rel="stylesheet" href="Styles/sidebar.css">
</head>
<body>
    <div class="sidebar">
        <h2>{Logo}  TINT</h2>
        <nav>
            <a href="/SRMS/SRMS_25/student.php">Dashboard</a>
            <a href="/SRMS/SRMS_25/attendance.php">Attendance</a>
            <a href="/SRMS/SRMS_25/marks.php" id="active"   >View Marks</a>
            <a>Documents</a>
            <a>Update Details</a>
            <a>Settings</a>
            <a href="/SRMS/SRMS_25/logout.php">Log out</a>
        </nav>
    </div>

    <div class="container">
        <main class="content">
            <header>
                <h2>Student Marks Categories</h2>
            </header>
            <br>
            <br>
            <div class="categories">
                <div class="category-box" onclick="navigateTo('Student_CA.php')">CA Marks</div>
                <div class="category-box" onclick="navigateTo('Student_PCA.php')">PCA Marks</div>
                <div class="category-box" onclick="navigateTo('marks_page.html?category=Semester')">Semester Marks</div>
            </div>
        </main>
    </div>
    <script>
        function navigateTo(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
