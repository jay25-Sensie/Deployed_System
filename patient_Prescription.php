<?php
session_start();
include ("connection.php"); 

if (!isset($_SESSION['pid'])) {
  header("Location: Patient_login.php");
  exit();
}

$pid = $_SESSION['pid']; // Get the patient's PID from the session

// Fetch the patient's medicine schedule from the database
$query = "SELECT * FROM medicine_schedule WHERE pid = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $pid);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
  die("Database query failed: " . mysqli_error($con));
}

$medicineSchedules = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="img/logo.png">
  <title>Patient Prescription</title>

 <!-- Font Awesome (local) -->
<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">

<!-- Tempus Dominus Bootstrap 4 (local) -->
<link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">

<!-- iCheck Bootstrap (local) -->
<link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">

<!-- JQVMap (local) -->
<link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">

<!-- AdminLTE Theme (local) -->
<link rel="stylesheet" href="dist/css/adminlte.min.css">

<!-- OverlayScrollbars (local) -->
<link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">

<!-- Daterange Picker (local) -->
<link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">

<!-- Summernote (local) -->
<link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">

  <style>
    .table-responsive {
        width: 100%;
        text-align: center;
    }
    .content-wrapper {
        padding-left: 2%;
        padding-right: 2%;
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
            <a href="Dashboard_Patient.php" class="nav-link active">
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
                <a href="patientrecordData.php" class="nav-link">
                  <i class="nav-icon fas fa-user"></i>
                  <p>Personal Record</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="patient_Prescription.php" class="nav-link active">
                  <i class="nav-icon fas fa-prescription"></i>
                  <p>Prescription</p>
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
    <div class="container mt-5">
        <h4 style="text-align: right;">Date: <?php echo date('m-d-Y'); ?></h4>
        <h3>Medicine Taking Schedule for Patient ID: <?php echo htmlspecialchars($pid); ?></h3>
        <?php if (!empty($medicineSchedules)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Medicine Name</th>
                        <th>Doses per Day</th>
                        <th>Meal Timings</th>
                        <th>Dose Timings</th>
                        <th>Duration (End Date)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($medicineSchedules as $medicineSchedule): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($medicineSchedule['medicine_name']); ?></td>
                            <td><?php echo htmlspecialchars($medicineSchedule['doses_per_day']); ?></td>
                            <td><?php echo htmlspecialchars($medicineSchedule['meal_timing']); ?></td>
                            <td>
                            

                                <?php
                                // Display the timings in the format 'h:i A' (e.g., 7:35 PM)
                                for ($i = 1; $i <= 5; $i++) {
                                    $timingColumn = "dose_timing_" . $i;
                                    if (isset($medicineSchedule[$timingColumn]) && !empty($medicineSchedule[$timingColumn])):
                                        echo date('h:i A', strtotime($medicineSchedule[$timingColumn])) . "<br>";
                                    endif;
                                }
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($medicineSchedule['end_date']); ?></td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
          <div class='alert alert-info' style='text-align: center;'>No Prescription found.</div>
        <?php endif; ?>
    </div>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
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
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<!-- Local AdminLTE JS -->
<script src="dist/js/adminlte.js"></script>
<!-- Local Bootstrap JS -->
<script src="plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="../logout.js"></script>
</body>
</html>