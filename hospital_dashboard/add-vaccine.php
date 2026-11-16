<?php
session_start();
if (!isset($_SESSION['hospital_id'])) {
    die("Unauthorized access!");
}

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vaccine_name = $_POST['vaccine_name'];
    $min_age = $_POST['min_age'];
    $max_age = $_POST['max_age'];
    // $status = $_POST['status'];
    $availability = $_POST['availability'];

// Prepare the SQL query
$query = "INSERT INTO vaccines (hospital_id, vaccine_name, min_age, max_age, availability, created_at) 
          VALUES (?, ?, ?, ?, ?, NOW())";

if ($stmt = mysqli_prepare($conn, $query)) {
    // Bind parameters correctly
    mysqli_stmt_bind_param($stmt, "isiss", $hospital_id, $vaccine_name, $min_age, $max_age, $availability);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        // Redirect with success message
        header("Location: add-vaccine.php?success=Vaccine added successfully");
        exit();
    } else {
        echo "Error: " . mysqli_stmt_error($stmt);
    }

    // Close statement
    mysqli_stmt_close($stmt);
} else {
    echo "Error preparing statement: " . mysqli_error($conn);
}

}

// Fetch existing vaccines
$query = "SELECT * FROM vaccines WHERE hospital_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $hospital_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>



<!DOCTYPE html>
<html lang="en">



<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">
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


body { font-family: Arial, sans-serif; padding: 20px; }
        .container { max-width: 800px; margin: auto; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }

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

        <div class="container mt-5">
        <h2>Manage Vaccines</h2>

<?php if (isset($_GET['success'])) { ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
<?php } ?>

<!-- Existing Vaccines -->
<h4>Existing Vaccines</h4>
<table class="table table-bordered">
    <tr>
        <th>Name</th>
        <th>Min Age (Months)</th>
        <th>Max Age (Months)</th>
        <!-- <th>Status</th> -->
        <th>Availability</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['vaccine_name']); ?></td>
            <td><?php echo htmlspecialchars($row['min_age']); ?></td>
            <td><?php echo htmlspecialchars($row['max_age']); ?></td>
            <!-- <td><?php echo htmlspecialchars($row['status']); ?></td> -->
            <td><?php echo htmlspecialchars($row['availability']); ?></td>
        </tr>
    <?php } ?>
</table>

<!-- Add New Vaccine -->
<h4>Add New Vaccine</h4>
<form method="POST">
    <div class="form-group">
        <label>Vaccine Name:</label>
        <input type="text" name="vaccine_name" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Min Age (Months):</label>
        <input type="number" name="min_age" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Max Age (years):</label>
        <input type="number" name="max_age" class="form-control" required>
    </div>
    <!-- <div class="form-group">
        <label>Status:</label>
        <select name="status" class="form-control" required>
            <option value="Available">Available</option>
            <option value="Not Available">Not Available</option>
        </select>
    </div> -->
    <div class="form-group">
        <label>Availability:</label>
        <input type="text" name="availability" class="form-control" placeholder="E.g. In Stock, Out of Stock" required>
    </div>
    <button type="submit" class="btn btn-primary">Add Vaccine</button>
</form>
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