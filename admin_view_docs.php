<?php
require_once("DB_Connect.php");
session_start();

// $conn = new mysqli("localhost", "root", "", "srms");

 $batch = $_GET['batch'] ?? '';
 $stream = $_GET['stream'] ?? '';
 $semester = $_GET['semester'] ?? '';
 $roll_no = $_GET['roll_no'] ?? '';
 $reg_no = $_GET['reg_no'] ?? '';

$sql = "SELECT * FROM admin_view_docs WHERE 1=1";
//$sql = "SELECT * FROM students WHERE 1=1";
 if ($batch) $sql .= " AND batch = '$batch'";
 if ($stream) $sql .= " AND stream = '$stream'";
 if ($semester) $sql .= " AND semester = '$semester'";
 if ($roll_no) $sql .= " AND roll_no = '$roll_no'";
 if ($reg_no) $sql .= " AND reg_no = '$reg_no'";
 $sql .= " ORDER BY roll_no";

$stmt = $conn->prepare($sql);

// Bind parameters dynamically
//if (!empty($values)) {
    //$stmt->bind_param($types, ...$values);  // Spread operator to pass the parameters
//}

$stmt->execute();
$result = $stmt->get_result();

 $batches = ['2019-23', '2020-24', '2021-25', '2022-26', '2023-27'];
 $streams = ['CSE', 'IT', 'AIML', 'ECE', 'EE', 'ME', 'CIVIL'];
 $semesters = range(1, 8);

// Create dummy ZIPs if missing
$uploadDir = "uploads/zips/";
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

function createDummyZip($filename) {
    $zip = new ZipArchive();
    if ($zip->open($filename, ZipArchive::CREATE) === TRUE) {
        $zip->addFromString("dummy.txt", "This is a dummy document file where all Students Documents is stored.");
        $zip->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!--<title>Student Documents (Uploaded By Students & Verified By HOD and Faculty)</title>-->
    <link rel="stylesheet" href="Styles/global_base.css">

    <style>
        body {
            padding-top: 120px;
        }

        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
            align-items: flex-end;
        }

        .filter-group {
            flex: 1;
            min-width: 150px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100% !important;
        }
    </style>
</head>
<body>
    <header style="background: #1abc9c; color: white; padding: 7px; display: flex; align-items: center; position: fixed; top: 0; width: 100%; z-index: 1000;">
        <img src="logo.png" alt="Logo" style="height: 100px; margin-right: 20px;">
        <div style="text-align: center; flex: 1;">
            <h1 style="margin: 0; font-size: 24px; font-weight: bold;">TECHNO INTERNATIONAL NEWTOWN</h1>
            <p style="margin: 0; font-size: 14px;">(Formerly Known as Techno India College Of Technology)</p>
        </div>
        <div style="display: flex; align-items: center; font-size: 14px; margin-left: 5px;">
            <i class="fas fa-phone-alt" style="margin-right: 5px;"></i>
            <span><p>&#9742; +338910530723 / 8910530723</p></span>
        </div>
    </header>

    <div style="
        position: relative;
        display: flex;
        justify-content: flex-end;
        gap: 6px;
        margin-top: -20px; 
        margin-right: 20px;
        z-index: 10;">
    <a href="admin_dashboard.php" style="text-decoration: none;">
        <button style="
            background: linear-gradient(135deg, #3498db, #2ecc71);
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;"
            onmouseover="this.style.transform='scale(1.05)'"
           onmouseout="this.style.transform='scale(1)'">
            ⬅ Dashboard
        </button>
    </a>
     <a href="logout.php" style="text-decoration: none;">
        <button style="
            background: red;
            color: white;
            border: none;
            padding: 12px 15px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            min-width: 130px;
        " onmouseover="this.style.transform='scale(1.05)'"
           onmouseout="this.style.transform='scale(1)'">
            Logout
        </button>
    </a>
    </div>

    <div class="container-fluid mt-5">
    <h2 class="text-center mb-4">Student Documents</h2>
    

    <form action="admin_view_docs.php" method="GET" class="filter-row">
        <div class="filter-group">
            <label for="batch" class="form-label">Batch</label>
            <select name="batch" id="batch" class="form-control">
                <option value="">All</option>
                <?php foreach ($batches as $b): ?>
                    <option value="<?= $b ?>" <?= $batch == $b ? 'selected' : '' ?>><?= $b ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-group">
            <label for="stream" class="form-label">Stream</label>
            <select name="stream" id="stream" class="form-control">
                <option value="">All</option>
                <?php foreach ($streams as $s): ?>
                    <option value="<?= $s ?>" <?= $stream == $s ? 'selected' : '' ?>><?= $s ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-group">
            <label for="semester" class="form-label">Semester</label>
            <select name="semester" id="semester" class="form-control">
                <option value="">All</option>
                <?php foreach ($semesters as $sem): ?>
                    <option value="<?= $sem ?>" <?= $semester == $sem ? 'selected' : '' ?>><?= $sem ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-group">
            <label for="roll_no" class="form-label">Roll No</label>
            <input type="text" name="roll_no" id="roll_no" class="form-control" value="<?= htmlspecialchars($roll_no) ?>">
        </div>
        <div class="filter-group">
            <label for="reg_no" class="form-label">Reg No</label>
            <input type="text" name="reg_no" id="reg_no" class="form-control" value="<?= htmlspecialchars($reg_no) ?>">
        </div>
        <div class="filter-group">
            <button type="submit" class="btn btn-success w-100">Filter</button>
        </div>
    </form>
    
    <div class="table-responsive">
    <table class="table table-bordered table-striped text-center align-middle w-100">
        <!-- table headers and rows -->
    </table>
    </div>

    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center align-middle">
                <thead class="table-success">
                    <tr>
                        <th>Student Name</th>
                        <th>Roll No</th>
                        <th>Reg No</th>
                        <th>Stream</th>
                        <th>Batch</th>
                        <th>Semester</th>
                        <th>Document Names</th>
                        <th>Uploaded On</th>
                        <th>Preview (ZIP)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): 
                        $roll = $row['roll_no'];
                        $zipPath = $uploadDir . $roll . ".zip";

                        if (!file_exists($zipPath)) {
                            createDummyZip($zipPath);
                        }
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($row['student_name']) ?></td>
                            <td><?= htmlspecialchars($roll) ?></td>
                            <td><?= htmlspecialchars($row['reg_no']) ?></td>
                            <td><?= htmlspecialchars($row['stream']) ?></td>
                            <td><?= htmlspecialchars($row['batch']) ?></td>
                            <td><?= htmlspecialchars($row['semester']) ?></td>
                            <td><?= htmlspecialchars($row['document_name']) ?></td>
                            <td><?= htmlspecialchars($row['upload_date']) ?></td>
                            <td>
                                <a href="<?= $zipPath ?>" class="btn btn-primary btn-sm" target="_blank">Download</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center">No records found.</div>
    <?php endif; ?>
</div>
</body>
</html>
