<?php
include __DIR__ . '/db.connection/db_connection.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    die("Invalid Appointment ID");
}

$stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: view_appointments.php");
    exit;
} else {
    echo "Delete Failed";
}
?>
