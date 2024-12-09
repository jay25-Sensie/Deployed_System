<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("connection.php");

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'doctor') {
  header("Location: Doctor_login.php"); 
  exit();
}

// sanitizing input data
function sanitize_input($con, $data) {
    return mysqli_real_escape_string($con, htmlspecialchars(strip_tags($data)));
}

// Initialize variables
$pid = $name = $lastname = $address = $age = $birthday = $phone_number = $gender = $status = '';

// Handle search query
$search_query = '';
if (isset($_GET['search'])) {
    $search = sanitize_input($con, $_GET['search']);
    if (is_numeric($search)) {
        // Search by PID if the input is numeric
        $search_query = "WHERE pid = $search";
    } else {
        // Search by name if the input is not numeric
        $search_query = "WHERE name LIKE '%$search%'";
    }
}

// Update status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $pid = intval($_POST['pid']); // Ensure pid is integer
    $status = sanitize_input($con, $_POST['status']);

    // Update patient status
    $query = "UPDATE patient_records SET status = '$status' WHERE pid = $pid";
    mysqli_query($con, $query);
    header("Location: patientRecords_Doctor.php");
    exit();
}

// Automatically set status to "Inactive" if the patient has not logged in within the last 5 years
$current_date = new DateTime();
$five_years_ago = $current_date->modify('-5 years')->format('Y-m-d');

$update_status_query = "UPDATE patient_records pr
                        JOIN users u ON pr.pid = u.username
                        SET pr.status = 'Not Active'
                        WHERE u.last_login < '$five_years_ago' AND pr.status = 'Active'";
mysqli_query($con, $update_status_query);

// Selecting patients from the database with optional search filtering
$query = "SELECT * FROM patient_records $search_query ORDER BY pid DESC";
$result = mysqli_query($con, $query);
$patients = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="img/logo.png">
  <title>Doctor Dashboard Patient Records</title>
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
          padding-left: 1%;
          padding-right: 1%;
      }
      .action-buttons a {
          margin-right: 5px;
      }    
      .table-responsive {
          width: 40%;
      }
      table, th{
          text-align:center;
      }
      .table-secondary {
          background-color: rgba(0, 0, 0, 0.1);
      }
      .nav-treeview .nav-item {
          padding-left: 3%;
      }
      .action-buttons{
          display: flex;
          justify-content: center;
          gap: 5px;
          font-size: 14px;             
          min-width: 80px;            
          text-align: center;          
          display: inline-flex;
      }
      .col-size{
        width: 15%;
      }

    </style>

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
        <a href="index.php " class="nav-link">Home</a>
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
                <form class="form-inline" action="patientRecords_Doctor.php" method="get">
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
    <div class="container mt-4" >
        <h2 class="mb-4">Patient Records</h2>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>PID</th>
                        <th class="col-size">First Name</th>
                        <th class="col-size">Last Name</th>
                        <th>Address</th>
                        <th>Age</th>
                        <th class="col-size">Birthdate</th>
                        <th>Phone Number</th>
                        <th>Gender</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($patients)): ?>
                        <?php foreach ($patients as $patient): ?>
                            <tr class="<?php echo ($patient['status'] === 'Not Active') ? 'table-secondary' : ''; ?>">
                                <td><?php echo htmlspecialchars($patient['pid']); ?></td>
                                <td class="col-size"><?php echo htmlspecialchars($patient['name']); ?></td>
                                <td class="col-size"><?php echo htmlspecialchars($patient['lastname']); ?></td>
                                <td><?php echo htmlspecialchars($patient['brgy'] ?? '') . ' ' .  htmlspecialchars($patient['municipality'] ?? '') . ', ' . htmlspecialchars($patient['province'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($patient['age']); ?></td>
                                <td class="col-size"><?php echo htmlspecialchars($patient['birthday']); ?></td>
                                <td><?php echo htmlspecialchars($patient['phone_number']); ?></td>
                                <td><?php echo htmlspecialchars($patient['gender']); ?></td>
                                <td><?php echo htmlspecialchars($patient['status']); ?></td>
                                <td class="action-buttons">
                                    <a href="viewPatient_Doctor.php?pid=<?php echo $patient['pid']; ?>" class="btn btn-sm btn-info">View More</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center">No patients found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
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
<script src="./Logout.js"></script>
</body>
</html>