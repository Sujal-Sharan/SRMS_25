<?php
require_once("DB_Connect.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['student_id'];
    $customName = $_POST['file_name'];
    $file = $_FILES['document'];

    if ($file['error'] == 0) {
        $originalName = basename($file['name']);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);

        // Customize file name
        $customName = $customName . "." . $extension;
        $target_dir = "uploads/";
        $target_path = $target_dir . $customName;

        if (move_uploaded_file($file['tmp_name'], $target_path)) {
            $stmt = $conn->prepare("INSERT INTO documents (student_id, filename, filepath) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $user_id, $customName, $target_path);
            $stmt->execute();

            echo "<script>
                    alert('Upload successful! File saved as $customName ');
                    window.location.href = '" . ($_SESSION['role'] === 'admin' ? 'admin_dashboard.php' : ($_SESSION['role'] === 'student' ? 'student.php' : 'faculty_dashboard.php')) . "';
                </script>";
        } else {

            echo "<script>
                    alert('Error: Could not uploading file');
                    window.location.href = '" . ($_SESSION['role'] === 'admin' ? 'admin_dashboard.php' : ($_SESSION['role'] === 'student' ? 'student.php' : 'faculty_dashboard.php')) . "';
                </script>";
        }
    } else {

        echo "<script>
                alert('Error: Could not uploading file');
                window.location.href = '" . ($_SESSION['role'] === 'admin' ? 'admin_dashboard.php' : ($_SESSION['role'] === 'student' ? 'student.php' : 'faculty_dashboard.php')) . "';
            </script>";
    }
}
?>
