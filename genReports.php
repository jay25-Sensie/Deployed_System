<?php
session_start();
include("connection.php");
include("function.php");

if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: Admin_Staff_login.php");
  exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pid'])) {
    $pid = $_POST['pid'];

    // Fetch patient details
    $patientQuery = "SELECT * FROM patient_records WHERE pid = '$pid'";
    $patientResult = mysqli_query($con, $patientQuery);
    $patient = mysqli_fetch_assoc($patientResult);

    // Fetch prescriptions
    $prescriptionQuery = "SELECT * FROM prescriptions_data WHERE pid = '$pid'";
    $prescriptionResult = mysqli_query($con, $prescriptionQuery);
    $prescriptions = mysqli_fetch_all($prescriptionResult, MYSQLI_ASSOC);

    // Fetch medical records
    $medicalRecordsQuery = "SELECT * FROM medical_records WHERE pid = '$pid'";
    $medicalRecordsResult = mysqli_query($con, $medicalRecordsQuery);
    $medicalRecords = mysqli_fetch_all($medicalRecordsResult, MYSQLI_ASSOC);

    // Fetch vital signs
    $vitalQuery = "SELECT * FROM vital_signs WHERE pid = $pid";
    $vitalResult = mysqli_query($con, $vitalQuery);
    $vital_signs = mysqli_fetch_all($vitalResult, MYSQLI_ASSOC);

    // Fetch diagnosis records
    $diagnosisQuery = "SELECT * FROM diagnosis WHERE pid = $pid";
    $diagnosisResult = mysqli_query($con, $diagnosisQuery);

    if ($diagnosisResult === false) {
        echo "<div class='alert alert-danger'>Error fetching diagnosis records: " . mysqli_error($con) . "</div>";
    }

    $noPatientFound = !$patient; // Set flag if no patient found
} else {
    $pid = null; // No PID provided
    $noPatientFound = false; // No alert needed
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="img/logo.png">
  <title>Generate Report Admin</title>

 <!-- jQuery UI (local) -->
<link rel="stylesheet" href="plugins/jquery-ui/jquery-ui.min.css">
<!-- Font Awesome (local) -->
<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
<!-- Tempusdominus Bootstrap 4 (local) -->
<link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
<!-- iCheck (local) -->
<link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
<!-- JQVMap (local) -->
<link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
<!-- Theme style (local) -->
<link rel="stylesheet" href="dist/css/adminlte.min.css">
<!-- overlayScrollbars (local) -->
<link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
<!-- Daterange picker (local) -->
<link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
<!-- Summernote (local) -->
<link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">

  <style>
    .content-wrapper{
        padding-left: 3%;
        padding-right: 3%;
    }
    #alert{
      text-align: center;
    }
    h2, h3{
      font-weight: bold;
    }
    @media print {
        #printButton {
            display: none;
        }
    }
    .nav-treeview .nav-item {
        padding-left: 3%;
    }
    .col-size{
        width: 15%;
    }
  </style>


</style>

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src=".//img/logo.png" alt="image Logo" height="200" width="200">
    <h2>Loading...</h2>
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index.php" class="nav-link">Home</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link" onclick="confirmLogout(event)">
          <i class="nav-icon fas fa-sign-out-alt"></i> Log out
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src=".//img/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-4" style="opacity: 1">
      <span class="brand-text font-weight-light">IMSClinic_HRMS</span>
    </a>

    <!-- Sidebar -->

    <div class="sidebar">
     

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Dashboard menu item -->
          <li class="nav-item">
            <a href="Dashboard_Admin.php" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <li class="nav-item has-treeview menu-open">
            <a href="#" class="nav-link ">
              <i class="nav-icon fas fa-folder"></i>
              <p>
                Services
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>

            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="patientRecords.php" class="nav-link">
                  <i class="nav-icon fas fa-user"></i>
                  <p>Patient Records</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="Admin_Prescription.php" class="nav-link">
                  <i class="nav-icon fas fa-prescription"></i>
                  <p>Prescription</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="medical_Records.php" class="nav-link">
                  <i class="nav-icon fas fa-file-medical"></i>
                  <p>Add Medical Records</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="genReports.php" class="nav-link active">
                  <i class="nav-icon fas fa-print"></i>
                  <p>Generate Reports</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="setCalendar.php" class="nav-link">
                  <i class="nav-icon fas fa-calendar-alt"></i>
                  <p>Set Calendar</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

<br>
<div class="content-wrapper">
    <div class="wrapper">
      <h2 class="text-center mb-4">General Surgeon/ Physician</h2>
      <h4 class="text-center mb-4">Immaculate Medico-Surgical Clinic</h4>
      <h4 class="text-center mb-4">Padre Diaz Street, Zone 6, Bulan, Sorsogon</h4>
      <hr>
      <br>
        <?php if ($pid === null): ?>
            <!-- Form to select PID and patient name -->
            <div class="container mt-4">
                <h2>Generate Patient Report</h2>
                <form method="POST" action="genReports.php" id="patientReportForm">
                    <div class="form-group">
                        <label for="patient_search">Select Patient (ID - Name):</label>
                        <input type="text" class="form-control" id="patient_search" name="patient_search" placeholder="Select Patient" autocmplte="off" required>
                        <div id="patient_suggestions" class="list-group" style="display: none;"></div>
                        <input type="hidden" id="pid" name="pid"> 
                    </div>
                    <button type="submit" class="btn btn-primary" id="generateReportButton" disabled>Generate Report</button>
                </form>
            </div>
        <?php else: ?>
            <!-- Display Patient Report -->
            <div class="container mt-4">
                <?php if ($patient): ?>
                    <h2>Generate Report for <?php echo htmlspecialchars($patient['name']); ?> <?php echo htmlspecialchars($patient['lastname']); ?></h2>

                    <!-- Patient Information -->
                    <h4>Patient Information</h4>
                    <table class="table table-bordered">
                        <tr><th>PID</th><td><?php echo htmlspecialchars($patient['pid']); ?></td></tr>
                        <tr><th>Name</th><td><?php echo htmlspecialchars($patient['name']); ?> <?php echo htmlspecialchars($patient['lastname']); ?></td></tr>
                        <tr><th>Address</th><td><?php echo htmlspecialchars($patient['brgy']) . ' ' .  htmlspecialchars($patient['municipality']) . ' ' .  htmlspecialchars($patient['province']); ?></td></tr>
                        <tr><th>Age</th><td><?php echo htmlspecialchars($patient['age']); ?></td></tr>
                        <tr><th>Birthdate</th><td><?php echo htmlspecialchars($patient['birthday']); ?></td></tr>
                        <tr><th>Phone Number</th><td><?php echo htmlspecialchars($patient['phone_number']); ?></td></tr>
                        <tr><th>Gender</th><td><?php echo htmlspecialchars($patient['gender']); ?></td></tr>
                        <tr><th>Status</th><td><?php echo htmlspecialchars($patient['status']); ?></td></tr>
                    </table>
                    <br>

                    <!-- Vital Signs Section -->
                    <h3>Vital Signs</h3>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>BP</th>
                                <th>CR</th>
                                <th>RR</th>
                                <th>T</th>
                                <th>WT</th>
                                <th>HT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vital_signs as $vital): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($vital['date']); ?></td>
                                <td><?php echo htmlspecialchars($vital['bp']); ?></td>
                                <td><?php echo htmlspecialchars($vital['cr']); ?></td>
                                <td><?php echo htmlspecialchars($vital['rr']); ?></td>
                                <td><?php echo htmlspecialchars($vital['t']); ?></td>
                                <td><?php echo htmlspecialchars($vital['wt']); ?></td>
                                <td><?php echo htmlspecialchars($vital['ht']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <br>

                    <h3>Diagnosis Records</h3>
                    <?php
                    // Fetch diagnosis records
                    if (isset($pid) && $pid > 0) {
                        $diagnosisQuery = "SELECT * FROM diagnosis WHERE pid = ?";
                        $stmt = $con->prepare($diagnosisQuery);
                        $stmt->bind_param("i", $pid);
                        $stmt->execute();
                        $diagnosisResult = $stmt->get_result();
                        
                        if ($diagnosisResult->num_rows > 0) {
                            echo '<table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="col-size">Date</th>
                                        <th>Subjective</th>
                                        <th>Objective</th>
                                        <th>Assessment</th>
                                        <th>Plan</th>
                                    </tr>
                                </thead>
                                <tbody>';
                            while ($row = $diagnosisResult->fetch_assoc()) {
                                echo '<tr>
                                    <td class="col-size">' . htmlspecialchars($row['date']) . '</td>
                                    <td>' . htmlspecialchars($row['subjective']) . '</td>
                                    <td>' . htmlspecialchars($row['objective']) . '</td>
                                    <td>' . htmlspecialchars($row['assessment']) . '</td>
                                    <td>' . htmlspecialchars($row['plan']) . '</td>
                                </tr>';
                            }
                            echo '</tbody></table>';
                        } else {
                            echo '<div class="alert alert-info" style="text-align: center;">No diagnosis records found for this patient.</div>';
                        }
                    }
                    ?>

                    <!-- Medical Records Section -->
                    <h4>Medical Records</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr><th>File Path</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($medicalRecords as $record): ?>
                            <tr><td><a href="<?php echo htmlspecialchars($record['file_path']); ?>" target="_self"><?php echo htmlspecialchars($record['file_path']); ?></a></td></tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!-- Print Button -->
                    <button id="printButton" onclick="window.print()" class="btn btn-primary">Print Report</button>
                    <br>
                    <br>

                <?php else: ?>
                    <div class="alert alert-warning mt-4" id="alert">No patient found with the provided PID.</div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<!-- ./wrapper -->

<!-- jQuery (local) -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI (local) -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 (local) -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS (local) -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline (local) -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap (local) -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart (local) -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker (local) -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 (local) -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote (local) -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars (local) -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App (local) -->
<script src="dist/js/adminlte.js"></script>
<script src="../Logout.js"></script>
<script>
    $(document).ready(function() {
        // Handle the input field for search
        $("#patient_search").on("keyup", function(event) {
            var search_term = $(this).val().trim();

            if (search_term.length > 2) {
                // Fetch suggestions from the server if input is more than 2 characters
                $.ajax({
                    url: 'search_patients.php',
                    method: 'GET',
                    data: { q: search_term },
                    success: function(response) {
                        if (response.trim() !== "") {
                            $("#patient_suggestions").html(response).show(); // Show suggestions if there are results
                        } else {
                            $("#patient_suggestions").hide(); // Hide if no results
                        }
                    }
                });
            } else {
                $("#patient_suggestions").hide(); // Hide suggestions when input is too short
            }
        });

        // Handle selection of a patient from suggestions
        $("#patient_suggestions").on("click", "li", function() {
            var patientName = $(this).text(); // Get the patient's name
            var patientId = $(this).data("pid"); // Get the patient's PID

            // Set the selected patient's name in the search field
            $("#patient_search").val(patientName);

            // Store the patient ID in the hidden input field
            $("#pid").val(patientId);

            // Hide the suggestions once a patient is selected
            $("#patient_suggestions").hide();

            // Enable the generate report button
            $("#generateReportButton").prop("disabled", false);
        });

        // Hide suggestions when the input loses focus, unless a suggestion is clicked
        $("#patient_search").on("blur", function() {
            setTimeout(function() {
                $("#patient_suggestions").hide(); // Hide suggestions after a short delay
            }, 300); // Small delay to allow click event on suggestion to register
        });

        // Handle Enter key press to select the first suggestion and submit the form
        $("#patient_search").on("keydown", function(event) {
            if (event.key === "Enter") {
                event.preventDefault(); // Prevent default form submission

                // Find the first suggestion
                var firstSuggestion = $("#patient_suggestions li").first();

                if (firstSuggestion.length) {
                    var patientName = firstSuggestion.text(); // Get the patient's name
                    var patientId = firstSuggestion.data("pid"); // Get the patient's PID

                    // Set the selected patient's name in the search field
                    $("#patient_search").val(patientName);

                    // Store the patient ID in the hidden input field
                    $("#pid").val(patientId);

                    // Hide the suggestions once a patient is selected
                    $("#patient_suggestions").hide();

                    // Enable the generate report button
                    $("#generateReportButton").prop("disabled", false);

                    // Submit the form programmatically
                    $("#patientReportForm").submit();
                }
            }
        });
    });
</script>

</script>
</body>
</html>

