<?php
session_start();
include("DB_Connect.php"); // Adjust if needed

// Simulated login session (replace with real session data)
$_SESSION['faculty'] = [
    'faculty_id' => 101,
    'semester' => 5
];

$faculty_id = $_SESSION['faculty']['faculty_id'];

// Get faculty info
$faculty_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM faculty_subjects WHERE faculty_id = $faculty_id"));
$subject_name = $faculty_info['subject_name'];
$subject_code = $faculty_info['subject_code'];
$department = $faculty_info['department'];
$semester = $faculty_info['semester'];

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'fetch_students') {
        $category = $_POST['category'];
        $year = $_POST['year'];
        $section = $_POST['section'];

        $students = [];
        $stmt = mysqli_prepare($conn, "SELECT student_id, student_name FROM student_details WHERE department = ? AND year = ? AND section = ?");
        mysqli_stmt_bind_param($stmt, "sis", $department, $year, $section);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $students[] = $row;
        }

        echo json_encode($students);
        exit();
    }

    // Save marks
    $input = json_decode(file_get_contents("php://input"), true);
    if ($input['action'] === 'save_marks') {
        $category = $input['category'];
        $marksData = $input['marks'];

        foreach ($marksData as $row) {
            $student_id = $row['student_id'];
            $ca1 = isset($row['ca1']) ? $row['ca1'] : null;
            $ca2 = isset($row['ca2']) ? $row['ca2'] : null;
            $ca3 = isset($row['ca3']) ? $row['ca3'] : null;
            $ca4 = isset($row['ca4']) ? $row['ca4'] : null;
            $pca1 = isset($row['pca1']) ? $row['pca1'] : null;
            $pca2 = isset($row['pca2']) ? $row['pca2'] : null;

            $check = mysqli_prepare($conn, "SELECT id FROM internal_marks WHERE student_id = ? AND subject_code = ? AND category = ?");
            mysqli_stmt_bind_param($check, "sss", $student_id, $subject_code, $category);
            mysqli_stmt_execute($check);
            mysqli_stmt_store_result($check);

            if (mysqli_stmt_num_rows($check) > 0) {
                // Update
                $stmt = mysqli_prepare($conn, "UPDATE internal_marks SET ca1=?, ca2=?, ca3=?, ca4=?, pca1=?, pca2=? WHERE student_id=? AND subject_code=? AND category=?");
                mysqli_stmt_bind_param($stmt, "iiiiissss", $ca1, $ca2, $ca3, $ca4, $pca1, $pca2, $student_id, $subject_code, $category);
            } else {
                // Insert
                $stmt = mysqli_prepare($conn, "INSERT INTO internal_marks (student_id, subject_code, category, ca1, ca2, ca3, ca4, pca1, pca2) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "sssiiiiii", $student_id, $subject_code, $category, $ca1, $ca2, $ca3, $ca4, $pca1, $pca2);
            }

            mysqli_stmt_execute($stmt);
        }

        echo json_encode(['status' => 'success']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Faculty Marks Upload</title>
    <link rel="stylesheet" href="Styles/global_base.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body { font-family: Arial; padding-top: 120px; }
        select, input[type='number'] { padding: 5px; margin: 5px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background: #f0c040; }
        button { background-color: #f1c40f;padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
<header style="background: #1abc9c; color: white; padding: 7px; display: flex; align-items: center; position: fixed; top: 0; width: 100%; z-index: 1000;">
        <img src="logo.png" alt="Logo" style="height: 100px; margin-right: 20px;">
        <div style="text-align: center; flex: 1;">
            <h1 style="margin: 0; font-size: 24px; font-weight: bold;padding-left: 20px;">TECHNO INTERNATIONAL NEWTOWN</h1>
            <p style="margin: 0; font-size: 14px;">(Formerly Known as Techno India College Of Technology)</p>
        </div>
        <div style="display: flex; align-items: center; font-size: 14px; margin-left: 5px;">
            <i class="fas fa-phone-alt" style="margin-right: 5px;"></i>
            <span><p>&#9742; +338910530723 / 8910530723</p></span>
        </div>
</header>

<h2 style="text-align: center;">Marks Uploading Page</h2><br>
<br>
<div style="text-align:center;">
    <strong>Subject:</strong> <?= $subject_name ?> (<?= $subject_code ?>) |
    <strong>Department:</strong> <?= $department ?> |
    <strong>Semester:</strong> <?= $semester ?>
</div><br>

<div style="text-align: center; margin-top: 20px;">
    <select id="category">
        <option value="">Select Category</option>
        <option value="CA">CA</option>
        <option value="PCA">PCA</option>
    </select>

    <select id="year">
        <option value="">Select Year</option>
        <?php for ($i = 1; $i <= 4; $i++) echo "<option value='$i'>$i</option>"; ?>
    </select>

    <select id="section">
        <option value="">Select Section</option>
        <option value="All">All</option>
        <option value="A">A</option>
        <option value="B">B</option>
        <option value="C">C</option>
    </select>

    <button onclick="fetchStudents()">Load Students</button>
</div>

<form id="marksForm">
    <table id="marksTable">
        <thead>
            <tr id="tableHead">
                <th>Student ID</th>
                <th>Student Name</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <!-- Rows go here -->
        </tbody>
    </table>
    <div style="text-align: center; margin-top: 15px;">
        <button type="button" onclick="saveMarks()">Save Marks</button>
    </div>
</form>

<script>
let currentCategory = '';

function fetchStudents() {
    const category = document.getElementById('category').value;
    const year = document.getElementById('year').value;
    const section = document.getElementById('section').value;

    if (!category || !year || !section) {
        alert("Select category, year, and section.");
        return;
    }

    currentCategory = category;

    const formData = new URLSearchParams();
    formData.append('action', 'fetch_students');
    formData.append('category', category);
    formData.append('year', year);
    formData.append('section', section);

    fetch('marks_upload(faculty).php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: formData
    })
    .then(res => res.json())
    .then(data => updateTable(data));
}

function updateTable(students) {
    const headRow = document.getElementById('tableHead');
    const body = document.getElementById('tableBody');

    headRow.innerHTML = `<th>Student ID</th><th>Student Name</th>`;
    body.innerHTML = '';

    if (currentCategory === 'CA') {
        ['CA 1', 'CA 2', 'CA 3', 'CA 4'].forEach(ca => {
            headRow.innerHTML += `<th>${ca}</th>`;
        });
    } else {
        ['PCA 1', 'PCA 2'].forEach(pca => {
            headRow.innerHTML += `<th>${pca}</th>`;
        });
    }

    students.forEach(s => {
        let row = `<tr data-id="${s.student_id}"><td>${s.student_id}</td><td>${s.student_name}</td>`;

        if (currentCategory === 'CA') {
            for (let i = 1; i <= 4; i++) {
                row += `<td><input type="number" name="ca${i}" min="0" max="25" /></td>`;
            }
        } else {
            for (let i = 1; i <= 2; i++) {
                row += `<td><input type="number" name="pca${i}" min="0" max="40" /></td>`;
            }
        }

        row += `</tr>`;
        body.innerHTML += row;
    });
}

function saveMarks() {
    const rows = document.querySelectorAll('#tableBody tr');
    const marks = [];

    rows.forEach(row => {
        const student_id = row.dataset.id;
        const inputs = row.querySelectorAll('input');
        const markEntry = { student_id };

        inputs.forEach(input => {
            markEntry[input.name] = input.value || null;
        });

        marks.push(markEntry);
    });

    fetch('marks_upload(faculty).php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'save_marks', category: currentCategory, marks })
    })
    .then(res => res.json())
    .then(resp => {
        if (resp.status === 'success') alert("Marks saved successfully!");
        else alert("Error saving marks.");
    });
}
</script>

</body>
</html>
