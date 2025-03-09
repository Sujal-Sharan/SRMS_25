<?php 
include_once("DB_Connect.php");
// session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Record Management - Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url('background-image.jpg') no-repeat center center/cover;
        }
        .container {
            display: flex;
            width: 900px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .left {
            flex: 1;
            background: url('techno.jpg') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }
        .left img {
            width: 80px;
            background: white;
            padding: 10px;
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .right {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        h2 {
            margin-bottom: 20px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn {
            background: #ffcc00;
            border: none;
            padding: 10px;
            width: 100%;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }
        .btn:hover {
            background: #e6b800;
        }
        .links {
            text-align: center;
            margin-top: 10px;
        }
        .links a {
            text-decoration: none;
            /*color: #4e5153;*/
        }
        .highlight {
            color: rgb(10, 155, 212);
            font-weight: bold;
        }
    </style>

</head>
<body>
    <div class="container">
        <div class="left">
            <img src="logo.jpg" alt="Logo">
        </div>
        
        <div class="right">
            <h2>Welcome</h2>
            <p>Please login to your account</p><br>

            <form action="login.php" method="post">
                <label> Select Role: </label>
                <select name="role">
                    <option value="Admin">Admin</option>
                    <option value="Student">Student</option>
                </select>

                <input type="text" name="userId" placeholder="User ID">
                <input type="password" name="password" placeholder="Password">
                <input type="submit" class="btn" name="login" value="Log In">
            </form>

            <div class="links">
                <a href="#">Forgot Password?</a>
                <br>
                <br>
                <a href="Student_Reg.html" class="highlight">New User?   Register Here</a>
                <br>
            </div>

        </div>
    </div>
</body>
</html>
<?php
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $userId = filter_input(INPUT_POST, "userId", FILTER_SANITIZE_SPECIAL_CHARS); // Sanitize username
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS); // Sanitize password
        $role = filter_input(INPUT_POST, "role", FILTER_SANITIZE_SPECIAL_CHARS); // Sanitize password

        if(empty($userId)){
            echo "Missing User ID";
        }
        elseif(empty($password)){
            // echo "Missing Password";
            echo "<script>alert('Missing password!'); window.history.back();</script>";
        }
        else{
            $hash = password_hash($password, PASSWORD_BCRYPT); // Hash password

            try{
                $stmt = $conn->prepare("SELECT * FROM login WHERE userId = ?");
                $stmt->bind_param("s", $_POST['userId']);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc(); 

                if ($user && password_verify($password, $user['password']) && ($user['role'] === $role)) {
                    // $_SESSION['id'] = $id;
                    // $_SESSION['userId'] = $username;
                    // $_SESSION['role'] = $role;

                    // header('location: student.php');
                    if($user['role'] == "Student")
                    {
                        header('location: student.php');
                    }
                    else
                    {
                        header('location: dashboard.php');
                    }
                    exit;
                } 
                else {
                    // echo "Incorrect Username or Password";
                    echo "<script>alert('User ID or Password or Selected Role is incorrect!'); window.history.back();</script>";
                }

                $stmt->close();
            }
            catch(mysqli_sql_exception){
                echo "Error_New";
            }
        }
    }
    mysqli_close($conn);
?>
