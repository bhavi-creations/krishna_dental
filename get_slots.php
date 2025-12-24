<?php
include __DIR__ . '/db.connection/db_connection.php';

$date = $_GET['date'];

$sql = "
    SELECT appointment_slot, COUNT(*) AS total 
    FROM appointments 
    WHERE appointment_date = ? 
    AND status = 'Booked'
    GROUP BY appointment_slot
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $date);
$stmt->execute();

$data = [];
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $data[$row['appointment_slot']] = $row['total'];
}

echo json_encode($data);