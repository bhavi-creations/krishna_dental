<?php
include './db.connection/db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);
$lat = $data['lat'] ?? null;
$lon = $data['lon'] ?? null;

if (!$lat || !$lon) exit;

$url = "https://nominatim.openstreetmap.org/reverse?format=json&lat=$lat&lon=$lon";

$opts = [
    "http" => [
        "header" => "User-Agent: VisitorAnalytics/1.0\r\n"
    ]
];
$context = stream_context_create($opts);
$res = file_get_contents($url, false, $context);

if (!$res) exit;

$json = json_decode($res, true);

$city =
    $json['address']['city']
    ?? $json['address']['town']
    ?? $json['address']['village']
    ?? 'Unknown';

$ip = $_SERVER['REMOTE_ADDR'];

$stmt = $conn->prepare("
    UPDATE visitor_logs
    SET city = ?
    WHERE ip_address = ? AND (city IS NULL OR city = 'Unknown')
");
$stmt->bind_param("ss", $city, $ip);
$stmt->execute();
