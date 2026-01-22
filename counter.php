<?php
include __DIR__ . './db.connection/db_connection.php';
session_start();

$page  = basename($_SERVER['PHP_SELF']);
$ip    = $_SERVER['REMOTE_ADDR'];
$today = date('Y-m-d');

// City (optional – ippatiki default)
$city = $_SESSION['city'] ?? 'Unknown';

/*
  First: ee IP, ee page, ee roju already unda?
*/
$check = $conn->prepare("
    SELECT id FROM visitor_logs
    WHERE page_name = ? AND ip_address = ? AND visit_date = ?
");
$check->bind_param("sss", $page, $ip, $today);
$check->execute();
$res = $check->get_result();

if ($res->num_rows == 0) {

    // 1️⃣ visitor_logs lo insert
    $ins = $conn->prepare("
        INSERT INTO visitor_logs
        (page_name, ip_address, visit_date, visited_at, city)
        VALUES (?, ?, ?, NOW(), ?)
    ");
    $ins->bind_param("ssss", $page, $ip, $today, $city);
    $ins->execute();

    // 2️⃣ visitors table update / insert
    $v = $conn->prepare("
        SELECT id FROM visitors WHERE page_name = ?
    ");
    $v->bind_param("s", $page);
    $v->execute();
    $vr = $v->get_result();

    if ($vr->num_rows > 0) {
        // already page exists → increment
        $up = $conn->prepare("
            UPDATE visitors SET visit_count = visit_count + 1
            WHERE page_name = ?
        ");
        $up->bind_param("s", $page);
        $up->execute();
    } else {
        // first time page
        $in = $conn->prepare("
            INSERT INTO visitors (page_name, visit_count)
            VALUES (?, 1)
        ");
        $in->bind_param("s", $page);
        $in->execute();
    }
}
