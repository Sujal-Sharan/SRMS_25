<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Faculty Document Panel</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-image: url('https://cdn2.advanceinfotech.org/bharatdirectory.in/1200x675/business/3135/techno-2-1709631821.webp');
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
      margin: 0;
      padding-top: 120px;
    }

    header {
      background: #1abc9c;
      color: white;
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 1000;
    }

    header img {
      height: 90px;
      /*margin-right: 20px;*/
    }
    header .center-heading h1 {
        margin: 0;
        font-size: 26px;
        font-weight: bold;
    }

    /*header div {
      text-align: center;
      flex: 1;
    }*/

    

    header .center-heading p {
        margin: 0;
        font-size: 14px;
    }


    .header-right {
      /*display: flex;*/
      /*align-items: center;*/
      font-size: 14px;
      white-space: nowrap;
    }

    .sub-header {
      color: #fff;
      font-size: 14px;
      text-align: center;
      background-color: #0047ab;
      padding: 7px 0;
      margin-top: -10px;
    }

    .container {
      background-color: rgba(255, 255, 255, 0.95);
      margin: 30px auto;
      padding: 20px;
      width: 90%;
      max-width: 1200px;
      border-radius: 8px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
    }

    .section {
      margin-bottom: 40px;
    }

    label, input, select, button {
      display: block;
      margin: 10px 0;
      width: 100%;
    }

    input[type="file"], button, select {
      padding: 14px;
      font-size: 17px;
    }

    iframe {
      width: 100%;
      height: 250px;
      margin-top: 10px;
      border: 1px solid #ccc;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: center;
    }

    th {
      background-color: #ffcc00;
    }

    .btn-verify, .btn-download {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 14px 22px;
      font-size: 17px;
      cursor: pointer;
      border-radius: 6px;
      margin: 5px;
    }

    .btn-verify:hover {
      background-color: #218838;
    }

    .btn-download {
      background-color: #007bff;
    }

    .btn-download:hover {
      background-color: #0056b3;
    }

    .alert-message {
      background-color: #ffcccb;
      color: #a70000;
      font-weight: bold;
      padding: 10px;
      text-align: center;
      margin-bottom: 20px;
      border: 2px solid #a70000;
      border-radius: 5px;
    }
  </style>
</head>
<body>
  <header>
    <img src="logo.png" alt="Logo" style="height: 100px; margin-right: 20px;">
    <div style="text-align: center; flex: 1;">
      <h1 style="margin:0; font-size: 24px; font-weight: bold;">TECHNO INTERNATIONAL NEWTOWN</h1>
      <p style="margin:0; font-size:14px;">(Formerly Known as Techno India College Of Technology)</p>
    </div>
    <div style="display:flex; align-items: center; font-size: 14px;">
      <i class="fas fa-phone-alt" style="margin-right: 5px;"></i>
      <span><p>&#9742; +338910530723 / 8910530723</p></span>
    </div>
  </header>
  <div class="sub-header">Faculty Document Upload and Verification Panel</div>

  <div class="container">
    <h2>Upload Document (Faculty)</h2>
    <div class="section">
      <label for="roll">Student Roll No:</label>
      <input type="text" id="roll" placeholder="Enter Roll Number">

      <label for="stream">Select Stream:</label>
      <select id="stream">
        <option value="">--Select Stream--</option>
        <option value="CSE">CSE</option>
        <option value="IT">IT</option>
        <option value="ECE">ECE</option>
        <option value="EE">EE</option>
        <option value="ME">ME</option>
        <option value="CE">CE</option>
      </select>

      <label for="year">Select Year:</label>
      <select id="year">
        <option value="">--Select Year--</option>
        <option value="1">1st Year</option>
        <option value="2">2nd Year</option>
        <option value="3">3rd Year</option>
        <option value="4">4th Year</option>
      </select>

      <label for="facultyDoc">Select Document:</label>
      <input type="file" id="facultyDoc" accept="application/pdf" onchange="previewDocument()">
      <iframe id="docPreview" title="Document Preview"></iframe>
      <button onclick="uploadDocument()">Upload Document</button>
    </div>

    <div id="alertBox" class="alert-message"></div>

    <h2>Verify Student Documents</h2>
    <div class="section">
      <table>
        <thead>
          <tr>
            <th>Student Name</th>
            <th>Document Name</th>
            <th>Preview</th>
            <th>Download</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="docTable">
          <tr>
            <td>Harsh Dhanuka</td>
            <td>Semester Grade Report</td>
            <td><a href="sem_grade card.pdf" target="_blank">View</a></td>
            <td><a href="sem_grade card.pdf" download><button class="btn-download">Download</button></a></td>
            <td id="status1">Unverified</td>
            <td><button class="btn-verify" onclick="verifyDoc('status1')">Verify</button></td>
          </tr>
          <tr>
            <td>Sujal Sharan</td>
            <td>Internship Certificate</td>
            <td><a href="internship.pdf" target="_blank">View</a></td>
            <td><a href="internship.pdf" download><button class="btn-download">Download</button></a></td>
            <td id="status2">Unverified</td>
            <td><button class="btn-verify" onclick="verifyDoc('status2')">Verify</button></td>
          </tr>
          <tr>
            <td>Swapnanil Das</td>
            <td>MOOCS Certificate</td>
            <td><a href="moocs.pdf" target="_blank">View</a></td>
            <td><a href="moocs.pdf" download><button class="btn-download">Download</button></a></td>
            <td id="status3">Unverified</td>
            <td><button class="btn-verify" onclick="verifyDoc('status3')">Verify</button></td>
          </tr>
          <tr>
            <td>Sourav Das</td>
            <td>Scholarnship Certificate</td>
            <td><a href="Scholarnship.pdf" target="_blank">View</a></td>
            <td><a href="scholarship.pdf" download><button class="btn-download">Download</button></a></td>
            <td id="status4">Unverified</td>
            <td><button class="btn-verify" onclick="verifyDoc('status4')">Verify</button></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    let unverifiedCount = 4;

    window.onload = () => updateAlert();

    function previewDocument() {
      const fileInput = document.getElementById('facultyDoc');
      const file = fileInput.files[0];
      const preview = document.getElementById('docPreview');
      if (file) {
        preview.src = URL.createObjectURL(file);
      }
    }

    function uploadDocument() {
      const fileInput = document.getElementById('facultyDoc');
      const file = fileInput.files[0];
      if (file) {
        alert(`Uploaded: ${file.name}`);
      } else {
        alert('Please select a document to upload.');
      }
    }

    function verifyDoc(statusId) {
      const statusCell = document.getElementById(statusId);
      if (statusCell.textContent !== 'Verified') {
        statusCell.textContent = 'Verified';
        statusCell.style.color = 'green';
        unverifiedCount--;
        updateAlert();
        alert('Document Verified');
      }
    }

    function updateAlert() {
      const alertBox = document.getElementById('alertBox');
      if (unverifiedCount > 0) {
        alertBox.textContent = `Alert!!! ${unverifiedCount} student${unverifiedCount > 1 ? 's' : ''} document${unverifiedCount > 1 ? 's are' : ' is'} left to verify. Please verify!`;
      } else {
        alertBox.textContent = 'All documents uploaded by the students are verified!!!';
        alertBox.style.backgroundColor = '#d4edda';
        alertBox.style.color = '#155724';
        alertBox.style.border = '2px solid #155724';
      }
    }
  </script>
</body>
</html>
