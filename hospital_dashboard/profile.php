<?php
session_start();
include "../forms/dbconn.php";

// Check if the hospital is logged in
if (!isset($_SESSION['hospital_loggedin']) || $_SESSION['hospital_loggedin'] !== true) {
    header("location: login.php");
    exit;
}

// Get hospital details from session
$hospital_id = $_SESSION['hospital_id'];
$hospital_name = $_SESSION['hospital_name'];

// Fetch hospital details from the database
$sql = "SELECT * FROM `hospitals` WHERE id = ?;";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $hospital_id);
$stmt->execute();
$result = $stmt->get_result();

// Initialize variables to avoid undefined errors
$hospital_email = "";
$hospital_phone = "";
$hospital_address = "";
$hospital_city = "";
$hospital_status = "";
$hospital_license = "";

if ($row = $result->fetch_assoc()) {
    $hospital_email = $row['email'];
    $hospital_phone = $row['contact'];
    $hospital_address = $row['address'];
    $hospital_city = $row['city'];
    $hospital_status = $row['status'];
    $hospital_license = $row['license_no'];
}

$stmt->close();
?>



<!DOCTYPE html>
<html lang="en">


<!-- profile22:59-->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">
    <title>VMC - Profile</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!--[if lt IE 9]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
	<![endif]-->
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
            <div class="col-sm-7 col-6">
                <h4 class="page-title">Hospital Profile</h4>
            </div>
            <div class="col-sm-5 col-6 text-right m-b-30">
                <a href="login.php" class="btn btn-primary btn-rounded"><i class="fa fa-sign-out"></i> Logout</a>
            </div>
        </div>

        <div class="card-box profile-header">
            <div class="row">
                <div class="col-md-12">
                    <div class="profile-view">
                        <div class="profile-img-wrap">
                            <div class="profile-img">
                                <a href="#"><img class="avatar" src="assets/images/hospital.png" alt="Hospital Logo"></a>
                            </div>
                        </div>
                        <div class="profile-basic">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="profile-info-left">
                                        <h3 class="user-name m-t-0 mb-0" style="text-transform: capitalize;"><?php echo $hospital_name ?></h3>
                                        <small class="text-muted"><?php echo ($hospital_status == 'Active') ? 'Operational' : 'active'; ?></small>
                                        <div class="staff-id">LICENSE NO: <?php echo $hospital_license ?></div>
                                        <div class="staff-msg"><a href="edit-profile.php" class="btn btn-primary">Edit Profile</a></div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <ul class="personal-info">
                                        <li><span class="title">Phone:</span> <span class="text"><?php echo $hospital_phone ?></span></li>
                                        <li><span class="title">Email:</span> <span class="text"><?php echo $hospital_email ?></span></li>
                                        <li><span class="title">City:</span> <span class="text"><?php echo $hospital_city . '- Pakistan' ?></span></li>
                                        <li><span class="title">Address:</span> <span class="text"><?php echo $hospital_address ?></span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="profile-tabs">
            <div class="tab-content">
                <div class="tab-pane show active" id="about-cont">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-box">
                                <h3 class="card-title">Account Preferences</h3>
                                <div class="experience-box">
                                    <ul class="experience-list">
                                        <li>
                                            <div class="experience-user"><div class="before-circle"></div></div>
                                            <div class="experience-content">
                                                <div class="timeline-content">
                                                    <a href="change-password.php" class="btn btn-info btn-rounded"><i class="fa fa-key"></i>&nbsp;&nbsp;Change Password</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="experience-user"><div class="before-circle"></div></div>
                                            <div class="experience-content">
                                                <div class="timeline-content">
                                                    <a href="#" data-toggle="modal" data-target="#toggle_status" class="btn btn-<?php echo ($hospital_status == 'Active') ? 'warning' : 'success'; ?> btn-rounded">
                                                        <i class="fa fa-<?php echo ($hospital_status == 'Active') ? 'pause-circle' : 'play-circle'; ?>"></i>&nbsp;&nbsp;
                                                        <?php echo ($hospital_status == 'Active') ? 'Deactivate Account' : 'Activate Account'; ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="experience-user"><div class="before-circle"></div></div>
                                            <div class="experience-content">
                                                <div class="timeline-content">
                                                    <a href="#" data-toggle="modal" data-target="#delete_hospital" class="btn btn-danger btn-rounded"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete Account</a>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Hospital Modal -->
        <div id="delete_hospital" class="modal fade delete-modal" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <img src="assets/img/sent.png" alt="" width="50" height="46">
                        <h3>Are you sure you want to delete Your Account?</h3>
                        <div class="m-t-20">
                            <a href="#" class="btn btn-white" data-dismiss="modal">Close</a>
                            <a href="h_delete.php?id=<?php echo $hospital_id ?>" class="btn btn-danger">Delete</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activate/Deactivate Hospital Modal -->
        <div id="toggle_status" class="modal fade delete-modal" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <img src="assets/img/sent.png" alt="" width="50" height="46">
                        <h3>Are you sure you want to <?php echo ($hospital_status == 'Active') ? 'Deactivate' : 'Activate'; ?> Your Account?</h3>
                        <div class="m-t-20">
                            <a href="#" class="btn btn-white" data-dismiss="modal">Close</a>
                            <a href="status.php?id=<?php echo $hospital_id ?>" class="btn btn-danger">Yes</a>
                        </div>
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
        <script src="assets/js/app.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>


<!-- profile23:03-->

</html>