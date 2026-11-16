<?php
include '../forms/dbconn.php';

if (isset($_POST['doctor_id'])) {
    $doctor_id = intval($_POST['doctor_id']);
    $query = "SELECT day, start_time, end_time FROM doctors_schedule WHERE doctor_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $schedules = [];
    while ($row = $result->fetch_assoc()) {
        $schedules[] = $row;
    }

    echo json_encode($schedules);
}
?>
