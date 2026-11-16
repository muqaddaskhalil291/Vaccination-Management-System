<?php

include "../forms/dbconn.php";
$d_id = $_GET['id'];

$sql = "UPDATE `appointments` SET `status`='Active' WHERE appointment_id = '$d_id'";
$result = mysqli_query($conn,$sql);

header ("location:appointments.php");

?>