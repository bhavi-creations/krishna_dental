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



<!DOCTYPE html>
<html>

<head>
    <title>Visitor Analytics</title>
    
    <link rel="stylesheet" href="visitor-analytics.css">
</head>

<body>

    <div class="va-container">

        <h2>📊 Visitor Analytics</h2>
        <a href="index.php" class="va-back-btn">⬅ Back</a>


        <!-- Cards -->
        <div class="va-cards">
            <div class="va-card va-total-visitors">
                <h3>👥 Total Unique Visitors</h3>
                <div class="va-number"><?= $totalCount ?></div>
            </div>
            <div class="va-card va-total-views">
                <h3>👁️ Total Page Views</h3>
                <div class="va-number"><?= $totalViews ?></div>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="va-filter">
            <form method="GET">
                <input type="date" name="from" value="<?= htmlspecialchars($from) ?>">
                <input type="date" name="to" value="<?= htmlspecialchars($to) ?>">
                <button type="submit">Filter</button>
                <a href="visitor-analytics.php">Reset</a>
            </form>
        </div>

        <!-- Page-wise Unique Visitors -->
        <div class="va-box">
            <h3>📄 Page-wise Unique Visitors</h3>
            <div class="va-table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Page</th>
                            <th>Visitors</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($pages && $pages->num_rows > 0): ?>
                            <?php while ($row = $pages->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['page_name']) ?></td>
                                    <td><?= $row['visit_count'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" class="no-data">No Data Found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Today City-wise Visitors -->
        <div class="va-box">
            <h3>🌍 Today City-wise Visitors</h3>
            <div class="va-table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>City</th>
                            <th>Visitors</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($cities && $cities->num_rows > 0): ?>
                            <?php while ($c = $cities->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($c['city']) ?></td>
                                    <td><?= $c['total'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" class="no-data">No Data Found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</body>

</html>