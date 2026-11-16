<?php
include 'forms/dbconn.php';

if (isset($_GET['city']) && !empty($_GET['city'])) {
    $city = htmlspecialchars($_GET['city']); // Sanitize input

    // Fetch distinct departments for the selected city
    $query = "SELECT DISTINCT department FROM doctors WHERE city = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $city);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . htmlspecialchars($row['department']) . "'>" . htmlspecialchars($row['department']) . "</option>";
        }
    } else {
        echo "<option value=''>No departments available</option>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<option value=''>Invalid request</option>";
}
?>
