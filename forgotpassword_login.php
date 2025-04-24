<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Techno International Newtown</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('https://cdn2.advanceinfotech.org/bharatdirectory.in/1200x675/business/3135/techno-2-1709631821.webp') no-repeat center center fixed;
            background-size: cover;
            padding-top: 120px;
        }

        header {
            background: #105fe8;
            color: white;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        header img {
            height: 100px;
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
        header .center-heading h1 {
        margin: 0;
        font-size: 26px;
        font-weight: bold;
        }
        .header-right {
        /*display: flex;*/
         /*align-items: center;*/
        font-size: 14px;
        white-space: nowrap;
        }


        header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        header p {
            margin: 0;
            font-size: 14px;
        }

        header .contact {
            display: flex;
            align-items: center;
            font-size: 14px;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            margin: 50px auto;
            padding: 90px;
            width: 90%;
            max-width: 1000px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        }

        .input-group {
            position: relative;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px 40px 10px 10px;
            margin: 10px 0 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #555;
        }

        button {
            background-color: #005be3;
            color: white;
            padding: 18px 28px;
            border: none;
            border-radius: 7px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        .success-message {
            display: none;
            color: green;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
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
        <div style="display: flex; align-items: center; font-size: 14px;">
            <i class="fas fa-phone-alt" style="margin-right: 5px;"></i>
            <span><p>&#9742; +338910530723 / 8910530723  </p></span>
        </div>
    </header>

    <div class="container">
        <h2>Forgot Password</h2>
        <form id="resetPasswordForm">
            <label for="email">Student Username or Email</label>
            <input type="email" id="email" name="email" required>

            <label for="newPassword">New Password</label>
            <div class="input-group">
                <input type="password" id="newPassword" name="newPassword" required>
                <span class="toggle-password" onclick="togglePassword('newPassword', this)"><i class="fas fa-eye"></i></span>
            </div>

            <label for="confirmPassword">Re-enter New Password</label>
            <div class="input-group">
                <input type="password" id="confirmPassword" name="confirmPassword" required>
                <span class="toggle-password" onclick="togglePassword('confirmPassword', this)"><i class="fas fa-eye"></i></span>
            </div>

            <button type="submit">Submit</button>
        </form>

        <div class="success-message" id="successMessage">
            Password changed successfully! Please login with your new password.
        </div>
    </div>

    <script>
        function togglePassword(fieldId, iconElement) {
            const input = document.getElementById(fieldId);
            const icon = iconElement.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        document.getElementById('resetPasswordForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (newPassword !== confirmPassword) {
                alert("Passwords do not match!");
                return;
            }

            document.getElementById('resetPasswordForm').style.display = 'none';
            document.getElementById('successMessage').style.display = 'block';
        });
    </script>
</body>
</html>
