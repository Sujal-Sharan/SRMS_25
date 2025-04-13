<?php
session_start();
$conn = new mysqli("localhost", "root", "", "srms");

// Check for cookies if session not set
if (!isset($_SESSION['user_id']) && isset($_COOKIE['user_id'])) {
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    $_SESSION['username'] = $_COOKIE['username'];
    $_SESSION['role'] = $_COOKIE['role'];
}

// If already logged in, redirect based on role
if (isset($_SESSION['user_id'])) {
    switch ($_SESSION['role']) {
        case 'admin':
            header("Location: Test_dashboard_responsive.html"); break;
        case 'faculty':
            header("Location: dashboard.php"); break;
        case 'student':
            header("Location: dashboard.php"); break;
    }
    exit;
}

if (isset($_POST["login"])) {

    $user_id = filter_input(INPUT_POST, "userId", FILTER_SANITIZE_SPECIAL_CHARS); // Sanitize user_Id
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS); // Sanitize password

    $stmt = $conn->prepare("SELECT * FROM login WHERE user_id = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if (isset($_POST['remember_me'])) {
                setcookie("user_id", $user['user_id'], time() + (7 * 24 * 60 * 60), "/");
                setcookie("username", $user['username'], time() + (7 * 24 * 60 * 60), "/");
                setcookie("role", $user['role'], time() + (7 * 24 * 60 * 60), "/");
            }

            header("Location: Test_dashboard_responsive.html"); // re-check for redirection
        } else {
            header("Location: Test_login.php?error=Invalid username or password");
        }
    } else {
        header("Location: Test_login.php?error=Invalid username or password");
    }
}
?>
