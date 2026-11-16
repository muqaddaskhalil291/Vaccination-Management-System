<?php
include "../forms/dbconn.php";
$d_id = $_GET['id'];
// echo $d_id;

$sql = "DELETE FROM user_info WHERE user_id = $d_id";
$result = mysqli_query($conn,$sql);

header ("location:../forms/login.php");


?>