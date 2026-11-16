<?php
// Start the session
session_start();

// Clear doctor-related session variables if logged in
if (isset($_SESSION['doctor_loggedin'])) {
    unset($_SESSION['doctor_loggedin']);
    unset($_SESSION['doctor_name']);
    unset($_SESSION['doctor_email']);
    unset($_SESSION['doctor_id']);
    unset($_SESSION['doctor_pic']);
    unset($_SESSION['status']);
}

$showalert = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "../forms/dbconn.php";

    $email = trim($_POST['email']);
    $number = trim($_POST['number']);
    $password = trim($_POST['password']);

    // Validate email and phone number
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($number) && !empty($password)) {
        $sql = "SELECT * FROM `doctors` WHERE `email` = ? AND `phone` = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $email, $number);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $new_password = password_hash($password, PASSWORD_DEFAULT);
            $update_sql = "UPDATE `doctors` SET `password` = ? WHERE `email` = ?";
            $update_stmt = mysqli_prepare($conn, $update_sql);
            mysqli_stmt_bind_param($update_stmt, "ss", $new_password, $email);
            if (mysqli_stmt_execute($update_stmt)) {
                header("Location: login.php");
                exit();
            } else {
                $showalert = "An error occurred while updating the password.";
            }
        } else {
            $showalert = "Invalid Email or Phone Number!";
        }
    } else {
        $showalert = "Please fill all fields correctly!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Care - Forgot Password</title>

  <!-- CSS -->
  <link rel="stylesheet" href="../assets/css/login.css">

  <!-- Boxicons CSS -->
  <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

  <style>
    .alert {
      padding: 15px;
      margin-bottom: 10px;
      border-radius: 4px;
    }

    .alert-danger {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    .alert .close {
      color: #721c24;
      font-size: 1.5rem;
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
  <section class="container forms">
    <div class="form login">

      <!-- Alert Message -->
      <?php
      if ($showalert) {
          echo '<div class="alert alert-danger alert-dismissible show" role="alert">
                  <strong>Oops!</strong> ' . htmlspecialchars($showalert) . '
                  <button type="button" class="close" aria-label="Close">
                    <span>&times;</span>
                  </button>
                </div>';
      }
      ?>

      <div class="form-content">
        <header>Reset Password</header>
        <form action="forget_password.php" method="POST">
          <div class="field input-field">
            <input type="email" placeholder="Email" class="input" name="email" required>
          </div>
          <div class="field input-field">
            <input type="text" placeholder="Phone Number" class="input" name="number" required>
          </div>
          <div class="field input-field">
            <input type="password" placeholder="New Password" class="password" name="password" required>
            <i class='bx bx-hide eye-icon'></i>
          </div>
          <div class="field button-field">
            <button type="submit">Reset Password</button>
          </div>
        </form>
      </div>
    </div>
  </section>

  <!-- JavaScript -->
  <script>
    document.querySelectorAll('.close').forEach(function(closeButton) {
      closeButton.addEventListener('click', function() {
        var alert = this.closest('.alert');
        alert.classList.remove('show');
        alert.classList.add('fade');
      });
    });
  </script>
</body>

</html>
