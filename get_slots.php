<?php
// Clear output buffering to avoid accidental whitespaces/warnings spilling into JSON
ob_start();
header('Content-Type: application/json; charset=utf-8');

// Clear completely error displays to prevent json format corruption
error_reporting(0);
ini_set('display_errors', 0);

include './db.connection/db_connection.php';

$date = isset($_GET['date']) ? trim($_GET['date']) : '';

$response = [
    'isHoliday' => false,
    'type' => '',
    'reason' => '',
    'slots' => []
];

// Check if database connection is alive
if (!$conn) {
    ob_end_clean();
    echo json_encode([
        'isHoliday' => false,
        'type' => '',
        'reason' => 'Database connectivity failed on server',
        'slots' => []
    ]);
    exit;
}

$slots_list = [
    "09:00 AM - 10:00 AM",
    "10:00 AM - 11:00 AM",
    "11:00 AM - 12:00 PM",
    "12:00 PM - 01:00 PM",
    "01:00 PM - 02:00 PM",
    "02:00 PM - 03:00 PM",
    "03:00 PM - 04:00 PM",
    "04:00 PM - 05:00 PM",
    "05:00 PM - 06:00 PM",
    "06:00 PM - 07:00 PM",
    "07:00 PM - 08:00 PM",
    "08:00 PM - 09:00 PM"
];

// 1. Holiday Mapping Check
$holiday = null;
$stmt = $conn->prepare("SELECT holiday_type, reason FROM holidays WHERE holiday_date = ?");
if ($stmt) {
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $holiday = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

if (!empty($holiday)) {
    $response['isHoliday'] = true;
    $response['type'] = trim(strtolower($holiday['holiday_type']));
    $response['reason'] = $holiday['reason'];
}

// 2. Strict Standard Mappings (Added leading zero matching checks)
$morningSlots = [
    "09:00 AM - 10:00 AM",
    "10:00 AM - 11:00 AM",
    "11:00 AM - 12:00 PM",
    "12:00 PM - 01:00 PM",
    "01:00 PM - 02:00 PM"
];

$afternoonSlots = [
    "02:00 PM - 03:00 PM",
    "03:00 PM - 04:00 PM",
    "04:00 PM - 05:00 PM",
    "05:00 PM - 06:00 PM",
    "06:00 PM - 07:00 PM",
    "07:00 PM - 08:00 PM",
    "08:00 PM - 09:00 PM"
];

// 3. Strict loop validation checking
foreach ($slots_list as $slot) {
    if (!empty($holiday)) {
        $h_type = trim(strtolower($holiday['holiday_type']));
        if ($h_type === 'fullday') {
            continue;
        }
        if ($h_type === 'morning' && in_array($slot, $morningSlots)) {
            continue;
        }
        if ($h_type === 'afternoon' && in_array($slot, $afternoonSlots)) {
            continue;
        }
    }

    // Fixed internal parameters execution to pass live hosting drivers safely
    $total = 0;
    $q_stmt = $conn->prepare("SELECT COUNT(*) as total FROM appointments WHERE appointment_date = ? AND time_slot = ?");
    if ($q_stmt) {
        $q_stmt->bind_param("ss", $date, $slot);
        $q_stmt->execute();
        $res = $q_stmt->get_result()->fetch_assoc();
        $total = isset($res['total']) ? (int)$res['total'] : 0;
        $q_stmt->close();
    }

    $max = 3; 
    $available = $max - $total;
    if ($available < 0) {
        $available = 0;
    }

    $response['slots'][] = [
        'time' => $slot,
        'available' => $available
    ];
}

// Flush all systems cleanly and output valid pure array array data format
ob_end_clean();
echo json_encode($response);
exit;