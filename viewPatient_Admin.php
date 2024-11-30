<?php
session_start();

include("connection.php");
include("function.php");

if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: Admin_Staff_login.php");
    exit();
}

$pid = $name = $lastname = $address = $age = $phone_number = $gender = $status = '';

// Validate and process form submission via GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Ensure PID is passed and is a valid integer
    if (isset($_GET['pid']) && ctype_digit($_GET['pid'])) {
        $pid = intval($_GET['pid']);  // Get PID securely from GET

        // Fetch patient details based on PID
        $query = "SELECT * FROM patient_records WHERE pid = $pid LIMIT 1";
        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $patient = mysqli_fetch_assoc($result);
        } else {
            echo "<div class='alert alert-danger' style='text-align: center;'>No record found for PID: $pid</div>";
            exit();
        }
    } else {
        echo "<div class='alert alert-danger' style='text-align: center;'>Invalid PID.</div>";
        exit();
    }

    // Retrieve medical records associated with the PID
    $medicalQuery = "SELECT * FROM medical_records WHERE pid = $pid";
    $medicalResult = mysqli_query($con, $medicalQuery);

    // Check for SQL errors
    if (!$medicalResult) {
        echo "<div class='alert alert-danger' style='text-align: center;'>Error retrieving data.</div>";
        exit();
    }

    // Fetch vital signs associated with the PID
    $vitalQuery = "SELECT * FROM vital_signs WHERE pid = $pid";
    $vitalResult = mysqli_query($con, $vitalQuery);
    $vital_signs = mysqli_fetch_all($vitalResult, MYSQLI_ASSOC);
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="img/logo.png">
  <title>View Patient Admin</title>

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
      padding: 3%;
    }
    h2, h3{
      font-weight: bold;
    }
    .nav-treeview .nav-item {
        padding-left: 3%;
    }
    #editbtn{
      width: 100px;
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
            <a href="Dashboard_Admin.php" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <!-- Start of nested menu items -->
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
                <a href="patientRecords.php" class="nav-link active">
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

<br>
  <div class="content-wrapper">
        <h2>Patient Record for <?php echo htmlspecialchars($patient['name']); ?></h2>
        <table class="table table-bordered">
            <tr><th>PID</th><td><?php echo htmlspecialchars($patient['pid']); ?></td></tr>
            <tr><th>First Name</th><td><?php echo htmlspecialchars($patient['name']); ?></td></tr>
            <tr><th>Last Name</th><td><?php echo htmlspecialchars($patient['lastname']); ?></td></tr>
            <tr><th>Address</th><td><?php echo htmlspecialchars($patient['brgy']) . ' ' .  htmlspecialchars($patient['municipality']) . ', ' . htmlspecialchars($patient['province']); ?></td></tr>
            <tr><th>Age</th><td><?php echo htmlspecialchars($patient['age']); ?></td></tr>
            <tr><th>Birthdate</th><td><?php echo htmlspecialchars($patient['birthday']); ?></td></tr>
            <tr><th>Phone Number</th><td><?php echo htmlspecialchars($patient['phone_number']); ?></td></tr>
            <tr><th>Gender</th><td><?php echo htmlspecialchars($patient['gender']); ?></td></tr>
            <tr><th>Status</th><td><?php echo htmlspecialchars($patient['status']); ?></td></tr>
        </table>
<br>
<br>
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
                    <th style="text-align:center;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vital_signs as $vital): ?>
                <tr>
                    <td><?php echo htmlspecialchars($vital['date']); ?></td>
                    <td><?php echo !empty($vital['bp']) ? htmlspecialchars($vital['bp']) : 'N/A'; ?></td>
                    <td><?php echo !empty($vital['cr']) ? htmlspecialchars($vital['cr']) : 'N/A'; ?></td>
                    <td><?php echo !empty($vital['rr']) ? htmlspecialchars($vital['rr']) : 'N/A'; ?></td>
                    <td><?php echo (!empty($vital['t']) && $vital['t'] != 0) ? htmlspecialchars($vital['t']) : 'N/A'; ?></td>
                    <td> <?php echo (!empty($vital['wt']) && $vital['wt'] != 0) ? htmlspecialchars($vital['wt']) : 'N/A'; ?></td>
                    <td> <?php echo (!empty($vital['ht']) && $vital['ht'] != 0) ? htmlspecialchars($vital['ht']) : 'N/A'; ?></td>
                    <td style="text-align: center;">
                        <!-- Edit Button to trigger modal -->
                        <button type="button" class="btn btn-primary btn-sm" id="editbtn" data-toggle="modal" data-target="#editVitalSignModal<?php echo $vital['id']; ?>">
                            Edit
                        </button>
                    </td>
                </tr>
        
                <!-- Edit Modal for Vital Sign -->
                <div class="modal fade" id="editVitalSignModal<?php echo $vital['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editVitalSignModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="updateVitalSign.php" method="post">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editVitalSignModalLabel">Edit Vital Signs for <?php echo htmlspecialchars($patient['name']); ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="vital_id" value="<?php echo $vital['id']; ?>">
                                    <input type="hidden" name="pid" value="<?php echo $patient['pid']; ?>">
        
                                    <div class="form-group">
                                        <label for="vital-date-<?php echo $vital['id']; ?>">Date</label>
                                        <input type="date" class="form-control" id="vital-date-<?php echo $vital['id']; ?>" name="date" value="<?php echo htmlspecialchars($vital['date']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="vital-bp-<?php echo $vital['id']; ?>">Blood Pressure</label>
                                        <input type="text" class="form-control" id="vital-bp-<?php echo $vital['id']; ?>" name="bp" value="<?php echo !empty($vital['bp']) ? htmlspecialchars($vital['bp']) : 'N/A'; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="vital-cr-<?php echo $vital['id']; ?>">Heart Rate</label>
                                        <input type="text" class="form-control" id="vital-cr-<?php echo $vital['id']; ?>" name="cr" value="<?php echo !empty($vital['cr']) ? htmlspecialchars($vital['cr']) : 'N/A'; ?>" oninput="onlyNumbers(this)">
                                    </div>
                                    <div class="form-group">
                                        <label for="vital-rr-<?php echo $vital['id']; ?>">Respiratory Rate</label>
                                        <input type="text" class="form-control" id="vital-rr-<?php echo $vital['id']; ?>" name="rr" value="<?php echo !empty($vital['rr']) ? htmlspecialchars($vital['rr']) : 'N/A'; ?>" oninput="onlyNumbers(this)">
                                    </div>
                                    <div class="form-group">
                                        <label for="vital-t-<?php echo $vital['id']; ?>">Temperature</label>
                                        <input type="text" class="form-control" id="vital-t-<?php echo $vital['id']; ?>" name="t" value="<?php echo !empty($vital['t']) ? htmlspecialchars($vital['t']) : 'N/A'; ?>" oninput="onlyNumbers(this)">
                                    </div>
                                    <div class="form-group">
                                        <label for="vital-wt-<?php echo $vital['id']; ?>">Weight (kg)</label>
                                        <input type="text" class="form-control" id="vital-wt-<?php echo $vital['id']; ?>" name="wt" value="<?php echo !empty($vital['wt']) ? htmlspecialchars($vital['wt']) : 'N/A'; ?>" oninput="onlyNumbers(this)">
                                    </div>
                                    <div class="form-group">
                                        <label for="vital-ht-<?php echo $vital['id']; ?>">Height (cm)</label>
                                        <input type="text" class="form-control" id="vital-ht-<?php echo $vital['id']; ?>" name="ht" value="<?php echo !empty($vital['ht']) ? htmlspecialchars($vital['ht']) : 'N/A'; ?>" oninput="onlyNumbers(this)">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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
                                      <a href="<?php echo htmlspecialchars($record['file_path']); ?>" target="_self">
                                          <?php echo basename(htmlspecialchars($record['file_path'])); ?>
                                      </a>
                                  </td>
                              </tr>
                          <?php endwhile; ?>
                      </tbody>
                  </table>
              <?php else: ?>
                  <div class='alert alert-info' style='text-align: center;'>No medical records found for this patient.</div>
              <?php endif; ?>
          </div>
      </div>
    </div>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
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
<script src="dist/js/adminlte.js"></script>
<script src="../Logout.js"></script>
<script>
    function onlyNumbers(input) {
        input.value = input.value.replace(/[^0-9]/g, '');
    }
</script>
<script>
  function removeStrings(input){
    input.value = input.value.replace(/[a-z]/g, '');
  }
</script>
</body>
</html>
