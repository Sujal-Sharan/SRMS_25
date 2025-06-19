<?php
require_once("DB_Connect.php");
require_once("session_logout.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $user_id = filter_input(INPUT_POST, "user", FILTER_SANITIZE_SPECIAL_CHARS); // Sanitize username
    $new_password = filter_input(INPUT_POST, "new_password", FILTER_SANITIZE_SPECIAL_CHARS); // Sanitize password
    $confirm_password = filter_input(INPUT_POST, "confirm_password", FILTER_SANITIZE_SPECIAL_CHARS); // Sanitize password

    $hash = password_hash($new_password, PASSWORD_BCRYPT); // Hash password
    $hash_c = password_hash($confirm_password, PASSWORD_BCRYPT); // Hash password


    if($hash != $hash_c){
        echo "<script>
                alert('Password's did NOT match. Please try again!');
                window.location.href = '" . ($_SESSION['role'] === 'admin' ? 'admin_dashboard.php' : 'login.php') . "';
            </script>";
    }
    else{

        try{
            // $sql = "UPDATE login
            //         SET password = ?
            //         WHERE user_id = ?";
            
            // $stmt = $conn->prepare($sql);

            $stmt = $conn->prepare("UPDATE login SET password = ? WHERE user_id = ?");

            $stmt->bind_param("ss", $hash, $user_id);
            
            if($stmt->execute()){
                echo "<script>
                    alert('✅ Password reset successfully');
                    window.location.href = '" . ($_SESSION['role'] === 'admin' ? 'admin_dashboard.php' : 'login.php') . "';
                </script>";
            }
            else{ 
                echo "<script>
                    alert('❌ Failed to reset password.');
                    window.location.href = '" . ($_SESSION['role'] === 'admin' ? 'admin_dashboard.php' : 'login.php') . "';
                </script>";
            }
        }catch(Exception $e){
            echo "<script>
                alert('Fatal Error!');
                window.location.href = '" . ($_SESSION['role'] === 'admin' ? 'admin_dashboard.php' : 'login.php') . "';
            </script>";
        }
    }
}
?>
