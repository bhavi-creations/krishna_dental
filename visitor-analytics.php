<?php
include './db.connection/db_connection.php';

$from = $_GET['from'] ?? '';
$to   = $_GET['to'] ?? '';

$isFiltered = (!empty($from) && !empty($to));

/* =========================
   TOTAL VISITORS
========================= */
if ($isFiltered) {
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS total
        FROM visitors
        WHERE DATE(visited_at) BETWEEN ? AND ?
    ");
    $stmt->bind_param("ss", $from, $to);
    $stmt->execute();
    $totalRes = $stmt->get_result();
} else {
    $totalRes = $conn->query("
        SELECT COUNT(*) AS total
        FROM visitors
    ");
}

$totalCount = $totalRes->fetch_assoc()['total'] ?? 0;

/* =========================
   PAGE-WISE VISITORS
========================= */
if ($isFiltered) {
    $stmt = $conn->prepare("
        SELECT page_name, COUNT(*) AS visit_count
        FROM visitors
        WHERE DATE(visited_at) BETWEEN ? AND ?
        GROUP BY page_name
        ORDER BY visit_count DESC
    ");
    $stmt->bind_param("ss", $from, $to);
    $stmt->execute();
    $pages = $stmt->get_result();
} else {
    $pages = $conn->query("
        SELECT page_name, COUNT(*) AS visit_count
        FROM visitors
        GROUP BY page_name
        ORDER BY visit_count DESC
    ");
}
?>



<!DOCTYPE html>
<html>

<head>
    <title>Srinivasa Multispeciality Dental Hospital Kakinada</title>
    <style>
        body {
            font-family: Arial;
            padding: 20px;
            background: #fafafa;
        }

        .va-container {
            max-width: 1000px;
            margin: auto;
        }

        .va-box {
            background: #fff;
            border: 1px solid #ddd;
            padding: 18px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .va-title {
            margin-bottom: 10px;
        }

        .va-total {
            font-size: 26px;
            color: #2c7be5;
        }

        .va-filter-form input,
        .va-filter-form button {
            padding: 7px 10px;
            margin-right: 10px;
        }

        .va-reset-btn {
            background: #f44336;
            color: #fff;
            border: none;
            cursor: pointer;
            padding: 7px 12px;
            border-radius: 5px;
        }

        table.va-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .va-table th,
        .va-table td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .va-table th {
            background: #f2f2f2;
        }

        .va-no-data {
            text-align: center;
            color: red;
            font-weight: bold;
            padding: 20px;
        }
    </style>
</head>

<body>

    <div class="va-container">

        <h2 class="va-title">📊 Visitor Analytics</h2>

        <!-- TOTAL VISITORS -->
        <div class="va-box">
            <h3>👥 Total Page Visitors</h3>
            <div class="va-total"><?php echo $totalCount; ?></div>
            <?php if ($isFiltered) { ?>
                <small><?php echo $from; ?> → <?php echo $to; ?></small>
            <?php } ?>
        </div>

        <!-- DATE FILTER -->
        <div class="va-box">
            <h3>📅 Filter by Date</h3>
            <form method="GET" class="va-filter-form">
                <input type="date" name="from" value="<?php echo htmlspecialchars($from); ?>" required>
                <input type="date" name="to" value="<?php echo htmlspecialchars($to); ?>" required>
                <button type="submit">Show</button>
                <?php if ($isFiltered) { ?>
                    <a href="visitor-analytics.php">
                        <button type="button" class="va-reset-btn">Reset</button>
                    </a>
                <?php } ?>
            </form>
        </div>

        <!-- PAGE-WISE VISITORS -->
        <div class="va-box">
            <h3>📄 Page-wise Visitors</h3>

            <table class="va-table">
                <tr>
                    <th>Page Name</th>
                    <th>Total Visitors</th>
                </tr>

                <?php if ($pages && $pages->num_rows > 0) { ?>
                    <?php while ($row = $pages->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['page_name']); ?></td>
                            <td><?php echo $row['visit_count']; ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="2" class="va-no-data">
                            ❌ No visitors in this date range
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>

    </div>

</body>

</html>