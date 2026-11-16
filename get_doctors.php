<?php
include "forms/dbconn.php";

if (!isset($_GET['hospital_id']) || empty($_GET['hospital_id'])) {
    echo "<option value=''>Invalid Request</option>";
    exit;
}

$hospital_id = intval($_GET['hospital_id']); // Ensure input is an integer

// Fetch doctors for the hospital
$query = "SELECT id, name FROM doctors WHERE hospital_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $hospital_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<option value=''>Select Doctor</option>";
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['name']) . "</option>";
    }
} else {
    echo "<option value=''>No doctors available</option>";
}

$stmt->close();
$conn->close();
?>
