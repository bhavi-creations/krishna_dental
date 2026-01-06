<?php
include './admin/public/counter.php';
include './db.connection/db_connection.php';

$page = basename($_SERVER['PHP_SELF']);

// Current page count
$stmt = $conn->prepare(
    "SELECT visit_count FROM visitors WHERE page_name = ?"
);
$stmt->bind_param("s", $page);
$stmt->execute();
$res = $stmt->get_result();
$pageCount = $res->fetch_assoc()['visit_count'] ?? 0;

// Total website visitors
$totalRes = $conn->query(
    "SELECT SUM(visit_count) AS total FROM visitors"
);
$totalCount = $totalRes->fetch_assoc()['total'] ?? 0;
?>

<!DOCTYPE html>
<html>

<head>
    <title>Krishna  Dental Hospital Kakinada</title>
</head>

<body>  
    

<a href="visitor-analytics.php" id="visitor-eye">
    <!-- 👁 -->
    <img src=".\assets\img\eye.png" class="img-fluid" alt="" style="width: 30px; height: 30px;">
    <div class="visitor-tooltip">
        <div>Total pages Visitors: <b><?php echo $totalCount; ?></b></div>
        <div>This Page Visitors: <b><?php echo $pageCount; ?></b></div>
    </div>
</a>

    <?php
    include './db.connection/db_connection.php';

    // Total website visitors
    $totalRes = $conn->query(
        "SELECT SUM(visit_count) AS total FROM visitors"
    );
    $totalCount = $totalRes->fetch_assoc()['total'] ?? 0;

    // All pages data
    $pages = $conn->query(
        "SELECT page_name, visit_count FROM visitors ORDER BY visit_count DESC"
    );
    ?>


</body>

</html>