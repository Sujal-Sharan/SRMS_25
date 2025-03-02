<?php
include("DB_Connect.php");
session_start();

?>
<!-- TODO: Fetch user values and dynamic display here -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Techno International New Town</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            display: flex;
            height: 100vh;
        }
        .sidebar {
            width: 250px;
            background: #0d1b2a;
            color: white;
            padding: 20px;
        }
        .sidebar h2 {
            margin-bottom: 20px;
        }
        .sidebar ul {
            list-style: none;
        }
        .sidebar ul li {
            padding: 10px;
            cursor: pointer;
        }
        .sidebar ul li:hover {
            background: #1b263b;
        }
        .main-content {
            flex: 1;
            padding: 20px;
            background: #f8f9fa;
        }
        .profile-section {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .profile-section img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-right: 15px;
        }
        .profile-details {
            line-height: 1.5;
        }
        .card {
            background: white;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn {
            background: #ffcc00;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }
        .btn:hover {
            background: #e6b800;
        }
        h2 {
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Techno International NewTown</h2>
        <ul>
            <li>Dashboard</li>
            <li>View Marks</li>
            <li>Documents</li>
            <li>Update Details</li>
            <li>Settings</li>
        </ul>
    </div>
    <div class="main-content">
        <div class="profile-section">
            <img src="profile.jpg" alt="Profile Picture">
            <br>
            <h2>
            <?php 
            if(isset($_SESSION['username'])) {
                echo "Welcome " . $_SESSION['username']; // Output: Welcome "JohnDoe"
            } else {
                echo "Session variable not set.";
            }
            ?>
            </h2>
            <br>        
        </div>
        <div class="card">
            <h3>Student Details</h3>
            <br>
            <br>
            <p><strong>Name:</strong><?php echo $_SESSION['username'] ?></p>
            <br>
            <p><strong>Roll:</strong> 123456</p>
            <br>
            <p><strong>Registration No:</strong> 2025001</p>
            <br>
            <p><strong>Stream:</strong> Computer Science</p>
            <br>
            <p><strong>Year:</strong> 3rd</p>
            <br>
        </div>
        <div class="card">
            <h3>Actions</h3>
            <br>
            <button class="btn" onclick="navigateTo('marks.php')">View Marks</button>
            <button class="btn">View Documents</button>
            <button class="btn">Update Details</button>
        </div>
    </div>
    <script>
        function navigateTo(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
