<?php
include 'forms/dbconn.php';

if (isset($_GET['doctor_id']) && !empty($_GET['doctor_id'])) {
    $doctorId = intval($_GET['doctor_id']); // Ensure it's an integer

    // Fetch doctor availability (day-wise)
    $query = "SELECT day, start_time, end_time FROM doctor_availability WHERE doctor_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $doctorId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<option value=''>Select time</option>";
        while ($row = $result->fetch_assoc()) {
            $day = htmlspecialchars($row['day']);
            $startTime = date("g:i A", startTime($row['start_time']));
            $endTime = date("g:i A", startTime($row['end_time']));
            echo "<option value='{$row['start_time']}'>$day ($startTime - $endTime)</option>";
        }
    } else {
        echo "<option value=''>No available time</option>";
    }
    $stmt->close();
} else {
    echo "<option value=''>Invalid request</option>";
}

$conn->close();
?>
