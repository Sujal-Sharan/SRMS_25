<?php
require_once("DB_Connect.php");
require_once("session_logout.php");

// Check if data was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $studentArray = filter_input(INPUT_POST, 'student_id', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
    // $subArray = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
    // $semArray = filter_input(INPUT_POST, 'semester', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
    $markArray = filter_input(INPUT_POST, 'mark', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
    // $statusArray = filter_input(INPUT_POST, 'all_ids', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);

    $subject = $_POST['subject'] ?? NULL;
    $sem = $_POST['semester'] ?? NULL;
    $test = $_POST['test'] ?? NULL;

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

    // $date = date('Y-m-d', strtotime($_POST['attendance_date']));

    if(is_null($studentArray)){
        $conn->close();
        echo "<script>
            alert('No data found! Please try again');
            window.location.href = '" . ($_SESSION['role'] === 'admin' ? 'admin_dashboard.php' : ($_SESSION['role'] === 'faculty' ? 'faculty_dashboard.php' : 'logout.php')) . "';
        </script>";
    }
    else{
        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO marks (student_id, subject_id, semester, test_type, marks_obtained, max_marks, is_absent) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siisddi", $student, $subject, $sem, $test, $mark, $maxMark, $status);

        // Loop through submitted data and insert each row
        for ($i = 0; $i < count($studentArray); $i++) {

            $student = $studentArray[$i];
            // $subject = $subArray[$i];
            // $sem = $semArray[$i];
            $mark = $markArray[$i];
            $status = is_null($mark) ? 1 : 0;

            $stmt->execute();
        }

        $stmt->close();
        $conn->close();

        echo "<script>
            alert('Data inserted successfully!');
            window.location.href = '" . ($_SESSION['role'] === 'admin' ? 'admin_dashboard.php' : ($_SESSION['role'] === 'faculty' ? 'faculty_dashboard.php' : 'logout.php')) . "';
        </script>";
    }
} else {
    echo "<script>
        alert('No data submitted!');
        window.location.href = '" . ($_SESSION['role'] === 'admin' ? 'admin_dashboard.php' : ($_SESSION['role'] === 'faculty' ? 'faculty_dashboard.php' : 'logout.php')) . "';
    </script>";
}

?>