<?php
$host = "localhost";
$user = "root";
$password = ""; // default in XAMPP
$db = "srms"; 

$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update status
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['verify_id'])) {
    $id = intval($_POST['verify_id']);
    $conn->query("UPDATE student_documents SET status='Verified' WHERE id=$id");
}

// Get filters from GET request
$batch = $_GET['batch'] ?? '';
$stream = $_GET['stream'] ?? '';
$semester = $_GET['semester'] ?? '';
$roll = $_GET['roll'] ?? '';
$reg = $_GET['reg'] ?? '';

// Build WHERE clause
$conditions = [];
if ($batch !== '') $conditions[] = "batch='$batch'";
if ($stream !== '') $conditions[] = "stream='$stream'";
if ($semester !== '') $conditions[] = "semester='$semester'";
if ($roll !== '') $conditions[] = "roll_no LIKE '%$roll%'";
if ($reg !== '') $conditions[] = "reg_no LIKE '%$reg%'";

$where = count($conditions) > 0 ? "WHERE " . implode(" AND ", $conditions) : "";

$sql = "SELECT * FROM student_documents $where";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Student Documents - HOD Panel</title>
    <link rel="stylesheet" href="Styles/global_base.css">
    <style>
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f5f5f5;
        }
        .alert {
        background-color: #ffdddd;
        color: red;
        padding: 10px;
        margin: 20px;
        text-align: center;
        font-weight: bold;
        border: 1px solid red;
        border-radius: 5px;
        }
        #alert-box {
            margin-top: 120px; /* Pushes alert below the fixed header */
        }

        .alert.success {
            background-color: #d4edda;
            color: green;
            border: 1px solid green;
        }
      
      
        /*.success {
            background-color: #d4edda;
            color: green;
            border: 1px solid green;
        }*/
        .filters {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            margin: 20px;
        }
        .filters select, .filters input {
            padding: 10px;
            font-size: 14px;
        }
        table {
            width: 95%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background-color: gold;
        }
        .btn-download, .btn-verify {
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
        }
        .btn-download {
            background-color: #007bff;
            color: white;
        }
        .btn-verify {
            background-color: #28a745;
            color: white;
        }
        .status-unverified {
            color: red;
            font-weight: bold;
        }
        .status-verified {
            color: green;
            font-weight: bold;
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
    <!--<div style="margin-top: 80px; text-align: center; background:rgb(50, 182, 120); padding: 7px 0; font-size: 16px; font-weight: bold; border-bottom: 1px solid #white;">
    Documents Verification Panel (HOD)
    </div>-->
    

    <?php
    $unverifiedCount = $conn->query("SELECT COUNT(*) AS count FROM student_documents WHERE status='Unverified'")->fetch_assoc()['count'];
    if ($unverifiedCount > 0): ?>
        <div class="alert" id="alert-box">
            Alert!!! <?= $unverifiedCount ?> student document(s) are left to verify. Please verify!
        </div>
    <?php else: ?>
        <div class="alert success" id="alert-box">
            ✅ All student documents are verified successfully!
        </div>
    <?php endif; ?>

   





    <form method="GET" class="filters">
        <select name="batch">
            <option value="">Select Batch</option>
            <option <?= $batch=="2020-24"?"selected":"" ?>>2020-24</option>
            <option <?= $batch=="2021-25"?"selected":"" ?>>2021-25</option>
            <option <?= $batch=="2022-26"?"selected":"" ?>>2022-26</option>
        </select>

        <select name="stream">
            <option value="">Select Department</option>
            <option <?= $stream=="CSE"?"selected":"" ?>>CSE</option>
            <option <?= $stream=="IT"?"selected":"" ?>>IT</option>
            <option <?= $stream=="ECE"?"selected":"" ?>>ECE</option>
            <option <?= $stream=="EE"?"selected":"" ?>>EE</option>
        </select>

        <select name="semester">
            <option value="">Select Semester</option>
            <?php for ($i=1; $i<=8; $i++): ?>
                <option value="<?= $i ?>" <?= $semester==$i?"selected":"" ?>><?= $i ?></option>
            <?php endfor; ?>
        </select>

        <input type="text" name="roll" placeholder="Search by Roll No" value="<?= htmlspecialchars($roll) ?>">
        <input type="text" name="reg" placeholder="Search by Reg No" value="<?= htmlspecialchars($reg) ?>">
        <button type="submit">Search</button>
    </form>

    <table id="document-table">
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Roll No</th>
                <th>Reg No</th>
                <th>Document Name</th>
                <th>Stream</th>
                <th>Batch</th>
                <th>Preview</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['student_name']) ?></td>
                <td><?= htmlspecialchars($row['roll_no']) ?></td>
                <td><?= htmlspecialchars($row['reg_no']) ?></td>
                <td><?= htmlspecialchars($row['document_name']) ?></td>
                <td><?= htmlspecialchars($row['stream']) ?></td>
                <td><?= htmlspecialchars($row['batch']) ?></td>
                <td><a href="<?= htmlspecialchars($row['document_url']) ?>" target="_blank">View</a></td>
                <td class="<?= $row['status'] == 'Verified' ? 'status-verified' : 'status-unverified' ?>">
                    <?= $row['status'] ?>
                </td>
                <td>
                    <?php if($row['status'] == 'Unverified'): ?>
                    <form method="POST">
                        <input type="hidden" name="verify_id" value="<?= $row['id'] ?>">
                        <button type="submit" class="btn-verify">Verify</button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
<?php $conn->close(); ?>

