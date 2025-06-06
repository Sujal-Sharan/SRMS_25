<!DOCTYPE html>
<html>
<head>
    <title>Insert Multiple Users</title>
    <link rel="stylesheet" href="Styles/global_base.css">
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
    <header>User Management</header>
    <div class="layout">
        <div class="sidebar">
        <img src="logo.png" alt="Logo" style="height: 100px; margin-left: 50px;">
        <nav>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="studentProfile.html">Student Profile</a>
            <a href="/SRMS/SRMS_25/marks.php">Marks</a>
            <a href="/SRMS/SRMS_25/Student_Attendance.php">Attendance</a>
            <a href="faculty_profile_admin.php">Faculty Profile</a>
            <a href="/SRMS/SRMS_25/admin_view_docs.php">Uploaded Documents</a>
            <a id="active" href="T_AddLogin.php">Add/Remove User</a>
            <a href="/SRMS/SRMS_25/logout.php">Log out</a>
        </nav>
        </div>

        <div class="main-content">
            <header>
                <h2>Add Users</h2>
            </header>

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
    
</body>
</html>
