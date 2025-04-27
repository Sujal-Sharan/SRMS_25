<!DOCTYPE html>
<html>
<head>
    <title>Insert Multiple Users</title>
    <script>
        function addRow() {
            var table = document.getElementById("userTable");
            var row = table.insertRow();
            row.innerHTML = `
                <td><input type="text" name="id[]" required></td>
                <td><input type="text" name="name[]" required></td>
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
</head>
<body>

<h2>Add Users</h2>
<form action="T_insert.php" method="post">
    <table id="userTable" border="1">
        <tr>
            <th>User_ID</th>
            <th>Username</th>
            <th>Password</th>
            <th>Role</th>
        </tr>
        <tr>
            <td><input type="text" name="id[]" required></td>
            <td><input type="text" name="name[]" required></td>
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
    <button type="button" onclick="addRow()">Add More</button>
    <button type="submit">Submit</button>
</form>

</body>
</html>
