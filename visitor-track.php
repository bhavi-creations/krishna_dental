<?php
include './db.connection/db_connection.php';
session_start();

// Page & IP
$page  = basename($_SERVER['PHP_SELF']);
$ip    = $_SERVER['REMOTE_ADDR'];
$today = date('Y-m-d');

// Localhost test fix
if ($ip == '127.0.0.1' || $ip == '::1') {
    $ip = '8.8.8.8';
}

// Get City (once per session using cURL)
if (!isset($_SESSION['city'])) {
    $city = 'Unknown';

    $url = "https://ip-api.com/json/{$ip}?fields=status,city,regionName,country";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response) {
        $data = json_decode($response, true);

        if (!empty($data['city'])) {
            $city = $data['city'];
            if (!empty($data['regionName'])) {
                $city .= ' - ' . $data['regionName'];
            }
        } elseif (!empty($data['regionName'])) {
            $city = $data['regionName'];
        } elseif (!empty($data['country'])) {
            $city = $data['country'];
        }
    }

    $_SESSION['city'] = $city;
} else {
    $city = $_SESSION['city'];
}

// Record once per page per day
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
