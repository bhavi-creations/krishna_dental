<?php
include '../../db.connection/db_connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: view_services.php?msg=Service deleted successfully!");
    exit();
} else {
    header("Location: view_services.php?msg=Invalid request!");
    exit();
}
?>
