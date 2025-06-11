<?php
require_once("DB_Connect.php");
session_start();

// Checking for proper role
if ($_SESSION['role'] !== 'admin') {
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
    <header>
		<img src="logo.png" alt="Logo" style="height: 120px; margin-right: 10px;">
		<div style="text-align: center; flex: 1;">
			<h1 style="margin: 0; font-size: 25px; font-weight: bold;">TECHNO INTERNATIONAL NEW TOWN</h1>
			<p style="margin: 0; font-size: 17px;">(Formerly Known as Techno India College Of Technology)</p>
		</div>
		<div style="display: flex; align-items: center; font-size: 14px; margin-left: 5px;">
            <i class="fas fa-phone-alt" style="margin-right: 5px;"></i>
            <span><p>&#9742; +338910530723 / 8910530723</p></span>
        </div>
	</header>

    <div class="layout">
		<div class="sidebar">
			<nav>
				<a href="admin_dashboard.php">Dashboard</a>
				<a href="studentProfile.php">Student Profile</a>
				<a href="view_Student_Marks.php">View Marks</a>
				<a href="upload_marks_UI.php">Add Marks</a>
				<a href="view_Student_Attendance.php">View Attendance</a>
				<a href="upload_attendance.php">Add Attendance</a>
				<a href="faculty_profile_admin.php">Faculty Profile</a>
				<a href="/SRMS/SRMS_25/admin_view_docs.php">Uploaded Documents</a>
				<a href="T_AddLogin.php">Add/Remove User</a>
				<a id="active" href="reset_password_UI.php">Reset Password</a>
				<a href="logout.php"> Log out</a>
			</nav>
        </div>

        <div class="main-content">

            <!-- TODO: Add basic filters OR a textbox to enter value -->
            <!-- TODO: Properly set UI -->
            <div class="card">
                <h3>Update User Password</h3><br>
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

