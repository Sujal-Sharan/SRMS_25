
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
        <a href="studentProfile.html">Student Profile</a>
        <a href="/SRMS/SRMS_25/marks.php">Marks</a>
        <a href="/SRMS/SRMS_25/Student_Attendance.php">Attendance</a>
        <a href="faculty_profile_admin.php">Faculty Profile</a>
        <a href="/SRMS/SRMS_25/admin_view_docs.php">Uploaded Documents</a>
        <a href="T_AddLogin.php">Add/Remove User</a>
        <a href="/SRMS/SRMS_25/logout.php">Log out</a>
      </nav>
    </div>
    
    <main class="content">
      <h2>Admin Dashboard</h2>
      <div class="dashboard-cards">
        <div class="card">
          <h3>Total Students</h3>
          <div class="value" style="color: blue;">1,245</div>
        </div>
        <div class="card">
          <h3>Total Faculty</h3>
          <div class="value" style="color: green;">150</div>
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

      <div class="recent-activity">
        <h3>Recent Activity</h3><br>
        <ul>
          <li>Marks uploaded for CSE - 2 hours ago</li> <br>
          <li>New students added - 1 hour ago</li><br>
          <li>Attendance report generated - 5 hours ago</li><br>
        </ul>
      </div>

      <!-- <div class="student-overview">
        <h3>Student Overview</h3>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Course</th>
              <th>Marks</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>001</td>
              <td>John Doe</td>
              <td>CSE</td>
              <td>87%</td>
            </tr>
            <tr>
              <td>002</td>
              <td>Jane Smith</td>
              <td>IT</td>
              <td>92%</td>
            </tr>
          </tbody>
        </table>
      </div> -->
    </main>
  </div>

  <script>
    document.querySelectorAll('.sidebar ul li').forEach(item => {
      item.addEventListener('click', () => {
        document.querySelectorAll('.sidebar ul li').forEach(el => el.classList.remove('active'));
        item.classList.add('active');
      });
    });
  </script>
</body>
</html>




