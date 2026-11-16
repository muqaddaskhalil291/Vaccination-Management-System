<?php
include "../forms/dbconn.php";

// Get the doctor ID from the URL
$d_id = $_GET['id'];

// Check if the doctor has appointments with 'Active' or 'Pending' status
$check_appointments_sql = "SELECT COUNT(*) AS count FROM appointments WHERE doctor_id = $d_id AND (status = 'Active' OR status = 'Pending')";
$check_appointments_result = mysqli_query($conn, $check_appointments_sql);

if (!$check_appointments_result) {
    // Redirect to a error-404 htmle if the query fails
    header('location: error-404.html');
    exit();
}

$appointment_data = mysqli_fetch_assoc($check_appointments_result);

// If there are active or pending appointments, redirect to an error page
if ($appointment_data['count'] > 0) {
    header('location: error-404.html');
    exit();
}

// Delete related rows in doctors_schedule first
$schedule_sql = "DELETE FROM doctors_schedule WHERE doctor_id = $d_id";
$schedule_result = mysqli_query($conn, $schedule_sql);

if (!$schedule_result) {
    // Redirect to a error-404 htmle if schedule deletion fails
    header('location: error-404.html');
    exit();
}

// Delete the doctor from the main table
$sql = "DELETE FROM doctors WHERE doctor_id = $d_id";
$result = mysqli_query($conn, $sql);

if (!$result) {
    // Redirect to a error-404 htmle if doctor deletion fails
    header('location: error-404.html');
    exit();
}

// Redirect to the doctors list page on successful deletion
header("location: doctors.php");
exit();
?>
