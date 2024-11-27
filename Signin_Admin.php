<?php
session_start();
include("connection.php"); 
include("function.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];  // Changed to 'email'
    $password = $_POST['password'];

    // Check if both email and password are provided and the email is valid
    if (!empty($email) && !empty($password) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email = mysqli_real_escape_string($con, $email);  // Sanitize email input

        // Check if the email already exists
        $query = "SELECT * FROM users WHERE username = '$email' LIMIT 1";  // Changed to use 'email' instead of 'phone_number'
        $result = mysqli_query($con, $query);

        if (mysqli_num_rows($result) > 0) {
            echo '<script>alert("User with this email already exists.");</script>';
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert user with default role 'admin'
            $query = "INSERT INTO users (username, password, role) VALUES ('$email', '$hashed_password', 'admin')";  // Changed to use 'email'
            $result = mysqli_query($con, $query);

            if ($result) {
                header("Location: Admin_Staff_login.php");
                exit(); 
            } else {
                echo "Error: " . mysqli_error($con);
            }
        }
    } else {
        echo '<script>alert("Please enter a valid email and password.");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/Sign_in.css">
    <link rel="icon" type="image/png" href="img/logo.png">
    <title>Sign Up</title>
</head>
<body>
<div class="con_2">
    <img src="./img/logo.png" style="width:20%; height: 20%;" alt="">
    <h3>ADMIN STAFF SIGN UP</h3>
    <div class="form2">
        <form method="post">
            <label for="email">Email Address:</label><br> 
            <input type="email" name="email" id="email" required><br> 
            <label for="password">Password:</label><br>
            <input type="password" name="password" id="password" required><br><br>
            <input type="submit" value="Sign Up" id="sign_up">
        </form><br><br><br>
    </div>
</div>
</body>
</html>
