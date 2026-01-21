<?php
include __DIR__ . '/db.connection/db_connection.php';

$page  = basename($_SERVER['PHP_SELF']);
$ip    = $_SERVER['REMOTE_ADDR'];
$today = date('Y-m-d');

// City (ippatiki default)
$city = 'Unknown';

/*
  Same IP + Same Page + Same Day already unda?
*/
$check = $conn->prepare("
    SELECT id FROM visitor_logs
    WHERE page_name = ? AND ip_address = ? AND visit_date = ?
");
$check->bind_param("sss", $page, $ip, $today);
$check->execute();
$res = $check->get_result();

if ($res->num_rows == 0) {

    // Insert into visitor_logs
    $ins = $conn->prepare("
        INSERT INTO visitor_logs
        (page_name, ip_address, visit_date, visited_at, city)
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

/* =========================
   COUNTS FOR DISPLAY
========================= */

// Total website visitors (all pages sum)
$totalRes = $conn->query("SELECT SUM(visit_count) AS total FROM visitors");
$totalCount = $totalRes->fetch_assoc()['total'] ?? 0;

// Current page visitors
$pstmt = $conn->prepare("SELECT visit_count FROM visitors WHERE page_name = ?");
$pstmt->bind_param("s", $page);
$pstmt->execute();
$pres = $pstmt->get_result();
$pageCount = $pres->fetch_assoc()['visit_count'] ?? 0;
?>

<style>
/* #visitor-eye{
    position: fixed;
    bottom: 20px;
    right: 20px;
    text-decoration: none;
    z-index: 9999;
}
.visitor-tooltip{
    display:none;
    position:absolute;
    bottom:40px;
    right:0;
    background:#000;
    color:#fff;
    padding:8px 12px;
    border-radius:6px;
    font-size:12px;
    white-space:nowrap;
} */
#visitor-eye:hover .visitor-tooltip{
    display:block;
}
</style>

<a href="visitor-analytics.php" id="visitor-eye">
    <img src=".\assets\img\eye.png" class="img-fluid" alt="" style="width: 30px; height: 30px;">
    <div class="visitor-tooltip">
        <div>Total Pages Visitors: <b><?php echo $totalCount; ?></b></div>
        <div>This Page Visitors: <b><?php echo $pageCount; ?></b></div>
    </div>
</a>



