<?php
header('Content-Type: application/json; charset=UTF-8');

include __DIR__ . '/db.connection/db_connection.php';
require_once __DIR__ . '/appointment_helpers.php';

$date = $_POST['date'] ?? '';

if ($date === '') {
    echo json_encode([
        'holiday' => false,
        'type' => '',
        'reason' => '',
        'slots' => [],
    ]);
    exit;
}

$response = [
    'holiday' => false,
    'type' => '',
    'reason' => '',
    'slots' => [],
];

$holidayStmt = $conn->prepare('SELECT holiday_type, reason FROM holidays WHERE holiday_date = ? LIMIT 1');
if ($holidayStmt) {
    $holidayStmt->bind_param('s', $date);
    $holidayStmt->execute();
    $holiday = $holidayStmt->get_result()->fetch_assoc();
    $holidayStmt->close();

    if ($holiday) {
        $response['holiday'] = true;
        $response['type'] = $holiday['holiday_type'];
        $response['reason'] = $holiday['reason'];
    }
}

$slots = appointment_slot_labels();
$morningSlots = appointment_morning_slots();
$afternoonSlots = appointment_afternoon_slots();
$slotColumn = appointment_slot_column($conn);
$maxPerSlot = 3;

foreach ($slots as $slot) {
    if ($response['holiday']) {
        if ($response['type'] === 'fullday') {
            continue;
        }

        if ($response['type'] === 'morning' && in_array($slot, $morningSlots, true)) {
            continue;
        }

        if ($response['type'] === 'afternoon' && in_array($slot, $afternoonSlots, true)) {
            continue;
        }
    }

    $countStmt = $conn->prepare(
        "SELECT COUNT(*) AS total
         FROM appointments
         WHERE appointment_date = ? AND {$slotColumn} = ?"
    );

    if (!$countStmt) {
        continue;
    }

    $countStmt->bind_param('ss', $date, $slot);
    $countStmt->execute();
    $countRow = $countStmt->get_result()->fetch_assoc();
    $countStmt->close();

    $booked = (int) ($countRow['total'] ?? 0);
    $response['slots'][] = [
        'time' => $slot,
        'available' => max(0, $maxPerSlot - $booked),
    ];
}

echo json_encode($response);
