<?php
include __DIR__ . '/db.connection/db_connection.php';

$date = $_POST['date'];

/* 1 hour slots */
$slots = [
    "10:00 - 11:00AM",
    "11:00 - 12:00PM",
    "12:00 - 01:00PM",
    "01:00 - 02:00PM",
    "02:00 - 03:00PM",
    "03:00 - 04:00PM",
    "04:00 - 05:00PM",
    "05:00 - 06:00PM",
    "06:00 - 07:00PM",
    "07:00 - 08:00PM",
    "08:00 - 09:00PM"
];

/* Initialize counts */
$data = [];
foreach ($slots as $slot) {
    $data[$slot] = 0;
}

/* Count bookings per slot */
$result = $conn->query("
    SELECT appointment_time, COUNT(*) as total 
    FROM appointments 
    WHERE appointment_date = '$date'
    GROUP BY appointment_time
");

while ($row = $result->fetch_assoc()) {
    $data[$row['appointment_time']] = (int)$row['total'];
}

echo json_encode($data);
