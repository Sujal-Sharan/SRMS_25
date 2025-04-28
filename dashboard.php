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
            display: flex;
            background: url('https://cdn2.advanceinfotech.org/bharatdirectory.in/1200x675/business/3135/techno-2-1709631821.webp') no-repeat center center;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
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
            height: 510px;
         }
        .main-content {
            margin-top: 80px;
            /* margin-left: 250px;  */
            /* padding: 20px; */
            /* padding-top: 130px;  */
            /* width: calc(100% - 250px); */
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
    <header style="background: #105fe8; color: white; padding: 7px; display: flex; align-items: center; position: fixed; top: 0; width: 100%; z-index: 1000;">
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
            <h2>{Logo}  TINT</h2>
            <nav>
                <a href="/SRMS/SRMS_25/dashboard.php"  id="active">Dashboard</a>
                <a href="/SRMS/SRMS_25/test.php">Attendance</a>
                <a href="/SRMS/SRMS_25/test.php">View Marks</a>
                <a href="/SRMS/SRMS_25/faculty_details.html">Faculty Details</a>
                <a>Update Details</a>
                <a>Settings</a>
                <a href="/SRMS/SRMS_25/logout.php">Log out</a>
            </nav>
        </div>

        <div class="main-content">
            <div class="grid">
                <div class="card" onclick="navigateTo('student.php')">
                    <button>Student Profile</button>
                </div>
                <div class="card" onclick="navigateTo('students.php')">
                    <button>Student<br>Total: 6000</button>
                </div>
                <div class="card" onclick="navigateTo('faculty_details.html')">
                    <button>Faculty<br>Total: 2000</button>
                </div>
                <div class="card" onclick="navigateTo('subject_details.html')">
                    <button>Subjects</button>
                </div>
                <div class="card" onclick="navigateTo('attendance.php')">
                    <button>Attendance</button>
                </div>
                <div class="card" onclick="navigateTo('academics.php')">
                    <button>Academics</button>
                </div>
                <div class="card" onclick="navigateTo('https://tint.techtron.net')">
                    <button>Pay Semester Fees Online</button>
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
</body>
</html>
