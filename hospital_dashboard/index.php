<?php

session_start();
include "../forms/dbconn.php";

$showalert = false;
$x = "danger";
$y = "Error!";

// Hospital login session
if (!isset($_SESSION['hospital_loggedin']) || $_SESSION['hospital_loggedin'] !== true) {
    header("location: login.php");
    exit;
}

$hospital_id = $_SESSION['hospital_id'];
$hospital_name = $_SESSION['hospital_name'];

// Check if any doctor has set availability
$alert_sql = "
    SELECT da.* FROM doctor_availability da
    INNER JOIN doctors d ON da.doctor_id = d.id
    WHERE d.hospital_id = $hospital_id
";
$alert_run = mysqli_query($conn, $alert_sql);
$alert_row = mysqli_fetch_assoc($alert_run);

if (!$alert_row) {
    $showalert = 'No doctors have set their availability yet. Please <a href="edit-schedule.php">create a schedule</a> so patients can book appointments.';
    $x = "warning";
    $y = "Warning!";
}

// Count appointments based on status
$completed_sql = "SELECT COUNT(*) FROM appointments WHERE status = 'Completed' AND hospital_id = $hospital_id";
$completed_result = mysqli_query($conn, $completed_sql);
$c_count = mysqli_fetch_row($completed_result)[0];

$rejected_sql = "SELECT COUNT(*) FROM appointments WHERE status = 'Rejected' AND hospital_id = $hospital_id";
$rejected_result = mysqli_query($conn, $rejected_sql);
$r_count = mysqli_fetch_row($rejected_result)[0];

// Fetch next appointment details
$status_sql = "
    SELECT a.appointment_date, a.appointment_time, p.parent_name 
    FROM appointments a
    INNER JOIN parents p ON a.parent_id = p.parent_id
    WHERE a.status = 'Active' AND a.hospital_id = $hospital_id
    ORDER BY a.appointment_date ASC LIMIT 1
";
$status_result = mysqli_query($conn, $status_sql);
$next_appointment = mysqli_fetch_assoc($status_result);

$appointment_date = $next_appointment['appointment_date'] ?? "N/A";
$formatted_time = isset($next_appointment['appointment_time']) ? date("h:i A", strtotime($next_appointment['appointment_time'])) : "N/A";
$patient_name = $next_appointment['parent_name'] ?? "No upcoming appointments.";

?>

<!DOCTYPE html>
<html lang="en">



<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link href="img/favicon.png" rel="icon">

    <title>VMC - My Dashboard</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
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


        #sts {
            transition: all 0.3s ease !important;
            /* Smooth transition */
            cursor: pointer;
            /* Change cursor on hover */

        }

        #sts:hover::after {
            content: " \f06e";
            /* Unicode for Font Awesome 'eye' icon */
            font-family: "Font Awesome 5 Free";
            /* Ensure Font Awesome is included */
            font-weight: 900;
            /* Required for some Font Awesome icons */

        }
    </style>
    <style>
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            margin-top: 8px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        .alert .close {
            color: #721c24;
            font-size: 1.5rem;
            line-height: 1;
            border: none;
            background: none;
            cursor: pointer;
        }

        .alert .close:hover {
            color: #f8d7da;
        }

        .fade {
            display: none;
            opacity: 0;
            transition: opacity 0.15s linear;
        }

        .show {
            opacity: 1;
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
            <div class="col-12">
                <?php if ($showalert) { ?>
                    <div class="alert alert-<?php echo $x; ?> alert-dismissible show" role="alert">
                        <strong><?php echo $y; ?></strong> <?php echo $showalert; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php } ?>

                <div class="dash-widget">
                    <h3 class="text-primary">Welcome, <b class="text-dark text-capitalize"><?php echo $hospital_name; ?></b></h3>

                    <?php if ($appointment_date !== "N/A") { ?>
                        <p style="font-size:1.2em;" class="text-dark">
                            Your next appointment is on <span class="text-light p-2 bg-primary rounded"> <?php echo $appointment_date; ?> </span>
                            <span class="text-light p-2 bg-primary rounded m-2"><?php echo $formatted_time; ?></span> With Patient - <?php echo $patient_name; ?>
                        </p>
                    <?php } else { ?>
                        <p style="font-size:1.2em;" class="text-dark">You have no upcoming appointments. Please check back soon.</p>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Upcoming Appointments -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title d-inline-block">Upcoming Appointments</h4> 
                        <a href="appointments.php" class="btn btn-primary float-right">View all</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <tbody>
                                    <?php
                                    $query = "
                                        SELECT p.parent_name, p.phone, a.appointment_date, a.appointment_time, a.status
                                        FROM appointments a
                                        INNER JOIN parents p ON a.parent_id = p.parent_id
                                        WHERE a.hospital_id = $hospital_id AND a.status = 'Active'
                                        ORDER BY a.appointment_date ASC
                                        LIMIT 5;
                                    ";
                                    $result = mysqli_query($conn, $query);

                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo '<tr>
                                                <td>
                                                    <h2 style="text-transform:capitalize;"><a href="#">' . $row["parent_name"] . '<span>Phone: ' . $row["phone"] . '</span></a></h2>
                                                </td>
                                                <td>
                                                    <h5 class="time-title p-0">Timing</h5>
                                                    <p>' . date("h:i A", strtotime($row['appointment_time'])) . ' | <span>' . $row['appointment_date'] . '</span></p>
                                                </td>
                                                <td class="text-right">
                                                    <span class="btn btn-outline-' . ($row['status'] == 'Completed' ? 'success' : ($row['status'] == 'Pending' ? 'warning' : ($row['status'] == 'Rejected' ? 'danger' : 'primary'))) . '">' . $row['status'] . '</span>
                                                </td>
                                            </tr>';
                                        }
                                    } else {
                                        echo "<tr><td colspan='3'>No upcoming appointments.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Statistics -->
        <div class="row">
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="dash-widget">
                    <span class="dash-widget-bg2"><i class="fa fa-check-circle" aria-hidden="true"></i></span>
                    <div class="dash-widget-info text-right">
                        <h3><?php echo $c_count; ?></h3>
                        <span class="widget-title2">Completed <i class="fa fa-check"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="dash-widget">
                    <span class="dash-widget-bg1 bg-danger"><i class="fa fa-times-circle"></i></span>
                    <div class="dash-widget-info text-right">
                        <h3><?php echo $r_count; ?></h3>
                        <span class="widget-title1 bg-danger">Rejected <i class="fa fa-times-circle"></i></span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


            </div>
        </div>
        <div class="sidebar-overlay" data-reff=""></div>
        <script src="assets/js/jquery-3.2.1.min.js"></script>
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/Chart.bundle.js"></script>
        <script src="assets/js/chart.js"></script>
        <script src="assets/js/app.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


        <script>
            document.querySelector('.close').addEventListener('click', function() {
                var alert = this.closest('.alert');
                alert.classList.remove('show');
                alert.classList.add('fade');
            });
        </script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

</body>



</html>