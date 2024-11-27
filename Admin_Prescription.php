<?php
session_start();

include("connection.php");
include("function.php");

if (!isset($_SESSION['userID']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: Admin_Staff_login.php");
    exit();
}

// Fetch the list of patients from the database
$query = "SELECT pid, name, lastname FROM patient_records";
$result = mysqli_query($con, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($con));
}

$patients = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pid = $_POST['pid'];

    // Check if the patient exists
    $query = "SELECT * FROM patient_records WHERE pid = '$pid'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        // Redirect to the prescription form with the patient ID
        header("Location: prescription_form.php?pid=$pid");
        exit();
    } else {
        $patientNotFound = true; // Set a flag for the alert message
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="img/logo.png">
  <title>Admin Prescription</title>

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
    .table-responsive {
        width: 100%;
        text-align: center;
    }
    .content-wrapper {
        padding-left: 5%;
        padding-right: 5%;
        padding-top: 3%;
    }
    .nav-treeview .nav-item {
        padding-left: 3%;
    }
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
      <img src=".//img/logo.png" alt="image Logo" class="brand-image img-circle elevation-4" style="opacity: 1">
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
                <a href="Admin_Prescription.php" class="nav-link active">
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
                <a href="genReports.php" class="nav-link">
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

  <div class="content-wrapper">
    <div class="wrapper">
        <!-- Form to select PID and patient name -->
        <div class="container mt-4">
            <h2>Generate Patient Prescription</h2>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="form-group">
                    <label for="patient_search">Select Patient (ID - Name):</label>
                    <input type="text" class="form-control" id="patient_search" name="patient_search" placeholder="Search by ID or Name" required>
                    <div id="patient_suggestions" class="list-group" style="display: none;"></div>
                    <input type="hidden" id="pid" name="pid">
                </div>
                <button type="submit" class="btn btn-primary">Print Prescription</button>
            </form>

            <?php if (isset($patientNotFound) && $patientNotFound): ?>
                <div class="alert alert-warning mt-4" id="alert">No patient found with the provided PID.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

  <!-- Main content -->
  

  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
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
<script src="../logout.js"></script>
<script>
$(document).ready(function() {
    // Handle the input field for search
    $("#patient_search").on("keyup", function(event) {
        var search_term = $(this).val().trim();

        if (search_term.length > 2) {
            // If input is more than 2 characters, fetch suggestions from the server
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

        // Optionally enable a button or perform other actions
        $("#generateReportButton").prop("disabled", false);
    });

    // Hide suggestions when the input loses focus, unless a suggestion is clicked
    $("#patient_search").on("blur", function() {
        setTimeout(function() {
            $("#patient_suggestions").hide(); // Hide suggestions after a short delay
        }, 300); // Small delay to allow click event on suggestion to register
    });

    // Handle Enter key press to select the first suggestion
    $("#patient_search").on("keydown", function(event) {
        if (event.key === "Enter") {
            event.preventDefault(); // Prevent the form from submitting

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

                // Enable the generate report button if it's disabled
                $("#generateReportButton").prop("disabled", false);
            }
        }
    });
});

</script>
</body>
</html>