<?php
session_start();

// if (($_SESSION['admin_loggedin'] != true)) {
//     header("location: login.php");
//     exit;
// }
include "../forms/dbconn.php";


// Fetch total hospitals
$hospital_query = "SELECT COUNT(*) as total FROM hospitals";
$hospital_result = mysqli_query($conn, $hospital_query);
$hospital_count = mysqli_fetch_assoc($hospital_result)['total'];

// Fetch total registered parents
$parent_query = "SELECT COUNT(*) as total FROM parents";
$parent_result = mysqli_query($conn, $parent_query);
$parent_count = mysqli_fetch_assoc($parent_result)['total'];

// Fetch total pending appointments
$pending_query = "SELECT COUNT(*) as total FROM appointments WHERE status = 'Pending'";
$pending_result = mysqli_query($conn, $pending_query);
$pending_count = mysqli_fetch_assoc($pending_result)['total'];

// Fetch total completed vaccinations
$completed_vaccination_query = "SELECT COUNT(*) as total FROM vaccination_records WHERE status = 'Completed'";
$completed_vaccination_result = mysqli_query($conn, $completed_vaccination_query);
$completed_vaccination_count = mysqli_fetch_assoc($completed_vaccination_result)['total'];
?><?php
// Query to get the total number of appointments per month
$sql = "SELECT DATE_FORMAT(appointment_date, '%Y-%m') AS month, COUNT(*) AS total_appointments 
        FROM appointments 
        GROUP BY month 
        ORDER BY month ASC";

$result = mysqli_query($conn, $sql);

$months = [];
$totals = [];

while ($row = mysqli_fetch_assoc($result)) {
    $months[] = $row['month'];
    $totals[] = $row['total_appointments'];
}

$query = "SELECT COUNT(*) AS parent_count FROM users WHERE role = 'Parent'"; 
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$parent_count = $row['parent_count'];

// Encode data for JavaScript
$months_json = json_encode($months);
$totals_json = json_encode($totals);
?>









<!DOCTYPE html>
<html lang="en">



<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">
    <title>VMC - Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="../User-Dashboard/assets/img/favicon.ico">

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
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="dash-widget">
                    <span class="dash-widget-bg1"><i class="fa fa-hospital-o" aria-hidden="true"></i></span>
                    <div class="dash-widget-info text-right">
                        <h3><?php echo $hospital_count; ?></h3>
                        <span class="widget-title1">Total Hospitals <i class="fa fa-check" aria-hidden="true"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="dash-widget">
                    <span class="dash-widget-bg2"><i class="fa fa-users"></i></span>
                    <div class="dash-widget-info text-right">
                        <h3><?php echo $parent_count; ?></h3>
                        <span class="widget-title2">Registered Parents <i class="fa fa-user" aria-hidden="true"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="dash-widget">
                    <span class="dash-widget-bg3"><i class="fa fa-stethoscope" aria-hidden="true"></i></span>
                    <div class="dash-widget-info text-right">
                        <h3><?php echo $completed_vaccination_count; ?></h3>
                        <span class="widget-title3">Completed Vaccinations <i class="fa fa-check" aria-hidden="true"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="dash-widget">
                    <span class="dash-widget-bg4"><i class="fa fa-clock-o" aria-hidden="true"></i></span>
                    <div class="dash-widget-info text-right">
                        <h3><?php echo $pending_count; ?></h3>
                        <span class="widget-title4">Pending Appointments <i class="fa fa-exclamation-circle" aria-hidden="true"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="chart-title">Appointments Total</h4>
                        <canvas id="linegraph"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
    <div class="card">
        <div class="card-body">
            <h4 class="chart-title">User Registrations</h4>
            <canvas id="userRegistrationGraph"></canvas>
        </div>
    </div>
    <?php
include "../forms/dbconn.php";

// Initialize arrays
$dates = [];
$counts = [];

// Check database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch user registration data (last 7 days)
$query = "SELECT DATE(time_created) AS reg_date, COUNT(*) AS count 
          FROM users    
          WHERE time_created >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
          GROUP BY reg_date 
          ORDER BY reg_date ASC";

$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $dates[] = date("d M", strtotime($row['reg_date'])); // Format date
        $counts[] = (int) $row['count']; // Ensure integer values
    }
} else {
    die("Query Failed: " . mysqli_error($conn)); // Show error if query fails
}

// Close database connection
mysqli_close($conn);
?>

</div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Upcoming Appointments</h4>
                        <a href="appointments.php" class="btn btn-primary float-right">View all</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                            <thead>
    <tr>
        <th>Patient Name</th>
        <th>Doctor Name</th>
        <th>Timing</th>
        <th class="text-right">Status</th>
    </tr>
</thead>
<tbody>
    <?php
    include "../forms/dbconn.php";

    // Get today's date
    $today = date('Y-m-d');

    // Query to get upcoming appointments
    $query = "SELECT a.*, p.name AS patient_name, d.name AS doctor_name 
              FROM appointments a
              JOIN parents p ON a.parent_id = p.id
              JOIN doctors d ON a.doctor_id = d.id
              WHERE a.appointment_date >= '$today'
              ORDER BY a.appointment_date ASC, a.appointment_time ASC";

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>
                    <td>' . htmlspecialchars($row['patient_name']) . '</td>
                    <td>' . htmlspecialchars($row['doctor_name']) . '</td>
                    <td>' . htmlspecialchars($row['appointment_date']) . ' at ' . htmlspecialchars($row['appointment_time']) . '</td>
                    <td class="text-right">' . htmlspecialchars($row['status']) . '</td>
                  </tr>';
        }
    } else {
        echo '<tr><td colspan="4" class="text-center">No upcoming appointments.</td></tr>';
    }
    ?>
</tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Doctors</h4>
                    </div>
<div class="card-body">
    <ul class="contact-list">
        <?php
      

        $query = "SELECT name FROM doctors";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<li>
                    <div class="contact-cont">
                        <div class="float-left user-img m-r-10">
                            <a title="' . htmlspecialchars($row['name']) . '">
                                <img alt="Doctor Image" class="rounded-circle img-fluid" style="width: 50px; height: 50px; object-fit: cover;" 
     src="' . (!empty($row['img']) && file_exists('assets/images/' . $row['img']) 
     ? 'assets/images/' . htmlspecialchars($row['img']) 
     : 'assets/img/user.jpg') . '">

                                <span class="status online"></span>
                            </a>
                        </div>
                        <div class="contact-info">
                            <span class="contact-name text-ellipsis">' . htmlspecialchars($row['name']) . '</span>
                        </div>
                    </div>
                </li>';
            }
        } else {
            echo "<li>No doctors found.</li>";
        }
        ?>
    </ul>
</div>
                    <div class="card-footer text-center">
                        <a href="doctors.php" class="text-muted">View all Doctors</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">New Parents</h4>
                        <a href="parents.php" class="btn btn-primary float-right">View all</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table">
    <thead>
        <tr>
            <th>Parent Name</th>
            <th>Email</th>
            <th>Registered On</th>
        </tr>
    </thead>
    <tbody>
        <?php
        include "../forms/dbconn.php";

        // Query to get recently registered parents (last 30 days)
        $query = "SELECT name, email, created_at FROM parents ORDER BY created_at DESC LIMIT 10";

        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>
                        <td>' . htmlspecialchars($row['name']) . '</td>
                        <td>' . htmlspecialchars($row['email']) . '</td>
                        <td>' . date("d M Y", strtotime($row['created_at'])) . '</td>
                      </tr>';
            }
        } else {
            echo '<tr><td colspan="3" class="text-center">No new parents found.</td></tr>';
        }
        ?>
    </tbody>
</table>

                            </table>
                        </div>
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
    <script src="assets/js/Chart.bundle.js"></script>
    <script src="assets/js/chart.js"></script>
    <script src="assets/js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Retrieve data from PHP
        const months = <?php echo $months_json; ?>;
        const totals = <?php echo $totals_json; ?>;

        // Render the chart
        const ctx = document.getElementById('linegraph').getContext('2d');
        const lineGraph = new Chart(ctx, {
            type: 'line',
            data: {
                labels: months, // X-axis labels (Months)
                datasets: [{
                    label: 'Total Appointments',
                    data: totals, // Y-axis data (Patient totals)
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Total Appointments'
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

<script>
    // Retrieve user registration data from PHP
    const monthsUsers = <?php echo $months_users_json; ?>;
    const totalsUsers = <?php echo $totals_users_json; ?>;

    // Render the chart for user registrations
    const ctxUsers = document.getElementById('userRegistrationGraph').getContext('2d');
    const userRegistrationGraph = new Chart(ctxUsers, {
        type: 'line',
        data: {
            labels: monthsUsers, // X-axis labels (Months)
            datasets: [{
                label: 'User Registrations',
                data: totalsUsers, // Y-axis data (User totals)
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Month'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Total Users'
                    },
                    beginAtZero: true
                }
            }
        }
    });
</script>
<?php
include "../forms/dbconn.php";

// Fetch registration data (last 7 days)
$query = "SELECT DATE(time_created) as reg_date, COUNT(*) as count 
          FROM users
          WHERE time_created >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
          GROUP BY reg_date 
          ORDER BY reg_date ASC";

$result = mysqli_query($conn, $query);

$dates = [];
$counts = [];

while ($row = mysqli_fetch_assoc($result)) {
    $dates[] = date("d M", strtotime($row['reg_date']));
    $counts[] = $row['count'];
}
?>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('userRegistrationGraph').getContext('2d');
var userRegistrationGraph = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($dates); ?>,
        datasets: [{
            label: 'User Registrations',
            data: <?php echo json_encode($counts); ?>,
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.2)',
            borderWidth: 2,
            pointRadius: 4,
            pointBackgroundColor: '#007bff',
            pointBorderColor: '#fff',
            pointHoverRadius: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false, // This prevents the chart from stretching
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

</script>

</body>



</html>