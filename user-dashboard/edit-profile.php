<?php
  session_start();
  include "../forms/dbconn.php";

  $showalert = false;
  $x = "danger";
  $y = "Error ! ";



  if(($_SESSION['loggedin'] != true)){
    header("location: ../forms/login.php");
    exit;
    
}

$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['email'];
$user_id = $_SESSION['user_id'];


$sql = "SELECT * FROM `users` WHERE user_id = '$user_id';";
$result = mysqli_query($conn, $sql);
$data_count = mysqli_num_rows($result);

if (mysqli_num_rows($result) > 0) {
  while($row = mysqli_fetch_assoc($result)) {
      // fetch user details
      $user_name = $row['User_name'];
      $user_email = $row['email'];
      $user_phone = $row['number'];
      $user_dob = $row['DOB'];
      $user_gender = $row['gender'];
      $user_address = $row['address'];
      $user_city = $row['city'];
    //   $user_image = $row['image'];

  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userid = $_POST['userid'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    // $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $number = $_POST['phone'];
    $city = $_POST['city'];

    $exist = true;


//     $sql = "SELECT Email FROM `users` WHERE Email = '$email';";
// $result = mysqli_query($conn, $sql);

// if (mysqli_num_rows($result) > 0) {
// $exist = false;
// }

if($exist){



        $update_query="UPDATE `users` SET `User_name`='$name',`email`='$email',`number`=$number,`DOB`='$dob',`city`='$city',`address`='$address' WHERE `user_id` = '$user_id'";

        $result = mysqli_query($conn,$update_query);
        
        if ($result) {
            $x = "success";
             $y = "Success";
            $showalert = "The Record Updated Successfully";

            
        } else {
            // echo "Registration failed!". mysqli_error($conn);
            $showalert = "There is some error in updating data !";
        }


}else{
    $showalert = "Email already exist!";
}

};



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
                    <div class="col-sm-12">
                    <?php
    if($showalert){
                echo'<div class="alert alert-'.$x.' alert-dismissible  show" role="alert">
                     <strong>'.$y.'</strong> '.$showalert.'
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
                                    <img class="inline-block" src="assets/img/user.jpg" alt="user">
                                    <!-- <div class="fileupload btn">
                                        <span class="btn-text">edit</span>
                                        <input class="upload" type="file">
                                    </div> -->
                                </div>
                                <div class="profile-basic">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group form-focus">
                                                <label class="focus-label">Full Name</label>
                                                <input type="text" class="form-control floating" value="<?php echo $user_name ?>" name="name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-focus">
                                                <label class="focus-label">User ID</label>
                                                <input type="text" class="form-control floating" value="<?php echo $user_id?>" readonly name="userid" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-focus">
                                                <label class="focus-label">Birth Date</label>
                                                <div class="cal-icon">
                                                    <input class="form-control floating datetimepicker" type="text" value="<?php echo $user_dob?>" name="dob" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-focus select-focus">
                                                <label class="focus-label">Gendar</label>
                                                <select class="select form-control floating" name="gender" required>
                                                    <option value="<?php echo $user_gender?>selected">Male</option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
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
                                    <label class="focus-label">Address</label>
                                    <input type="text" class="form-control floating" value="<?php echo $user_address?>" name="address" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-focus">
                                    <label class="focus-label">City</label>
                                    <input type="text" class="form-control floating" value="<?php echo $user_city?>" name="city" required>
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
                                    <input type="email" class="form-control floating" value="<?php echo $user_email?>" name="email" readonly required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-focus">
                                    <label class="focus-label">Phone Number</label>
                                    <input type="text" class="form-control floating" value="<?php echo $user_phone?>" name="phone" required pattern="[0-9]{11}" maxlength="11">
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