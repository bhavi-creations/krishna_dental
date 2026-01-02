<?php
include __DIR__ . '/../../db.connection/db_connection.php';

// ----------------------------
// PAGE NAME
// ----------------------------
$page = basename($_SERVER['PHP_SELF']);

// USER IP
$ip = $_SERVER['REMOTE_ADDR'];

// TODAY DATE
$today = date('Y-m-d');

// ----------------------------
// CHECK IF ALREADY VISITED TODAY
// ----------------------------
$logCheck = $conn->prepare(
    "SELECT id FROM visitor_logs 
     WHERE page_name = ? AND ip_address = ? AND visit_date = ?"
);
$logCheck->bind_param("sss", $page, $ip, $today);
$logCheck->execute();
$logResult = $logCheck->get_result();

// 👉 IF NOT VISITED TODAY
if ($logResult->num_rows == 0) {

    // INSERT INTO LOG TABLE
    $logInsert = $conn->prepare(
        "INSERT INTO visitor_logs (page_name, ip_address, visit_date)
         VALUES (?, ?, ?)"
    );
    $logInsert->bind_param("sss", $page, $ip, $today);
    $logInsert->execute();
    $logInsert->close();

    // ----------------------------
    // UPDATE / INSERT MAIN COUNT
    // ----------------------------
    $check = $conn->prepare(
        "SELECT id FROM visitors WHERE page_name = ?"
    );
    $check->bind_param("s", $page);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {

        $update = $conn->prepare(
            "UPDATE visitors 
             SET visit_count = visit_count + 1 
             WHERE page_name = ?"
        );
        $update->bind_param("s", $page);
        $update->execute();
        $update->close();

    } else {

        $insert = $conn->prepare(
            "INSERT INTO visitors (page_name, visit_count) 
             VALUES (?, 1)"
        );
        $insert->bind_param("s", $page);
        $insert->execute();
        $insert->close();
    }

    $check->close();
}

$logCheck->close();
?>
