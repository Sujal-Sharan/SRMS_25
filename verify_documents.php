<?php
require_once("DB_Connect.php");
require_once("session_logout.php");

if (!empty($_POST['verify_ids'])) {

    $ids = filter_input(INPUT_POST, "verify_ids", FILTER_SANITIZE_SPECIAL_CHARS); 

    try{
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $conn->prepare("UPDATE documents SET status = 'verified' WHERE id IN ($placeholders)");

        // Bind the IDs dynamically
        $types = str_repeat('s', count($ids));
        $stmt->bind_param($types, ...$ids);

        $stmt->execute();
        $stmt->close();

        echo "<script>
            alert('Data inserted successfully!');
            window.location.href = '" . ($_SESSION['role'] === 'admin' ? 'admin_dashboard.php' : ($_SESSION['role'] === 'faculty' ? 'faculty_dashboard.php' : 'logout.php')) . "';
        </script>";
    }
    catch(Exception $e){
        echo "<script>
            alert('ERROR: Could not complete the operation!');
            window.location.href = '" . ($_SESSION['role'] === 'admin' ? 'admin_dashboard.php' : ($_SESSION['role'] === 'faculty' ? 'faculty_dashboard.php' : 'logout.php')) . "';
        </script>";
    }
}
else{
    echo "<script>
        alert('NO Data Found!');
        window.location.href = '" . ($_SESSION['role'] === 'admin' ? 'admin_dashboard.php' : ($_SESSION['role'] === 'faculty' ? 'faculty_dashboard.php' : 'logout.php')) . "';
    </script>";
}
?>
