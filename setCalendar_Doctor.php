<?php
session_start(); // Start session if not already started
include("connection.php");
include("function.php");

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'doctor') {
  header("Location: Doctor_login.php");
  exit();
}

// To set calendar dates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Sanitize input and retrieve selected dates
    $selected_dates = $_POST['selected_dates']; 
    $userID = $_SESSION['userID']; // Assuming user_id is stored in session

    // Save the selected dates in the database
    foreach ($selected_dates as $date) {
        $stmt = $con->prepare("INSERT INTO calendar_closed (userID, selected_date, status) VALUES (?, ?, 'closed')");
        $stmt->bind_param("is", $userID, $date);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: Dashboard_Doctor.php");
    exit();
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="img/logo.png">
  <title>Set Calendar</title>
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
            <a href="Dashboard_Doctor.php" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <li class="nav-item has-treeview menu-open">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-folder"></i>
              <p>
                Services
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="patientRecords_Doctor.php" class="nav-link">
                  <i class="nav-icon fas fa-user"></i>
                  <p>Patient Records</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="Doctor_Prescription.php" class="nav-link">
                  <i class="nav-icon fas fa-prescription"></i>
                  <p>Prescription</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="genReports_Doctor.php" class="nav-link">
                  <i class="nav-icon fas fa-print"></i>
                  <p>Generate Reports</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="setCalendar_Doctor.php" class="nav-link active">
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
    <div class="container">
      <h2 class="mt-4">Set Calendar - Closing Days</h2>
      <form method="post">
        <div class="form-group">
          <label for="selected_dates">Select Dates:</label>
          <div class="input-group">
            <input type="date" id="selected_dates" name="selected_dates[]" class="form-control" required style="width: 900px;" min="<?php echo date('Y-m-d'); ?>">
            <!-- Add more date inputs as needed -->
            <div class="input-group-append">
              <span class="input-group-text" id="calendar-icon">
                <i class="bi bi-calendar"></i> <!-- Bootstrap Icons calendar icon -->
              </span>
            </div>
          </div>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>

</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- Daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- OverlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<script src="./logout.js"></script>
</body>
</html>