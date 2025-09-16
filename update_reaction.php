<?php
session_start();
include 'db.connection/db_connection.php'; // Your DB connection

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $comment_id = intval($_POST['comment_id']);
    $type = $_POST['type'];

    if (!in_array($type, ['like', 'dislike'])) {
        echo json_encode(["success" => false, "message" => "Invalid type"]);
        exit;
    }

    // Initialize session reactions array if not exists
    if (!isset($_SESSION['reactions'])) {
        $_SESSION['reactions'] = [];
    }

    // Prevent multiple clicks
    if (isset($_SESSION['reactions'][$comment_id])) {
        echo json_encode(["success" => false, "message" => "You already reacted"]);
        exit;
    }

    // Update DB counts
    if ($type === 'like') {
        $conn->query("UPDATE blog_comments SET likes = likes + 1 WHERE id=$comment_id");
    } else {
        $conn->query("UPDATE blog_comments SET dislikes = dislikes + 1 WHERE id=$comment_id");
    }

    // Store reaction in session
    $_SESSION['reactions'][$comment_id] = $type;

    // Fetch updated counts
    $res = $conn->query("SELECT likes, dislikes FROM blog_comments WHERE id=$comment_id");
    $row = $res->fetch_assoc();

    echo json_encode([
        "success" => true,
        "likes" => (int)$row['likes'],
        "dislikes" => (int)$row['dislikes']
    ]);
}
?>
