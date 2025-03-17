<?php
include_once("DB_Connect.php");
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Marks Dashboard</title>
    <link rel="stylesheet" href="marks.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <h2>Techno International New Town</h2>
            <ul>
                <li>Dashboard</li>
                <li class="active">Student Marks</li>
                <li>Settings</li>
                <li>Logout</li>
            </ul>
        </aside>
        <main class="content">
            <header>
                <h2>Student Marks Categories</h2>
            </header>
            <br>
            <br>
            <br>
            <br>
            <div class="categories">
                <div class="category-box" onclick="location.href='Student_CA.php'">CA Marks</div>
                <div class="category-box" onclick="location.href='Student_PCA.php'">PCA Marks</div>
                <div class="category-box" onclick="location.href='marks_page.html?category=Semester'">Semester Marks</div>
            </div>
        </main>
    </div>
</body>
</html>
