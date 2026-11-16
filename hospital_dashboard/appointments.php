<?php
session_start();
include "../forms/dbconn.php";

$showalert = false;
$x = "danger";
$y = "Error ! ";
if (!isset($_SESSION['hospital_loggedin']) || $_SESSION['hospital_loggedin'] !== true) {
    header("location: login.php");
    exit;
}

$hospital_id = $_SESSION['hospital_id'];
$hospital_name = $_SESSION['hospital_name'];
$act_sql = "SELECT COUNT(*) FROM appointments WHERE hospital_id = $hospital_id AND `status` = 'Active';";
$act_result = mysqli_query($conn, $act_sql);
$act_count = mysqli_fetch_row($act_result)[0];

$pen_sql = "SELECT COUNT(*) FROM appointments WHERE hospital_id = $hospital_id AND `status` = 'Pending';";
$pen_result = mysqli_query($conn, $pen_sql);
$pen_count = mysqli_fetch_row($pen_result)[0];

?>



<!DOCTYPE html>
<html lang="en">


<!-- appointments23:19-->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">
    <title>VMC - Appointments</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <!-- Font Awesome 5.15.4 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!--[if lt IE 9]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
	<![endif]-->
    <style>
        .min-width-200 {
            min-width: 100px;
        }
    </style>

</head>

<body>
    <div class="main-wrapper">
    <div class="header">
            <div class="header-left">
                <a href="index-2.php" class="logo">
                    <img src="assets/img/logo.png" width="35" height="35" alt=""> <span>VMC</span>
                </a>
            </div>
            <a id="toggle_btn" href="javascript:void(0);"><i class="fa fa-bars"></i></a>
            <a id="mobile_btn" class="mobile_btn float-left" href="#sidebar"><i class="fa fa-bars"></i></a>
            <ul class="nav user-menu float-right">
                <li class="nav-item dropdown has-arrow">
                    <a href="#" class="dropdown-toggle nav-link user-link" data-toggle="dropdown">
                    <span class="user-img">
    <img class="rounded-circle" src="" width="24" alt="">
    <span class="status online"></span>
</span>

<span style="text-transform:capitalize;"><?php echo $hospital_name; ?></span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="profile.php">My Profile</a>
                        <!-- <a class="dropdown-item" href="edit-profile.php">Edit Profile</a> -->
                        <!-- <a class="dropdown-item" href="settings.php">Settings</a> -->
                        <a class="dropdown-item" href="login.php">Logout</a>
                    </div>
                </li>
            </ul>
            <div class="dropdown mobile-user-menu float-right">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="profile.php">My Profile</a>
                    <!-- <a class="dropdown-item" href="edit-profile.php">Edit Profile</a> -->
                    <!-- <a class="dropdown-item" href="settings.php">Settings</a> -->
                    <a class="dropdown-item" href="login.php">Logout</a>
                </div>
            </div>
        </div>



        <div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    <ul>
                        <li class="menu-title">My Dashboard</li>

                        <li class="active">
                            <a href="index.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
                        </li>
                        <li>
                            <a href="profile.php"><i class="fa fa-user-circle"></i> <span>Profile</span></a>
                        </li>
                        <li>
                            <a href="appointments.php"><i class="fa fa-calendar"></i> <span>Appointments</span></a>
                        </li>
                        <li>
                            <a href="edit-schedule.php"><i class="fa fa-clock"></i> <span>Schedule</span></a>
                        </li>

                        <li>
                            <a href="add-vaccine.php"><i class="fa fa-clock"></i> <span>vaccines</span></a>
                        </li>
                        <li>
                            <a href="login.php"><i class="fa fa-sign-out"></i> <span>Logout</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>



        <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-sm-4 col-3">
                        <h4 class="page-title">All Appointments</h4>
                    </div>
                    <div class="col-sm-8 col-9 text-right m-b-20">

                        <a href="#" class="btn btn btn-primary btn-rounded float-right"><i class="fa fa-check"></i> Active Appointments <?php echo $act_count ?> </a>
                        <div class="col-sm-8 col-9 text-right m-b-20">
                            <a href="#" class="btn btn btn-warning btn-rounded float-right"><i class="fa fa-clock"></i> New Appointments <?php echo $pen_count ?> </a>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped custom-table">
                                <thead>
                                    <tr>
                                        <th>Appointment ID</th>
                                        <th>Patient Name</th>
                                        <!-- <th>-</th> -->
                                        <th>Phone</th>
                                        <th>Day</th>
                                        <th>Appointment Date</th>
                                        <th>Appointment Time</th>
                                        <th colspan="6">Status</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
$query = "
SELECT 
    a.id AS appointment_id,
    p.parent_name AS user_name,
    p.phone,
    c.child_name,
    v.vaccine_name,
    h.hospital_name,
    a.appointment_date,
    a.appointment_time,
    a.status
FROM 
    appointments a
JOIN 
    parents p ON a.parent_id = p.parent_id
JOIN 
    children c ON c.id = a.parent_id
JOIN 
    vaccines v ON a.vaccine_id = v.id
JOIN 
    hospitals h ON a.hospital_id = h.id
WHERE 
    a.hospital_id = $hospital_id
ORDER BY 
    a.id DESC;
";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn)); // Debugging step
}

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $formatted_time = date("h:i A", strtotime($row['appointment_time']));
        $appointment_id = $row['appointment_id'];

        echo '                                    
        <tr>
            <td>' . $appointment_id . '</td>
            <td style="text-transform:capitalize;">
                <img width="28" height="28" src="assets/img/user.jpg" class="rounded-circle m-r-5" alt=""> 
                ' . $row["user_name"] . '
            </td>
            <td style="text-transform:capitalize;">' . $row["phone"] . '</td>
            <td>' . $row["child_name"] . '</td>
            <td>' . $row["vaccine_name"] . '</td>
            <td>' . $row["hospital_name"] . '</td>
            <td>' . $row["appointment_date"] . '</td>
            <td>' . $formatted_time . '</td>
            <td>
                <span class="custom-badge status-' . 
                    ($row['status'] == 'Completed' ? 'green' : 
                    ($row['status'] == 'Pending' ? 'orange' : 
                    ($row['status'] == 'Rejected' ? 'red' : 'blue'))) . '">'
                    . $row['status'] . 
                '</span>
            </td>
        </tr>';
    }
} else {
    echo '<tr><td colspan="9">No appointments found.</td></tr>';
}?>




</div>
    </div>
    <div class="sidebar-overlay" data-reff=""></div>
    <script src="assets/js/jquery-3.2.1.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.slimscroll.js"></script>
    <script src="assets/js/select2.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script>
        $(function() {
            $('#datetimepicker3').datetimepicker({
                format: 'LT'
            });
            $('#datetimepicker4').datetimepicker({
                format: 'LT'
            });
        });
    </script>
</body>


<!-- appointments23:20-->

</html>