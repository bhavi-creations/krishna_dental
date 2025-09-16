<?php
include '../../db.connection/db_connection.php'; // DB connection

// Get blog_id from URL
$blog_id = isset($_GET['blog_id']) ? intval($_GET['blog_id']) : 0;

// Fetch all comments (if blog_id 0 ichite anni chupistundi)
$sql = $blog_id > 0 
    ? "SELECT * FROM blog_comments WHERE blog_id = $blog_id ORDER BY created_at DESC" 
    : "SELECT * FROM blog_comments ORDER BY created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>krishna Dental - Comments</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,700,800" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include 'sidebar.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include 'navbar.php'; ?>

                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h2 class="h2 mb-0 text-info mx-2">
                            <?php if($blog_id > 0){ ?>
                                Comments for Blog ID: <?php echo $blog_id; ?>
                            <?php } else { ?>
                                All Blog Comments
                            <?php } ?>
                        </h2>
                        <a href="blog_commets.php" class="btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Blogs
                        </a>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">All Comments</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Blog ID</th>
                                            <th>User Name</th>
                                            <th>Email</th>
                                            <th>Comment</th>
                                            <th>Likes</th>
                                            <th>Dislikes</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($result && $result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $row['id']; ?></td>
                                                    <td><?php echo $row['blog_id']; ?></td>
                                                    <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['user_email']); ?></td>
                                                    <td><?php echo nl2br(htmlspecialchars($row['comment'])); ?></td>
                                                    <td><?php echo $row['likes']; ?></td>
                                                    <td><?php echo $row['dislikes']; ?></td>
                                                    <td><?php echo $row['created_at']; ?></td>
                                                    <td>
                                                        <a href="reply_comment.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">💬 Reply</a>
                                                        <a href="edit_comment.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">✏️ Edit</a>
                                                        <a href="delete_comment.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger"
                                                           onclick="return confirm('Are you sure you want to delete this comment?');">🗑 Delete</a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='9' class='text-center text-danger'>No comments found.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

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
