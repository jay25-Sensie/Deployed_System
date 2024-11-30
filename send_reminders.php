<?php
// Set the default time zone to your local time zone (adjust if necessary)
date_default_timezone_set('Asia/Manila'); // Change this to your specific time zone

// Include connection to the database
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

    // Log any cURL errors
    if (curl_errno($ch)) {
        file_put_contents('sms_log.txt', date('Y-m-d H:i:s') . " - CURL Error: " . curl_error($ch) . "\n", FILE_APPEND);
    }

    curl_close($ch);

    return $response;
}

// Function to send reminders for the current time
function sendScheduledReminders($con, $api_key) {
    // Set current time to match the stored database format (HH:MM)
    $current_time = date('H:i'); // Get current time in HH:MM format (without seconds)

    // Log current time to check if it's correct
    file_put_contents('sms_log.txt', date('Y-m-d H:i:s') . " - Current time: " . $current_time . "\n", FILE_APPEND);

    // Query the database for matching reminders
    $stmt = $con->prepare("
        SELECT ms.pid, ms.medicine_name, ms.end_date, pr.phone_number
        FROM medicine_schedule ms
        JOIN patient_records pr ON ms.pid = pr.pid
        WHERE 
            (TIME(ms.dose_timing_1) = ? OR 
            TIME(ms.dose_timing_2) = ? OR 
            TIME(ms.dose_timing_3) = ? OR 
            TIME(ms.dose_timing_4) = ?)
    ");

    // Bind the current time to all dose timings
    $stmt->bind_param("ssss", $current_time, $current_time, $current_time, $current_time);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if any reminders need to be sent
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $medicine_name = $row['medicine_name'];
            $phone_number = $row['phone_number'];
            $end_date = $row['end_date'];

            // Check if the current date is past the end date
            $current_date = date('Y-m-d');
            if (strtotime($current_date) > strtotime($end_date)) {
                // Skip sending reminders for this prescription
                continue;
            }

            // Prepare the message
            $message = "It's time for your medicine. Please take your $medicine_name now. Stay healthy and have a great day!."; 

            // Send the SMS
            $response = sendSMS($phone_number, $message, $api_key);

            // Log the response from Semaphore
            file_put_contents('sms_log.txt', date('Y-m-d H:i:s') . " - Response from Semaphore: " . $response . "\n", FILE_APPEND);

            // Check if the message was successfully queued
            $response_data = json_decode($response, true);
            if (isset($response_data['status']) && $response_data['status'] === "Queued") {
                file_put_contents('sms_log.txt', date('Y-m-d H:i:s') . " - Sent reminder for $medicine_name to $phone_number\n", FILE_APPEND);
            } else {
                file_put_contents('sms_log.txt', date('Y-m-d H:i:s') . " - Failed to send reminder for $medicine_name to $phone_number\n", FILE_APPEND);
            }
        }
    } else {
        file_put_contents('sms_log.txt', date('Y-m-d H:i:s') . " - No reminders to send at this time.\n", FILE_APPEND);
    }

    $stmt->close();
}

// Call the function to send reminders at the scheduled time
sendScheduledReminders($con, $semaphore_api_key);
?>