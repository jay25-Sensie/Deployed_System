<?php
session_start();
include("connection.php");
include("function.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pid = sanitize_input($con, $_POST['username']);
    $password = sanitize_input($con, $_POST['password']);

    $query = "SELECT * FROM users WHERE username = '$pid' LIMIT 1";
    $result = mysqli_query($con, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        if ($user['role'] === 'patient') {
            $_SESSION['pid'] = $pid;
            header("Location: Dashboard_Patient.php");
            exit();
        } else {
            echo '<script>alert("Access denied: You are not a patient.");</script>';
        }
    } else {
        echo '<script>alert("Invalid PID or Password.");</script>';
    }
}
function sanitize_input($con, $data) {
    return mysqli_real_escape_string($con, htmlspecialchars(strip_tags($data)));
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="img/logo.png">
    <title>Patient Login</title>
    <link rel="stylesheet" href="../css/patient_login.css">
</head>
<body>
    <div class="con_1">
        <img src=".//img/logo.png" style="width:20%; height: 20%;" alt="">
        <h2>Patient Login</h2>
        <div class="form1">
            <form method="POST">
                <label for="username">PID</label>
                <input type="text" id="username" name="username" class="form-control" required>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
                <button type="submit" class="btn btn-primary" id="login_p">Login</button>
            </form> <br>
        </div>
        
    </div>
</body>
</html>

