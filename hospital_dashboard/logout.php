<?php
// Start the session
session_start();

// Check if the doctor is logged in
if (isset($_SESSION['doctor_loggedin'])) {
    // Unset only doctor-related session variables
    unset($_SESSION['doctor_loggedin']);
    unset($_SESSION['doctor_name']);
    unset($_SESSION['doctor_email']);
    unset($_SESSION['doctor_id']);
}

// Redirect to the doctor login page after logout
echo "ok"; 
// header("Location: login.php");
exit();
?>
