<?php
include __DIR__ . '/db.connection/db_connection.php';

$page  = basename($_SERVER['PHP_SELF']);
$ip    = $_SERVER['REMOTE_ADDR'];
$today = date('Y-m-d');

// ===============================
// Record visitor (once per page per day)
// ===============================
$check = $conn->prepare("
    SELECT id FROM visitor_logs
    WHERE page_name = ? AND ip_address = ? AND visit_date = ?
");
$check->bind_param("sss", $page, $ip, $today);
$check->execute();
$res = $check->get_result();

if ($res->num_rows == 0) {
    $city = 'Unknown';

    $ins = $conn->prepare("
        INSERT INTO visitor_logs
        (page_name, ip_address, visit_date, visited_at, city)
        VALUES (?, ?, ?, NOW(), ?)
    ");
    $ins->bind_param("ssss", $page, $ip, $today, $city);
    $ins->execute();
}

// ===============================
// COUNTS (FROM visitor_logs ONLY)
// ===============================

// 🌍 Total unique visitors (entire website)
$totalRes = $conn->query("
    SELECT COUNT(DISTINCT ip_address) AS total
    FROM visitor_logs
");
$totalCount = $totalRes->fetch_assoc()['total'] ?? 0;

// 📄 This page unique visitors
$pstmt = $conn->prepare("
    SELECT COUNT(DISTINCT ip_address) AS total
    FROM visitor_logs
    WHERE page_name = ?
");
$pstmt->bind_param("s", $page);
$pstmt->execute();
$pageRes = $pstmt->get_result();
$pageCount = $pageRes->fetch_assoc()['total'] ?? 0;
?>

<style>
#visitor-eye:hover .visitor-tooltip{
    display:block;
}
</style>

<!-- <a href="visitor-analytics.php" id="visitor-eye">
    <img src="./assets/img/eye.png" style="width:30px;height:30px;">
    <div class="visitor-tooltip">
        <div>Total Website Visitors: <b><?= $totalCount ?></b></div>
        <div>This Page Visitors: <b><?= $pageCount ?></b></div>
    </div>
</a> -->
