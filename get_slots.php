<?php
// JSON రెస్పాన్స్ మాత్రమే పంపుతున్నామని బ్రౌజర్‌కి చెప్పడానికి
header('Content-Type: application/json');

// ఏవైనా చిన్న వార్నింగ్స్ వస్తే JSON బ్రేక్ అవ్వకుండా దాచడానికి (Production లో హెల్ప్ అవుతుంది)
error_reporting(0); 

include './db.connection/db_connection.php';

// డేట్ సరిగ్గా ఉందో లేదో వెరిఫై చేయడం
$date = isset($_GET['date']) ? $conn->real_escape_string($_GET['date']) : '';

$response = [
    'isHoliday' => false,
    'type' => '',
    'reason' => '',
    'slots' => []
];

if (empty($date)) {
    echo json_encode($response);
    exit;
}

// 1. ప్రధాన స్లాట్‌ల లిస్ట్
$slots_list = [
    "9:00 AM - 10:00 AM",
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

// 2. హాలిడే ఫిల్టరింగ్ కోసం గ్రూప్‌లు (స్లాట్ టెక్స్ట్ లు పైన ఉన్నవాటితో పక్కాగా మ్యాచ్ అవ్వాలి)
$morningSlots = [
    "9:00 AM - 10:00 AM",
    "10:00 AM - 11:00 AM",
    "11:00 AM - 12:00 PM",
    "12:00 PM - 01:00 PM"
];

$afternoonSlots = [
    "01:00 PM - 02:00 PM",
    "02:00 PM - 03:00 PM",
    "03:00 PM - 04:00 PM",
    "04:00 PM - 05:00 PM",
    "05:00 PM - 06:00 PM",
    "06:00 PM - 07:00 PM",
    "07:00 PM - 08:00 PM",
    "08:00 PM - 09:00 PM"
];

// హాలిడే చెక్ క్వెరీ
$res = $conn->query("SELECT * FROM holidays WHERE holiday_date='$date'");
$holiday = $res ? $res->fetch_assoc() : null;

if ($holiday) {
    $response['isHoliday'] = true;
    $response['type'] = $holiday['holiday_type'];
    $response['reason'] = $holiday['reason'];
}

foreach ($slots_list as $slot) {

    // హాలిడే ఫిల్టరింగ్ లాజిక్
    if ($holiday) {
        if ($holiday['holiday_type'] == 'fullday') {
            continue; // ఫుల్ డే హాలిడే అయితే లూప్ ఆపేసి ఖాళీ స్లాట్స్ పంపుతుంది
        }
        if ($holiday['holiday_type'] == 'morning' && in_array($slot, $morningSlots)) {
            continue; // మార్నింగ్ హాలిడే అయితే మార్నింగ్ స్లాట్స్ స్కిప్ అవుతాయి
        }
        if ($holiday['holiday_type'] == 'afternoon' && in_array($slot, $afternoonSlots)) {
            continue; // ఆఫ్టర్నూన్ హాలిడే అయితే ఆఫ్టర్నూన్ స్లాట్స్ స్కిప్ అవుతాయి
        }
    }

    // ఆ స్లాట్ కి ఆల్రెడీ ఎన్ని బుకింగ్స్ ఉన్నాయో కౌంట్ చేయడం
    $q = $conn->query("SELECT COUNT(*) as total FROM appointments WHERE appointment_date='$date' AND time_slot='$slot'");
    
    $total_booked = 0;
    if ($q) {
        $r = $q->fetch_assoc();
        $total_booked = (int)$r['total'];
    }

    $max = 3; // ఒక స్లాట్ కి గరిష్టంగా 3 అపాయింట్‌మెంట్‌లు
    $available = $max - $total_booked;
    if ($available < 0) $available = 0;

    $response['slots'][] = [
        'time' => $slot,
        'available' => $available
    ];
}

// పక్కాగా JSON ని మాత్రమే ఎకో చేయడం
echo json_encode($response);
exit;
?>