<?php 
require_once("DB_Connect.php");
session_start();
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
        .error{
            background-color: #f2dede;
            color: #a94442;
            padding: 10px;
            width: 95%;
            border-radius: 5px;
            margin: 20px, auto;
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

            <!-- Displays error message -->
            <?php if(isset($_GET['error'])){ ?>
                <p class = "error"> <?php echo $_GET['error']; ?>
            <?php } ?>

            <form action="login.php" method="post">
                <label> Select Role: </label>
                <select name="role">
                    <option value="admin">Admin</option>
                    <option value="faculty">Faculty</option>
                    <option value="student">Student</option>
                </select>

                <input type="text" name="user_id" placeholder="User ID">
                <input type="password" name="password" placeholder="Password">
                <input type="submit" class="btn" name="login" value="Log In">
            </form>

            <div class="links">
                <a href="forgotpassword_login.php" class="highlight">Forgot Password?</a>
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
        // TODO: Maybe add ability to login using either 'userId' or 'userName'

        $user_Id = filter_input(INPUT_POST, "user_id", FILTER_SANITIZE_SPECIAL_CHARS); // Sanitize username
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS); // Sanitize password
        $role = filter_input(INPUT_POST, "role", FILTER_SANITIZE_SPECIAL_CHARS); // Sanitize password

        if(empty($user_Id)){
            header('location: login.php?error=Missing User ID');
            exit();
        }
        elseif(empty($password)){
            header('location: login.php?error=Missing Password');
            exit();
        }
        else{
            $hash = password_hash($password, PASSWORD_BCRYPT); // Hash password

            try{
                $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
                $stmt->bind_param("s", $user_Id);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc(); 
                
                if ($user && password_verify($password, $user['password_hash']) && ($user['role'] === $role)) {
                    $_SESSION['userId'] = $userId;
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $role;

                    if($role === 'student'){
                        header('location: student.php');
                        exit();  
                    }
                    else if($role === 'faculty'){
                        header('location: login.php?error=Faculty page not found');
                        exit();
                    }
                    else{
                        header('location: dashboard.php');
                        exit();
                    }
                } 
                else {

                    if(!$user){
                        header('location: login.php?error=User does not exists!');
                        exit();  
                    }
                    else if(!password_verify($password, $user['password_hash'])){
                        header('location: login.php?error=Incorrect Password');
                        exit();
                    }
                    else{
                        header('location: login.php?error=Please select the correct Role');
                        exit();
                    }
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
