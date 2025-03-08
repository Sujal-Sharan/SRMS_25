<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            background: url('https://cdn2.advanceinfotech.org/bharatdirectory.in/1200x675/business/3135/techno-2-1709631821.webp') no-repeat center center;
            background-size: cover;
        }
        .sidebar {
            width: 250px;
            background: #0A1931;
            color: white;
            padding: 20px;
            height: 100vh;
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
        }
        .main-content {
            flex: 1;
            padding: 20px;
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
        .addon-services {
            position: fixed;
            right: 0;
            top: 60%;
            background: #FFC107;
            padding: 25px;
            transform: rotate(-90deg);
            transform-origin: right;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="profile">Welcome User</div>
        <nav>
            <ul>
                <li><a href="#">Go To Super Admin</a></li>
                <li ><a>My College</a>
                    <ul>
                        <li class="active"><a href="#">Dashboard</a></li>
                        <li><a href="#">Enquiry</a></li>
                    </ul>
                </li>
                <li><a href="#">Management</a></li>
            </ul>
        </nav>
    </div>
    <div class="main-content">
        <header>
            <h1>Techno International New Town</h1>
        </header>
        
        <div class="grid">
        <div class="card" onclick="navigateTo('student.php')">
                <button>Student Profile</button>
    </div>
        <div class="card" onclick="navigateTo('students.php')">
        <button>Student<br>Total: 6000</button>
        </div>
        <div class="card" onclick="navigateTo('faculty.php')">
        <button>Faculty<br>Total: 2000</button>
        </div>
        <div class="card" onclick="navigateTo('parents.php')">
        <button>Parents</button>
        </div>
        <div class="card" onclick="navigateTo('attendance.php')">
        <button>Attendance</button>
        </div>
        <div class="card" onclick="navigateTo('class.php')">
        <button>Class<br>Total: 3000</button>
        </div>
        <div class="card" onclick="navigateTo('academics.php')">
        <button>Academics</button>
        </div>
        <div class="card" onclick="navigateTo('settings.php')">
        <button>Settings</button>
        </div>
        <div class="card" onclick="navigateTo('card.php')">
        <button>Card</button>
        </div>
        <div class="card" onclick="navigateTo('https://tint.techtron.net')">
        <button>Pay Semester Fees Online</button>
        </div>
        <div class="addon-services">Addon Services</div>
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
