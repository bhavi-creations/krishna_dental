<?php
header('Content-Type: application/json; charset=UTF-8');

include __DIR__ . '/db.connection/db_connection.php';
require_once __DIR__ . '/appointment_helpers.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo json_encode([]);
    exit;
}

$sql = 'SELECT ' . appointment_select_sql($conn) . ' FROM appointments WHERE id = ?';
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([]);
    exit;
}

$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result ? $result->fetch_assoc() : [];

echo json_encode($data ?: []);
