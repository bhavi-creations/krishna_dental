<?php
include '../../db.connection/db_connection.php'; // DB connection

// Get service ID from URL
$edit_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// If no ID found, redirect
if ($edit_id <= 0) {
    header("Location: view_services.php?msg=Invalid Service ID!");
    exit();
}

// Fetch existing service details
$stmt = $conn->prepare("SELECT service_name FROM services WHERE id = ?");
$stmt->bind_param("i", $edit_id);
$stmt->execute();
$stmt->bind_result($service_name);
$stmt->fetch();
$stmt->close();

// If no record found
if (empty($service_name)) {
    header("Location: view_services.php?msg=Service not found!");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Edit Service</h2>

    <!-- ✅ Success Message -->
    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_GET['msg']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form action="update_service.php" method="post">
        <input type="hidden" name="id" value="<?= $edit_id ?>">

        <div class="mb-3">
            <label class="form-label">Service Name</label>
            <input type="text" name="service_name" class="form-control" 
                   value="<?= htmlspecialchars($service_name) ?>" required>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="view_services.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
