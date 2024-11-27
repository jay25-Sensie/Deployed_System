<?php
session_start();
include("connection.php");
include("function.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];  // Changed to 'email'
    $password = $_POST['password'];

    // Check if email and password are provided and validate the email format
    if (!empty($email) && !empty($password) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email = mysqli_real_escape_string($con, $email);  // Sanitize email input

        // Query to get user data from the database
        $query = "SELECT * FROM users WHERE username = '$email' LIMIT 1"; 
        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);

            // Verify the password
            if (password_verify($password, $user_data['password'])) {
                $_SESSION['userID'] = $user_data['userID'];
                $_SESSION['role'] = $user_data['role'];
                $_SESSION['username'] = $user_data['username'];

                // Redirect to dashboard if role is 'admin'
                if ($user_data['role'] == 'admin') {
                    header("Location: Dashboard_Admin.php");
                    exit();
                } else {
                    echo '<script>alert("Access denied. Unknown user."); window.location.href="Admin_Staff_login.php";</script>';
                }
            } else {
                echo '<script>alert("Incorrect password");</script>';
            }
        } else {
            echo '<script>alert("Email not found");</script>';
        }
    } else {
        echo '<script>alert("Please fill in all fields correctly.");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/Admin_Staff_login.css"> 
    <link rel="icon" type="image/png" href="img/logo.png">
    <title>Admin Staff Login Page</title>
</head>
<body>
    <div class="con_2">
        <img src="./img/logo.png" style="width:20%; height: 20%;" alt="">  
        <h3>ADMIN STAFF LOGIN PAGE</h3>
        <div class="form2">
            <form method="post">
                <label for="email">Email Address:</label><br> 
                <input type="email" name="email" id="email" required><br> 
                <label for="password">Password:</label><br>
                <input type="password" name="password" id="password" required><br><br>
                <input type="submit" value="Log In" id="login_as">
            </form><br>
            <a href="Signin_Admin.php" id="signin">Sign Up</a>
            <a href="forgot_password.php" id="forgotpass">Forgot Password?</a><br>
        </div>
    </div>
</body>
</html>
