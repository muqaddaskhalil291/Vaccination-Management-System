<?php


session_start();

if (($_SESSION['loggedin'] != true)) {
    header("location: ../forms/login.php");
    exit;
}

$user_id = $_SESSION['userid'];


$showalert = false;
$x = "danger";
$y = "<i class='fa fa-lock'></i>  ";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "../forms/dbconn.php";

    $sql = "SELECT * FROM `user_info` WHERE user_id = '$user_id';";
    $result = mysqli_query($conn, $sql);


    $old_password = $_POST['old_password'];
    $password = $_POST['password'];
    $c_password = $_POST['c_password'];





    if (mysqli_num_rows($result) > 0) {

        while ($row = mysqli_fetch_assoc($result)) {
            $user_password = $row["Password"];
            $user_name = $row['Username'];


            if (password_verify($old_password, $user_password)) {
                if ($password == $c_password) {

                    if (strlen($password) >= 1) {
                        $x = "success";
                        $y = "<i class='fa fa-key'></i>  ";
                        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

                        $sql = "UPDATE `user_info` SET `Password`='$hash' WHERE `user_id`='$user_id'";
                        mysqli_query($conn, $sql);
                        $showalert = "Password Changed Successfully!";
                    } else {
                        $showalert = "Password must be at least 8 characters long!";
                    }
                } else {
                    $showalert = "Passwords do not match!";
                }
            } else {
                $showalert = "Wrong Password !";
            }
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">


<!-- change-password24:06-->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">
    <title>VMC - Change Password</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <!--[if lt IE 9]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
	<![endif]-->

    <style>
        .alert {
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 4px;
            /* margin-top:12px; */
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
  <!-- Header -->
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
                            <img class="rounded-circle" src="assets/img/user.jpg" width="24" alt="Admin">
                            <span class="status online"></span>
                        </span>
                        <span><?php echo $user_name; ?></span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="profile.php">My Profile</a>
                        <a class="dropdown-item" href="edit-profile.php">Edit Profile</a>
                        <a class="dropdown-item" href="../forms/login.php">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
        <!-- /Header -->

        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    <ul>
                        <li class="menu-title">My Dashboard</li>
                        <li class="active"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                        <li><a href="profile.php"><i class="fa fa-user-circle"></i> Profile</a></li>
                        <li><a href="appointments.php"><i class="fa fa-calendar"></i> Appointments</a></li>
                        <li><a href="children.php"><i class="fa fa-user-md"></i> Children Detail</a></li>
                        <li><a href="../forms/login.php"><i class="fa fa-sign-out"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Sidebar -->


        <div class="page-wrapper">
            <div class="content">
                <div class="row">


                    <div class="col-md-6 offset-md-3">

                        <?php
                        if ($showalert) {
                            echo '<div class="alert alert-'.$x.' alert-dismissible  show" role="alert">
                     <strong>'.$y.'</strong> ' . $showalert . '
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                             </button>
                                </div>';
                        }
                        ?>

                        <h4 class="page-title">Change Password</h4>
                        <form action="#" method="POST">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Old password</label>
                                        <input type="password" name="old_password" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>New password</label>
                                        <input type="password" name="password" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Confirm password</label>
                                        <input type="password" name="c_password" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 text-center m-t-20">
                                    <button type="submit" class="btn btn-primary submit-btn">Update Password</button>
                                </div>
                            </div>
                        </form>
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

    <script>
        document.querySelector('.close').addEventListener('click', function() {
            var alert = this.closest('.alert');
            alert.classList.remove('show');
            alert.classList.add('fade');
        });
    </script>
</body>


<!-- change-password24:06-->

</html>