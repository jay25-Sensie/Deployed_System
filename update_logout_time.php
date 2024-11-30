<?php
session_start();
include("connection.php");

if (isset($_SESSION['pid'])) {
    $pid = $_SESSION['pid'];
    $last_login = date('Y-m-d H:i:s');
    $update_query = "UPDATE users SET last_login = '$last_login' WHERE username = '$pid'";
    if (mysqli_query($con, $update_query)) {
        session_destroy();
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update logout time: ' . mysqli_error($con)]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
}
?>