<?php
session_start();

    include("connection.php");
    include("function.php");

    // Check if the user is logged in and has the correct role
    if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'doctor') {
      header("Location: Doctor_login.php");
      exit();
    }

    $user_data = check_login($con);

    $pid = $name = $lastname = $address = $age = $phone_number = $gender = $status = '';

    // search query
    $search_query = '';
    if (isset($_POST['search'])) {
        $search = sanitize_input($con, $_POST['search']);
        $search_query = "WHERE pid LIKE '%$search%' OR name LIKE '%$search%'";
    }
    
    // update status operations
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Update patient status
        if (isset($_POST['update_status'])) {
            $pid = intval($_POST['pid']); // Ensure pid is integer
            $status = sanitize_input($con, $_POST['status']);
    
            $query = "UPDATE patient_records SET status = '$status' WHERE pid = $pid";
            mysqli_query($con, $query);
            header("Location: patientRecords_Doctor.php");
            exit();
        }
    }
    
    // selecting all patients from the database with optional search filtering
    $query = "SELECT * FROM patient_records $search_query";
    $result = mysqli_query($con, $query);
    $patients = mysqli_fetch_all($result, MYSQLI_ASSOC);


// Ensure PID is passed and is a valid integer
if (isset($_GET['pid']) && ctype_digit($_GET['pid'])) {
  $pid = intval($_GET['pid']);

  // Fetch patient details based on PID
  $query = "SELECT * FROM patient_records WHERE pid = $pid LIMIT 1";
  $result = mysqli_query($con, $query);

  if ($result && mysqli_num_rows($result) > 0) {
      $patient = mysqli_fetch_assoc($result);
  } else {
      echo "No record found for PID: $pid";
      exit();
  }
} else {
  echo "Invalid PID.";
  exit();
}

// Retrieve medical records associated with the PID
$medicalQuery = "SELECT * FROM medical_records WHERE pid = '$pid'";
$medicalResult = mysqli_query($con, $medicalQuery);


// Prepare the SQL query to fetch diagnosis for the patient
$stmt = $con->prepare("SELECT date, subjective, objective, assessment, plan, laboratory FROM diagnosis WHERE pid = ?");
$stmt->bind_param("i", $pid);
$stmt->execute();
$diagnosisResult = $stmt->get_result();

$pid = intval($_GET['pid']);
$query = "SELECT * FROM vital_signs WHERE pid = $pid";
$result = mysqli_query($con, $query);
$vital_signs = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="img/logo.png">
  <title>View Patient Doctor</title>

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
      padding-top: 5%;
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
                <a href="patientRecords_Doctor.php" class="nav-link active">
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
          <!-- Add Diagnosis Form -->
          <h3>Add Diagnosis</h3>
          <form method="POST" action="diognosis.php">
              <table class="table table-bordered">
                  <tr>
                      <th><label for="pid">Patient ID (PID):</label></th>
                      <td><input type="number" name="pid" class="form-control" value="<?php echo htmlspecialchars($pid); ?>" readonly></td>
                  </tr>
                  <tr>
                      <th><label for="date">Date:</label></th>
                      <td><input type="date" name="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required></td>
                  </tr>
                  <tr>
                      <th><label for="subjective">Subjective:</label></th>
                      <td><textarea name="subjective" class="form-control"required></textarea></td>
                  </tr>
                  <tr>
                      <th><label for="objective">Objective:</label></th>
                      <td><textarea name="objective" class="form-control" required></textarea></td>
                  </tr>
                  <tr>
                      <th><label for="assessment">Assessment:</label></th>
                      <td><textarea name="assessment" class="form-control" required></textarea></td>
                  </tr>
                  <tr>
                      <th><label for="plan">Plan:</label></th>
                      <td><textarea name="plan" class="form-control" required></textarea></td>
                  </tr>
              </table>
              <button type="submit" class="btn btn-primary">Add Diagnosis</button>
          </form>
          <br>
          <br>
          <br>
          <!-- Display Diagnosis Records -->
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
                              <th>PID</th>
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
                          <td>' . htmlspecialchars($row['pid']) . '</td>
                          <td>' . htmlspecialchars($row['date']) . '</td>
                          <td>' . htmlspecialchars($row['subjective']) . '</td>
                          <td>' . htmlspecialchars($row['objective']) . '</td>
                          <td>' . htmlspecialchars($row['assessment']) . '</td>
                          <td>' . htmlspecialchars($row['plan']) . '</td>
                      </tr>';
                  }
                  echo '</tbody></table>';
              } else {
                  echo '<div class="alert alert-info">No diagnosis records found for this patient.</div>';
              }
          }
          ?>

<br>
<br>
<br>
          <!-- Display Medical Records -->
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
                      <div class='alert alert-info' style='text-align: center;'>No medical records found for this patient.</div>
                  <?php endif; ?>

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
<script src="../Logout.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Function to capitalize words in an input field
    function capitalizeWords(event) {
      const value = event.target.value;
      event.target.value = value
        .toLowerCase() // Ensure all text is lowercase
        .replace(/\b\w/g, (char) => char.toUpperCase()); // Capitalize first letter of every word
    }

    // Attach the capitalizeWords function to all text and textarea inputs
    // This will work even if the inputs are dynamically added later
    const inputs = document.querySelectorAll('input[type="text"], textarea');
    inputs.forEach(input => {
      input.addEventListener('change', capitalizeWords); // Use 'change' event for more reliable capitalization
    });
  });
</script>
</body>
</html>
