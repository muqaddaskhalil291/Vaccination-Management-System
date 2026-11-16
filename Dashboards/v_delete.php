<?php
include "../forms/dbconn.php";
$d_id = $_GET['id'];
echo $d_id;

$sql = "DELETE FROM vaccine WHERE vaccine_id = $d_id";
$result = mysqli_query($conn,$sql);

header ("location:vacine.php");


?>