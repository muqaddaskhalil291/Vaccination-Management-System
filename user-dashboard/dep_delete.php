<?php
include "../forms/dbconn.php";
$d_id = $_GET['id'];

$sql = "DELETE FROM `departments` WHERE department_id = $d_id";
$result = mysqli_query($conn,$sql);

header ("location:departments.php");

?>