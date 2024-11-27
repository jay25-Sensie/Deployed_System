<?php
include("connection.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (!empty($token) && !empty($new_password) && !empty($confirm_password)) {
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

            // Query to validate token and get user email
            $query = $con->prepare("SELECT ur.username, pr.user_id FROM password_reset pr
                                    INNER JOIN users ur ON pr.user_id = ur.userID
                                    WHERE pr.token = ? AND pr.expiry > NOW() LIMIT 1");
            $query->bind_param("s", $token);
            $query->execute();
            $result = $query->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $user_id = $row['user_id'];
                $user_email = $row['username'];  // Retrieve email from users table

                // Update password in the users table
                $update_password = $con->prepare("UPDATE users SET password = ? WHERE userID = ?");
                $update_password->bind_param("si", $hashed_password, $user_id);
                $update_password->execute();

                // Delete the token after password reset
                $delete_token = $con->prepare("DELETE FROM password_reset WHERE token = ?");
                $delete_token->bind_param("s", $token);
                $delete_token->execute();

                // Send confirmation email
                $mail = new PHPMailer(true);

                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'jayoredapse@gmail.com';
                    $mail->Password = 'gdna vpis djio nzbl';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // Recipients
                    $mail->setFrom('jayoredapse@gmail.com', 'IMSClinic');
                    $mail->addAddress($user_email);  // Use $user_email here

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Password Reset Successful';
                    $mail->Body    = 'Hello,<br><br>Your password has been successfully reset. If you did not request this change, please contact support immediately.<br><br>Thank you,<br>YourWebsite';

                    $mail->send();
                    echo '<script>alert("Password updated successfully. A confirmation email has been sent to your email."); window.location.href="Doctor_login.php";</script>';
                } catch (Exception $e) {
                    echo '<script>alert("Password updated but failed to send confirmation email. Please try again later."); window.location.href="Doctor_login.php";</script>';
                }
            } else {
                echo '<script>alert("Invalid or expired token.");</script>';
            }
        } else {
            echo '<script>alert("Passwords do not match.");</script>';
        }
    } else {
        echo '<script>alert("Please fill in all fields.");</script>';
    }
} elseif (isset($_GET['token'])) {
    $token = htmlspecialchars($_GET['token']);
} else {
    echo '<script>alert("Invalid request."); window.location.href="forgot_password_doctor.php";</script>';
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="img/logo.png">
    <title>Reset Password</title>
    <link href="plugins/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('/img/background.jpeg') no-repeat center center fixed;
            background-size: cover;
        }
        .card {
            background: rgba(255, 255, 255, 0.85); 
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Reset Password</h4>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <input type="hidden" name="token" value="<?php echo $token; ?>">
                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password:</label>
                                <input type="password" name="new_password" id="new_password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password:</label>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Reset Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="plugins/css/bootstrap.bundle.min.js"></script>
</body>
</html>
