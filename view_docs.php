<?php
require_once("DB_Connect.php");
require_once("session_logout.php");

$result = $conn->query("SELECT * FROM documents WHERE status = 'pending'");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Docs</title>
    <link rel="stylesheet" href="Styles/global_base.css">
    <link rel="icon" type="image/x-icon" href="logo.png">

</head>
<body>
    <header>
        <img src="logo.png" alt="Logo" style="height: 100px; margin-right: 20px;">
        <div style="text-align: center; flex: 1;">
            <h1 style="margin: 0; font-size: 24px; font-weight: bold;">TECHNO INTERNATIONAL NEWTOWN</h1>
            <p style="margin: 0; font-size: 14px;">(Formerly Known as Techno India College Of Technology)</p>
        </div>
        <div style="display: flex; align-items: center; font-size: 14px; margin-left: 5px;">
            <i class="fas fa-phone-alt" style="margin-right: 5px;"></i>
            <span><p>Logged in as <?php echo ($_SESSION['name']) ?? $_SESSION['user_id'] ?></p></span>
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
				<a href="upload_file_UI.html">Upload Document</a>
				<a id="active" href="view_docs.php">View Documents</a>
				<a href="T_AddLogin.php">Add/Remove User</a>
				<a href="reset_password_UI.php">Reset Password</a>
				<a href="logout.php"> Log out</a>
			</nav>
		</div>

        <div class="main-content">
            <div class="card">
                <h2>Verify Uploaded Documents</h2>
                <form method="POST" action="verify_documents.php">
                    <table border="1">
                        <tr>
                            <th>Filename</th>
                            <th>Preview</th>
                            <th>Mark As</th>
                        </tr>

                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['filename']) ?></td>

                            <td>
                            <?php
                                $filePath = $row['filepath'];
                                $ext = pathinfo($filePath, PATHINFO_EXTENSION);
                                if (in_array(strtolower($ext), ['png', 'jpg', 'jpeg', 'gif', 'pdf', 'csv'])) {
                                    echo "<a href='{$filePath}' target='_blank'>View</a>";
                                } else {
                                    echo "Unsupported";
                                }
                            ?>
                            </td>

                            <td>
                                <label>
                                    <input type="radio" name="status[<?= $row['id'] ?>]" value="verified"> Verified
                                </label>
                                <label>
                                    <input type="radio" name="status[<?= $row['id'] ?>]" value="incorrect"> Incorrect
                                </label>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </table>

                    <button class="btn-save" type="submit">Submit Status</button>
                    </form>
            </div>
        </div>
    </div>

</body>
</html>