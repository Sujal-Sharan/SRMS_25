<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "srms");
$result = $conn->query("SELECT user_id, username, role FROM users ORDER BY role, username");
?>

<h2>Admin: Reset User Password</h2>

<form action="admin_update_password.php" method="POST">
    <label>Select User:</label><br>
    <select name="user_id" required>
        <?php while ($user = $result->fetch_assoc()): ?>
            <option value="<?php echo $user['user_id']; ?>">
                <?php echo htmlspecialchars($user['username'] . " (" . $user['role'] . ")"); ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label>New Password:</label><br>
    <input type="password" name="new_password" required><br><br>

    <button type="submit" name="reset">Reset Password</button>
</form>
