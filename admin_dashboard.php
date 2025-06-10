<?php
require_once("DB_Connect.php");
session_start();

// TODO: Dynamic bind all display values
// Query to fecth distinct number of students
$stmt = $conn->prepare("SELECT DISTINCT COUNT(*) AS total FROM students");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Storing total student numbers
$_SESSION['total_student'] = $row['total'];

// Query to fecth distinct number of faculty
$stmt = $conn->prepare("SELECT DISTINCT COUNT(*) AS total FROM faculty");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$_SESSION['total_faculty'] = $row['total'];

// TO-DO List Query
// Handle AJAX Dismissal
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["dismiss_hash"])) {
    $hash = $conn->real_escape_string($_POST["dismiss_hash"]);
    $conn->query("INSERT IGNORE INTO admin_dismissed_tasks (task_hash) VALUES ('$hash')");
    exit;
}

// $todoItems = [];
// // 1. Check for students without assigned department/section
// $unassignedStudents = $conn->query("SELECT COUNT(*) as count FROM student_details WHERE section IS NULL OR department IS NULL");
// $row = $unassignedStudents->fetch_assoc();
// if ($row['count'] > 0) {
//     $todoItems[] = "Assign department/section to $row[count] newly added student(s)";
// }

// // 2. Faculty without subjects
// $facultyUnassigned = $conn->query("SELECT COUNT(*) as count FROM faculty WHERE faculty_id NOT IN (SELECT DISTINCT fac_id FROM faculty_subjects)");
// $row = $facultyUnassigned->fetch_assoc();
// if ($row['count'] > 0) {
//     $todoItems[] = "Assign subjects to $row[count] new faculty member(s)";
// }

// // 3. Students missing uploaded documents
// $missingDocs = $conn->query("SELECT COUNT(*) as count FROM students WHERE id NOT IN (SELECT id FROM student_documents)");
// $row = $missingDocs->fetch_assoc();
// if ($row['count'] > 0) {
//     $todoItems[] = "$row[count] student(s) missing required documents";
// }

// // 4. Documents pending verification
// $pendingVerification = $conn->query("SELECT COUNT(*) as count FROM student_documents WHERE status = 'Unverified'");
// $row = $pendingVerification->fetch_assoc();
// if ($row['count'] > 0) {
//     $todoItems[] = "Verify $row[count] pending document(s)";
// }

// // 5. Password reset requests
// $resetRequests = $conn->query("SELECT COUNT(*) as count FROM password_resets WHERE status = 'pending'");
// $row = $resetRequests->fetch_assoc();
// if ($row['count'] > 0) {
//     $todoItems[] = "Review $row[count] pending password reset request(s)";
// }

// // 6. CA/PCA marks not submitted
// $pendingMarks = $conn->query("SELECT COUNT(*) as count FROM subjects WHERE subject_id NOT IN (SELECT DISTINCT subject_id FROM marks)");
// $row = $pendingMarks->fetch_assoc();
// if ($row['count'] > 0) {
//     $todoItems[] = "Submit CA/PCA marks for $row[count] subject(s)";
// }

// // Get dismissed tasks
// $dismissed = [];
// $res = $conn->query("SELECT task_hash FROM admin_dismissed_tasks");
// while ($row = $res->fetch_assoc()) {
//     $dismissed[] = $row['task_hash'];
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard - Student Record Management System</title>
  <link rel="stylesheet" href="Styles/global_base.css">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    header {
      background: #1abc9c;
      color: white;
      padding: 0;
      display: flex;
      align-items: center;
      position: fixed;
      top: 0;
      width: 100%;
      height: 110px;
      z-index: 1000;
    }

    header img {
      height: 100px;
      padding: 5px;
    }

    .container {
      display: flex;
      margin-top: 110px;
      border-radius: 6px;
    }

    .sidebar {
      width: 226px;
      background: #0A1931;
      color: white;
      height: calc(100vh - 110px);
      padding-top: 20px;
      position: fixed;
      top: 110px;
      left: 0;
      overflow-y: auto;
      
    }

    .sidebar nav a {
      display: block;
      color: white;
      background-color: #123456;
      margin: 10px;
      padding: 10px;
      text-decoration: none;
      border-radius: 10px;
      text-align: center;
      border-radius: 20px 6px 6px 20px;
    }

    .sidebar nav a:hover,
    .sidebar nav a.active {
      background-color: #ffc107;
      color: black;
      border-radius: 20px 6px 6px 20px;
    }

    /* TODO: Some style changes to nav elements, such as active, hover and base */
    #active{
      color: black;
      border-color: #666;
    }

    .content {
      margin-left: 226px;
      padding: 20px;
      flex: 1;
      background: #f4f4f4;
      min-height: calc(100vh - 110px);
    }

    .dashboard-cards {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
      padding-top: 45px;
    }

    .card {
      background: white;
      border-radius: 8px;
      padding: 20px;
      flex: 1;
      min-width: 200px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .card h3 {
      font-size: 1rem;
      color: #666;
      text-align: center;
    }

    .card .value {
      font-size: 1.5rem;
      margin-top: 10px;
      text-align: center;
    }

    .recent-activity,
    .student-overview {
      background: white;
      border-radius: 8px;
      padding: 20px;
      margin-top: 30px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    table th,
    table td {
      padding: 10px;
      border-bottom: 1px solid #ccc;
      text-align: left;
    }
  </style>
</head>
<body>
  <header style="background: #1abc9c; color: white; padding: 0px; display: flex; align-items: center; position: fixed; top: 0; width: 100%; z-index: 1000;">
    <img src="logo.png" alt="Logo" style="height: 120px; margin-right: 10px;">
    <div style="text-align: center; flex: 1;">
        <h1 style="margin: 0; font-size: 25px; font-weight: bold;">TECHNO INTERNATIONAL NEW TOWN</h1>
        <p style="margin: 0; font-size: 17px;">(Formerly Known as Techno India College Of Technology)</p>
    </div>
    <div style="display: flex; align-items: center; font-size: 15px; margin-left: 2px;">
        <i class="fas fa-phone-alt" style="margin-right: 10px;"></i>
        <span><p>&#9742; +338910530723 / 8910530723</p></span>
    </div>
  </header>

  <div class="container">
    <div class="sidebar">
      <nav>
        <a id="active" href="admin_dashboard.php">Dashboard</a>
        <a href="studentProfile.php">Student Profile</a>
        <a href="view_Student_Marks.php">Marks</a>
        <a href="view_Student_Attendance.php">Attendance</a>
        <a href="upload_attendance.php">Add Attendance</a>
        <a href="faculty_profile_admin.php">Faculty Profile</a>
        <a href="/SRMS/SRMS_25/admin_view_docs.php">Uploaded Documents</a>
        <a href="T_AddLogin.php">Add/Remove User</a>
        <a href="reset_password_UI.php">Reset Password</a>
        <a href="/SRMS/SRMS_25/logout.php"> Log out</a>
      </nav>
    </div>
    
    <main class="content">
      <h2>Admin Dashboard</h2>
      <div class="dashboard-cards">
        <div class="card">
          <h3>Total Students</h3>
          <!-- Dynamic value bidning for no. of students -->
          <div class="value" style="color: blue;"><?php echo $_SESSION['total_student'] ?></div>
        </div>
        <div class="card">
          <h3>Total Faculty</h3>
          <div class="value" style="color: green;"><?php echo $_SESSION['total_faculty'] ?></div>
        </div>
        <div class="card">
          <h3>Total Documents</h3>
          <div class="value" style="color: orange;">850</div>
        </div>
        <div class="card">
          <h3>Pending Requests</h3>
          <div class="value" style="color: red;">42</div>
        </div>
      </div>

      <!-- TO-DO List Card -->
      <div style="margin-top: 30px; padding: 20px; background: white; border-radius: 10px; box-shadow: 0px 1px 6px rgba(0,0,0,0.1);">
        <h4 style="margin-bottom: 15px; font-weight: 600;">Admin TO-DO List</h4>
        <?php
        $filtered = array_diff_key($todoItems, array_flip($dismissed));
        if (count($filtered) > 0):
        ?>
            <ul style="list-style: none; padding-left: 0;" id="todoList">
              <?php foreach ($filtered as $hash => $item): ?>
                <li id="<?= $hash ?>" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; padding: 10px 12px; background: #f8f9fa; border-left: 4px solid #007bff; border-radius: 6px;">
                    <span><?= htmlspecialchars($item) ?></span>
                    <button onclick="dismissTask('<?= $hash ?>')" style="background: #28a745; color: white; border: none; border-radius: 4px; padding: 6px 12px; cursor: pointer;">Done</button>
                </li>
              <?php endforeach; ?>
            </ul>
        <?php else: ?>
          <p style="color: green;">✅ All admin tasks are up to date!</p>
        <?php endif; ?>
      </div>
    </main>
  </div>

  <script>
    document.querySelectorAll('.sidebar ul li').forEach(item => {
      item.addEventListener('click', () => {
        document.querySelectorAll('.sidebar ul li').forEach(el => el.classList.remove('active'));
        item.classList.add('active');
      });
    });

    function dismissTask(hash) {
    fetch("", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "dismiss_hash=" + hash
    }).then(() => {
        document.getElementById(hash).remove();
        if (document.querySelectorAll("#todoList li").length === 0) {
            document.getElementById("todoList").outerHTML = "<p style='color: green;'>✅ All admin tasks are up to date!</p>";
        }
    });
}
  </script>
</body>
</html>




