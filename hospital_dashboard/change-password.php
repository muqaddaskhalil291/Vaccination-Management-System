<?php
session_start();

if (!isset($_SESSION['hospital_loggedin']) || $_SESSION['hospital_loggedin'] != true) {
    header("location: login.php");
    exit;
}

$hospital_id = $_SESSION['hospital_id'];
$hospital_name = $_SESSION['hospital_name'];
$hospital_logo = $_SESSION['hospital_logo']; 

$showalert = false;
$x = "danger";
$y = "Error ! ";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "../forms/dbconn.php";

    $sql = "SELECT * FROM `hospitals` WHERE id = '$hospital_id';";
    $result = mysqli_query($conn, $sql);

    $old_password = $_POST['old_password'];
    $password = $_POST['password'];
    $c_password = $_POST['c_password'];

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $hospital_password = $row["password"];

        // Verify hashed password
        if (password_verify($old_password, $hospital_password)) {
            if ($password == $c_password) {
                if (strlen($password) >= 8) {
                    $x = "success";
                    $y = "Success";
                    $hash = password_hash($password, PASSWORD_DEFAULT);

                    $sql = "UPDATE `hospitals` SET `password`='$hash' WHERE `id`='$hospital_id'";
                    mysqli_query($conn, $sql);
                    $showalert = "Password Changed Successfully!";
                } else {
                    $showalert = "Password must be at least 8 characters long!";
                }
            } else {
                $showalert = "Passwords do not match!";
            }
        } else {
            $showalert = "Wrong Password!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">
    <title>Care - Change Password</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">

    <style>
        .alert {
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
        .alert .close {
            color: #721c24;
            font-size: 1.5rem;
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
                <a href="index.php" class="logo">
                    <img src="assets/img/logo.png" width="35" height="35" alt=""> <span>Care</span>
                </a>
            </div>
            <a id="toggle_btn" href="javascript:void(0);"><i class="fa fa-bars"></i></a>
            <a id="mobile_btn" class="mobile_btn float-left" href="#sidebar"><i class="fa fa-bars"></i></a>
            <ul class="nav user-menu float-right">
                <li class="nav-item dropdown has-arrow">
                    <a href="#" class="dropdown-toggle nav-link user-link" data-toggle="dropdown">
                        <span class="user-img">
                            <img class="rounded-circle" src="assets/images/<?php echo $hospital_logo ?>" width="24" alt="Hospital">
                            <span class="status online"></span>
                        </span>
                        <span style="text-transform:capitalize;"><?php echo $hospital_name ?></span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="profile.php">My Profile</a>
                        <a class="dropdown-item" href="login.php">Logout</a>
                    </div>
                </li>
            </ul>
            <div class="dropdown mobile-user-menu float-right">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="profile.php">My Profile</a>
                    <a class="dropdown-item" href="login.php">Logout</a>
                </div>
            </div>
        </div>

        <div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    <ul>
                        <li class="menu-title">Hospital Dashboard</li>
                        <li class="active">
                            <a href="index.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
                        </li>
                        <li>
                            <a href="profile.php"><i class="fa fa-hospital-o"></i> <span>Hospital Profile</span></a>
                        </li>
                        <li>
                            <a href="appointments.php"><i class="fa fa-calendar"></i> <span>Appointments</span></a>
                        </li>
                        <li>
                            <a href="doctors.php"><i class="fa fa-user-md"></i> <span>Doctors</span></a>
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
                    <div class="col-md-6 offset-md-3">
                        <?php
                        if ($showalert) {
                            echo '<div class="alert alert-' . $x . ' alert-dismissible show" role="alert">
                            <strong>' . $y . '</strong> ' . $showalert . '
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
</body>
</html>
