<?php
session_start();

include("connection.php");
include("function.php");

if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: Admin_Staff_login.php");
    exit();
}

$uploadDir = 'uploads/';

// Check if the directory exists
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true); // Create directory with proper permissions
}

// Define the allowed file types and maximum file size (5MB)
$allowedFileTypes = ['pdf', 'doc', 'docx', 'jpeg', 'jpg', 'png'];
$maxFileSize = 5 * 1024 * 1024; // 5MB in bytes

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['upload'])) {
        $pid = $_POST['pid'];
        $file = $_FILES['file'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $targetFile = $uploadDir . basename($fileName);

        // Check if the PID exists in the database
        $pidCheckSql = "SELECT COUNT(*) as count FROM patient_records WHERE pid='$pid'";
        $result = mysqli_query($con, $pidCheckSql);
        $row = mysqli_fetch_assoc($result);

        // Get the file extension
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($row['count'] > 0) {
            // Check if the file size is within the limit
            if ($fileSize > $maxFileSize) {
                echo "<div class='alert alert-danger' style='padding-left:20%; text-align: center;'>File size exceeds the 5MB limit.</div>";
            } elseif (!in_array($fileExtension, $allowedFileTypes)) {
                echo "<div class='alert alert-danger' style='padding-left:20%; text-align: center;'>Invalid file type. Only PDF, DOC, JPEG, JPG, PNG files are allowed.</div>";
            } else {
                // Proceed with file upload
                if (move_uploaded_file($fileTmpName, $targetFile)) {
                    $sql = "INSERT INTO medical_records (pid, file_path) VALUES ('$pid', '$targetFile')";
                    if (mysqli_query($con, $sql)) {
                        echo "<div class='alert alert-success' style='padding-left:20%; text-align: center;'>File uploaded successfully.</div>";
                    } else {
                        echo "<div class='alert alert-danger' style='padding-left:20%; text-align: center;'>Error uploading file to database.</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger' style='padding-left:20%; text-align: center;'>Error moving file. Check directory permissions.</div>";
                }
            }
        } else {
            echo "<div class='alert alert-danger' style='padding-left:20%; text-align: center;'>No PID Found.</div>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link rel="icon" type="image/png" href="img/logo.png">
  <title>Medical Records</title>

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
    .nav-treeview .nav-item {
        padding-left: 3%;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src=".//img/logo.png" alt="image sLogo" height="200" width="200">
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
                <a href="Admin_Prescription.php" class="nav-link">
                  <i class="nav-icon fas fa-prescription"></i>
                  <p>Prescription</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="medical_Records.php" class="nav-link active">
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

  <div class="wrapper">
    <div class="content-wrapper">
        <div class="container mt-5">
            <!-- Display messages -->
            <?php if (!empty($message)): ?>
                <div class="alert-container">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <!-- Form to upload files -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Upload Medical Records</h2>
                </div>
                <div class="card-body">
                    <form action="medical_Records.php" method="POST" enctype="multipart/form-data">
                        <!-- Patient Search with Suggestions -->
                        <div class="form-group">
                            <label for="patient_search">Select Patient (ID - Name):</label>
                            <input type="text" class="form-control" id="patient_search" name="patient_search" placeholder="Select Patient" autocomplete="off" required>
                            <div id="patient_suggestions" class="list-group" style="display: none;"></div>
                            <input type="hidden" id="pid" name="pid">
                        </div>

                        <!-- File Upload -->
                        <div class="form-group">
                            <label for="file">Select File/Image:</label>
                            <input type="file" class="form-control-file" id="file" name="file" required>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" name="upload" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
  $(document).ready(function () {
    // Fetch patient suggestions dynamically
    $("#patient_search").on("keyup", function (event) {
      const search_term = $(this).val().trim();

      if (search_term.length > 2) {
        $.ajax({
          url: 'search_patients.php',
          method: 'GET',
          data: { q: search_term },
          success: function (response) {
            if (response.trim() !== "") {
              $("#patient_suggestions").html(response).show();
            } else {
              $("#patient_suggestions").hide();
            }
          }
        });
      } else {
        $("#patient_suggestions").hide();
      }
    });

    // Select patient from suggestions
    $("#patient_suggestions").on("click", "li", function () {
      const patientName = $(this).text();
      const patientId = $(this).data("pid");

      $("#patient_search").val(patientName);
      $("#pid").val(patientId);
      $("#patient_suggestions").hide();
    });

    // Handle Enter key press to select the first suggestion
    $("#patient_search").on("keydown", function (event) {
      if (event.key === "Enter") {
        event.preventDefault();
        const firstSuggestion = $("#patient_suggestions li").first();

        if (firstSuggestion.length) {
          const patientName = firstSuggestion.text();
          const patientId = firstSuggestion.data("pid");

          $("#patient_search").val(patientName);
          $("#pid").val(patientId);
          $("#patient_suggestions").hide();
        }
      }
    });

    // Hide suggestions when input loses focus
    $("#patient_search").on("blur", function () {
      setTimeout(() => {
        $("#patient_suggestions").hide();
      }, 200);
    });
  });
</script>
</body>
</html>