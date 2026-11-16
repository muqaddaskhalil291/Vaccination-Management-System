<?php

  session_start();

  $showalert = false;


  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      include "../forms\dbconn.php";

      $sql = "SELECT * FROM `admins`";
$result = mysqli_query($conn, $sql);

$email = $_POST['email'];
$password = $_POST['password'];





if (mysqli_num_rows($result) > 0) {

    while ($row = mysqli_fetch_assoc($result)) {
      $admin_id = $row["admin_id"];
       $user_email = $row["email"];
       $username = $row["username"];
      $user_password = $row["password"];

      if ($email == $user_email && $password == $user_password) {
         $_SESSION['loggedin'] = true;
         $_SESSION['username'] = $user_name;
         $_SESSION['useremail'] = $user_email;
         $_SESSION['userid'] = $user_id;
         header("location: ../Dashboards\index.php");
         exit();
      }else{
         $showalert = "Invalid Email Or Password !";
      }
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
  <title>Care - Admin-Login</title>

  <!-- CSS -->
  <link rel="stylesheet" href="../assest\css\login.css">

  <!-- Boxicons CSS -->
  <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

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
  <section class="container forms">
    <div class="form login">

    <?php
    if($showalert){
                echo'<div class="alert alert-danger alert-dismissible  show" role="alert">
                     <strong>Error!</strong> '.$showalert.'
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                             </button>
                                </div>';
    }
         ?>

      <div class="form-content">
        <header>Admin - Login</header>
        <form  method="POST">
          <div class="field input-field">
            <input type="email" placeholder="Email" class="input" name="email" required>
          </div>

          <div class="field input-field">
            <input type="password" placeholder="Password" class="password" name="password" required>
            <i class='bx bx-hide eye-icon'></i>
          </div>

          <div class="form-link">
            <a href="#" class="forgot-pass">Forgot password?</a>
          </div>

          <div class="field button-field">
            <button>Login</button>
          </div>
        </form>
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