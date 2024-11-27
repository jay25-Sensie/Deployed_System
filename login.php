<?php
session_start();

require_once 'vendor/autoload.php'; // Include Google API client library
include("connection.php"); 
include("function.php");

// Google Client Configuration
$client = new Google_Client();
$client->setClientId('YOUR_CLIENT_ID'); // Replace with your actual Client ID
$client->setClientSecret('YOUR_CLIENT_SECRET'); // Replace with your actual Client Secret
$client->setRedirectUri('http://your-website.com/login.php'); // Replace with your redirect URI
$client->addScope(Google_Service_Oauth2::USERINFO_EMAIL);
$client->addScope(Google_Service_Oauth2::USERINFO_PROFILE);

// Handle user login
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);

    $oauth2 = new Google_Service_Oauth2($client);
    $user = $oauth2->userinfo->get();

    $email = $user['email'];
    $name = $user['name'];

    // Check if user exists in your database
    $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        // User exists, log them in
        $user_data = mysqli_fetch_assoc($result);
        $_SESSION['userID'] = $user_data['userID'];
        $_SESSION['role'] = $user_data['role'];
        $_SESSION['username'] = $user_data['username'];

        // Redirect to appropriate dashboard based on role
        if ($user_data['role'] == 'admin') {
            header("Location: Dashboard_Admin.php");
            exit();
        } else {
            header("Location: Dashboard_User.php"); // Replace with your user dashboard
            exit();
        }
    } else {
        // User doesn't exist, create a new account
        $hashed_password = password_hash(uniqid(), PASSWORD_BCRYPT); // Generate random password
        $query = "INSERT INTO users (email, password, name, role) VALUES ('$email', '$hashed_password', '$name', 'user')"; // Set default role to 'user'
        $result = mysqli_query($con, $query);

        if ($result) {
            // Get the newly created user's ID
            $userID = mysqli_insert_id($con);

            $_SESSION['userID'] = $userID;
            $_SESSION['role'] = 'user';
            $_SESSION['username'] = $email;

            header("Location: Dashboard_User.php"); // Redirect to user dashboard
            exit();
        } else {
            echo "Error creating user: " . mysqli_error($con);
        }
    }
} else {
    // Redirect to Google's authorization endpoint
    $authUrl = $client->createAuthUrl();
    header('Location: ' . $authUrl);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="icon" type="image/png" href="img/logo.png">
    <title>Login Page</title>
</head>
<body>
    <div class="con_2">
        <img src=".//img/logo.png" style="width:20%; height: 20%;" alt="">
        <h3>LOGIN PAGE</h3>
        <div class="form2">
            <a href="?code=google" id="google_login">Login with Google</a>
            <a href="Admin_Staff_login.php" id="signin">Admin Login</a>
        </div>
    </div>
</body>
</html>