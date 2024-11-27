<?php

include("connection.php");

// Check if the form is submitted and required fields are set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vital_id'], $_POST['pid'])) {
    // Retrieve data from POST request
    $vital_id = $_POST['vital_id'];
    $pid = $_POST['pid'];
    $date = $_POST['date'];
    $bp = $_POST['bp'];
    $cr = $_POST['cr'];
    $rr = $_POST['rr'];
    $t = $_POST['t'];
    $wt = $_POST['wt'];
    $ht = $_POST['ht'];

    // Validate input data
    if (empty($date) || empty($bp) || empty($cr) || empty($rr) || empty($t) || empty($wt) || empty($ht)) {
        // Redirect back with error message if any field is empty
        header("Location: patient_record.php?pid=$pid&error=Please fill in all required fields");
        exit;
    }

    // Update vital sign in the database
    $updateQuery = "UPDATE vital_signs SET date = ?, bp = ?, cr = ?, rr = ?, t = ?, wt = ?, ht = ? WHERE id = ? AND pid = ?";
    $stmt = $con->prepare($updateQuery);
    $stmt->bind_param("ssssssiii", $date, $bp, $cr, $rr, $t, $wt, $ht, $vital_id, $pid);

    if ($stmt->execute()) {
        // Redirect back to the patient record page with success message
        header("Location: viewPatient_Admin.php?pid=$pid&message=Vital sign updated successfully");
        exit;
    } else {
        // Redirect back with error message if update fails
        header("Location: viewPatient_Admin.php?pid=$pid&error=Failed to update vital sign");
        exit;
    }
} else {
    // Redirect back with error message if form data is missing
    header("Location: viewPatient_Admin.php?pid=$pid&error=Invalid request");
    exit;
}
?>