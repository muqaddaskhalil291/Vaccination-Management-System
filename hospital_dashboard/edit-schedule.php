<?php
session_start();
if (!isset($_SESSION['hospital_id'])) {
    die("Unauthorized access!");
}

include "../forms/dbconn.php";
$hospital_id = $_SESSION['hospital_id'];

// Define days of the week
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

// Fetch doctors list from the database
$query = "SELECT id, name, specialization, contact, email FROM doctors WHERE hospital_id = ?";
$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->bind_param("i", $hospital_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    die("Query preparation failed: " . $conn->error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $specialization = $_POST['specialization'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];

    // Insert doctor into `doctors` table
    $query = "INSERT INTO doctors (hospital_id, name, specialization, contact, email, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issss", $hospital_id, $name, $specialization, $contact, $email);
    if (!$stmt->execute()) {
        die("Error adding doctor: " . $conn->error);
    }

    // Get the last inserted doctor ID
    $doctor_id = $conn->insert_id;

    // Insert doctor availability schedule
    foreach ($days as $day) {
        $day_lower = strtolower($day);
        $start_time = $_POST[$day_lower . '_start'] ?? '';
        $end_time = $_POST[$day_lower . '_end'] ?? '';
        $enabled = isset($_POST[$day_lower . '_enabled']);

        if ($enabled && !empty($start_time) && !empty($end_time)) {
            $schedule_query = "INSERT INTO `doctor_availability`(`id`, `doctor_id`, `day`, `start_time`, `end_time`, `created_at`)  VALUES (?, ?, ?, ?, NOW())";
            $schedule_stmt = $conn->prepare($schedule_query);
            $schedule_stmt->bind_param("isss", $doctor_id, $day, $start_time, $end_time);
            if (!$schedule_stmt->execute()) {
                die("Error adding doctor availability: " . $conn->error);
            }
            $schedule_stmt->close();
        }
    }

    // Redirect to avoid form resubmission
    header("Location: edit-schedule.php?success=Doctor added successfully");
    exit();
}
$availability_query = "INSERT INTO doctor_availability (doctor_id, day, start_time, end_time, created_at) 
                       VALUES (?, ?, ?, ?, NOW())";

$stmt = $conn->prepare($availability_query);
foreach ($days as $day) {
    $day_lower = strtolower($day);
    if (isset($_POST["{$day_lower}_enabled"])) { // Check if enabled
        $start_time = $_POST["{$day_lower}_start"];
        $end_time = $_POST["{$day_lower}_end"];
        if (!empty($start_time) && !empty($end_time)) {
            $stmt->bind_param("isss", $doctor_id, $day, $start_time, $end_time);
            $stmt->execute();
        }
    }
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">


<!-- edit-schedule24:07-->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">
    <title>VMC - Schedule</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!--[if lt IE 9]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
	<![endif]-->
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 900px; margin: auto; padding-top: 20px; }
        .table { width: 100%; margin-top: 20px; }
        .form-group { margin-bottom: 15px; }
    
    
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
    <img class="rounded-circle" src="" width="24" alt="Hospital">
    <span class="status online"></span>
</span>

<!-- <span style="text-transform:capitalize;"><?php echo $hospital_name; ?></span> -->
                    <!-- </a> -->
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
        <div class="container">
    <h2>Doctors in Your Hospital</h2>
    <table class="table table-bordered">
        <tr>
            <th>Name</th>
            <th>Specialization</th>
            <th>Contact</th>
            <th>Email</th>
            <th>Schedule</th>
        </tr>
        <?php
        $query = "SELECT * FROM doctors";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['specialization']); ?></td>
                    <td><?php echo htmlspecialchars($row['contact']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td>
                        <?php
                        $doctor_id = $row['id'];
                        $schedule_query = "SELECT day, start_time, end_time FROM doctor_availability WHERE doctor_id = ?";
                        $schedule_stmt = $conn->prepare($schedule_query);
                        $schedule_stmt->bind_param("i", $doctor_id);
                        $schedule_stmt->execute();
                        $schedule_result = $schedule_stmt->get_result();

                        if ($schedule_result->num_rows > 0) {
                            while ($schedule = $schedule_result->fetch_assoc()) {
                                echo "<strong>" . htmlspecialchars($schedule['day']) . "</strong>: " . htmlspecialchars($schedule['start_time']) . " - " . htmlspecialchars($schedule['end_time']) . "<br>";
                            }
                        } else {
                            echo "No schedule found.";
                        }
                        ?>
                    </td>
                </tr>
            <?php }
        } else {
            echo "<tr><td colspan='5'>No doctors found.</td></tr>";
        }
        ?>
    </table>

    <h2>Add Doctor</h2>
    <form method="POST">
        <div class="form-group">
            <label>Doctor Name:</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Specialization:</label>
            <input type="text" name="specialization" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Contact:</label>
            <input type="text" name="contact" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <h3>Set Doctor Availability</h3>
        <?php
        foreach ($days as $day) {
            $day_lower = strtolower($day);
            echo "<div class='form-group'>
                    <label>$day:</label>
                    <input type='time' name='{$day_lower}_start' class='form-control'>
                    <input type='time' name='{$day_lower}_end' class='form-control'>
                    <input type='checkbox' name='{$day_lower}_enabled' value='1'> Enable
                  </div>";
        }
        ?>
        <button type="submit" class="btn btn-primary">Add Doctor & Set Schedule</button>
    </form>
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
    <script src="assets/js/app.js"></script>
    <!-- JavaScript for Dynamic Input Toggle -->
    <script>
        function toggleInputs(day) {
            const isEnabled = document.getElementById(`${day}_enabled`).checked;
            document.getElementById(`${day}_start`).disabled = !isEnabled;
            document.getElementById(`${day}_end`).disabled = !isEnabled;
        }
    </script>
    <script>
        $(function() {
            $('#datetimepicker3').datetimepicker({
                format: 'LT'

            });
        });
    </script>
    <script>
        $(function() {
            $('#datetimepicker4').datetimepicker({
                format: 'LT'

            });
        });
    </script>


    <script>
        document.querySelector('.close').addEventListener('click', function() {
            var alert = this.closest('.alert');
            alert.classList.remove('show');
            alert.classList.add('fade');
        });
    </script>
</body>


<!-- edit-schedule24:07-->

</html>