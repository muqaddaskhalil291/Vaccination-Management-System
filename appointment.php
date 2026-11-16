<?php
session_start();
include "forms/dbconn.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: forms/login.php");
    exit;
}

$user_name = $_SESSION['user_name'];
$child_name = ""; // Initialize variable

if (isset($_SESSION['parent_id'])) {
    $parent_id = $_SESSION['parent_id']; // Assign session value

    $query = "SELECT child_name FROM children WHERE parent_id = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $parent_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $child_name = $row['child_name'];
        } else {
            echo "<script>alert('No child found for this parent.');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Database query failed!');</script>";
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $parent_id = $_SESSION['parent_id'];
    $child_name = $_POST['child_name']; // Get from form input instead of session
    $child_dob = $_POST['child_dob'];
    $child_gender = $_POST['child_gender'];
    $hospital_id = $_POST['hospital_id'];
    $doctor_id = $_POST['doctor_id'];
    $vaccine_id = $_POST['vaccine_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];

    // Check if child exists
    $checkChildQuery = "SELECT id FROM children WHERE child_name = ? AND parent_id = ?";
    $stmt = $conn->prepare($checkChildQuery);
    $stmt->bind_param("si", $child_name, $parent_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $child_id = $row['id'];
    } else {
        $insertChildQuery = "INSERT INTO children (child_name, parent_id, dob, gender) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertChildQuery);
        $stmt->bind_param("siss", $child_name, $parent_id, $child_dob, $child_gender);
        $stmt->execute();
        $child_id = $stmt->insert_id;
    }
    $stmt->close();

    // Check doctor availability
    $stmt = $conn->prepare("SELECT id FROM doctor_availability WHERE doctor_id = ? AND day = DAYNAME(?) AND start_time <= ? AND end_time >= ?");
    $stmt->bind_param("isss", $doctor_id, $appointment_date, $appointment_time, $appointment_time);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        echo "<script>alert('Doctor is not available at the selected time.'); window.location.href='appointment.php';</script>";
        exit;
    }
    $stmt->close();

    // Insert appointment
    $stmt = $conn->prepare("INSERT INTO appointments (parent_id, child_id, hospital_id, doctor_id, vaccine_id, appointment_date, appointment_time, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending', NOW())");
    $stmt->bind_param("iiiiiss", $parent_id, $child_id, $hospital_id, $doctor_id, $vaccine_id, $appointment_date, $appointment_time);

    if ($stmt->execute()) {
        echo "<script>alert('Appointment booked successfully!'); window.location.href='appointment.php';</script>";
    } else {
        echo "<script>alert('Error booking appointment.'); window.location.href='appointment.php';</script>";
    }
    $stmt->close();
    $conn->close();
}
?>


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background: #f8f9fa; font-family: 'Poppins', sans-serif; }
        .appointment-container {
            max-width: 600px; margin: 50px auto; background: #fff;
            padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 { text-align: center; margin-bottom: 20px; color: #007bff; }
        label { font-weight: 600; margin-top: 10px; }
        .btn-primary { width: 100%; padding: 10px; font-size: 18px; border-radius: 5px; transition: all 0.3s ease; }
        .btn-primary:hover { background: #0056b3; }
        select, input { padding: 10px; border-radius: 5px; }
        

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            margin-top: 8px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        .alert .close {
            color: #721c24;
            font-size: 1.5rem;
            line-height: 1;
            border: none;
            background: none;
            cursor: pointer;
        }

        .alert .close:hover {
            color: #f8d7da;
        }

        .fade {
            display: none;
            opacity: 0;
            transition: opacity 0.15s linear;
        }

        .show {
            opacity: 1;
        }
        


        @keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

form {
  animation: fadeInUp 1s ease-in-out;
}


    </style>


    
</head>
<body>
<div class="appointment-container">
    <h2><i class="fas fa-calendar-check"></i> Book an Appointment</h2>
    <form action="appointment.php" method="post">
        <label>Child Name :</label>
        <input type="text" name="child_name" class="form-control" placeholder="Enter Child Name" required>

        <label>Child Date of Birth:</label>
        <input type="date" name="child_dob" class="form-control" required>

        <label>Child Gender:</label>
        <select name="child_gender" class="form-select" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>        
        <label>Select Hospital:</label>
        <select name="hospital_id" id="hospital" class="form-select" required>
            <option value="">Select Hospital</option>
            <?php
            $hospitalQuery = "SELECT id, hospital_name FROM hospitals WHERE status = 'Active'";
            $result = $conn->query($hospitalQuery);
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['hospital_name'] . "</option>";
                }
            } else {
                echo "<option value=''>No active hospitals found</option>";
            }
            
            ?>
        </select>

        <label>Select Doctor:</label>
        <select name="doctor_id" id="doctor" class="form-select" required>
            <option value="">Select Doctor</option>
        </select>

        <label>Select Vaccine:</label>
        <select name="vaccine_id" id="vaccine" class="form-select" required>
            <option value="">Select Vaccine</option>
        </select>

        <label>Appointment Date:</label>
        <input type="date" name="appointment_date" class="form-control" required>

        
        <!-- <label>Appointment Time:</label>
<select name="appointment_time" id="appointment_time" class="form-select" required>
    <option value="">Select time</option>
</select> -->

        <label>Appointment Time:</label>
<input type="time" id="appointment_time" name="appointment_time" class="form-control" required>


        <button type="submit" class="btn btn-primary mt-3">Book Appointment</button>
    </form>
</div>
<script>
document.getElementById("hospital").addEventListener("change", function() {
    var hospital_id = this.value;
    if (hospital_id) {
        console.log("Fetching vaccines for hospital_id:", hospital_id); // Debugging

        fetch("get_vaccines.php?hospital_id=" + hospital_id)
        .then(response => response.text())
        .then(data => {
            console.log("Vaccine response:", data); // Debugging output
            document.getElementById("vaccine").innerHTML = data;
        })
        .catch(error => console.error("Error fetching vaccines:", error));
    } else {
        document.getElementById("vaccine").innerHTML = "<option value=''>Select Vaccine</option>";
    }
});
</script>
<script>
document.getElementById("hospital").addEventListener("change", function() {
    var hospital_id = this.value;
    if (hospital_id) {
        console.log("Fetching doctors for hospital_id:", hospital_id); // Debugging

        fetch("get_doctors.php?hospital_id=" + hospital_id)
        .then(response => response.text())
        .then(data => {
            console.log("Vaccine response:", data); // Debugging output
            document.getElementById("doctor").innerHTML = data;
        })
        .catch(error => console.error("Error fetching doctors:", error));
    } else {
        document.getElementById("doctor").innerHTML = "<option value=''>Select doctor</option>";
    }
});


document.getElementById("hospital").addEventListener("change", function() {
    var hospital_id = this.value;
    if (hospital_id) {
        console.log("Fetching doctors for hospital_id:", hospital_id); // Debugging

        fetch("get_doctors.php?hospital_id=" + hospital_id)
        .then(response => response.text())
        .then(data => {
            console.log("Doctor response:", data); // Debugging output
            document.getElementById("doctor").innerHTML = data;
            document.getElementById("appointment_time").value = ""; // Reset time when hospital changes
        })
        .catch(error => console.error("Error fetching doctors:", error));
    } else {
        document.getElementById("doctor").innerHTML = "<option value=''>Select doctor</option>";
        document.getElementById("appointment_time").value = "";
    }
});

// Fetch doctor availability when a doctor is selected
document.getElementById("doctor").addEventListener("change", function() {
    var doctor_id = this.value;
    var appointmentTimeInput = document.getElementById("appointment_time");

    if (doctor_id) {
        console.log("Fetching availability for doctor_id:", doctor_id); // Debugging

        fetch("get_doctor_time.php?doctor_id=" + doctor_id)
        .then(response => response.text())
        .then(data => {
            console.log("Doctor Time response:", data); // Debugging output
            appointmentTimeInput.value = data; // Auto-fill time
        })
        .catch(error => console.error("Error fetching doctor time:", error));
    } else {
        appointmentTimeInput.value = "";
    }
});

document.getElementById("doctor").addEventListener("change", function() {
    var doctorId = this.value;
    if (doctorId) {
        console.log("Fetching schedule for doctor_id:", doctorId);

        fetch("get_appointment_times.php?doctor_id=" + doctorId)
        .then(response => response.text())
        .then(data => {
            console.log("Doctor Schedule Response:", data); // Debugging output
            document.getElementById("appointment_time").innerHTML = data;
        })
        .catch(error => console.error("Error fetching schedule:", error));
    } else {
        document.getElementById("appointment_time").innerHTML = "<option value=''>Select doctor first</option>";
    }
});

</script>
</body>
</html>
