<?php
// Database connection
include '../../db.connection/db_connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch file names first to delete files
    $stmt_select = $conn->prepare("SELECT main_image, video FROM blogs WHERE id = ?");
    $stmt_select->bind_param("i", $id);
    $stmt_select->execute();
    $stmt_select->bind_result($main_image, $video);
    $stmt_select->fetch();
    $stmt_select->close();

    // Delete blog from database
    $stmt = $conn->prepare("DELETE FROM blogs WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Delete files if they exist
        if (!empty($main_image) && file_exists("../../uploads/images/" . $main_image)) {
            unlink("../../uploads/images/" . $main_image);
        }
        if (!empty($video) && file_exists("../../uploads/videos/" . $video)) {
            unlink("../../uploads/videos/" . $video);
        }

        $stmt->close();
        $conn->close();
        // Redirect after successful deletion
        header("Location: allBlog.php");
        exit;
    } else {
        $stmt->close();
        $conn->close();
        die("Error deleting blog post: " . $stmt->error);
    }
} else {
    // No ID provided, redirect back
    $conn->close();
    header("Location: allBlog.php");
    exit;
}
?>
