<?php
$conn = new mysqli("localhost","root","","krishnadental");
if($conn->connect_error) die("DB Error");

$date = $_POST['date'];

$slots = [
    "10:00 - 11:00AM",
    "11:00 - 12:00AM",
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

$response = [];

foreach($slots as $slot){
    $stmt = $conn->prepare(
        "SELECT COUNT(*) FROM appointments 
         WHERE appointment_date=? AND appointment_time=?"
    );
    $stmt->bind_param("ss",$date,$slot);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    $available = 3 - $count;

    $response[$slot] = [
        "booked" => $count,
        "available" => $available
    ];
}

echo json_encode($response);
