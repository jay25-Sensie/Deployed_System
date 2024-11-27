<?php
session_start();
include("connection.php");

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
            echo "Access denied: You are not a patient.";
        }
    } else {
        echo "Invalid PID or Password.";
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
    <title>Patient Sign-In</title>
    <link rel="stylesheet" href="..//WBHR_MS/css/Signin_patient.css">
</head>
<body>
    <div class="con_2">
        <img src=".//img/logo.png" style="width:20%; height: 20%;" alt="">
        <h2>Patient Sign-In</h2>
        <div class="form2">
            <form method="POST">
                <label for="pid">Enter Your PID</label>
                <input type="text" id="username" name="username" class="form-control" required>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
                <button type="submit" class="btn btn-primary">Sign In</button>
            </form>
        </div>
    </div>
</body>
</html>
