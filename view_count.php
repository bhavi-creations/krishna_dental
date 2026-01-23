<?php
// session_start();

include __DIR__ . '/db.connection/db_connection.php';

// ❌ Analytics page ni count cheyyakudadhu
if (basename($_SERVER['PHP_SELF']) === 'visitor-analytics.php') {
    return;
}

$page  = basename($_SERVER['PHP_SELF']);
$ip    = $_SERVER['REMOTE_ADDR'];
$today = date('Y-m-d');

// Localhost testing fix
if ($ip == '127.0.0.1' || $ip == '::1') {
    $ip = '8.8.8.8';
}

/* ===============================
   GET LOCATION (AUTO & FREE)
================================ */

// City not set OR Unknown unna malli try cheyyali
if (!isset($_SESSION['city']) || $_SESSION['city'] === 'Unknown') {

    $city = 'Unknown';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://ip-api.com/json/{$ip}");
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
        }
    }

    $_SESSION['city'] = $city;
} else {
    $city = $_SESSION['city'];
}

/* ===============================
   INSERT VISITOR (ONCE PER DAY)
================================ */

$check = $conn->prepare("
    SELECT id FROM visitor_logs
    WHERE page_name = ? AND ip_address = ? AND visit_date = ?
");
$check->bind_param("sss", $page, $ip, $today);
$check->execute();
$res = $check->get_result();

if ($res->num_rows == 0) {

    $ins = $conn->prepare("
        INSERT INTO visitor_logs
        (page_name, ip_address, visit_date, visited_at, city)
        VALUES (?, ?, ?, NOW(), ?)
    ");
    $ins->bind_param("ssss", $page, $ip, $today, $city);
    $ins->execute();
}

/* ===============================
   COUNTS (FROM visitor_logs)
================================ */

// Total unique visitors
$totalRes = $conn->query("
    SELECT COUNT(DISTINCT ip_address) AS total
    FROM visitor_logs
");
$totalCount = $totalRes->fetch_assoc()['total'] ?? 0;

// Current page unique visitors
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

<!-- Eye Icon -->
<a href="visitor-analytics.php" id="visitor-eye">
    <img src="./assets/img/eye.png" style="width:30px;height:30px;">
    <div class="visitor-tooltip">
        <div>Total Website Visitors: <b><?= $totalCount ?></b></div>
        <div>This Page Visitors: <b><?= $pageCount ?></b></div>
    </div>
</a>
