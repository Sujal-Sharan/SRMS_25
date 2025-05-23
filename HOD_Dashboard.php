<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="Styles/global_base.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        background: white;
        min-height: 100vh;
    }

    .layout {
        display: flex;
        background-color: transparent;
    }

    .sidebar {
        margin-top: 80px;
        height: calc(100vh - 80px);
        background-color: rgba(10, 25, 49, 0.85);
        color: white;
        padding: 16px;
        width: 226px;
        position: fixed;
        overflow-y: auto;
    }

    .main-content {
        margin-top: 80px;
        margin-left: 226px; /* exactly matches sidebar width */
        padding: 30px;
        flex-grow: 1;
        background-color: transparent;
        min-height: calc(100vh - 80px);
    }

    header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 50px;
    }

    .card {
        background: #FFC107;
        padding: 0;
        text-align: center;
        border: 4px solid #FFC107;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100px;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        transition: transform 0.2s ease;
    }

    .card:hover {
        transform: scale(1.03);
    }

    .card button {
        background: none;
        border: none;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        width: 100%;
        height: 100%;
        padding: 10px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        line-height: 1.4;
        white-space: normal;
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
                <a href="/SRMS_25/dashboard.php" id="active">Dashboard</a>
                <a href="/SRMS_25/test.php">Attendance</a>
                <a href="/SRMS_25/marks.php">View Marks</a>
                <a href="/SRMS_25/faculty_details.html">Faculty Details</a>
                <a>Add New Faculty</a>
                <a>Department Settings</a>
                <a href="/SRMS_25/logout.php">Log out</a>
            </nav>
        </div>



        <div class="main-content">
            <h2>HOD Dashboard : CSE</h2>
            <div class="grid">
                <div class="card" onclick="navigateTo('faculty_details.html')">
                    <button>Faculty Directory</button>
                </div>
                <div class="card" onclick="navigateTo('students.php')">
                    <button>Student Details<br>Total: 6000</button>
                </div>
                <div class="card" onclick="navigateTo('marks.php')">
                    <button>Student Performance <br>(Internal & External)</button>
                </div>
                <div class="card" onclick="navigateTo('Student_Attendance.php')">
                    <button>Attendance</button>
                </div>
                <div class="card" onclick="navigateTo('class_routine.pdf')">
                    <button>Class Routine</button>
                </div>
                <div class="card" onclick="navigateTo('HOD_DOC_Verify.php')">
                    <button>Verify Documents</button>
                </div>
                <div class="card" onclick="navigateTo('https://tint.edu.in/')">
                    <button>Department Notices</button>
                </div>

                <div class="card" onclick="navigateTo('https://makautexam.net/routine.html')">
                    <button>Exam Schedules</button>
                </div>

                <div class="card" onclick="navigateTo('export.html')">
                    <button>Academic Report<br>(Generate & Download)</button>
                </div>

                <div class="card" onclick="navigateTo('subject_details.html')">
                    <button>Subjects</button>
                </div>
                <div class="card" onclick="navigateTo('')">
                    <button>Faculty Meeting <br>(call Or view schedule)</button>
                </div>
                <div class="card" onclick="navigateTo('')">
                    <button>Academic Calender </button>
                </div>

            </div>
            <div style="margin-top: 40px;">
                <h2 style="margin-bottom: 20px; color: #0A1931;">HOD Activity Overview :</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    <div style="background: #f1f1f1; padding: 15px; border-left: 6px solid #ff9800;">
                        <strong>Add faculty</strong>
                        <p><span id="verifyCount">2 </span> new faculty are added!</p>
                    </div>
                    <div style="background: #f1f1f1; padding: 15px; border-left: 6px solid #2196f3;">
                        <strong>Academic performance :</strong>
                        <p>External marks viewed.<br>Updated on: <span id="marksDeadline">05/10/2025</span></p>
                    </div>
                    <div style="background: #f1f1f1; padding: 15px; border-left: 6px solid #f44336;">
                        <strong>Exam Schedules :</strong>
                        <p><span id="lowAttendanceCount">5</span> Internal Exam schedules added.</p>
                    </div>
                    <div style="background: #f1f1f1; padding: 15px; border-left: 6px solid #9c27b0;">
                        <strong>Document Verification:</strong>
                        <p><span id="docCount">4</span> students documents are left to verify.</p>
                    </div>
                    <div style="background: #f1f1f1; padding: 15px; border-left: 6px solid #4caf50;">
                        <strong>Scheduled Meeting today :</strong>
                        <p>Topic - placement reports , today at 2 pm.</p>
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
            </script>

</body>

</html>
