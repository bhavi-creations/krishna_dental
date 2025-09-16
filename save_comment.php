<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "krishnadental"; 
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blog_id    = intval($_POST['blog_id']);
    $user_name  = trim($_POST['user_name']);
    $user_email = trim($_POST['user_email']);
    $comment    = trim($_POST['comment']);

    if (!empty($blog_id) && !empty($user_name) && !empty($user_email) && !empty($comment)) {
        // ✅ Prepared statement (safe from SQL injection)
        $stmt = $conn->prepare("INSERT INTO blog_comments 
            (blog_id, user_name, user_email, comment, likes, dislikes, created_at) 
            VALUES (?, ?, ?, ?, 0, 0, NOW())");
        $stmt->bind_param("isss", $blog_id, $user_name, $user_email, $comment);

        if ($stmt->execute()) {
            // ✅ Redirect back to blog page
            echo "<script>
                    alert('✅ Comment added successfully!');
                    window.location.href = document.referrer;
                  </script>";
        } else {
            echo "<script>alert('❌ Error: " . addslashes($stmt->error) . "');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('❌ All fields are required!');</script>";
    }
}

$conn->close();
?>
