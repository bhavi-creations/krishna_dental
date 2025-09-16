<?php
include __DIR__ . '/../../db.connection/db_connection.php';

// Get comment id
$comment_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch comment details
$stmt = $conn->prepare("SELECT * FROM blog_comments WHERE id = ?");
$stmt->bind_param("i", $comment_id);
$stmt->execute();
$result = $stmt->get_result();
$comment = $result->fetch_assoc();

if (!$comment) {
    die("Comment not found!");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name   = $_POST['user_name'] ?? '';
    $user_email  = $_POST['user_email'] ?? '';
    $comment_txt = $_POST['comment'] ?? '';
    $likes       = intval($_POST['likes'] ?? 0);
    $dislikes    = intval($_POST['dislikes'] ?? 0);

    $stmt = $conn->prepare("UPDATE blog_comments 
                            SET user_name=?, user_email=?, comment=?, likes=?, dislikes=? 
                            WHERE id=?");
    $stmt->bind_param("sssiii", $user_name, $user_email, $comment_txt, $likes, $dislikes, $comment_id);

    if ($stmt->execute()) {
        header("Location: blog_commets.php?blog_id=" . $comment['blog_id'] . "&updated=1");
        exit;
    } else {
        echo "Error updating comment: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Comment</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">

<div id="wrapper">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
    <!-- End Sidebar -->

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <!-- Navbar -->
            <?php include 'navbar.php'; ?>
            <!-- End Navbar -->

            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h2 class="h3 mb-0 text-info">Edit Comment </h2>
                    <a href="blog_commets.php?blog_id=<?= $comment['blog_id']; ?>" class="btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Comments
                    </a>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Update Comment</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label>User Name</label>
                                <input type="text" name="user_name" class="form-control"
                                       value="<?= htmlspecialchars($comment['user_name']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label>User Email</label>
                                <input type="email" name="user_email" class="form-control"
                                       value="<?= htmlspecialchars($comment['user_email']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label>Comment</label>
                                <textarea name="comment" class="form-control" rows="4" required><?= htmlspecialchars($comment['comment']); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label>Likes</label>
                                <input type="number" name="likes" class="form-control" value="<?= $comment['likes']; ?>">
                            </div>

                            <div class="mb-3">
                                <label>Dislikes</label>
                                <input type="number" name="dislikes" class="form-control" value="<?= $comment['dislikes']; ?>">
                            </div>

                            <button type="submit" class="btn btn-success">Update Comment</button>
                            <a href="blog_commets.php?blog_id=<?= $comment['blog_id']; ?>" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto text-center">
                <p style="color:black">©2024 Srinivasa Dental. Designed & Developed by 
                    <a href="https://bhavicreations.com/" target="_blank" style="color:black;text-decoration:none;">Bhavi Creations</a>
                </p>
            </div>
        </footer>
    </div>
</div>

<a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>
</body>
</html>
