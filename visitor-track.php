<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/db.connection/db_connection.php';

$page  = basename($_SERVER['PHP_SELF']);
$ip    = $_SERVER['REMOTE_ADDR'];
$today = date('Y-m-d');

// Localhost fix
if ($ip === '127.0.0.1' || $ip === '::1') {
    $ip = '8.8.8.8';
}

// City
$city = $_SESSION['city'] ?? 'Unknown';

// ===============================
// Check: already visited today?
// ===============================
$check = $conn->prepare("
    SELECT id FROM visitor_logs
    WHERE page_name = ? AND ip_address = ? AND visit_date = ?
");
$check->bind_param("sss", $page, $ip, $today);
$check->execute();
$res = $check->get_result();

// ===============================
// Insert ONLY ONCE per day per IP
// ===============================
if ($res->num_rows === 0) {

    $ins = $conn->prepare("
        INSERT INTO visitor_logs
        (page_name, ip_address, visit_date, visited_at, city)
        VALUES (?, ?, ?, NOW(), ?)
    ");
    $ins->bind_param("ssss", $page, $ip, $today, $city);
    $ins->execute();
}
