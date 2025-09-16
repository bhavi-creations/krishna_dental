<?php
// Turn on error logging (not displaying)
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . "/php_error.log"); // errors will be saved here

// Database connection - 🔴 change these to your cPanel details
$host = "localhost"; 
$user = "krishnadentacureclinic";      // 👉 your cPanel DB username
$pass = "ip4IvBVvK8TlT7y";  // 👉 your cPanel DB password
$db   = "krishnadentacureclinic";        // 👉 your cPanel DB name

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    error_log("DB Connection failed: " . $conn->connect_error);
    die("We are facing a technical issue. Please try again later.");
}

// Handle comment form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blog_id    = intval($_POST['blog_id']);
    $user_name  = trim($_POST['user_name']);
    $user_email = trim($_POST['user_email']);
    $comment    = trim($_POST['comment']);

    // Validation
    if (empty($user_name) || empty($user_email) || empty($comment)) {
        echo "<script>alert('⚠️ All fields are required.'); window.history.back();</script>";
        exit;
    }

    // Insert comment with prepared statement
    $stmt = $conn->prepare("
        INSERT INTO blog_comments (blog_id, user_name, user_email, comment)
        VALUES (?, ?, ?, ?)
    ");
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        die("Something went wrong. Please try again later.");
    }

    $stmt->bind_param("isss", $blog_id, $user_name, $user_email, $comment);

    if ($stmt->execute()) {
        echo "<script>
                alert('✅ Comment added successfully!');
                window.location.href = document.referrer;
              </script>";
    } else {
        error_log("Execute failed: " . $stmt->error);
        echo "<script>alert('❌ Error saving comment. Please try again later.');</script>";
    }

    $stmt->close();
}

$conn->close();
?>
