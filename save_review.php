<?php
include './db.connection/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blog_id = intval($_POST['blog_id']);
    $name = trim($_POST['user_name']);
    $email = trim($_POST['user_email']);
    $text = trim($_POST['review_text']);

    if ($name && $email && $text) {
        $stmt = $conn->prepare("INSERT INTO reviews (blog_id, user_name, user_email, review_text) VALUES (?,?,?,?)");
        $stmt->bind_param("isss", $blog_id, $name, $email, $text);
        $stmt->execute();
    }
    header("Location: service_detsils.php?id=".$blog_id);
    exit;
}
