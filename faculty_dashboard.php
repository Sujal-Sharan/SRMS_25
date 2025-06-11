<?php
require_once("DB_Connect.php");
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if session has expired
if (time() - $_SESSION['login_time'] > $_SESSION['expire_after']) {
    session_unset();
    session_destroy();
    header("Location: login.php?session_expired=1");
    exit();
} else {
    // Reset login_time to extend session
    $_SESSION['login_time'] = time();
}


// Get student details from DB
$stmt = $conn->prepare("SELECT * FROM faculty WHERE user_id = ?");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

try{
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $_SESSION['user_id'] = $row["user_id"];
            $_SESSION['name'] = $row["name"];
            $_SESSION['department'] = $row["department"];
            $_SESSION['designation'] = $row["designation"];
            $_SESSION['phone'] = $row["phone"];
            $_SESSION['email'] = $row["email"];

        }
    }
}catch(Exception $e){
    echo 'Message: ' .$e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="Styles/global_base.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            /*background: url('https://cdn2.advanceinfotech.org/bharatdirectory.in/1200x675/business/3135/techno-2-1709631821.webp') no-repeat center center;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;*/
            background: white;
            min-height: 100vh;
        }
        /* .sidebar {
            width: 226px;
            background: #0A1931;
            color: white;
            padding: 16px;
            height: 100vh;
            margin-top: 110px; 
            position: fixed;
            overflow-y: auto;
        }
        .sidebar .profile {
            font-weight: bold;
            margin-bottom: 20px;
        }
        .sidebar nav ul {
            list-style: none;
            padding: 0;
        }
        .sidebar nav ul li {
            margin: 10px 0;
        }
        .sidebar nav ul li a {
            color: white;
            text-decoration: none;
            padding: 10px;
            display: block;
        }
        .active {
            background: #FFC107;
        } */
        .sidebar{
            margin-top: 80px;
            height: 800px;
            background-color: rgba(10, 25, 49, 0.85); /* Slight transparency */
            color: white;
            padding: 16px;
            width: 226px;
            position: fixed;
            overflow-y: auto;
         }
        .main-content {
            margin-top: 80px;
            /* margin-left: 250px;  */
            /* padding: 20px; */
            /* padding-top: 130px;  */
            /* width: calc(100% - 250px); */
            margin-left: 250px;
            padding: 20px;
            background-color: transparent; /* Ensure it doesn't override background image */
        }
        .layout {
            display: flex;
            background-color: transparent; /* Remove white background */
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-top: 50px;
        }
        .card {
            background: #FFC107;
            padding: 15px;
            text-align: center;
            border: 2px solid #FFC107;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 70px;
         }
         .card button {
            background: none;
            border: none;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            height: 100%;
            display: block;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
    </style>
</head>
<body>
    <header>
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

    <div class="layout">
        <div class="sidebar">
            <nav>
                <a id="active" href="faculty_dashboard.php">Dashboard</a>
                <a href="faculty_view_attendace.php">View Attendance</a>
                <a href="faculty_upload_attendance.php">Update Attendance</a>
                <a href="faculty_view_marks.php">View Marks</a>
                <a href="faculty_upload_marks.php">Add Marks</a>
                <a href="faculty_details.html">Faculty Details</a>
                <a href="logout.php">Log out</a>
            </nav>
        </div>
    </div>
        
    <div class="main-content">
        <h2>Faculty Dashboard</h2>
        <div class="grid">
            <div class="card" onclick="navigateTo('faculty_details.html')">
                <button>Faculty Details</button>
            </div>
            <div class="card" onclick="navigateTo('faculty_dashboard.php')">
                    <button>Student List</button>
            </div>
                <div class="card" onclick="navigateTo('faculty_upload_marks')">
                    <button>Upload Marks</button>
            </div>
                <div class="card" onclick="navigateTo('Student_Attendance.php')">
                    <button>Attendance</button>
            </div>
            <div class="card" onclick="navigateTo('class_routine.php')">
                    <button>Class RouVitine</button>
            </div>
            <div class="card" onclick="navigateTo('doc_Verify.php')">
                    <button>Upload & Verify Documents</button>
            </div>
            <div class="card" onclick="navigateTo('https://tint.edu.in/')">
                    <button>Department Notices</button>
            </div>
            <div class="card" onclick="navigateTo('https://makautexam.net/routine.html')">
                    <button>Exam Schedules</button>
            </div>
            <div class="card" onclick="navigateTo('export.html')">
                    <button>Report<br>(Generate & Download)</button>
            </div>
            <div class="card" onclick="navigateTo('subject_details.html')">
                <button>Subjects</button>
            </div>
        </div>

        <section id="at-risk-section" style="padding: 2rem; background: #f9fbfe; width: 60%; text-align: center;">
            <h2 style="text-align: center;">
                <span style="color: red;">🔔</span> 
                <strong>Students At Risk (Attendance &lt; 75% &amp; Marks &lt; 40%)</strong>
            </h2>

            <div style="text-align: right; margin-bottom: 1rem;">
                <label for="deptFilter">Department:</label>
                <select id="deptFilter">
                    <option value="all">All</option>
                    <option value="cse">CSE</option>
                    <option value="it">IT</option>
                    <option value="ece">ECE</option>
                    <!-- Add more departments as needed -->
                </select>
            </div>

            <div id="chart-container" style="display: flex; justify-content: center;">
                <canvas id="atRiskChart" style="width: 100px; height:50px;"></canvas>
            </div>
        </section>
        <div style="margin-top: 40px;">
            <h2 style="margin-bottom: 20px; color: #0A1931;">Faculty Activity Overview :</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    <div style="background: #f1f1f1; padding: 15px; border-left: 6px solid #ff9800;">
                        <strong>Student Verification:</strong>
                        <p><span id="verifyCount">5</span> students are left to verify. Please verify them!</p>
                    </div>
                    <div style="background: #f1f1f1; padding: 15px; border-left: 6px solid #2196f3;">
                        <strong>Internal Marks Submission:</strong>
                        <p>Marks submission pending.<br>Last Date: <span id="marksDeadline">05/10/2025</span></p>
                    </div>
                    <div style="background: #f1f1f1; padding: 15px; border-left: 6px solid #f44336;">
                        <strong>Low Attendance Alerts:</strong>
                        <p><span id="lowAttendanceCount">25</span> students have low attendance.</p>
                    </div>
                    <div style="background: #f1f1f1; padding: 15px; border-left: 6px solid #9c27b0;">
                        <strong>Document Verification:</strong>
                        <p><span id="docCount">4</span> students documents are left to verify.</p>
                    </div>
                    <div style="background: #f1f1f1; padding: 15px; border-left: 6px solid #4caf50;">
                            <strong>Report Upload Reminder:</strong>
                            <p>Please generate and upload final reports.</p>
                    </div>

                </div>
        </div>
    </div>

    <script>
        function navigateTo(url) {
            window.location.href = url;
        }

        document.querySelectorAll('.sidebar nav ul li a').forEach(item => {
            item.addEventListener('click', () => {
                document.querySelectorAll('.sidebar nav ul li a').forEach(link => link.classList.remove('active'));
                item.classList.add('active');
            });
        });
    </script>
    <script>
    // Simulated dynamic values (replace with backend later)
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("verifyCount").textContent = 5;
        document.getElementById("marksDeadline").textContent = "05/10/2025";
        document.getElementById("lowAttendanceCount").textContent = 25;
        document.getElementById("docCount").textContent = 4;
    });

    
const ctx = document.getElementById('atRiskChart').getContext('2d');

let atRiskChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['Alice (101)', 'Bob (105)'],
    datasets: [
      {
        label: 'Attendance (%)',
        data: [60, 70],
        backgroundColor: 'rgba(255, 99, 132, 0.6)'
      },
      {
        label: 'Average Marks',
        data: [25, 25],
        backgroundColor: 'rgba(54, 162, 235, 0.6)'
      }
    ]
  },
  options: {
    responsive: true,
    plugins: {
      title: {
        display: true,
        text: 'At-Risk Students'
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        max: 100
      }
    }
  }
});

// Filter function (dummy for now — extend with backend/AJAX logic)
document.getElementById('deptFilter').addEventListener('change', function () {
  const selectedDept = this.value;

  // Example logic for filtering chart data
  if (selectedDept === 'cse') {
    atRiskChart.data.labels = ['Alice (101)'];
    atRiskChart.data.datasets[0].data = [60];
    atRiskChart.data.datasets[1].data = [25];
  } else if (selectedDept === 'ece') {
    atRiskChart.data.labels = ['Bob (105)'];
    atRiskChart.data.datasets[0].data = [70];
    atRiskChart.data.datasets[1].data = [25];
  } else {
    atRiskChart.data.labels = ['Alice (101)', 'Bob (105)'];
    atRiskChart.data.datasets[0].data = [60, 70];
    atRiskChart.data.datasets[1].data = [25, 25];
  }

  atRiskChart.update();
});
</script>

</body>
</html>
