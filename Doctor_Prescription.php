<?php
session_start();
include 'connection.php';

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'doctor') {
  header("Location: Doctor_login.php"); 
  exit();
}

$diagnosisQuery = "
    SELECT 
        d.pid, 
        d.date, 
        d.subjective, 
        d.objective, 
        d.assessment, 
        d.plan, 
        CONCAT(p.name, ' ', p.lastname) AS patient_name
    FROM 
        diagnosis d 
    JOIN 
        patient_records p ON d.pid = p.pid
";
$stmt = $con->prepare($diagnosisQuery);
$stmt->execute(); // Execute the prepared statement
$diagnosisResult = $stmt->get_result();

$diagnosisRecords = [];
if ($diagnosisResult->num_rows > 0) {
    while ($row = $diagnosisResult->fetch_assoc()) {
        $diagnosisRecords[] = $row;
    }
} else {
    $diagnosisRecords = [];
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="img/logo.png">
  <title>Doctor Prescription</title>

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
    
    .table-responsive{
        width: 100%;
        text-align: center;
    }
    .content-wrapper{
          padding-left: 3%;
          padding-right: 3%;
          padding-top: 3%;
    }
    .nav-treeview .nav-item {
        padding-left: 3%;
    }
    .col-size{
      width: 15%;
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
            <a href="Dashboard_Doctor.php" class="nav-link active">
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
                <a href="patientRecords_Doctor.php" class="nav-link">
                  <i class="nav-icon fas fa-user"></i>
                  <p>Patient Records</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="Doctor_Prescription.php" class="nav-link active">
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
                <a href="setCalendar_Doctor.php" class="nav-link">
                  <i class="nav-icon fas fa-calendar-alt"></i>
                  <p>set calendar</p>
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
    <h3>Diagnosis Records</h3>

    <?php
    // Include the diagnosis fetching PHP file

    // Display the diagnosis records
    if (!empty($diagnosisRecords)) {
        echo '<table class="table table-bordered">
            <thead>
                <tr>
                    <th>PID</th>
                    <th class="col-size">Patient Name</th>
                    <th class="col-size">Date</th>
                    <th class="col-size">Subjective</th>
                    <th class="col-size">Objective</th>
                    <th class="col-size">Assessment</th>
                    <th class="col-size">Plan</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>';
        foreach ($diagnosisRecords as $record) {
            echo '<tr>
                <td>' . htmlspecialchars($record['pid']) . '</td>
                <td class="col-size">' . htmlspecialchars($record['patient_name']) . '</td>
                <td class="col-size">' . htmlspecialchars($record['date']) . '</td>
                <td class="col-size">' . htmlspecialchars($record['subjective']) . '</td>
                <td class="col-size">' . htmlspecialchars($record['objective']) . '</td>
                <td class="col-size">' . htmlspecialchars($record['assessment']) . '</td>
                <td class="col-size">' . htmlspecialchars($record['plan']) . '</td>
                <td style="text-align: center;">
                    <a href="prescribe.php?pid=' . htmlspecialchars($record['pid']) . '" class="btn btn-sm btn-info" >Set Medicine</a>
                </td>
            </tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<div class="alert alert-info">No diagnosis records found.</div>';
    }
    ?>
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
<script src="../logout.js"></script>
</body>
</html>
