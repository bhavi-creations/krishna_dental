<?php
include __DIR__ . '/db.connection/db_connection.php';

$page  = basename($_SERVER['PHP_SELF']);
$ip    = $_SERVER['REMOTE_ADDR'];
$today = date('Y-m-d');

// Localhost fix
if ($ip == '127.0.0.1' || $ip == '::1') {
    $ip = '8.8.8.8'; // test IP (India)
}

/* =========================
   GET CITY FROM IP (NO POPUP)
========================= */
$city = 'Unknown';

$ipApiUrl = "http://ip-api.com/json/{$ip}?fields=status,city";
$ipData = @file_get_contents($ipApiUrl);

if ($ipData) {
    $data = json_decode($ipData, true);
    if ($data['status'] === 'success' && !empty($data['city'])) {
        $city = $data['city']; // Kakinada, Amalapuram etc
    }
}

/* =========================
   SAME IP + SAME PAGE + SAME DAY
========================= */
$check = $conn->prepare("
    SELECT id FROM visitor_logs
    WHERE page_name = ? AND ip_address = ? AND visit_date = ?
");
$check->bind_param("sss", $page, $ip, $today);
$check->execute();
$res = $check->get_result();

if ($res->num_rows == 0) {

    // visitor_logs
    $ins = $conn->prepare("
        INSERT INTO visitor_logs
        (page_name, ip_address, visit_date, visited_at, city)
        VALUES (?, ?, ?, NOW(), ?)
    ");
    $ins->bind_param("ssss", $page, $ip, $today, $city);
    $ins->execute();

    // visitors table
    $v = $conn->prepare("SELECT id FROM visitors WHERE page_name = ?");
    $v->bind_param("s", $page);
    $v->execute();
    $vr = $v->get_result();

    if ($vr->num_rows > 0) {
        $up = $conn->prepare("
            UPDATE visitors SET visit_count = visit_count + 1
            WHERE page_name = ?
        ");
        $up->bind_param("s", $page);
        $up->execute();
    } else {
        $in = $conn->prepare("
            INSERT INTO visitors (page_name, visit_count)
            VALUES (?, 1)
        ");
        $in->bind_param("s", $page);
        $in->execute();
    }
}

/* =========================
   COUNTS
========================= */
$totalRes = $conn->query("SELECT SUM(visit_count) AS total FROM visitors");
$totalCount = $totalRes->fetch_assoc()['total'] ?? 0;

$pstmt = $conn->prepare("SELECT visit_count FROM visitors WHERE page_name = ?");
$pstmt->bind_param("s", $page);
$pstmt->execute();
$pageCount = $pstmt->get_result()->fetch_assoc()['visit_count'] ?? 0;
?>

<!-- Eye Icon -->
<a href="visitor-analytics.php" id="visitor-eye">
    <img src="./assets/img/eye.png" style="width:30px;height:30px;">
    <div class="visitor-tooltip">
        <div>Total Website Visitors: <b><?= $totalCount ?></b></div>
        <div>This Page Visitors: <b><?= $pageCount ?></b></div>
    </div>
</a>