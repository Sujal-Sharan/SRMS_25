

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

<?php if (isset($_GET['error'])): ?>
    <p style="color:red;"><?php echo htmlspecialchars($_GET['error']); ?></p>
<?php endif; ?>

<form action="Test_auth.php" method="POST">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <label>
        <input type="checkbox" name="remember_me"> Remember Me
    </label><br><br>

    <button type="submit" name="login">Login</button>
</form>

<p><a href="Test_forgot_password.php">Forgot Password?</a></p>

</body>
</html>
