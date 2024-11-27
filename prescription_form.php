<?php
session_start();

include("connection.php");
include("function.php");

if (!isset($_SESSION['userID']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: Admin_Staff_login.php");
    exit();
}

// Get the patient ID from the query parameter
$pid = $_GET['pid'];

// Check if the patient ID is valid
if (empty($pid)) {
    echo "Invalid patient ID.";
    exit;
}

// Fetch the patient's details from the database
$query = "SELECT * FROM patient_records WHERE pid = '$pid'";
$result = mysqli_query($con, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($con));
}

$patient = mysqli_fetch_assoc($result);

if (!$patient) {
    echo "Patient not found.";
    exit;
}

// Fetch the medicine schedule for the selected patient
$query = "SELECT * FROM medicine_schedule WHERE pid = '$pid'";
$result = mysqli_query($con, $query);

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
    .table-responsive{
       width: 100%;
       text-align: center;
    }
    .content-wrapper{
       padding-left: 5%;
       padding-right: 5%;
       padding-top: 3%;
    }
    .nav-treeview .nav-item {
       padding-left: 3%;
    }
    .agb{
       text-align: right;
       position: relative;
    }
    @media print {
       .row.mb-4 > div {
         display: inline-block;
         width: 50%;
         vertical-align: top;
       }
       .row.mb-4 > div > p {
         margin: 0;
       }
    }
    @media print {
        title, #printButton {
            display: none;
        }
    .row mb-4{
      position: fixed;
    }
    }
    .pright{
      text-align: right;
      position: relative;
    }
    .row {
      display: flex; 
      justify-content: space-between; 
      align-items: flex-start; 
    }

    .left-column {
      text-align: left; 
      flex-basis: 40%; 
    }

    .right-column {
      text-align: right; 
      flex-basis: 50%; 
      list-style-type: none; 
      padding: 0; 
    }

    .right-column li {
      margin-bottom: 0.5em; 
    }

    .mb-4 {
      margin-bottom: 1rem; 
    }

    .row hr {
      margin: 1rem 0; 
      border-top: 1px solid #ccc; 
      width: 100%; 
    }
    ul{
      list-style: none;
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
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search"></i>
            </a>
            <div class="navbar-search-block">
                <form class="form-inline" action="patientRecords.php" method="post">
                    <div class="input-group input-group-sm">
                    <input class="form-control form-control-navbar" type="search" name="search" placeholder="Search by PID or Name" aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-navbar" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                </form>
            </div>
        </li>     
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
  <!-- Main Content -->
  <div class="content-wrapper">
    <div class="container mt-5">
      <h2 class="text-center mb-4">General Surgeon/ Physician</h2>
      <h4 class="text-center mb-4">Immaculate Medico-Surgical Clinic</h4>
      <h4 class="text-center mb-4">Padre Diaz Street, Zone 6, Bulan, Sorsogon</h4>
      <hr>
      <h4 class="text-center mb-4">Prescription Records for PID: <?php echo $pid; ?></h4>
      
      <!-- Display Patient Information -->
      <div class="row mb-4">
        <div class="col-md-6">
          <p><strong>Name:</strong> <?php echo htmlspecialchars($patient['name']) . ' ' . htmlspecialchars($patient['lastname']); ?></p>
          <p><strong>Address:</strong> <?php echo htmlspecialchars($patient['brgy']) .' ' . htmlspecialchars($patient['municipality']) . ', ' . htmlspecialchars($patient['province']); ?></p>
          <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($patient['phone_number']); ?></p>
        </div>
        <div class="col-md-6">
          <p class="agb"><strong>Age:</strong> <?php echo htmlspecialchars($patient['age']); ?></p>
          <p class="agb"><strong>Sex:</strong> <?php echo htmlspecialchars($patient['gender']); ?></p>
          <p class="agb"><strong>Birthday:</strong> <?php echo htmlspecialchars($patient['birthday']); ?></p>
        </div>
      </div>

      <img src=".//img/rx-icon.png" style="height: 100px;" alt="rx"> <br><br>

      <!-- Display Medicine Schedule -->
      <?php if (!empty($medicineSchedules)): ?>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Medicine Name</th>
              <th>Doses per Day</th>
              <th>Dose Timings</th>
              <th>Meal Timing</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($medicineSchedules as $medicineSchedule): ?>
              <tr>
                <td><?php echo htmlspecialchars($medicineSchedule['medicine_name']); ?></td>
                <td><?php echo htmlspecialchars($medicineSchedule['doses_per_day']); ?></td>
                <td>
                  <?php
                  for ($i = 1; $i <= 5; $i++) {
                    $timingColumn = "dose_timing_" . $i;
                    if (isset($medicineSchedule[$timingColumn]) && !empty($medicineSchedule[$timingColumn])):
                      echo date('h:i A', strtotime($medicineSchedule[$timingColumn])) . "<br>";
                    endif;
                  }
                  ?>
                </td>
                <td><?php echo htmlspecialchars($medicineSchedule['meal_timing']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <div class='alert alert-info' style='text-align: center;'>No Prescription found.</div>
      <?php endif; ?>
      <div class="row mb-4">
        <div class="left-column">
          <p> Next Appointment:</p>
        <input type="date" style="border: none;">
        </div>
        <div class="right-column">
          <ul>
            <li>Leonardo G. Carpio, MD.</li>
            <li>Li. No. : 55597</li>
            <li>PTR No. : </li>
            <li>S2 No.  :</li>
          </ul> 
        </div>
      </div>
      <button id="printButton" class="btn btn-primary" onclick="window.print()">Print Prescription</button> <br><br>
    </div>
  </div>

</div> 

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

</body>
</html>
