<?php
// Include database connection
include 'forms/dbconn.php';
if (!isset($_GET['doctor_id']) || empty($_GET['doctor_id'])) {
    die("doctor_id not set or empty");
}
// Check if doctor_id is set in the GET request
if (isset($_GET['doctor_id']) && !empty($_GET['doctor_id'])) {
    // Sanitize and retrieve the doctor_id
    $doctorId = intval($_GET['doctor_id']); // Convert to integer for safety

    // Fetch available days for the selected doctor
    $query = "SELECT DISTINCT `day` FROM doctors_schedule WHERE doctor_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $doctorId); // Bind doctor_id
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if days are available
    if ($result->num_rows > 0) {
        // Generate and echo day options
        echo "<option value=''> Select Day </option>";
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . htmlspecialchars($row['day']) . "'>" . htmlspecialchars($row['day']) . "</option>";
        }
    } else {
        // If no days are found
        echo "<option value=''>No days available</option>";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // If doctor_id is not set or invalid
    echo "<option value=''>Invalid request</option>";
}
?>

