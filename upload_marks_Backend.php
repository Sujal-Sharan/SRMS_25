<?php
require_once("DB_Connect.php");
session_start();

// Check if data was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $studentArray = filter_input(INPUT_POST, 'student_id', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
    $subArray = filter_input(INPUT_POST, 'subject_id', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
    $semArray = filter_input(INPUT_POST, 'semester', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
    $markArray = filter_input(INPUT_POST, 'semester', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
    $statusArray = filter_input(INPUT_POST, 'all_ids', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);

    $test = $_POST['test_type'];

    $maxMark = 25;
    switch($test){
        case "PCA1":
            $maxMark = 40;
            break;
        case "PCA2":
            $maxMark = 40;
            break;
        default:
            $maxMark = 25;
    }

    $date = date('Y-m-d', strtotime($_POST['attendance_date']));

    if(is_null($studentArray)){
        $conn->close();
        echo "NO DATA FOUND <a href='admin_dashboard.php'> Back </a>";
        exit();
    }
    else{
        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO marks (student_id, subject_id, semester, test_type, marks_obtained, max_marks, is_absent) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siisddi", $student, $subject, $sem, $test, $mark, $maxMark, $status);

        // Loop through submitted data and insert each row
        for ($i = 0; $i < count($studentArray); $i++) {

            $student = $studentArray[$i];
            $subject = $subArray[$i];
            $sem = $semArray[$i];
            $mark = $markArray[$i];

            // $status = (($statusArray[$i] == 1) ? 'PRESENT' : 'ABSENT');
            $status = isset($_POST['attendance'][$statusArray[$i]]) ? 'PRESENT' : 'ABSENT';

            $stmt->execute();
        }

        $stmt->close();
        $conn->close();

        echo "Data inserted successfully! <a href='admin_dashboard.php'> Back </a> ";
    }
} else {
    echo "No data submitted! <a href='admin_dashboard.php'> Back </a>";
}

?>