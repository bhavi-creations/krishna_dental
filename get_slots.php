<?php
include './db.connection/db_connection.php';

$date = $_GET['date'];

$slots_list = [
  
  "10:00 AM - 11:00 AM",
  "11:00 AM - 12:00 PM",
  "12:00 PM - 01:00 PM",
  "01:00 PM - 02:00 PM",
  "02:00 PM - 03:00 PM",
  "03:00 PM - 04:00 PM",
  "04:00 PM - 05:00 PM",
  "05:00 PM - 06:00 PM",
  "06:00 PM - 07:00 PM",
  "07:00 PM - 08:00 PM"
];

$res = $conn->query("SELECT * FROM holidays WHERE holiday_date='$date'");
$holiday = $res->fetch_assoc();

$response = [
  'isHoliday' => false,
  'type' => '',
  'reason' => '',
  'slots' => []
];

if ($holiday) {
  $response['isHoliday'] = true;
  $response['type'] = $holiday['holiday_type'];
  $response['reason'] = $holiday['reason'];
}

$morningSlots = [
  "9:00 AM - 10:00 AM",
  "10:00 AM - 11:00 AM",
  "11:00 AM - 12:00 PM",
  "12:00 PM - 01:00 PM"
];

$afternoonSlots = [
  "02:00 PM - 03:00 PM",
  "03:00 PM - 04:00 PM",
  "04:00 PM - 05:00 PM",
  "05:00 PM - 06:00 PM",
  "06:00 PM - 07:00 PM",
  "07:00 PM - 08:30 PM"
];

foreach ($slots_list as $slot) {

  // Holiday filtering
  if ($holiday) {
    if ($holiday['holiday_type'] == 'fullday') continue;
    if ($holiday['holiday_type'] == 'morning' && in_array($slot, $morningSlots)) continue;
    if ($holiday['holiday_type'] == 'afternoon' && in_array($slot, $afternoonSlots)) continue;
  }

  $q = $conn->query("SELECT COUNT(*) as total FROM appointments 
                     WHERE appointment_date='$date' AND time_slot='$slot'");
  $r = $q->fetch_assoc();

  $max = 3; // per slot limit
  $available = $max - $r['total'];
  if ($available < 0) $available = 0;

  $response['slots'][] = [
    'time' => $slot,
    'available' => $available
  ];
}

echo json_encode($response);
   