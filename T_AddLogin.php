<?php
require_once("DB_Connect.php");
require_once("session_logout.php");

?>
<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <link rel="stylesheet" href="Styles/global_base.css">
    <link rel="icon" type="image/x-icon" href="logo.png">

    <script>
        function addRow() {
            var table = document.getElementById("userTable");
            var row = table.insertRow();
            row.innerHTML = `
                <td><input type="text" name="id[]" required></td>
                <td><input type="password" name="password[]" required></td>
                <td>
                    <select name="role[]">
                        <option value="admin">Admin</option>
                        <option value="faculty">Faculty</option>
                        <option value="student">Student</option>
                    </select>
                </td>
            `;
        }
    </script>
    <!-- TODO: Align table content -->
    <style>
        .btn{
            background-color: rgb(50, 225, 47);
            border: 1px, solid, black;
            margin: 20px;
            margin-bottom: 2px;
            padding: 10px;
        }
        .btn:hover{
            background-color: rgb(43, 193, 41);
            border: 2px, solid, black;
        }
        #table_header{
            border: none;
            margin-left: 200px;
            margin-bottom: 10px;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            font-size: medium;
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
		<div style="display: flex; align-items: center; font-size: 14px; margin-left: 5px;">
            <i class="fas fa-phone-alt" style="margin-right: 5px;"></i>
            <span><p>Logged in as <?php echo $_SESSION['user_id'] ?></p></span>
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
				<a href="view_docs.php">View Documents</a>
				<a id="active" href="T_AddLogin.php">Add/Remove User</a>
				<a href="reset_password_UI.php">Reset Password</a>
				<a href="logout.php"> Log out</a>
			</nav>
        </div>

        <div class="main-content">
            <div class="card">
                <h2>Add New User</h2><br>
                <div class="card">
                    <form action="preview_table.php" method="POST" enctype="multipart/form-data">
                        <input type="file" name="csv_file" required>

                        <!-- Hidden field, table name wll be pre-set as given in database -->
                        <input type="text" name="table" value="login" hidden>
                        <button class="btn-save" type="submit">Preview</button>
                    </form>
                </div>

                <div class="card">
                    <form action="T_insert.php" method="post">
                        <table id="userTable" border="1">
                            <tr>
                                <th>User_ID</th>
                                <th>Password</th>
                                <th>Role</th>
                            </tr>
                            <tr>
                                <td><input type="text" name="id[]" required></td>
                                <td><input type="password" name="password[]" required></td>
                                <td>
                                    <select name="role[]">
                                        <option value="admin">Admin</option>
                                        <option value="faculty">Faculty</option>
                                        <option value="student">Student</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <br>
                        <button class="btn" type="button" onclick="addRow()">Add More</button>
                        <button class="btn" id="submit" type="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>
