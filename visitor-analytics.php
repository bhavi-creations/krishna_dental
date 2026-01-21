<?php
include './db.connection/db_connection.php';
session_start();

// Current page & visitor info
$page  = basename($_SERVER['PHP_SELF']);
$ip    = $_SERVER['REMOTE_ADDR'];
$today = date('Y-m-d');

// ===============================
// Get City from IP if not in session
// ===============================
if (!isset($_SESSION['city'])) {
    $ipApiUrl = "http://ip-api.com/json/{$ip}";
    $ipData = @file_get_contents($ipApiUrl);
    $city = 'Unknown';
    if ($ipData) {
        $ipData = json_decode($ipData, true);
        if (isset($ipData['city']) && !empty($ipData['city'])) {
            $city = $ipData['city'];
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
    // Insert visitor log
    $ins = $conn->prepare("
        INSERT INTO visitor_logs (page_name, ip_address, visit_date, visited_at, city)
        VALUES (?, ?, ?, NOW(), ?)
    ");
    $ins->bind_param("ssss", $page, $ip, $today, $city);
    $ins->execute();

    // Update visitors table
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

// ===============================
// Filter dates
// ===============================
$from = $_GET['from'] ?? '';
$to   = $_GET['to'] ?? '';
$isFiltered = (!empty($from) && !empty($to));

// ===============================
// Total Unique Visitors
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
    <style>
        .va-container {
            max-width: 1100px;
            margin: auto;
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        /* Heading */
        .va-container h2 {
            text-align: center;
            font-size: 32px;
            margin-bottom: 30px;
            color: #2c7be5;
        }

        /* Cards */
        .va-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }

        .va-card {
            flex: 1 1 200px;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            color: #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .va-card h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .va-number {
            font-size: 28px;
            font-weight: bold;
        }

        /* Gradient backgrounds for cards */
        .va-total-visitors {
            /* background: linear-gradient(135deg, #4facfe, #00f2fe); */
            background-color: #007aff;
        }

        .va-total-views {
            /* background: linear-gradient(135deg, #43e97b, #38f9d7); */
              background-color: #ff6600;
        }

        /* Filter Form */
        .va-filter {
            margin-bottom: 30px;
            text-align: center;
        }

        .va-filter form {
            display: inline-flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .va-filter input {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .va-filter button {
            padding: 8px 15px;
            border: none;
            border-radius: 6px;
            background: #2c7be5;
            color: #fff;
            cursor: pointer;
            font-weight: bold;
        }

        .va-filter a {
            padding: 8px 15px;
            border-radius: 6px;
            background: #f0f0f0;
            color: #333;
            text-decoration: none;
            border: 1px solid #ccc;
        }

        /* Boxes */
        .va-box {
            background: #fff;
            border: 1px solid #ddd;
            padding: 18px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        /* Table Wrapper for responsive scroll */
        .va-table-wrapper {
            overflow-x: auto;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        thead {
            background-color: #2c7be5;
            color: #fff;
        }

        thead+thead.va-city {
            background-color: #43e97b;
          
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        td:last-child,
        th:last-child {
            text-align: right;
        }

        tbody tr {
            border-bottom: 1px solid #eee;
            transition: background 0.3s;
        }

        tbody tr:hover {
            background: #f0f8ff;
        }

        .no-data {
            text-align: center;
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="va-container">

        <h2>📊 Visitor Analytics</h2>

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