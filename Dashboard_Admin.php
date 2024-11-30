<?php
session_start();

include("connection.php");
include("function.php");

if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: Admin_Staff_login.php");
    exit();
}

// Fetch user data based on userID stored in the session
$user_data = check_login($con); // Assuming this function fetches user data

// Function to check if a date is in the selected dates array
function isClosed($date, $closed_dates) {
    return in_array($date, $closed_dates); // Check if $date exists in $closed_dates array
}

// Fetch closed dates from the database
$closed_dates_query = "SELECT selected_date FROM calendar_closed WHERE status = 'closed'";
$closed_dates_result = mysqli_query($con, $closed_dates_query);
$closed_dates = [];
while ($row = mysqli_fetch_assoc($closed_dates_result)) {
    $closed_dates[] = $row['selected_date'];
}

// Getting the current month and year
$current_month = date('n'); // Numeric representation of a month, without leading zeros (1-12)
$current_year = date('Y');  // Full numeric representation of a year (e.g., 2024)

// Number of days in the current month
$num_days_in_month = date('t', mktime(0, 0, 0, $current_month, 1, $current_year));

// Starting day of the week for the first day of the month
$start_day_of_week = date('N', mktime(0, 0, 0, $current_month, 1, $current_year));

// Get the full month name
$month_name = date('F', mktime(0, 0, 0, $current_month, 1, $current_year)); // Full month name (January, February, etc.)
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="img/logo.png">
  <title>Admin Dashboard</title>

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
    .calendar {
        margin-top: 20px;
    }
    .calendar th,
    .calendar td {
        text-align: center;
        width: 200px;
        height: 40px;
    }
    .calendar th {
        background-color: #007bff;
        color: #fff;
    }
    .closed {
        background-color: #f8d7da; 
    }
    .nav-treeview .nav-item {
        padding-left: 3%;
    }
    .mt-4 {
        text-align: right;
        padding-right: 10%;
    } 
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src=".//img/logo.png" alt="AdminLTELogo" height="200" width="200">
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
    <div class="container" style="padding-left: 7%;">
      <h2 class="mt-4">Calendar View</h2>
      <h3 class="month"><?php echo $month_name . " " . $current_year; ?></h3>
      <div class="calendar">
        <table class="table table-bordered" style="width: 90%;">
          <thead>
            <tr>
              <th scope="col">Mon</th>
              <th scope="col">Tue</th>
              <th scope="col">Wed</th>
              <th scope="col">Thu</th>
              <th scope="col">Fri</th>
              <th scope="col">Sat</th>
              <th scope="col">Sun</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <?php
              $day_count = 1;
              $current_date = 1;

              // Loop through each day of the week
              for ($i = 1; $i <= 7; $i++) {
                echo "<td>";

                // Checking if valid day to display
                if ($day_count >= $start_day_of_week && $current_date <= $num_days_in_month) {
                  $date = sprintf('%04d-%02d-%02d', $current_year, $current_month, $current_date);
                  echo $current_date;

                  // Checking if the date is in the closed dates array
                  if (isClosed($date, $closed_dates)) {
                    echo '<br><span class="badge badge-danger">Doctor\'s Not <br> Available</span>';
                  }

                  $current_date++;
                }

                echo "</td>";
                $day_count++;
              }

              echo "</tr>";

              while ($current_date <= $num_days_in_month) {
                echo "<tr>";

                for ($i = 0; $i < 7; $i++) {
                  echo "<td>";

                  if ($current_date <= $num_days_in_month) {
                    $date = sprintf('%04d-%02d-%02d', $current_year, $current_month, $current_date);
                    echo $current_date;

                    if (isClosed($date, $closed_dates)) {
                      echo '<br><span class="badge badge-danger">Doctor\'s Not <br> Available</span>';
                    }

                    $current_date++;
                  }

                  echo "</td>";
                }

                echo "</tr>";
              }
              ?>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="mt-4">
        <button class="btn btn-primary" onclick="editDates()">Edit Selected Dates</button>
      </div>
    </div>
  </div>

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
<script src="../Logout.js"></script>

<script>
function editDates() {
  // Redirect to a page where the dates can be edited
  window.location.href = 'editDates.php';
}
</script>
</body>
</html>