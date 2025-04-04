<?php
include("DB_Connect.php");
session_start();

// Get student details from DB
$stmt = $conn->prepare("SELECT * FROM student_records WHERE roll = ?");
$stmt->bind_param("s", $_SESSION['userId']);
$stmt->execute();
$result = $stmt->get_result();

?>

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
            height: auto;
            background:rgb(22, 40, 60);
            color: white;
            padding: 20px;
        }
        .sidebar h2 {
            margin-bottom: 20px;
        }
        .sidebar ul {
            height: 100vh;
            background-color: rgb(33, 50, 70);;
            list-style: none;
        }
        .sidebar ul li {
            padding: 10px;
            cursor: pointer;
        }
        .sidebar ul li:hover {
            border: 1px, solid, white;
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
            margin-left: 15px;
            margin-right: 50px;
            margin-bottom: 25px;
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
        <h2>{Logo}  TINT</h2>
        <ul>
            <li onclick="navigateTo('dashboard.php')">Dashboard</li>
            <!-- TODO: Change attendance URL to proper student one -->
            <li onclick="navigateTo('attendance.php')">Attendance</li>  
            <li onclick="navigateTo('marks.php')">View Marks</li>
            <li>Documents</li>
            <li>Update Details</li>
            <li>Settings</li>
            <li onclick="navigateTo('logout.php')">Log out</li>
        </ul>
    </div>
    <div class="main-content">
        <div class="profile-section">
            <img src="profile.jpg" alt="Profile Picture" ><br>
            <?php 
            if(isset($_SESSION['userName'])) {
                echo "<h2>Welcome " . $_SESSION['userName'] . "<h2>"; // Output: Welcome "JohnDoe"
            } else {
                echo "Session variable not set.";
            }
            ?><br>    
        </div>
        <div class="card">
            <h3>Student Details</h3>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<br><p>Name : " . $row["name"] . "</p><br>" .
                        "<p>Roll No.: " . $row["roll"] . "</p><br>" .
                        "<p>Registration Id : " . $row["reg_no"] . "</p><br>" .
                        "<p>Email : " . $row["email"] . "</p><br>" .
                        "<p>Mobile No. : " . $row["mobile"] . "</p><br>" .
                        "<p>Stream : " . $row["stream"] . "</p><br>" ;

                    $_SESSION['roll'] = $row['roll'];   //Storing roll
                    $_SESSION['reg_no'] = $row['reg_no'];   //Storing registration_no
                    $_SESSION['email'] = $row['email'];   //Storing email address
                    $_SESSION['mobile'] = $row['mobile'];   //Storing mobile_no
                    $_SESSION['stream'] = $row['stream'];   //Storing stream
                }
            } else {
                echo "No records found";
            }
            ?>
        </div>
        <div class="card">
            <h3>Actions</h3>
            <br>
            <button class="btn" onclick="navigateTo('marks.php')">View Marks</button>
            <button class="btn" onclick="navigateTo('Student_Attendance.php')">Attendance</button>
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
