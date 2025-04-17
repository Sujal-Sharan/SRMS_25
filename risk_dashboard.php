<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Students At Risk Dashboard</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f7f9fc;
      padding: 40px;
      color: #333;
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    #controls {
      max-width: 1000px;
      margin: auto;
      margin-bottom: 20px;
      display: flex;
      justify-content: flex-end;
      gap: 10px;
    }
    canvas {
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      max-width: 1000px;
      margin: auto;
    }
  </style>
</head>
<body>

  <h2>🚨 Students At Risk (Attendance < 75% & Marks < 40%)</h2>

  <div id="controls">
    <label for="department">Department:</label>
    <select id="department">
      <option value="">All</option>
      <option value="CSE">CSE</option>
      <option value="ECE">ECE</option>
      <option value="EE">EE</option>
      <option value="IT">IT</option>
      <!-- Add more departments as needed -->
    </select>
  </div>

  <canvas id="riskChart" height="350"></canvas>

  <script>
    let riskChart;

    function loadChart(department = '') {
      const url = department ? `risk_students.php?department=${department}` : 'risk_students.php';

      fetch(url)
        .then(response => response.json())
        .then(data => {
          const names = data.map(d => `${d.name} (${d.student_id})`);
          const attendance = data.map(d => d.attendance);
          const avgMarks = data.map(d => d.average_marks);

          if (riskChart) riskChart.destroy(); // reset chart if already exists

          riskChart = new Chart(document.getElementById('riskChart'), {
            type: 'bar',
            data: {
              labels: names,
              datasets: [
                {
                  label: 'Attendance (%)',
                  data: attendance,
                  backgroundColor: 'rgba(255, 99, 132, 0.7)'
                },
                {
                  label: 'Average Marks',
                  data: avgMarks,
                  backgroundColor: 'rgba(54, 162, 235, 0.7)'
                }
              ]
            },
            options: {
              responsive: true,
              plugins: {
                title: {
                  display: true,
                  text: `At-Risk Students${department ? ' - ' + department : ''}`,
                  font: { size: 18 }
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
        });
    }

    document.getElementById('department').addEventListener('change', function () {
      loadChart(this.value);
    });

    window.onload = () => loadChart(); // load all by default
  </script>

</body>
</html>
