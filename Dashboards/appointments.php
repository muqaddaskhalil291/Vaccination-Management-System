<?php
include "../forms/dbconn.php";
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
                    <img src="assets/img/logo9.png" width="35" height="35" alt=""> <span>VMC</span>
                </a>
            </div>
            <a id="toggle_btn" href="javascript:void(0);"><i class="fa fa-bars"></i></a>
            <a id="mobile_btn" class="mobile_btn float-left" href="#sidebar"><i class="fa fa-bars"></i></a>
            <ul class="nav user-menu float-right">
                <li class="nav-item dropdown has-arrow">
                    <a href="#" class="dropdown-toggle nav-link user-link" data-toggle="dropdown">
                        <span class="user-img">
                            <img class="rounded-circle" src="assets/img/user-04.jpg" width="24" alt="Admin">
                            <span class="status online"></span>
                        </span>
                        <span>Admin</span>
                    </a>
                    <div class="dropdown-menu">
                        <!-- <a class="dropdown-item" href="profile.php">My Profile</a>
                        <a class="dropdown-item" href="edit-profile.php">Edit Profile</a> -->
                        <a class="dropdown-item" href="../forms/admin_login.php">Logout</a>
                    </div>
                </li>
            </ul>
            <div class="dropdown mobile-user-menu float-right">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <!-- <a class="dropdown-item" href="profile.php">My Profile</a>
                    <a class="dropdown-item" href="edit-profile.php">Edit Profile</a> -->
                    <a class="dropdown-item" href="../forms/admin_login.php">Logout</a>
                </div>
            </div>
        </div>



        <div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    <ul>
                        <li class="menu-title">Main</li>
                        <li class="active">
                            <a href="index.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
                        </li>
                        <li>
                            <a href="vacine.php"><i class="fa fa-user-md"></i> <span>vaccine</span></a>
                        </li>
                        <li>
                            <a href="children.php"><i class="fa fa-wheelchair"></i> <span>All childrens details</span></a>
                        </li>
                        <li>
                            <a href="appointments.php"><i class="fa fa-calendar"></i> <span>Appointments</span></a>
                        </li>
                        <li>
                            <a href="hospitals.php"><i class="fa fa-calendar-check-o"></i> <span>Hospital</span></a>
                        </li>
                       
                       
                    </ul>
                </div>
            </div>
        </div>



        <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-sm-4 col-3">
                        <h4 class="page-title">Appointments</h4>
                    </div>
                    <!-- <div class="col-sm-8 col-9 text-right m-b-20">
                        <a href="add-appointment.html" class="btn btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i> Add Appointment</a>
                    </div> -->
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
                                        <th>Doctor Name</th>
                                        <th>Department</th>
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
        p.name AS parent_name,
        h.hospital_name,
        d.name AS doctor_name,
        a.appointment_date,
        a.appointment_time,
        a.status
    FROM 
        appointments a
    JOIN 
        hospitals h ON a.hospital_id = h.id
    JOIN 
        doctors d ON a.hospital_id = d.hospital_id
    JOIN 
        parents p ON a.parent_id = p.id
    ORDER BY 
        a.appointment_date ASC
";

$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $formatted_time = date("h:i A", strtotime($row['appointment_time']));
        echo '<tr>
                <td>' . $row["appointment_id"] . '</td>
                <td style="text-transform:capitalize;"><img width="28" height="28" src="assets/img/user.jpg" class="rounded-circle m-r-5" alt=""> ' . $row["parent_name"] . '</td>
                <td style="text-transform:capitalize;">' . $row["hospital_name"] . '</td>
                <td style="text-transform:capitalize;">' . $row["doctor_name"] . '</td>
                <td>' . $row["appointment_date"] . '</td>
                <td>' . $formatted_time . '</td>
                <td><span class="custom-badge status-' . ($row['status'] == 'Completed' ? 'green' : ($row['status'] == 'Pending' ? 'orange' : ($row['status'] == 'Rejected' ? 'red' : 'blue'))) . '">' . $row['status'] . '</span></td>
            </tr>';
    }
} else {
    echo '<tr>
            <td colspan="7">No appointments found.</td>
        </tr>';
}
?>

</tbody>


                                </tbody>
                            </table>
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