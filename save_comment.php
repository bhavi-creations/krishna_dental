<?php
/* =====================
   1. DB CONNECTION
===================== */
include './db.connection/db_connection.php';

/* =====================
   2. FORM DATA
===================== */
$name  = $_POST['patient_name'] ?? '';
$phone = $_POST['phone'] ?? '';
$date  = $_POST['appointment_date'] ?? '';
$time  = $_POST['appointment_time'] ?? '';

if ($name == '' || $phone == '' || $date == '' || $time == '') {
    echo "<script>
            alert('All fields are required');
            window.history.back();
          </script>";
    exit;
}

/* =====================
   3. HOLIDAY CHECK
===================== */
$holidaySql = "SELECT id FROM holidays WHERE holiday_date = ?";
$holidayStmt = $conn->prepare($holidaySql);

if (!$holidayStmt) {
    die("Holiday Query Error");
}

$holidayStmt->bind_param("s", $date);
$holidayStmt->execute();
$holidayStmt->store_result();

if ($holidayStmt->num_rows > 0) {
    echo "<script>
            alert('Selected date is a Holiday. Please choose another date.');
            window.history.back();
          </script>";
    exit;
}

$holidayStmt->close();

/* =====================
   4. SAVE APPOINTMENT
===================== */
$insertSql = "INSERT INTO appointments 
              (patient_name, phone, appointment_date, appointment_time)
              VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($insertSql);

if (!$stmt) {
    die("Insert Prepare Failed");
}

$stmt->bind_param("ssss", $name, $phone, $date, $time);

if ($stmt->execute()) {

    echo "<script>
            alert('Appointment booked successfully!');
            window.location.href='index.php';
          </script>";

} else {
    echo "<script>
            alert('Appointment booking failed');
            window.history.back();
          </script>";
}

$stmt->close();
$conn->close();
?>
