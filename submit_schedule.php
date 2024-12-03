<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// submit_schedule.php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pid = $_POST['pid'];
    $medicine_names = $_POST['medicine_name'];
    $doses_per_day_array = $_POST['doses_per_day'];
    $dose_timings_array = $_POST['dose_timings']; // Expecting an array of arrays for timings
    $end_date_array = $_POST['end_date'];
    $meal_timings_array = $_POST['meal_time'] ?? [];

    // Fetch the patient's phone number
    $patient_stmt = $con->prepare("SELECT phone_number FROM patient_records WHERE pid = ?");
    $patient_stmt->bind_param("i", $pid);
    $patient_stmt->execute();
    $patient_stmt->bind_result($phone_number);
    $patient_stmt->fetch();
    $patient_stmt->close();

    if (!$phone_number) {
        echo "<script>alert('Error: Patient phone number not found.'); window.location.href = 'Doctor_Prescription.php';</script>";
        exit;
    }

    // Loop through each medicine schedule and save it in the database
    for ($i = 0; $i < count($medicine_names); $i++) {
        $medicine_name = $medicine_names[$i];
        $doses_per_day = $doses_per_day_array[$i];
        $timings = $dose_timings_array[$i]; // Array of timings for the current medicine
        $end_date = $end_date_array[$i];
        $meal_timing = $meal_timings_array[$i] ?? null;

        // Extract timings into variables (or null if not provided)
        $timing1 = isset($timings[0]) ? $timings[0] : null;
        $timing2 = isset($timings[1]) ? $timings[1] : null;
        $timing3 = isset($timings[2]) ? $timings[2] : null;
        $timing4 = isset($timings[3]) ? $timings[3] : null;
        $timing5 = isset($timings[4]) ? $timings[4] : null; // Properly retrieve timing5 from the array

        // Validate the end date
        if (strtotime($end_date) < strtotime('today')) {
            echo "<script>alert('Error: End date cannot be in the past.'); window.location.href = 'Doctor_Prescription.php';</script>";
            exit;
        }

        // Insert medicine schedule into the database
        $stmt = $con->prepare("
            INSERT INTO medicine_schedule 
            (pid, medicine_name, doses_per_day, dose_timing_1, dose_timing_2, dose_timing_3, dose_timing_4, dose_timing_5, meal_timing, end_date, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");

        // Bind the parameters
        $stmt->bind_param(
            "isssssssss",
            $pid,
            $medicine_name,
            $doses_per_day,
            $timing1,
            $timing2,
            $timing3,
            $timing4,
            $timing5, // Properly pass timing5
            $meal_timing,
            $end_date
        );

        if ($stmt->execute()) {
            echo "<script>alert('Medicine schedule created successfully.'); window.location.href = 'Doctor_Prescription.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
            file_put_contents('error_log.txt', date('Y-m-d H:i:s') . " - Error saving schedule for $medicine_name: " . $stmt->error . "\n", FILE_APPEND);
        }

        $stmt->close();
    }
}
?>
