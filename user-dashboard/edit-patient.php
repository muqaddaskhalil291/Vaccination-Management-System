<?php
$showalert = false;
$x = "danger";
$y = "Error ! ";
include "../forms/dbconn.php";
$patient_id = intval($_GET['id']);
$query = "SELECT * FROM `user_info` WHERE user_id = $patient_id;";
$result = mysqli_query($conn, $query);
// $patient = mysqli_fetch_assoc($result); // Fetch the patient's data

while ($patient = mysqli_fetch_assoc($result)) {
    $user_id = $patient['user_id'];
    $user_name = $patient['Username'];
    $user_email = $patient['Email'];
    $user_password = $patient['Password'];
    $user_dob = $patient['D_O_B'];
    $user_gender = $patient['Gender'];
    $user_number = $patient['Number'];
    $user_address = $patient['Address'];
    $user_city = $patient['City'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userid = $_POST['userid'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    // $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $password = $_POST['password'];
    $c_password = $_POST['cpassword'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $number = $_POST['phone'];
    $city = $_POST['city'];

    $exist = true;


    //     $sql = "SELECT Email FROM `user_info` WHERE Email = '$email';";
    // $result = mysqli_query($conn, $sql);

    // if (mysqli_num_rows($result) > 0) {
    // $exist = false;
    // }

    if ($exist) {


        if (strlen($password) >= 8) {

            if ($password == $c_password) {

                $update_query = "UPDATE `user_info` SET `Username`='$name',`Email`='$email',`Password`='$password',`Number`=$number,`D_O_B`='$dob',`City`='$city',`Address`='$address' WHERE `user_id` = $userid";

                $result = mysqli_query($conn, $update_query);

                if ($result) {
                    $x = "success";
                    $y = "Success";
                    $showalert = "The Record Updated Successfully";
                } else {
                    // echo "Registration failed!". mysqli_error($conn);
                    $showalert = "There is some error in updating data !";
                }
            } else {
                $showalert = "Password doesn't match !";
            }
        } else {
            $showalert = "Password is at least 8 characters long";
        }
    } else {
        $showalert = "Email already exist!";
    }
};

?>


<!DOCTYPE html>
<html lang="en">


<!-- edit-patient24:07-->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">
    <title>VMC - Edit Pateint</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <!--[if lt IE 9]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
	<![endif]-->


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

        <div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    <ul>
                        <li class="menu-title">Main</li>
                        <li>
                            <a href="index.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
                        </li>
                        <li>
                            <a href="doctors.php"><i class="fa fa-user-md"></i> <span>Doctors</span></a>
                        </li>
                        <li class="active">
                            <a href="patients.php"><i class="fa fa-wheelchair"></i> <span>Patients</span></a>
                        </li>
                        <li>
                            <a href="appointments.php"><i class="fa fa-calendar"></i> <span>Appointments</span></a>
                        </li>
                        <li>
                            <a href="schedule.html"><i class="fa fa-calendar-check-o"></i> <span>Doctor Schedule</span></a>
                        </li>
                        <li>
                            <a href="departments.php"><i class="fa fa-hospital-o"></i> <span>Departments</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">

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



                        <h4 class="page-title">Edit Patient</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <form method="POST" id="edit-patient-form">

                            <div class="row">

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Full Name</label>
                                        <input class="form-control" required type="text" name="name" value="<?php echo $user_name; ?>">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Email </label>
                                        <input class="form-control" required type="email" name="email" value="<?php echo $user_email; ?>">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input class="form-control" required type="password" name="password" value="<?php echo $user_password; ?>">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Confirm Password</label>
                                        <input class="form-control" required type="password" name="cpassword" value="<?php echo $user_password; ?>">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Date of Birth</label>
                                        <div class="cal-icon">
                                            <input type="text" class="form-control" required name="dob" value="<?php echo $user_dob; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 col-lg-3">
                                    <div class="form-group">
                                        <label>ID</label>
                                        <input class="form-control" required type="text" name="userid" value="<?php echo $user_id; ?>" readonly>
                                    </div>
                                </div>


                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input type="text" class="form-control " required name="address" value="<?php echo $user_address; ?>">
                                            </div>
                                        </div>


                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Phone </label>
                                                <input class="form-control" required type="number" name="phone" value="<?php echo $user_number; ?>"
                                                    pattern="[0-9]{11}" maxlength="11">
                                            </div>
                                        </div>




                                        <div class="col-sm-6 col-md-6 col-lg-3">
                                            <div class="form-group">
                                                <label>City</label>
                                                <input type="text" class="form-control" required name="city" value="<?php echo $user_city; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-t-20 text-center">
                                    <button class="btn btn-primary submit-btn">Update</button>
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


<!-- edit-patient24:07-->

</html>