<?php
require_once("DB_Connect.php");
session_start();

// Check if data was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $studentArray = $_POST['student_id'] ?? NULL;
    $subArray = $_POST['subject_id'] ?? NULL;
    $semArray = $_POST['semester'] ?? NULL;
    $statusArray = $_POST['all_ids'] ?? NULL;

    // TODO: Fix date value fetch, getting 0000-00-00 as default
    $date = $_POST['attendance_date'] ?? NULL;

    if(is_null($studentArray)){
        $conn->close();
        echo "NO DATA FOUND <a href='admin_dashboard.php'>Back</a>";
        exit();
    }
    else{
        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO attendance (student_id, subject_id, semester, attendance_date, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("siiss", $student, $subject, $sem, $date, $status);

        // Loop through submitted data and insert each row
        for ($i = 0; $i < count($studentArray); $i++) {

            $student = $studentArray[$i];
            $subject = $subArray[$i];
            $sem = $semArray[$i];

            // $status = (($statusArray[$i] == 1) ? 'PRESENT' : 'ABSENT');
            $status = isset($_POST['attendance'][$statusArray[$i]]) ? 'PRESENT' : 'ABSENT';

            $stmt->execute();
        }

        $stmt->close();
        $conn->close();

        echo "Data inserted successfully! <a href='admin_dashboard.php'>Back</a> ";
    }
} else {
    echo "No data submitted!";
}

?>