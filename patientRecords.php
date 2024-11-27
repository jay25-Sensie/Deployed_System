<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("connection.php");

if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: Admin_Staff_login.php");
    exit();
}

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


function sanitize_input($con, $data) {
    return mysqli_real_escape_string($con, htmlspecialchars(strip_tags($data)));
}

// Function to check if a patient with the same name and lastname exists
function check_patient_name_exists($con, $name, $lastname, $pid = null) {
    $query = "SELECT * FROM patient_records WHERE name = '$name' AND lastname = '$lastname'";
    if ($pid) {
        $query .= " AND pid != $pid";
    }
    $result = mysqli_query($con, $query);
    return mysqli_num_rows($result) > 0;
}

// Function to check if a patient with the same phone number exists
function check_phone_number_exists($con, $phone_number, $pid = null) {
    $query = "SELECT * FROM patient_records WHERE phone_number = '$phone_number'";
    if ($pid) {
        $query .= " AND pid != $pid";
    }
    $result = mysqli_query($con, $query);
    return mysqli_num_rows($result) > 0;
}

function format_phone_number($phone_number) {
    if (substr($phone_number, 0, 1) === '0') {
        return '+63' . substr($phone_number, 1);
    }
    return $phone_number;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize_input($con, $_POST['name']);
    $lastname = sanitize_input($con, $_POST['lastname']);
    $brgy = sanitize_input($con, $_POST['brgy']);
    $municipality = sanitize_input($con, $_POST['municipality']);
    $province = sanitize_input($con, $_POST['province']);
    $birthday = sanitize_input($con, $_POST['birthday']);
    $phone_number = sanitize_input($con, $_POST['phone_number']);
    $gender = sanitize_input($con, $_POST['gender']);
    $pid = isset($_POST['pid']) ? intval($_POST['pid']) : null;

    $validation_passed = true;
    $formatted_phone_number = format_phone_number($phone_number);

    if (!ctype_digit(substr($formatted_phone_number, 3)) || strlen($formatted_phone_number) != 13) {
        echo "<script>alert('Phone number must contain exactly 11 digits!');</script>";
        $validation_passed = false;
    }

    if (check_patient_name_exists($con, $name, $lastname, $pid)) {
        echo "<script>alert('A patient with this name and last name already exists!');</script>";
        $validation_passed = false;
    }

    if (check_phone_number_exists($con, $formatted_phone_number, $pid)) {
        echo "<script>alert('A patient with this phone number already exists!');</script>";
        $validation_passed = false;
    }

    if ($validation_passed) {
        $birthdate = new DateTime($birthday);
        $today = new DateTime();
        $age = $today->diff($birthdate)->y;

        if (isset($_POST['add_patient'])) {
            $query = "INSERT INTO patient_records (name, lastname, brgy,  municipality, province, age, birthday, phone_number, gender, status) 
                      VALUES ('$name', '$lastname', '$brgy', '$municipality', '$province',  $age, '$birthday', '$formatted_phone_number', '$gender', 'Active')";
            if (mysqli_query($con, $query)) {
                $pid = mysqli_insert_id($con);
                $hashed_password = password_hash($pid, PASSWORD_BCRYPT);
                $query_user = "INSERT INTO users (username, password, role) VALUES ('$pid', '$hashed_password', 'patient')";
                mysqli_query($con, $query_user);
        
                header("Location: patientRecords.php");
                exit();
            } else {
                echo "Error: " . mysqli_error($con);
            }
        } elseif (isset($_POST['update_patient'])) {
            $query = "UPDATE patient_records SET 
                      name = '$name', lastname = '$lastname', province = '$province', 
                      municipality = '$municipality', brgy = '$brgy', 
                      age = $age, birthday = '$birthday', phone_number = '$formatted_phone_number', gender = '$gender' 
                      WHERE pid = $pid";
            if (mysqli_query($con, $query)) {
                header("Location: patientRecords.php");
                exit();
            } else {
                echo "Error: " . mysqli_error($con);
            }
        }
        

        if (isset($_POST['update_status'])) {
            $status = sanitize_input($con, $_POST['status']);
            $query = "UPDATE patient_records SET status = '$status' WHERE pid = $pid";
            if (mysqli_query($con, $query)) {
                header("Location: patientRecords.php");
                exit();
            } else {
                echo "Error: " . mysqli_error($con);
            }
        }
    } else {
        echo "<script>window.history.back();</script>";
        exit();
    }
}

$query = "SELECT * FROM patient_records";
$result = mysqli_query($con, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($con));
}

$patients = mysqli_fetch_all($result, MYSQLI_ASSOC);

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
  <title>Admin Dashboard Patient Records</title>
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
        
        .content-wrapper{
            padding-left: 1%;
            padding-right: 1%;
        }
        .table-secondary {
            background-color: rgba(0, 0, 0, 0.1);
            color: white;
        }
        tr,th{
            text-align: center;
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
            width: 22%;
        }
    </style>

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
                <form class="form-inline" action="patientRecords.php" method="get">
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


  <div class="content-wrapper">
        <div class="container mt-4">
            <h2 class="mb-4">Patient Records</h2>

            <div class="mb-3">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPatientModal">
                    Add Patient
                </button>
            </div>

            <!-- Patient Records Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>PID</th>
                            <th class="col-size">First Name</th>
                            <th class="col-size">Last Name</th>
                            <th class="col-size">Address</th>
                            <th class="col-size">Birthdate</th>
                            <th>Age</th>
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
                                    <td class="col-size-add"><?php echo htmlspecialchars($patient['brgy']) . ' ' .htmlspecialchars($patient['municipality']) . ', ' . htmlspecialchars($patient['province']); ?></td>
                                    <td class="col-size"><?php echo htmlspecialchars($patient['birthday']); ?></td>
                                    <td><?php echo htmlspecialchars($patient['age']); ?></td>
                                    <td><?php echo htmlspecialchars($patient['phone_number']); ?></td>
                                    <td><?php echo htmlspecialchars($patient['gender']); ?></td>
                                    <td><?php echo htmlspecialchars($patient['status']); ?></td>
                                    <td class="action-buttons">
                                        <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editPatientModal<?php echo $patient['pid']; ?>">Edit</a>
                                        <a href="viewPatient_Admin.php?pid=<?php echo $patient['pid']; ?>" class="btn btn-sm btn-info">View More</a>
                                        <a href="#" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#vitalSignModal<?php echo $patient['pid']; ?>">Vital Sign</a>
                                    </td>
                                </tr>

                                <!-- Edit Patient Modal -->
                                <div class="modal fade" id="editPatientModal<?php echo $patient['pid']; ?>" tabindex="-1" role="dialog" aria-labelledby="editPatientModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="patientRecords.php" method="post">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editPatientModalLabel">Edit Patient</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="pid" value="<?php echo $patient['pid']; ?>">
                                                    
                                                    <div class="form-group">
                                                        <label for="edit-name">First Name</label>
                                                        <input type="text" class="form-control" id="edit-name" name="name" value="<?php echo $patient['name']; ?>" required oninput="cleanInput(this)">
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="edit-lastname">Last Name</label>
                                                        <input type="text" class="form-control" id="edit-lastname" name="lastname" value="<?php echo $patient['lastname']; ?>" required oninput="cleanInput(this)">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="edit-brgy">Barangay</label>
                                                        <input type="text" class="form-control" id="edit-brgy" name="brgy" value="<?php echo $patient['brgy']; ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="edit-municipality">Municipality</label>
                                                        <input type="text" class="form-control" id="edit-municipality" name="municipality" value="<?php echo $patient['municipality']; ?>" required oninput="cleanInput(this)">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="edit-province">Province</label>
                                                        <input type="text" class="form-control" id="edit-province" name="province" value="<?php echo $patient['province']; ?>" required oninput="cleanInput(this)">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="edit-birthday">Birthdate</label>
                                                        <input type="date" class="form-control birthday-input" id="edit-birthday" name="birthday" value="<?php echo $patient['birthday']; ?>" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="edit-age">Age</label>
                                                        <input type="number" class="form-control age-input" id="edit-age" name="age" value="<?php echo $patient['age']; ?>" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="edit-phone-number">Phone Number</label>
                                                        <input type="text" class="form-control" id="edit-phone-number" name="phone_number" value="<?php echo $patient['phone_number']; ?>" required oninput="onlyNumbers(this)">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="edit-gender">Gender</label>
                                                        <select class="form-control" id="edit-gender" name="gender" required>
                                                            <option value="Male" <?php echo ($patient['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                                                            <option value="Female" <?php echo ($patient['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary" name="update_patient">Save changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            
                                <!-- Vital Sign Modal -->
                                <div class="modal fade" id="vitalSignModal<?php echo $patient['pid']; ?>" tabindex="-1" role="dialog" aria-labelledby="vitalSignModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="vitalSign.php" method="post">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="vitalSignModalLabel">Add Vital Signs for <?php echo htmlspecialchars($patient['name']); ?></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="pid" value="<?php echo $patient['pid']; ?>">

                                                    <?php
                                                    // Fetch the most recent weight and height for the specific patient
                                                    $pid = $patient['pid'];
                                                    $query = "SELECT wt, ht FROM vital_signs WHERE pid = ? ORDER BY date DESC LIMIT 1";
                                                    $stmt = $con->prepare($query);
                                                    $stmt->bind_param("i", $pid);
                                                    $stmt->execute();
                                                    $stmt->bind_result($prev_wt, $prev_ht);
                                                    $stmt->fetch();
                                                    $stmt->close();
                                                    ?>

                                                    <div class="form-group">
                                                        <label for="vital-date">Date</label>
                                                        <input type="date" class="form-control" id="vital-date" name="date" value="<?php echo date('Y-m-d'); ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="vital-bp">Blood Pressure</label>
                                                        <input type="text" class="form-control" id="vital-bp" name="bp" required oninput="onlyNumbers(this)">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="vital-cr">Heart Rate</label>
                                                        <input type="text" class="form-control" id="vital-cr" name="cr" required oninput="onlyNumbers(this)">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="vital-rr">Respiratory Rate</label>
                                                        <input type="text" class="form-control" id="vital-rr" name="rr" required oninput="onlyNumbers(this)">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="vital-t">Temperature</label>
                                                        <input type="text" class="form-control" id="vital-t" name="t" required oninput="onlyNumbers(this)">
                                                    </div>

                                                    <!-- Weight and Height with previous values pre-filled -->
                                                    <div class="form-group">
                                                        <label for="vital-wt">Weight (kg)</label>
                                                        <input type="text" class="form-control" id="vital-wt" name="wt" value="<?php echo htmlspecialchars($prev_wt); ?>" required oninput="onlyNumbers(this)">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="vital-ht">Height (cm)</label>
                                                        <input type="text" class="form-control" id="vital-ht" name="ht" value="<?php echo htmlspecialchars($prev_ht); ?>" required oninput="onlyNumbers(this)">
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save Vital Signs</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>


                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10">No patient records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Patient Modal -->
    <div class="modal fade" id="addPatientModal" tabindex="-1" role="dialog" aria-labelledby="addPatientModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="patientRecords.php" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPatientModalLabel">Add New Patient</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">First Name</label>
                            <input type="text" class="form-control" id="name" name="name" required  oninput="cleanInput(this)">
                        </div>
                        <div class="form-group">
                            <label for="lastname">Last Name</label>
                            <input type="text" class="form-control" id="lastname" name="lastname" required  oninput="cleanInput(this)">
                        </div>
                        <div class="form-group">
                            <label for="brgy">Barangay</label>
                            <input type="text" class="form-control" id="brgy" name="brgy" required>
                        </div>
                        <div class="form-group">
                            <label for="municipality">Municipality</label>
                            <input type="text" class="form-control" id="municipality" name="municipality" required oninput="cleanInput(this)">
                        </div>
                        <div class="form-group">
                            <label for="province">Province</label>
                            <input type="text" class="form-control" id="province" name="province" required oninput="cleanInput(this)">
                        </div>
       
                        <div class="form-group">
                            <label for="birthday">Birthdate</label>
                            <input type="date" class="form-control birthday-input" id="birthday" name="birthday" required>
                        </div>
                        <div class="form-group">
                            <label for="age">Age</label>
                            <input type="number" class="form-control age-input" id="age" name="age" readonly>
                        </div>

                        <div class="form-group">
                            <label for="phone_number">Phone Number</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" required oninput="validatePhoneNumber(this)">
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select class="form-control" id="gender" name="gender" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="add_patient">Add Patient</button>
                    </div>
                </form>
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
<script src="../Bday_Validation.js"></script>
<script src="../logout.js"></script>
<script>
    function onlyNumbers(input) {
        input.value = input.value.replace(/[^0-9]/g, '');
    }
</script>

<script>
    function cleanInput(input) {
        input.value = input.value.replace(/[^a-zA-Z\s']/g, '');
    }
</script>


<script>
    // Function to capitalize only the first letter of each word
    function capitalizeFirstLetter(input) {
        input.value = input.value
            .toLowerCase()
            .replace(/(?:^|\s)\S/g, function(a) { return a.toUpperCase(); });
    }

    // Attach the function to each input field outside the modal
    document.getElementById("name").addEventListener("input", function() {
        capitalizeFirstLetter(this);
    });
    document.getElementById("lastname").addEventListener("input", function() {
        capitalizeFirstLetter(this);
    });
    document.getElementById("province").addEventListener("input", function() {
        capitalizeFirstLetter(this);
    });
    document.getElementById("municipality").addEventListener("input", function() {
        capitalizeFirstLetter(this);
    });
    document.getElementById("brgy").addEventListener("input", function() {
        capitalizeFirstLetter(this);
    });

    // Use jQuery to ensure event listeners are reattached every time the modal is opened
    $(document).on('shown.bs.modal', '.modal', function () {
        $(this).find('#edit-name, #edit-lastname, #edit-brgy, #edit-municipality, #edit-province').each(function() {
            $(this).on('input', function() {
                capitalizeFirstLetter(this);
            });
        });
    });


</script>
<script>
function validatePhoneNumber(input) {
    input.value = input.value.replace(/\D/g, '');
    if (input.value.length > 11) {
        input.value = input.value.slice(0, 11);
    }
}
</script>
</body>
</html>