<?php
require_once("DB_Connect.php");
session_start();

// Checking for proper role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// TODO: Add proper filters to narrow the search range
// Fecth list of users
$result = $conn->query("SELECT user_id, role FROM login");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="Styles/global_base.css">
</head>
<body>
    <header>Reset Password</header>
    <div class="layout">
        <div class="sidebar">
        <img src="logo.png" alt="Logo" style="height: 100px; margin-left: 50px;">
            <nav>
                <a id="active" href="admin_dashboard.php">Dashboard</a>
                <a href="studentProfile.html">Student Profile</a>
                <a href="/SRMS/SRMS_25/marks.php">Marks</a>
                <a href="/SRMS/SRMS_25/Student_Attendance.php">Attendance</a>
                <a href="faculty_profile_admin.php">Faculty Profile</a>
                <a href="/SRMS/SRMS_25/admin_view_docs.php">Uploaded Documents</a>
                <a href="T_AddLogin.php">Add/Remove User</a>
                <a href="/SRMS/SRMS_25/logout.php">Log out</a>
            </nav>
        </div>

        <div class="main-content">
            <header>
                <h3>Main Header</h3>
            </header><br>
            
            <!-- TODO: Add basic filters OR a textbox to enter value -->
            <!-- TODO: Properly set UI -->
            <div class="card">
                <h3>Form header</h3><br>
                <form action="reset_password_actual.php" method="POST">
                    <label>Select User:</label><br>
                    <select name="user_id" required>
                        <?php while ($user = $result->fetch_assoc()): ?>
                            <option value="<?php echo $user['user_id']; ?>">
                                <?php echo htmlspecialchars($user['user_id'] . " (" . $user['role'] . ")"); ?>
                            </option>
                        <?php endwhile; ?>
                    </select><br><br>

                    <label>Enter New Password:</label><br>
                    <input type="password" name="new_password" required><br><br>

                    <button class="btn" type="submit" name="reset">Reset Password</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

