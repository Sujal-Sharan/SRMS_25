<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{ $conn = new mysqli("localhost", "root", "", "srms",3307); 
 if ($conn->connect_error) {
 die("Connection failed: " . $conn->connect_error);
 }

 $stmt = $conn->prepare("INSERT INTO students (student_name, father_name, mother_name, address, gender, pincode, state, district, city, phone, registration_no, email, dob, blood_group, disabilities) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
 $stmt->bind_param("sssssssssssssss",
 $_POST["studentName"],
 $_POST["fatherName"],
 $_POST["motherName"],
 $_POST["address"],
 $_POST["gender"],
 $_POST["pincode"],
 $_POST["state"],
 $_POST["district"],
 $_POST["city"],
 $_POST["phone"],
 $_POST["registrationNo"],
 $_POST["email"],
 $_POST["dob"],
 $_POST["bloodGroup"],
 $_POST["disabilities"]
 );

 if ($stmt->execute()) {
 echo "<script>alert('Data saved successfully'); window.location.href='student_info.php';</script>";
 } else {
 echo "Error: " . $stmt->error;
 }

 $stmt->close();
 $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Details Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f0f2f5;
        }
        header {
            background-color: #105fe8;
            color: white;
            padding: 10px;
            text-align: center;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }
        .container {
            margin-top: 120px;
            padding: 20px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .passport-photo {
            display: block;
            margin: 0 auto 20px auto;
            width: 120px;
            height: 150px;
            object-fit: cover;
            border: 2px solid #000;
        }
        .info {
            margin-bottom: 15px;
        }
        .info strong {
            display: inline-block;
            width: 200px;
        }
        .checkbox-section {
            margin-top: 30px;
        }
        .button-container {
            margin-top: 20px;
            text-align: center;
        }
        button {
            background-color: #105fe8;
            color: white;
            border: none;
            padding: 10px 25px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
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
        <div style="display: flex; align-items: center; font-size: 14px;">
            <i class="fas fa-phone-alt" style="margin-right: 5px;"></i>
            <span><p>&#9742; +338910530723 / 8910530723  </p></span>
        </div>
    </header>

    
 




<div class="container">
    <img src="profile.jpg" alt="Passport Photo" class="passport-photo" id="profilePic">

    <div class="info"><strong>Student Name:</strong> <span id="studentName"></span></div>
    <div class="info"><strong>Father's Name:</strong> <span id="fatherName"></span></div>
    <div class="info"><strong>Mother's Name:</strong> <span id="motherName"></span></div>
    <div class="info"><strong>Address:</strong> <span id="address"></span></div>
    <div class="info"><strong>Gender:</strong> <span id="gender"></span></div>
    <div class="info"><strong>Pincode:</strong> <span id="pincode"></span></div>
    <div class="info"><strong>State:</strong> <span id="state"></span></div>
    <div class="info"><strong>District:</strong> <span id="district"></span></div>
    <div class="info"><strong>City:</strong> <span id="city"></span></div>
    <div class="info"><strong>Phone No.:</strong> <span id="phone"></span></div>
    <div class="info"><strong>Registration No.:</strong> <span id="registrationNo"></span></div>
    <div class="info"><strong>Email:</strong> <span id="email"></span></div>
    <div class="info"><strong>Date of Birth:</strong> <span id="dob"></span></div>
    <div class="info"><strong>Blood Group:</strong> <span id="bloodGroup"></span></div>
    <div class="info"><strong>Disabilities (PWD):</strong> <span id="disabilities"></span></div>

    <div class="checkbox-section">
        <input type="checkbox" id="confirmCheckbox"> 
        <label for="confirmCheckbox">I confirm that all the details filled up by me is correct and can not be changed later.</label>
    </div>

    <div class="button-container">
        <button onclick="confirmAndSave()">Confirm and Save</button>
    </div>
</div>

<script>
 const studentData = JSON.parse(sessionStorage.getItem("studentInfo")) || {};

 window.onload = function() {
 document.getElementById("profilePic").src = studentData.photo || "profile.jpg";

 // Fill visible data
 document.getElementById("studentName").textContent = studentData.studentName || '';
 document.getElementById("fatherName").textContent = studentData.fatherName || '';
 document.getElementById("motherName").textContent = studentData.motherName || '';
 document.getElementById("address").textContent = studentData.address || '';
 document.getElementById("gender").textContent = studentData.gender || '';
 document.getElementById("pincode").textContent = studentData.pincode || '';
 document.getElementById("state").textContent = studentData.state || '';
 document.getElementById("district").textContent = studentData.district || '';
 document.getElementById("city").textContent = studentData.city || '';
 document.getElementById("phone").textContent = studentData.phone || '';
 document.getElementById("registrationNo").textContent = studentData.registrationNo || '';
 document.getElementById("email").textContent = studentData.email || '';
 document.getElementById("dob").textContent = studentData.dob || '';
 document.getElementById("bloodGroup").textContent = studentData.bloodGroup || '';
 document.getElementById("disabilities").textContent = studentData.disabilities || '';

 // Fill hidden form inputs
 for (let key in studentData) {
 let input = document.getElementById("input" + key.charAt(0).toUpperCase() + key.slice(1));
 if (input) input.value = studentData[key];
 }
 };

function confirmAndSave() {
 if (document.getElementById("confirmCheckbox").checked) {
 document.getElementById("studentForm").submit();
 } else {
 alert("Please confirm the checkbox before submitting.");
 }
 }
</script>


</body>
<form id="studentForm" method="POST" style="display: none;">
 <input type="hidden" name="studentName" id="inputStudentName">
 <input type="hidden" name="fatherName" id="inputFatherName">
 <input type="hidden" name="motherName" id="inputMotherName">
 <input type="hidden" name="address" id="inputAddress">
 <input type="hidden" name="gender" id="inputGender">
 <input type="hidden" name="pincode" id="inputPincode">
 <input type="hidden" name="state" id="inputState">
 <input type="hidden" name="district" id="inputDistrict">
 <input type="hidden" name="city" id="inputCity">
 <input type="hidden" name="phone" id="inputPhone">
 <input type="hidden" name="registrationNo" id="inputRegistrationNo">
 <input type="hidden" name="email" id="inputEmail">
 <input type="hidden" name="dob" id="inputDob">
 <input type="hidden" name="bloodGroup" id="inputBloodGroup">
 <input type="hidden" name="disabilities" id="inputDisabilities">
</form>



</html>
