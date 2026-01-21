<?php
include './db.connection/db_connection.php';

// User IP
$ip = $_SERVER['REMOTE_ADDR'];

// Current page
$page = basename($_SERVER['PHP_SELF']);

// Today date
$today = date('Y-m-d');

// Location API
$api = "http://ip-api.com/json/$ip";
$response = @file_get_contents($api);
$data = json_decode($response, true);

$country = $data['country'] ?? 'Unknown';
$region  = $data['regionName'] ?? 'Unknown';
$city    = $data['city'] ?? 'Unknown';

// Insert only once per day per page per user
$stmt = $conn->prepare("
    INSERT IGNORE INTO visitors
    (ip_address, visit_date, page_name, country, region, city)
    VALUES (?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "ssssss",
    $ip,
    $today,
    $page,
    $country,
    $region,
    $city
);

$stmt->execute();
?>
