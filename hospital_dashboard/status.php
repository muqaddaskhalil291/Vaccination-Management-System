<?php

include "../forms/dbconn.php";


session_start();
$showalert = false;
$x = "danger";
$y = "Error ! ";
if (!isset($_SESSION['hospital_loggedin']) || $_SESSION['hospital_loggedin'] !== true) {
    header("location: login.php");
    exit;
}


$hospital_id = $_SESSION['hospital_id'];
$hospital_name = $_SESSION['hospital_name'];

$query = "SELECT `status` FROM `doctors` Where doctor_id = $doctor_id";

$result = mysqli_query($conn, $query);

$row = mysqli_fetch_assoc($result);

if ($row['status'] == 'Active' ) {
    $status = 'Inactive';
}else {
    $status = 'Active';
}


$sql = "UPDATE `doctors` SET `status`='$status' WHERE `doctor_id`='$doctor_id'";
mysqli_query($conn, $sql);

header("location: profile.php");



?>