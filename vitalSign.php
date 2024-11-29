<?php
session_start();
include("connection.php");
include("function.php"); 

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $pid = isset($_POST['pid']) ? intval($_POST['pid']) : 0;
    $date = sanitize_input($_POST['date']);
    $bp = isset($_POST['bp']) && !empty($_POST['bp']) ? sanitize_input($_POST['bp']) : 'N/A';
    $cr = isset($_POST['cr']) && !empty($_POST['cr']) ? sanitize_input($_POST['cr']) : 'N/A';
    $rr = isset($_POST['rr']) && !empty($_POST['rr']) ? sanitize_input($_POST['rr']) : 'N/A';
    $t = isset($_POST['t']) && !empty($_POST['t']) ? sanitize_input($_POST['t']) : 'N/A';
    $wt = isset($_POST['wt']) && !empty($_POST['wt']) ? sanitize_input($_POST['wt']) : 'N/A';
    $ht = isset($_POST['ht']) && !empty($_POST['ht']) ? sanitize_input($_POST['ht']) : 'N/A';

    // Validate PID
    if ($pid > 0) {
        // Insert new vital signs record
        $current_time = date('H:i:s'); // Format as HH:MM:SS

        $stmt = $con->prepare("INSERT INTO vital_signs (pid, date, bp, cr, rr, t, wt, ht, time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssss", $pid, $date, $bp, $cr, $rr, $t, $wt, $ht, $current_time);

        if ($stmt->execute()) {
            // Fetch the latest height and weight
            $stmt_display = $con->prepare("SELECT ht, wt FROM vital_signs WHERE pid = ? ORDER BY date DESC, time DESC LIMIT 1");
            $stmt_display->bind_param("i", $pid);
            $stmt_display->execute();
            $stmt_display->bind_result($height, $weight);
            $stmt_display->fetch();

            header("Location: viewPatient_Admin.php?pid=$pid&ht=$height&wt=$weight");
            exit();
        } else {
            echo "<div class='alert alert-danger' style='text-align: center;'>Error inserting vital signs: " . $stmt->error . "</div>";
        }

        $stmt->close();
        $stmt_display->close();
        $con->close();
    } else {
        echo "<div class='alert alert-danger' style='text-align: center;'>Invalid PID.</div>";
    }
}
?>
