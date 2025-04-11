<?php
session_start();
$conn = new mysqli("localhost", "root", "", "srms");

$error = "";

if (isset($_POST["login"])) {
    $username = $conn->real_escape_string($_POST["username"]);
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // ✅ Credentials OK
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            switch ($user['role']) {
                case 'admin':
                    header("Location: admin_dashboard.php");
                    break;
                case 'faculty':
                    header("Location: faculty_dashboard.php");
                    break;
                case 'student':
                    header("Location: student_dashboard.php");
                    break;
            }
            exit;
        } else {
            $error = "❌ Invalid username or password!";
        }
    } else {
        $error = "❌ Invalid username or password!";
    }
}
?>

<!-- HTML + Form Section -->

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login to Student Record System</h2>

<?php if (!empty($error)): ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>

<form action="Test_auth.php" method="POST">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit" name="login">Login</button>
</form>

</body>
</html>
