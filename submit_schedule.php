<?php
// submit_schedule.php
include 'connection.php';

// Semaphore API credentials
$semaphore_api_key = '598b6a6303a6fb12fe5a5f46d1af565f';

// Function to send SMS via Semaphore
function sendSMS($phone_number, $message, $api_key) {
    $url = "https://api.semaphore.co/api/v4/messages";

    $data = [
        "apikey" => $api_key,
        "number" => $phone_number,
        "message" => $message,
        "sendername" => 'Thesis'
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo "CURL Error: " . curl_error($ch);
        file_put_contents('sms_log.txt', date('Y-m-d H:i:s') . " - CURL Error: " . curl_error($ch) . "\n", FILE_APPEND);
    }

    curl_close($ch);

    return $response;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pid = $_POST['pid'];
    $medicine_names = $_POST['medicine_name'];
    $doses_per_day_array = $_POST['doses_per_day'];
    $dose_timings_array = $_POST['dose_timings'];
    $durations_array = $_POST['duration'];
    $meal_timings_array = isset($_POST['meal_time']) ? $_POST['meal_time'] : [];
    $end_date_array = $_POST['end_date'];  // Add this line to capture the end dates

    // Fetch the patient's phone number
    $patient_stmt = $con->prepare("SELECT phone_number FROM patient_records WHERE pid = ?");
    $patient_stmt->bind_param("i", $pid);
    $patient_stmt->execute();
    $patient_stmt->bind_result($phone_number);
    $patient_stmt->fetch();
    $patient_stmt->close();

    if (empty($phone_number)) {
        echo "<script>alert('Error: Patient phone number not found.'); window.location.href = 'Doctor_Prescription.php';</script>";
        exit;
    }

    // Loop through each medicine schedule and save it in the database
    for ($i = 0; $i < count($medicine_names); $i++) {
        $medicine_name = $medicine_names[$i];
        $doses_per_day = $doses_per_day_array[$i];
        $timings = $dose_timings_array[$i];
        $end_date = $end_date_array[$i]; // Get the specific end date for this medicine
        $meal_timing = isset($meal_timings_array[$i]) ? $meal_timings_array[$i] : null;

        $timing1 = $timings[0] ?? null;
        $timing2 = $timings[1] ?? null;
        $timing3 = $timings[2] ?? null;
        $timing4 = $timings[3] ?? null;
        $timing5 = null; // Added for dose_timing_5 (assuming you might want it here later)

        // Validate the end date is in the future
        if (strtotime($end_date) < strtotime('today')) {
            echo "<script>alert('Error: End date cannot be in the past.');</script>";
            exit;
        }

        // Insert medicine schedule into the database
        $stmt = $con->prepare("
            INSERT INTO medicine_schedule 
            (pid, medicine_name, doses_per_day, dose_timing_1, dose_timing_2, dose_timing_3, dose_timing_4, dose_timing_5, meal_timing, end_date, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->bind_param("isssssssss", $pid, $medicine_name, $doses_per_day, $timing1, $timing2, $timing3, $timing4, $timing5, $meal_timing, $end_date);

        if ($stmt->execute()) {
            // Send SMS reminder
            $message = "Reminder: You have been prescribed $medicine_name. Please follow the prescribed timings.";
            sendSMS($phone_number, $message, $semaphore_api_key);

            echo "<script>alert('Medicine schedule created successfully.');  window.location.href = 'Doctor_Prescription.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
            file_put_contents('sms_log.txt', date('Y-m-d H:i:s') . " - Error saving schedule for $medicine_name: " . $stmt->error . "\n", FILE_APPEND);
        }

        $stmt->close();
    }
}
?>
