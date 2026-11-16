<?php
session_start();
include "forms/dbconn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $parent_id = $_POST['user_id'];
    $child_id = $_POST['child_id'];
    $hospital_id = $_POST['hospital_id'];
    $vaccine_id = $_POST['vaccine_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];

    $query = "INSERT INTO appointments (parent_id, child_id, hospital_id, vaccine_id, appointment_date, appointment_time, status, created_at) 
              VALUES (?, ?, ?, ?, ?, ?, 'Pending', NOW())";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iiiiis", $parent_id, $child_id, $hospital_id, $vaccine_id, $appointment_date, $appointment_time);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: take_appointment.php?success=1");
        exit();
    } else {
        header("Location: take_appointment.php?error=1");
        exit();
    }
}
?>
