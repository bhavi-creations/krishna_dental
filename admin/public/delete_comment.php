<?php
// Start output buffering (prevents accidental output before header)
ob_start();

// Database connection
include '../../db.connection/db_connection.php';

// Check if the comment id is set in the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Prepare and bind the delete statement
    $stmt = $conn->prepare("DELETE FROM blog_comments WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    // Execute the statement
    if ($stmt->execute()) {
        // ✅ Redirect back to comments page with success flag
        header("Location:blog_commets.php?deleted=1");
        exit;
    } else {
        // ✅ Redirect with error message
        header("Location:blog_commets.php?error=" . urlencode($stmt->error));
        exit;
    }
    
    // Close the statement
    $stmt->close();
} else {
    // No comment ID given
    header("Location:blog_commets.php?error=No+ID+Provided");
    exit;
}

// Close the connection
$conn->close();

// End output buffering
ob_end_flush();
?>
