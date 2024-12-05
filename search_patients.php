<?php
include("connection.php");

if (isset($_GET['q'])) {
    $searchTerm = mysqli_real_escape_string($con, $_GET['q']);

    // Search by PID, Name, or "PID - Name" format
    $query = "SELECT pid, CONCAT(pid, ' - ', name, ' ', lastname) AS display_name 
              FROM patient_records 
              WHERE pid LIKE '%$searchTerm%' 
                 OR name LIKE '%$searchTerm%' 
                 OR lastname LIKE '%$searchTerm%' 
                 OR CONCAT(pid, ' - ', name, ' ', lastname) LIKE '%$searchTerm%' 
              LIMIT 10";

    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li class='list-group-item' data-pid='" . htmlspecialchars($row['pid']) . "'>" . htmlspecialchars($row['display_name']) . "</li>";
        }
    } else {
        echo "<li class='list-group-item'>No matches found</li>";
    }
}
?>
