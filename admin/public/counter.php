<?php
include './db.connection/db_connection.php';

$page = basename($_SERVER['PHP_SELF']);
$cookie_name = "visited_page_" . md5($page);

if (!isset($_COOKIE[$cookie_name])) {

    // 1 year cookie
    // setcookie($cookie_name, 'yes', time() + (60 * 60 * 24 * 365), "/");

    /* =========================
       MAIN VISITOR COUNT TABLE
    ========================= */
    $stmt = $conn->prepare("SELECT id FROM visitors WHERE page_name = ?");
    $stmt->bind_param("s", $page);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $update = $conn->prepare(
            "UPDATE visitors SET visit_count = visit_count + 1 WHERE page_name = ?"
        );
        $update->bind_param("s", $page);
        $update->execute();
    } else {
        $insert = $conn->prepare(
            "INSERT INTO visitors (page_name, visit_count) VALUES (?, 1)"
        );
        $insert->bind_param("s", $page);
        $insert->execute();
    }

    /* =========================
       DATE-WISE LOG TABLE
    ========================= */
    // $log = $conn->prepare(
    //     "INSERT INTO visitor_logs (page_name) VALUES (?)"
    // );
    // $log->bind_param("s", $page);
    // $log->execute();
}
?>
