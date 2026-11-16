<?php
include "../forms/dbconn.php";

// Additional 10 doctors from different cities
$extra_doctors = [
    // Peshawar
    ["doctor_name" => "Dr. Kamran Abbas", "department_id" => 1, "department_name" => "General Medicine", "email" => "kamran.peshawar@gmail.com", "phone" => "03123456700", "city" => "Peshawar", "img" => "doctor_13"],
    ["doctor_name" => "Dr. Samina Akhtar", "department_id" => 2, "department_name" => "Cardiology", "email" => "samina.peshawar@gmail.com", "phone" => "03122334400", "city" => "Peshawar", "img" => "doctor_14"],

    // Quetta
    ["doctor_name" => "Dr. Rizwan Sheikh", "department_id" => 6, "department_name" => "Orthopedics", "email" => "rizwan.quetta@gmail.com", "phone" => "03019876567", "city" => "Quetta", "img" => "doctor_15"],
    ["doctor_name" => "Dr. Farah Tariq", "department_id" => 14, "department_name" => "Ophthalmology", "email" => "farah.quetta@gmail.com", "phone" => "03112233445", "city" => "Quetta", "img" => "doctor_16"],

    // Faisalabad
    ["doctor_name" => "Dr. Faheem Iqbal", "department_id" => 3, "department_name" => "Neurology", "email" => "faheem.faisalabad@gmail.com", "phone" => "03210987678", "city" => "Faisalabad", "img" => "doctor_17"],
    ["doctor_name" => "Dr. Hira Khan", "department_id" => 13, "department_name" => "Gynecology", "email" => "hira.faisalabad@gmail.com", "phone" => "03012345699", "city" => "Faisalabad", "img" => "doctor_18"],

    // Multan
    ["doctor_name" => "Dr. Ahmed Raza", "department_id" => 5, "department_name" => "Pediatrics", "email" => "ahmed.multan@gmail.com", "phone" => "03122334411", "city" => "Multan", "img" => "doctor_19"],
    ["doctor_name" => "Dr. Saba Yousuf", "department_id" => 16, "department_name" => "Radiology", "email" => "saba.multan@gmail.com", "phone" => "03127894532", "city" => "Multan", "img" => "doctor_20"],

    // Abbottabad
    ["doctor_name" => "Dr. Kashif Ali", "department_id" => 17, "department_name" => "Oncology", "email" => "kashif.abbottabad@gmail.com", "phone" => "03214567812", "city" => "Abbottabad", "img" => "doctor_21"],
    ["doctor_name" => "Dr. Nadia Javed", "department_id" => 15, "department_name" => "Psychiatry", "email" => "nadia.abbottabad@gmail.com", "phone" => "03213456789", "city" => "Abbottabad", "img" => "doctor_22"]
];

foreach ($extra_doctors as $doctor) {
    $name = $doctor["doctor_name"];
    $department_id = $doctor["department_id"];
    $department_name = $doctor["department_name"];
    $email = $doctor["email"];
    $phone = $doctor["phone"];
    $city = $doctor["city"];
    $img = $doctor["img"];
    $password = password_hash("123", PASSWORD_DEFAULT);
    $status = "Active";
    $created_at = date("Y-m-d H:i:s");

    $sql = "INSERT INTO `doctors`(`doctor_id`, `doctor_name`, `department_id`, `Department`, `email`, `password`, `phone`, `specialization`, `city`, `created_at`, `status`, `img`) 
            VALUES (NULL, '$name', '$department_id', '$department_name', '$email', '$password', '$phone', '$department_name', '$city', '$created_at', '$status', '$img')";

    if (mysqli_query($conn, $sql)) {
        $doctor_id = mysqli_insert_id($conn);

        // Assign schedules for the doctor
        $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        foreach ($days as $day) {
            $start_time = "09:00:00";
            $end_time = "17:00:00";

            $schedule_sql = "INSERT INTO `doctors_schedule`(`schedule_id`, `doctor_id`, `day`, `start_time`, `end_time`) 
                             VALUES (NULL, '$doctor_id', '$day', '$start_time', '$end_time')";

            mysqli_query($conn, $schedule_sql);
        }
        echo "Doctor $name added with schedule.<br>";
    } else {
        echo "Error adding doctor $name: " . mysqli_error($conn) . "<br>";
    }
}

mysqli_close($conn);
?>
