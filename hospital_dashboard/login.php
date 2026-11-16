<?php

// Start the session
session_start();

// Check if the hospital is logged in and log them out
if (isset($_SESSION['hospital_loggedin'])) {
    unset($_SESSION['hospital_loggedin']);
    unset($_SESSION['hospital_name']);
    unset($_SESSION['hospital_email']);
    unset($_SESSION['hospital_id']);
    unset($_SESSION['license_no']);
    unset($_SESSION['status']);
}

$showalert = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "../forms/dbconn.php";

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch hospital details based on email
    $sql = "SELECT * FROM hospitals WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $db_password = $row["password"];
        
        if (password_verify($password, $db_password)) {
            $_SESSION['hospital_loggedin'] = true;
            $_SESSION['hospital_id'] = $row['id'];
            $_SESSION['hospital_name'] = $row['hospital_name'];
            $_SESSION['hospital_email'] = $row['email'];
            $_SESSION['license_no'] = $row['license_no'];
            $_SESSION['status'] = $row['status'];

            header("Location: index.php"); // Redirect to hospital dashboard
            exit();
        } else {
            $showalert = "Invalid Email or Password!";
        }
    } else {
        $showalert = "Hospital not found!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico"> -->
  <link rel="shortcut icon" type="image/x-icon" href="../User-Dashboard/assets/img/favicon.ico">

  <title>Care - Login</title>

  <!-- CSS -->
  <link rel="stylesheet" href="../assest\css\login.css">

  <!-- Boxicons CSS -->
  <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

  <style>
    .container{
      background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url(assets/img/doctors_login.jpg);
            background-repeat: no-repeat;
      background-size: cover;
      background-position: center;
    }
    
    .form{
      background-color: rgba(255, 255, 255, 0.5); /* White with 50% transparency */

    }

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
  animation: fadeInUp 1.3s ease-in-out;
}

  </style>


</head>

<body>
  <section class="container forms">
    <div class="form login">

      <?php
      if ($showalert) {
        echo '<div class="alert alert-danger alert-dismissible  show" role="alert">
                     <strong>Error!</strong> ' . $showalert . '
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                             </button>
                                </div>';
      }
      ?>

      <div class="form-content">
        <header>Login</header>
        <form action="login.php" method="POST">
          <div class="field input-field">
            <input type="email" placeholder="Email" class="input" name="email" required>
          </div>

          <div class="field input-field">
            <input type="password" placeholder="Password" class="password" name="password" required>
            <!-- <i class='bx bx-hide eye-icon'></i> -->
          </div>

          <div class="form-link">
            <a href="forget_password.php" class="forgot-pass">Forgot password?</a>
          </div>

          <div class="field button-field">
            <button>Login</button>
          </div>
        </form>

        <!-- <div class="form-link">
          <span>Don't have an account? <a href="registration.php" class="link signup-link">Register Now</a></span>
        </div> -->
      </div>

    </div>


  </section>

  <!-- JavaScript -->
  <script>
    document.querySelector('.close').addEventListener('click', function() {
      var alert = this.closest('.alert');
      alert.classList.remove('show');
      alert.classList.add('fade');
    });
  </script>
</body>

</html>