<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Dependent Filters</title>
  <script>
    function fetchSections(dept) {
      fetch('fetch_sections.php?department=' + dept)
        .then(res => res.text())
        .then(html => {
          document.getElementById('section').innerHTML = html;
          document.getElementById('group').innerHTML = '<option value="">Select Group</option>';
          document.getElementById('results').innerHTML = '';
        });
    }

    function fetchGroups(section) {
      const dept = document.getElementById('department').value;
      fetch(`fetch_groups.php?department=${dept}&section=${section}`)
        .then(res => res.text())
        .then(html => {
          document.getElementById('group').innerHTML = html;
          document.getElementById('results').innerHTML = '';
        });
    }

    function fetchData(group) {
      const dept = document.getElementById('department').value;
      const section = document.getElementById('section').value;

      if (dept && section && group) {
        fetch(`fetch_data.php?department=${dept}&section=${section}&group=${group}`)
          .then(res => res.text())
          .then(html => document.getElementById('results').innerHTML = html);
      } else {
        document.getElementById('results').innerHTML = '';
      }
    }
  </script>
</head>
<body>
  <h2>Filter Students</h2>

  <!-- Department Dropdown -->
  <select id="department" onchange="fetchSections(this.value)">
    <option value="">Select Department</option>
    <?php
    $stmt = $pdo->query("SELECT DISTINCT department FROM students");
    while ($row = $stmt->fetch()) {
        echo "<option value='{$row['department']}'>{$row['department']}</option>";
    }
    ?>
  </select>

  <!-- Section Dropdown -->
  <select id="section" onchange="fetchGroups(this.value)">
    <option value="">Select Section</option>
  </select>

  <!-- Group Dropdown -->
  <select id="group" onchange="fetchData(this.value)">
    <option value="">Select Group</option>
  </select>

  <hr>
  <div id="results"></div>
</body>
</html>
