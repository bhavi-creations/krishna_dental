<?php
header("Content-Type: application/json");
include './db.connection/db_connection.php';

if (!isset($_POST['blog_id']) || !isset($_POST['vote_type'])) {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

$blog_id = intval($_POST['blog_id']);
$vote_type = $_POST['vote_type']; // like or dislike

// Insert Reaction
$insert_sql = "INSERT INTO blog_reactions (blog_id, reaction, created_at) 
               VALUES (?, ?, NOW())";

$insert_stmt = $conn->prepare($insert_sql);
$insert_stmt->bind_param("is", $blog_id, $vote_type);
$insert_stmt->execute();
$insert_stmt->close();

// Fetch Updated Counts
$count_sql = "SELECT 
                SUM(reaction='like') AS likes,
                SUM(reaction='dislike') AS dislikes
              FROM blog_reactions 
              WHERE blog_id = ?";

$count_stmt = $conn->prepare($count_sql);
$count_stmt->bind_param("i", $blog_id);
$count_stmt->execute();
$count_stmt->bind_result($likes, $dislikes);
$count_stmt->fetch();
$count_stmt->close();

echo json_encode([
    "success" => true,
    "message" => "Vote updated",
    "new_likes" => $likes,
    "new_dislikes" => $dislikes
]);

$conn->close();
?>
