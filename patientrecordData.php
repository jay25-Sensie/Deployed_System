<?php
session_start();
include("connection.php");
include("function.php");


// Ensure the user is logged in by checking the session
if (!isset($_SESSION['pid'])) {
  header("Location: Patient_login.php");
  exit();
}

// Retrieve patient data based on PID
$pid = $_SESSION['pid'];
$query = "SELECT * FROM patient_records WHERE pid = '$pid' LIMIT 1";
$result = mysqli_query($con, $query);
$patient = mysqli_fetch_assoc($result);

// If patient not found
if (!$patient) {
    echo "<div class='alert alert-danger' style='text-align: center;'>Patient record not found.</div>";
    exit();
}

// Retrieve medical records associated with the PID
$medicalQuery = "SELECT * FROM medical_records WHERE pid = '$pid'";
$medicalResult = mysqli_query($con, $medicalQuery);

// Retrieve prescription data associated with the PID
$prescriptionQuery = "SELECT * FROM prescriptions_data WHERE pid = '$pid'";
$prescriptionResult = mysqli_query($con, $prescriptionQuery);

// Fetch vital signs
$vitalQuery = "SELECT * FROM vital_signs WHERE pid = $pid";
$vitalResult = mysqli_query($con, $vitalQuery);
$vital_signs = mysqli_fetch_all($vitalResult, MYSQLI_ASSOC);

// Check for SQL errors
if (!$medicalResult || !$prescriptionResult) {
  echo "<div class='alert alert-danger' style='text-align: center;'>Error retrieving data.</div>";
  exit();
}

// Selecting patients from the database with optional search filtering
$query = "SELECT * FROM patient_records $search_query";
$result = mysqli_query($con, $query);
$patients = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="img/logo.png">
  <title>Patient Record</title>

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
        .calendar {
            margin-top: 20px;
        }
        .calendar th {
            background-color: #007bff;
            color: #fff;
        }
        .closed {
            background-color: #f8d7da; 
        }
        .content-wrapper{
            padding-left: 3%;
            padding-right: 3%;
        }
        h2, h3{
            font-weight: bold;
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
    <a href="index3.html" class="brand-link">
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
            <a href="Dashboard_Patient.php" class="nav-link active">
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
                <a href="patientrecordData.php" class="nav-link active">
                  <i class="nav-icon fas fa-user"></i>
                  <p>Personal Record</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="patient_Prescription.php" class="nav-link">
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
            <h2>Patient Record for <?php echo htmlspecialchars($patient['name']); ?></h2>
            <table class="table table-bordered">
                <tr><th>PID</th><td><?php echo htmlspecialchars($patient['pid']); ?></td></tr>
                <tr><th>First Name</th><td><?php echo htmlspecialchars($patient['name']); ?></td></tr>
                <tr><th>Last Name</th><td><?php echo htmlspecialchars($patient['lastname']); ?></td></tr>
                <tr><th>Address</th><td><?php echo htmlspecialchars($patient['brgy']) .' ' .  htmlspecialchars($patient['municipality']) . ', ' .  htmlspecialchars($patient['province']);  ?></td></tr>
                <tr><th>Age</th><td><?php echo htmlspecialchars($patient['age']); ?></td></tr>
                <tr><th>Birthdate</th><td><?php echo htmlspecialchars($patient['birthday']); ?></td></tr>
                <tr><th>Phone Number</th><td><?php echo htmlspecialchars($patient['phone_number']); ?></td></tr>
                <tr><th>Gender</th><td><?php echo htmlspecialchars($patient['gender']); ?></td></tr>
                <tr><th>Status</th><td><?php echo htmlspecialchars($patient['status']); ?></td></tr>
            </table>

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
                                <th>Date</th>
                                <th>Subjective</th>
                                <th>Objective</th>
                                <th>Assessment</th>
                                <th>Plan</th>
                            </tr>
                        </thead>
                        <tbody>';
                    while ($row = $diagnosisResult->fetch_assoc()) {
                        echo '<tr>
                            <td>' . htmlspecialchars($row['date']) . '</td>
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
<br>
<br>
            <h3>Medical Records</h3>
            <?php if (mysqli_num_rows($medicalResult) > 0): ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>File</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($record = mysqli_fetch_assoc($medicalResult)): ?>
                            <tr>
                                <td>
                                    <a href="<?php echo htmlspecialchars($record['file_path']); ?>" target="_blank">
                                        <?php echo basename(htmlspecialchars($record['file_path'])); ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class='alert alert-info' style='text-align: center;'>No medical records found.</div>
            <?php endif; ?>
        </div>
    </div>
 <!--put calendar view here with inline-->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery (local) -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 (local) -->
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