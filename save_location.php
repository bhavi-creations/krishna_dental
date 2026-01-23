<?php
include './db.connection/db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

$lat = $data['lat'] ?? null;
$lon = $data['lon'] ?? null;

$city = "Unknown";

if ($lat && $lon) {

    $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat=$lat&lon=$lon";

    $opts = [
        "http" => [
            "header" => "User-Agent: Mozilla/5.0\r\n"
        ]
    ];
    $context = stream_context_create($opts);

    $response = file_get_contents($url, false, $context);
    $res = json_decode($response, true);

    if (!empty($res['address'])) {
        $city =
            $res['address']['city']
            ?? $res['address']['town']
            ?? $res['address']['village']
            ?? "Unknown";
    }
}

$page = basename($_SERVER['PHP_SELF']);

$stmt = $conn->prepare(
    "UPDATE visitors 
     SET city = ? 
     WHERE page_name = ? 
     ORDER BY id DESC 
     LIMIT 1"
);
$stmt->bind_param("ss", $city, $page);
$stmt->execute();
