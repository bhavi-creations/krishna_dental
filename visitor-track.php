<?php
include './db.connection/db_connection.php';
session_start();

// ===============================
// Page & IP
// ===============================
$page  = basename($_SERVER['PHP_SELF']);
$ip    = $_SERVER['REMOTE_ADDR'];
$today = date('Y-m-d');

// Localhost testing fix
if ($ip == '127.0.0.1' || $ip == '::1') {
    $ip = '8.8.8.8'; // test public IP
}

// ===============================
// Get City from IP (once per session)
// ===============================
if (!isset($_SESSION['city'])) {
    $city = 'Unknown';
    $ipApiUrl = "http://ip-api.com/json/{$ip}";
    $ipData = @file_get_contents($ipApiUrl);

    if ($ipData) {
        $ipData = json_decode($ipData, true);
        if (!empty($ipData['city'])) {
            $city = $ipData['city'];
            if (!empty($ipData['regionName'])) {
                $city .= ' - ' . $ipData['regionName'];
            }
        }
    }
    $_SESSION['city'] = $city;
} else {
    $city = $_SESSION['city'];
}

// ===============================
// Record visitor only once per page per day
// ===============================
$check = $conn->prepare("
    SELECT id FROM visitor_logs
    WHERE page_name = ? AND ip_address = ? AND visit_date = ?
");
$check->bind_param("sss", $page, $ip, $today);
$check->execute();
$res = $check->get_result();

if ($res->num_rows == 0) {
    $ins = $conn->prepare("
        INSERT INTO visitor_logs (page_name, ip_address, visit_date, visited_at, city)
        VALUES (?, ?, ?, NOW(), ?)
    ");
    $ins->bind_param("ssss", $page, $ip, $today, $city);
    $ins->execute();
}

// ===============================
// Filters
// ===============================
$from = $_GET['from'] ?? '';
$to   = $_GET['to'] ?? '';
$isFiltered = (!empty($from) && !empty($to));

// ===============================
// Total Unique Visitors (IP-wise)
// ===============================
if ($isFiltered) {
    $stmt = $conn->prepare("
        SELECT COUNT(DISTINCT ip_address) AS total
        FROM visitor_logs
        WHERE visit_date BETWEEN ? AND ?
    ");
    $stmt->bind_param("ss", $from, $to);
    $stmt->execute();
    $totalRes = $stmt->get_result();
} else {
    $totalRes = $conn->query("
        SELECT COUNT(DISTINCT ip_address) AS total
        FROM visitor_logs
    ");
}
$totalCount = $totalRes->fetch_assoc()['total'] ?? 0;

// ===============================
// Total Page Views
// ===============================
if ($isFiltered) {
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS total_views
        FROM visitor_logs
        WHERE visit_date BETWEEN ? AND ?
    ");
    $stmt->bind_param("ss", $from, $to);
    $stmt->execute();
    $viewRes = $stmt->get_result();
} else {
    $viewRes = $conn->query("
        SELECT COUNT(*) AS total_views
        FROM visitor_logs
    ");
}
$totalViews = $viewRes->fetch_assoc()['total_views'] ?? 0;

// ===============================
// Page-wise Unique Visitors
// ===============================
if ($isFiltered) {
    $stmt = $conn->prepare("
        SELECT page_name, COUNT(DISTINCT ip_address) AS visit_count
        FROM visitor_logs
        WHERE visit_date BETWEEN ? AND ?
        GROUP BY page_name
        ORDER BY visit_count DESC
    ");
    $stmt->bind_param("ss", $from, $to);
    $stmt->execute();
    $pages = $stmt->get_result();
} else {
    $pages = $conn->query("
        SELECT page_name, COUNT(DISTINCT ip_address) AS visit_count
        FROM visitor_logs
        GROUP BY page_name
        ORDER BY visit_count DESC
    ");
}

// ===============================
// Today City-wise Visitors
// ===============================
$cities = $conn->query("
    SELECT city, COUNT(DISTINCT ip_address) AS total
    FROM visitor_logs
    WHERE visit_date = CURDATE()
    GROUP BY city
    ORDER BY total DESC
");
?>

