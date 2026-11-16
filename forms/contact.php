<?php
include 'forms/dbconn.php ';


// Prepare and bind
$stmt = $conn->prepare("INSERT INTO contact_us (name, email, subject, message) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $subject, $message);

// Set parameters and execute
$name = $_POST['name'];
$email = $_POST['email'];
$subject = $_POST['subject'];
$message = $_POST['message'];
$stmt->execute();

// echo "Message has been saved.";

$showalert = "Message Sent";
header('Location: ../index.php#contact');
$stmt->close();
$conn->close();
?>
