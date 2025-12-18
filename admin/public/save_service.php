<?php
include '../../db.connection/db_connection.php';

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$service_name = trim($_POST['service_name']);

// Generate service_id if adding new
if ($id == 0) {
    $result = $conn->query("SELECT MAX(service_id) AS max_id FROM services");
    $row = $result->fetch_assoc();
    $next_service_id = $row['max_id'] + 1;

    $stmt = $conn->prepare("INSERT INTO services (service_id, service_name) VALUES (?, ?)");
    $stmt->bind_param("is", $next_service_id, $service_name);
    $stmt->execute();
    $stmt->close();

    header("Location: addservice.php?success=added");
} else {
    $stmt = $conn->prepare("UPDATE services SET service_name = ? WHERE id = ?");
    $stmt->bind_param("si", $service_name, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: addservice.php?success=updated");
}
exit();
