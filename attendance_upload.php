<?php
require_once("DB_Connect.php");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
session_start();

$departments = ['CSE', 'IT', 'AIML', 'ECE', 'EE', 'ME', 'CIVIL'];

function fetchOptions($conn, $dept = '', $semester = '') {
    $subjects = $faculty = [];

    if (!empty($dept)) {
        $query = "SELECT subject_id FROM subjects WHERE department = ?";
        if (!empty($semester)) {
            $query .= " AND current_semester = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $dept, $semester);
        } else {
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $dept);
        }

        $stmt->execute();
        $subjects = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        $stmt = $conn->prepare("SELECT faculty_id, name FROM faculty_details WHERE department = ?");
        $stmt->bind_param("s", $dept);
        $stmt->execute();
        $faculty = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }

    return [$subjects, $faculty];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_attendance'])) {
    $subject_id = $_POST['subject'];
    $faculty_id = $_POST['faculty'];
    $date = $_POST['attendance_date'];
    $student_ids = $_POST['student_id'];
    $attendance = $_POST['attendance'] ?? [];

    $stmt = $conn->prepare("INSERT INTO attendance (student_id, subject_id, faculty_id, attendance_date, is_present) VALUES (?, ?, ?, ?, ?)");

    foreach ($student_ids as $id) {
        $present = in_array($id, $attendance) ? 1 : 0;
        $stmt->bind_param("siisi", $id, $subject_id, $faculty_id, $date, $present);
        $stmt->execute();
    }

    $stmt->close();
    $message = "Attendance saved successfully.";
}

$students = [];
$subjects = $faculty_details = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['filter'])) {
    $dept = $_GET['department'];
    $semester = $_GET['current_semester'];
    $section = $_GET['section'];
    $group = $_GET['group'];
    $year = $_GET['batch_year'];

    $sql = "SELECT * FROM students WHERE department=? AND current_semester=? AND section=? AND batch_year=? AND `group`=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisis", $dept, $semester, $section, $year, $group);
    $stmt->execute();
    $students = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    list($subjects, $faculty_details) = fetchOptions($conn, $dept, $semester);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Attendance</title>

    <!-- Embedded working CSS -->
    <style>
        body{
            margin: 0px;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding-top: 120px; /* Space for fixed header */
        }
        header {
            background: #1abc9c;
            color: white;
            padding: 7px;
            display: flex;
            align-items: center;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .layout {
            display: flex;
        }

        /* Fix found: Remove all whitespaces then tab for appropriate spacing */
        /* Do as shown below for all, then apply proper spacing */
        .sidebar {width: 220px;background: #2c3e50;min-height: 100vh;padding-top: 20px;position: fixed;top: 120px;left: 0;overflow-y: auto;}

        .sidebar nav a {
            display: block;
            padding: 12px;
            color: white;
            text-decoration: none;
            border-bottom: 1px solid #34495e;
        }

        .sidebar nav a:hover, .sidebar nav a#active {
            background: #1abc9c;
        }

        .main-content {
            margin-left: 240px;
            padding: 20px;
            width: calc(100% - 240px);
        }

        h1 {
            text-align: center;
            color: #2c3e50;
        }

        form {
            background: white;
            padding: 20px;
            margin: 20px auto;
            max-width: 900px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        form label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        select, input[type="date"], input[type="submit"], button {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        table th {
            background-color: #f2f2f2;
        }

        button {
            background: #1abc9c;
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }

        .success {
            color: green;
            font-weight: bold;
            text-align: center;
        }
</style>
</head>

<body>
    <header
        style="background: #1abc9c; color: white; padding: 7px; display: flex; align-items: center; position: fixed; top: 0; width: 100%; z-index: 1000;">
        <img src="logo.png" alt="Logo" style="height: 100px; margin-right: 20px;">
        <div style="text-align: center; flex: 1;">
            <h1 style="margin: 0; font-size: 24px; font-weight: bold;">TECHNO INTERNATIONAL NEWTOWN</h1>
            <p style="margin: 0; font-size: 14px;">(Formerly Known as Techno India College Of Technology)</p>
        </div>
        <div style="display: flex; align-items: center; font-size: 14px; margin-left: 5px;">
            <i class="fas fa-phone-alt" style="margin-right: 5px;"></i>
            <span>
                <p>&#9742; +338910530723 / 8910530723</p>
            </span>
        </div>
    </header>

    <div class="layout">
        <div class="sidebar">
            <nav>
                <a href="admin_dashboard.php">Dashboard</a>
                <a href="studentProfile.php">Student Profile</a>
                <a id="active" href="view_Student_Marks.php">Marks</a>
                <a href="">Attendance</a>
                <a href="faculty_profile_admin.php">Faculty Profile</a>
                <a href="/SRMS/SRMS_25/admin_view_docs.php">Uploaded Documents</a>
                <a href="T_AddLogin.php">Add/Remove User</a>
                <a href="reset_password_UI.php">Reset Password</a>
                <a href="/SRMS/SRMS_25/logout.php"> Log out</a>
            </nav>
        </div>
    </div>

    <div class="main-content">
        <h1>Upload Attendance</h1>

        <?php if (!empty($message)): ?>
            <p class="success"><?= $message ?></p>
        <?php endif; ?>

        <form method="GET" action="attendance_upload.php">
            <!-- Filters -->
            <label>Department:
                <select name="department" required>
                    <option value="">Select</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?= $dept ?>" <?= ($_GET['department'] ?? '') === $dept ? 'selected' : '' ?>><?= $dept ?></option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label>Semester:
                <select name="current_semester" required>
                    <option value="">Select</option>
                    <?php for ($i = 1; $i <= 8; $i++): ?>
                        <option value="<?= $i ?>" <?= ($_GET['current_semester'] ?? '') == $i ? 'selected' : '' ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </label>

            <label>Year:
                <select name="batch_year" required>
                    <option value="">Select</option>
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <option value="<?= $i ?>" <?= ($_GET['batch_year'] ?? '') == $i ? 'selected' : '' ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </label>

            <label>Section:
                <select name="section" required>
                    <option value="">Select</option>
                    <?php foreach (['A', 'B', 'C'] as $sec): ?>
                        <option value="<?= $sec ?>" <?= ($_GET['section'] ?? '') === $sec ? 'selected' : '' ?>><?= $sec ?></option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label>Group:
                <select name="group" required>
                    <option value="">Select</option>
                    <?php foreach (['A', 'B', 'Both'] as $grp): ?>
                        <option value="<?= $grp ?>" <?= ($_GET['group'] ?? '') === $grp ? 'selected' : '' ?>><?= $grp ?></option>
                    <?php endforeach; ?>
                </select>
            </label>

            <input type="submit" name="filter" value="Load Students">
        </form>

        <?php if (!empty($students)): ?>
        <form method="POST">
            <label>Subject:
                <select name="subject" required>
                    <?php foreach ($subjects as $sub): ?>
                        <option value="<?= $sub['subject_id'] ?>"><?= $sub['subject_id'] ?></option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label>Faculty:
                <select name="faculty" required>
                    <?php foreach ($faculty_details as $fac): ?>
                        <option value="<?= $fac['faculty_id'] ?>"><?= $fac['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label>Date: <input type="date" name="attendance_date" required></label>

            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Mark Present</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $stu): ?>
                        <tr>
                            <td>
                                <input type="hidden" name="student_id[]" value="<?= $stu['student_id'] ?>">
                                <?= $stu['student_id'] ?>
                            </td>
                            <td><input type="checkbox" name="attendance[]" value="<?= $stu['student_id'] ?>"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <button type="submit" name="save_attendance">Save Attendance</button>
        </form>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
