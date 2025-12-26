<?php
include './db.connection/db_connection.php';

$date = $_POST['date'] ?? '';

if (!$date) {
    echo json_encode([]);
    exit;
}

/* =====================
   CHECK HOLIDAY (ADMIN SET)
===================== */
$holiday = $conn->prepare(
    "SELECT id FROM holidays WHERE holiday_date = ?"
);
$holiday->bind_param("s", $date);
$holiday->execute();
$res = $holiday->get_result();

if ($res->num_rows > 0) {
    echo json_encode([
        "holiday" => true,
        "message" => "Clinic closed"
    ]);
    exit;
}

/* =====================
   SLOT CONFIG
===================== */
$slots = [
    "10:00 - 11:00AM",
    "11:00 - 12:00PM",
    "12:00 - 01:00PM",
    "01:00 - 02:00PM",
    "02:00 - 03:00PM",
    "03:00 - 04:00PM",
    "04:00 - 05:00PM"
];

$maxPerSlot = 3;
$data = [];

foreach ($slots as $slot) {

    $stmt = $conn->prepare(
        "SELECT COUNT(*) total 
         FROM appointments 
         WHERE appointment_date = ? 
         AND appointment_time = ?"
    );
    $stmt->bind_param("ss", $date, $slot);
    $stmt->execute();
    $count = $stmt->get_result()->fetch_assoc()['total'];

    $data[$slot] = [
        "available" => max(0, $maxPerSlot - $count)
    ];
}

echo json_encode($data);
