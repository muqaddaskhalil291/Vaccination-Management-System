<?php
include "forms/dbconn.php";

if (isset($_GET['hospital_id']) && !empty($_GET['hospital_id'])) {
    $hospital_id = intval($_GET['hospital_id']); // Ensure input is an integer
    echo "Hospital ID: " . $hospital_id . "<br>"; // Debugging line

    // Fetch vaccines for the hospital
    $query = "SELECT id, vaccine_name, availability FROM vaccines WHERE hospital_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $hospital_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "Query executed!<br>"; // Debugging line

    if ($result->num_rows > 0) {
        echo "<option value=''>Select Vaccine</option>";
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['vaccine_name']) . " (Available: " . $row['availability'] . ")</option>";
        }
    } else {
        echo "<option value=''>No vaccines available</option>";
        echo "<br>SQL Query: SELECT id, vaccine_name, availability FROM vaccines WHERE hospital_id = $hospital_id";
    }
    $stmt->close();
} else {
    echo "<option value=''>Invalid Request</option>";
}

$conn->close();
?>
