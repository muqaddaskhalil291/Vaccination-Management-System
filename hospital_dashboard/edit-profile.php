<?php
session_start();
include "../forms/dbconn.php";

$showalert = false;
$x = "danger";
$y = "Error ! ";

if (($_SESSION['doctor_loggedin'] != true)) {
    header("location: login.php");
    exit;
}

$doctor_id = $_SESSION['doctor_id']; // Doctor ID from session
$doc_pic = $_SESSION['doctor_pic'];


$sql = "SELECT * FROM `doctors` WHERE doctor_id = '$doctor_id';";
$result = mysqli_query($conn, $sql);
$data_count = mysqli_num_rows($result);

if ($data_count > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Fetch doctor details
        $doctor_name = $row['doctor_name'];
        $doctor_email = $row['email'];
        $doctor_phone = $row['phone'];
        $doctor_specialization = $row['specialization'];
        $doctor_department = $row['Department'];
        $doctor_city = $row['city'];
        $doctor_created_at = $row['created_at'];
        $doctor_status = $row['status'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $specialization = $_POST['specialization'];
    $department = $_POST['department'];
    $city = $_POST['city'];

    $exist = true;

    if ($exist) {
        $update_query = "UPDATE `doctors` SET 
            `doctor_name` = '$name', 
            `phone` = '$phone', 
            `specialization` = '$specialization', 
            `Department` = '$department', 
            `city` = '$city' 
            WHERE `doctor_id` = '$doctor_id';";

        $result = mysqli_query($conn, $update_query);

        if ($result) {
            $x = "success";
            $y = "Success";
            $showalert = "The Record Updated Successfully";
        } else {
            $showalert = "There is some error in updating data!";
        }
    } else {
        $showalert = "Email already exists!";
    }
}
?>



<!DOCTYPE html>
<html lang="en">


<!-- edit-profile23:03-->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">
    <title>Care - Edit Profile</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <!--[if lt IE 9]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
	<![endif]-->
</head>

<body>
    <div class="main-wrapper">
    <div class="header">
            <div class="header-left">
                <a href="index-2.html" class="logo">
                    <img src="assets/img/logo.png" width="35" height="35" alt=""> <span>Care</span>
                </a>
            </div>
            <a id="toggle_btn" href="javascript:void(0);"><i class="fa fa-bars"></i></a>
            <a id="mobile_btn" class="mobile_btn float-left" href="#sidebar"><i class="fa fa-bars"></i></a>
            <ul class="nav user-menu float-right">
                <li class="nav-item dropdown has-arrow">
                    <a href="#" class="dropdown-toggle nav-link user-link" data-toggle="dropdown">
                        <span class="user-img">
                            <img class="rounded-circle" src="assets/images/<?php echo $doc_pic ?>" width="24" alt="Admin">
                            <span class="status online"></span>
                        </span>
                        
                        <span style="text-transform:capitalize;"><?php echo $doctor_name ?></span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="profile.php">My Profile</a>
                        <!-- <a class="dropdown-item" href="edit-profile.html">Edit Profile</a> -->
                        <!-- <a class="dropdown-item" href="settings.html">Settings</a> -->
                        <a class="dropdown-item" href="login.php">Logout</a>
                    </div>
                </li>
            </ul>
            <div class="dropdown mobile-user-menu float-right">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="profile.html">My Profile</a>
                    <!-- <a class="dropdown-item" href="edit-profile.html">Edit Profile</a> -->
                    <!-- <a class="dropdown-item" href="settings.html">Settings</a> -->
                    <a class="dropdown-item" href="login.php">Logout</a>
                </div>
            </div>
        </div>



        <div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    <ul>
                        <li class="menu-title">My Dashboard</li>

                        <li>
                            <a href="index.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
                        </li>
                        <li class="active">
                            <a href="profile.php"><i class="fa fa-user-circle"></i> <span>Profile</span></a>
                        </li>
                        <li>
                            <a href="appointments.php"><i class="fa fa-calendar"></i> <span>Appointments</span></a>
                        </li>
                        <li>
                            <a href="doctors.php"><i class="fa fa-user-md"></i> <span>Doctors</span></a>
                        </li>

                        <li>
                            <a href="../forms/login.php"><i class="fa fa-sign-out"></i> <span>Logout</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>





        <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-sm-12">
                        <?php
                        if ($showalert) {
                            echo '<div class="alert alert-' . $x . ' alert-dismissible  show" role="alert">
                     <strong>' . $y . '</strong> ' . $showalert . '
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                             </button>
                                </div>';
                        }
                        ?>

                        <h4 class="page-title">Edit Profile</h4>
                    </div>
                </div>
                <form method="POST">
                    <div class="card-box">
                        <h3 class="card-title">Basic Informations</h3>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="profile-img-wrap">
                                    <img class="inline-block" src="assets/images/<?php echo $doc_pic ?>" alt="user">
                                    <div class="fileupload btn">
                                        <span class="btn-text">edit</span>
                                        <input class="upload" type="file">
                                    </div>
                                </div>
                                <div class="profile-basic">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group form-focus">
                                                <label class="focus-label">Full Name</label>
                                                <input type="text" class="form-control floating" value="<?php echo $doctor_name ?>" name="name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-focus">
                                                <label class="focus-label">ID</label>
                                                <input type="text" class="form-control floating" value="<?php echo $doctor_id ?>" readonly name="userid" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-focus">
                                                <label class="focus-label">Joined</label>
                                                <div class="cal-icon">
                                                    <input class="form-control floating " type="text" value="<?php echo $doctor_created_at ?>" name="dob" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-focus select-focus">
                                                <label class="focus-label">Department</label>
                                                <select class="select form-control floating" name="department" required>
                                                    <option value="<?php echo $doctor_department ?>selected"><?php echo $doctor_department ?></option>
                                                    <?php
                                                    include '../forms/dbconn.php';

                                                    $departmentsQuery = "SELECT * FROM `departments` WHERE status = 'Active';";
                                                    $dep_result = mysqli_query($conn, $departmentsQuery);

                                                    if ($dep_result) {
                                                        while ($row = $dep_result->fetch_assoc()) {
                                                            echo '<option value="' . htmlspecialchars($row['department_id']) . '" name="department">' . htmlspecialchars($row['department_name']) . '</option>';
                                                            $_SESSION['dep'] = $row['department_name'];
                                                        }
                                                    } else {
                                                        echo '<option value="">No departments available</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-box">
                        <h3 class="card-title">Contact Informations</h3>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group form-focus">
                                    <label class="focus-label">Specialization</label>
                                    <input type="text" class="form-control floating" value="<?php echo $doctor_specialization ?>" name="specialization" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-focus">
                                    <label class="focus-label">City</label>
                                    <input type="text" class="form-control floating" value="<?php echo $doctor_city ?>" name="city" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-focus">
                                    <label class="focus-label">Country</label>
                                    <input type="text" class="form-control floating" value="Pakistan">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-focus">
                                    <label class="focus-label">Email</label>
                                    <input type="email" class="form-control floating" value="<?php echo $doctor_email ?>" name="email" readonly required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-focus">
                                    <label class="focus-label">Phone Number</label>
                                    <input type="text" class="form-control floating" value="<?php echo $doctor_phone ?>" name="phone" required pattern="[0-9]{11}" maxlength="11">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center m-t-20">
                        <button class="btn btn-primary submit-btn" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="sidebar-overlay" data-reff=""></div>
    <script src="assets/js/jquery-3.2.1.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.slimscroll.js"></script>
    <script src="assets/js/select2.min.js"></script>
    <script src="assets/js/moment.min.js"></script>
    <script src="assets/js/bootstrap-datetimepicker.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script>
        document.querySelector('.close').addEventListener('click', function() {
            var alert = this.closest('.alert');
            alert.classList.remove('show');
            alert.classList.add('fade');
        });
    </script>

</body>


<!-- edit-profile23:05-->

</html>