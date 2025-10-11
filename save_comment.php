<?php
// ====================================================
// DEBUG MODE — show errors for development (KEEP THIS DURING TESTING)
// ====================================================
error_reporting(E_ALL);
ini_set('display_errors', 1); // show errors on screen
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . "/php_error.log"); // save errors here

// ====================================================
// DATABASE CONNECTION
// ====================================================
if ($_SERVER['SERVER_NAME'] == 'localhost') {
    // Localhost credentials
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "krishnadental"; // change to your local DB name
} else {
    // Live server credentials
    $host = "localhost";
    $user = "krishnadentacureclinic";
    $pass = "ip4IvBVvK8TlT7y";
    $db   = "krishnadentacureclinic";
}

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    error_log("DB Connection failed: " . $conn->connect_error);
    // Use the JavaScript alert to inform the user without showing raw server info
    echo "<script>alert('❌ A database error occurred. Please try again later.'); window.history.back();</script>";
    exit;
}

// ====================================================
// HANDLE COMMENT FORM SUBMISSION
// ====================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize input
    $blog_id        = isset($_POST['blog_id']) ? intval($_POST['blog_id']) : 0;
    $user_name      = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
    $user_email     = isset($_POST['user_email']) ? trim($_POST['user_email']) : '';
    $comment        = isset($_POST['comment']) ? trim($_POST['comment']) : '';
    
    // --- NEW: Capture the original referring URL for reliable redirect ---
    // If the form sends this in a hidden field, use that. Otherwise, rely on HTTP_REFERER.
    $redirect_url   = $_SERVER['HTTP_REFERER'] ?? '/'; 

    // Validation
    if ($blog_id <= 0 || empty($user_name) || empty($user_email) || empty($comment)) {
        echo "<script>alert('⚠️ All fields are required, and the Blog ID must be valid.'); window.history.back();</script>";
        $conn->close();
        exit;
    }

    // Insert comment using prepared statement
    // Note: The blog_comments table should have a column named 'created_at' and 'likes'/'dislikes' (default 0)
    $stmt = $conn->prepare("
        INSERT INTO blog_comments (blog_id, user_name, user_email, comment, likes, dislikes, created_at)
        VALUES (?, ?, ?, ?, 0, 0, NOW())
    ");

    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        echo "<script>alert('❌ A system error occurred. Prepare failed.'); window.history.back();</script>";
        $conn->close();
        exit;
    }

    // 'isss' = integer, string, string, string
    $stmt->bind_param("isss", $blog_id, $user_name, $user_email, $comment);

    if ($stmt->execute()) {
        
        // ----------------------------------------------------------------------------------
        // !!! IMPORTANT UPDATE FOR REDIRECTION !!!
        // Use PHP's header() redirect instead of unreliable JavaScript document.referrer.
        // Also, append an anchor (#comments) to jump straight to the comment section.
        // ----------------------------------------------------------------------------------
        $final_redirect = $redirect_url . "#comments";
        
        // Flash message (optional, for a nicer UX)
        // If you are using sessions, you can set a $_SESSION['success'] message here.
        
        // Perform the redirect
        header("Location: $final_redirect");
        exit;
        
    } else {
        error_log("Execute failed for blog_id $blog_id: " . $stmt->error);
        echo "<script>alert('❌ Failed to add comment: A database error occurred.'); window.history.back();</script>";
    }

    $stmt->close();
}

$conn->close();
?>