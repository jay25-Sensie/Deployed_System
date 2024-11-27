<?php
session_start();
include("connection.php");
include("function.php");

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'doctor') {
    header("Location: Doctor_login.php");
    exit();
}

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $pid = isset($_POST['pid']) ? intval($_POST['pid']) : 0; // Change to $_POST
    $date = sanitize_input($_POST['date']);
    $subjective = sanitize_input($_POST['subjective']);
    $objective = sanitize_input($_POST['objective']);
    $assessment = sanitize_input($_POST['assessment']);
    $plan = sanitize_input($_POST['plan']);
    $laboratory = isset($_POST['laboratory']) ? sanitize_input($_POST['laboratory']) : '';

    // Validate PID
    if ($pid > 0) {
        // Prepare the SQL query to insert diagnosis
        $stmt = $con->prepare("INSERT INTO diagnosis (pid, date, subjective, objective, assessment, plan, laboratory) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $pid, $date, $subjective, $objective, $assessment, $plan, $laboratory);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect to the patient record page
            header("Location: viewPatient_Doctor.php?pid=$pid");
            exit();
        } else {
            echo "<div class='alert alert-danger' style='text-align: center;'>Error inserting diagnosis: " . $stmt->error . "</div>";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "<div class='alert alert-danger' style='text-align: center;'>Invalid PID.</div>";
    }
}

// Close the database connection
$con->close();
?>
