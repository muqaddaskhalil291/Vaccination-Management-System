<?php
$showalert = false;
$x = "danger";
$y = "Error ! ";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "../forms/dbconn.php";

    $role = $_POST['role']; // Role select kiya gaya

    // Common fields
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $c_password = $_POST['cpassword'];
    $number = $_POST['number'];
    $address = $_POST['address'];
    $city = $_POST['city'];

    // Password validation
    if (strlen($password) < 8) {
        $showalert = "Password must be at least 8 characters long!";
    } elseif ($password !== $c_password) {
        $showalert = "Passwords do not match!";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        if ($role == "parent") {
            // Parent Registration
            $dob = $_POST['dob'];
            $gender = $_POST['gender'];

            // Check if email already exists in users table
            $check_sql = "SELECT email FROM `users` WHERE email = '$email'";
            $result = mysqli_query($conn, $check_sql);

            if (mysqli_num_rows($result) > 0) {
                $showalert = "Email already exists!";
            } else {
                $sql = "INSERT INTO `users`(`user_name`, `email`, `password`, `number`, `gender`, `DOB`, `city`, `address`, `time_created`, `role`) 
                        VALUES ('$name', '$email', '$hash', '$number', '$gender', '$dob', '$city', '$address', current_timestamp(), '$role')";

                if (mysqli_query($conn, $sql)) {
                    header('Location: login.php');
                    exit();
                } else {
                    $showalert = "Registration failed! " . mysqli_error($conn);
                }
            }
        }elseif ($role == "hospital") {
            // Hospital Registration
            $hospital_name = $_POST['hospital_name'];
            $license_no = $_POST['license_no'];
        
            // Check if email already exists in hospitals table
            $check_sql = "SELECT email FROM `hospitals` WHERE email = '$email'";
            $result = mysqli_query($conn, $check_sql);
        
            if (mysqli_num_rows($result) > 0) {
                $showalert = "Email already exists!";
            } else {
                // Corrected SQL Query
                $sql = "INSERT INTO `hospitals`(`hospital_name`, `email`, `password`, `license_no`, `contact`, `address`, `city`, `status`) 
                VALUES ('$hospital_name', '$email', '$hash', '$license_no', '$number', '$address', '$city', 'pending')";
        
                if (mysqli_query($conn, $sql)) {
                    header('Location: ../hospital_dashboard/login.php');
                    exit();
                } else {
                    $showalert = "Hospital Registration failed! " . mysqli_error($conn);
                }
            }
           } else {
            $showalert = "Invalid role selection!";
        }
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="../User-Dashboard/assets/img/favicon.ico">

    <!----======== CSS ======== -->
    <link rel="stylesheet" href="../assest/css/registration.css">

    <!----===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">


    <title>Registration</title>

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
        


        @keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

form {
  animation: fadeInUp 1s ease-in-out;
}


    </style>

</head>

<body>


    <div class="container">
        <header>Registration</header>






        <form action="#" method="POST">
    <div class="fields">
        <div class="input-field">
            <label>Full Name</label>
            <input type="text" placeholder="Enter your name" name="name" required>
        </div>

        <!-- <div class="input-field">
            <label>Date of Birth</label>
            <input type="date" name="dob" required>
        </div> -->

        <div class="input-field">
            <label>Mobile Number</label>
            <input type="text" placeholder="Enter mobile number" name="number" required pattern="[0-9]{11}" maxlength="11">
        </div>

        <div class="input-field">
            <label>Email</label>
            <input type="email" placeholder="Enter your email" name="email" required>
        </div>

        <div class="input-field">
            <label>Password</label>
            <input type="password" placeholder="Enter password" name="password" required>
        </div>

        <div class="input-field">
            <label>Confirm Password</label>
            <input type="password" placeholder="Confirm password" name="cpassword" required>
        </div>

        <!-- <div class="input-field">
            <label>Gender</label>
            <select name="gender" required>
                <option disabled selected>Select gender</option>
                <option>Male</option>
                <option>Female</option>
                <option>Others</option>
            </select>
        </div> -->

        <div class="input-field">
            <label>Address</label>
            <input type="text" placeholder="Enter Your Address" name="address" required>
        </div>

        <div class="input-field">
            <label>City</label>
            <input type="text" placeholder="Enter Your City" name="city" required>
        </div>
<!-- 
        <div class="input-field">
            <label>Role</label>
            <select name="role" required>
                <option disabled selected>Select Role</option>
                <option value="parent">Parent</option>
                <option value="hospital">Hospital</option>
            </select>
        </div> -->
        <div class="input-field">
        <label for="role">Select Role</label>
    <select id="role" name="role" required onchange="toggleHospitalFields()">
        <option value="parent">Parent</option>
        <option value="hospital">Hospital</option>
    </select>
</div>
    <!-- Parent Specific Fields -->
    <div id="parentFields" >
        <div class="input-field" style="width: 250px">
            <label>Date of Birth</label>
            <input type="date" name="dob">
        </div>

        <div class="input-field" style="width: 250px">
            <label>Gender</label>
            <select name="gender">
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
        </div>
    </div>

    <!-- Hospital Specific Fields -->
    <div id="hospitalFields" style="display: none;">
        <div class="input-field"style="width: 250px">
            <label>Hospital Name</label>
            <input type="text" placeholder="Enter Hospital Name" name="hospital_name">
        </div>

        <div class="input-field"style="width: 250px">
            <label>License Number</label>
            <input type="text" placeholder="Enter License Number" name="license_no">
        </div>
    </div>


        <button type="submit">Register Now</button>
    </div>
</form>

    </div>

    <script>
        document.querySelector('.close').addEventListener('click', function() {
            var alert = this.closest('.alert');
            alert.classList.remove('show');
            alert.classList.add('fade');
        });
    </script>
  <script>
        function toggleHospitalFields() {
            var role = document.getElementById("role").value;
            var hospitalFields = document.getElementById("hospitalFields");
            var parentFields = document.getElementById("parentFields");

            if (role === "hospital") {
                hospitalFields.style.display = "block";  // Hospital fields show
                parentFields.style.display = "none";  // Parent fields hide
            } else {
                hospitalFields.style.display = "none";  // Hospital fields hide
                parentFields.style.display = "block";  // Parent fields show
            }
        }
    </script>
</body>

</html>